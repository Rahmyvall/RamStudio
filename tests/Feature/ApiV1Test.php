<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PricingFeature;
use App\Models\PricingPackage;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Tag;
use App\Models\Technology;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
  use RefreshDatabase;

  private function admin(): User
  {
    return User::factory()->create(['role' => User::ROLE_ADMIN]);
  }

  public function test_public_service_listing_is_json_and_paginated(): void
  {
    $category = ServiceCategory::create(['name' => 'Web', 'slug' => 'web']);
    Service::create(['service_category_id' => $category->id, 'name' => 'Service A', 'slug' => 'service-a', 'is_active' => true]);

    $response = $this->getJson('/api/v1/services?per_page=5');

    $response->assertOk()
      ->assertJsonPath('success', true)
      ->assertJsonStructure([
        'data' => [['id', 'name', 'slug', 'category']],
        'links',
        'meta',
      ]);
  }

  public function test_public_search_and_filter_work(): void
  {
    $category = ServiceCategory::create(['name' => 'Web', 'slug' => 'web']);
    Service::create(['service_category_id' => $category->id, 'name' => 'Alpha', 'slug' => 'alpha', 'is_active' => true]);
    Service::create(['service_category_id' => $category->id, 'name' => 'Beta', 'slug' => 'beta', 'is_active' => false]);

    $this->getJson('/api/v1/services?search=alpha')
      ->assertOk()
      ->assertJsonCount(1, 'data')
      ->assertJsonPath('data.0.slug', 'alpha');

    $this->getJson('/api/v1/services?is_active=0')
      ->assertOk()
      ->assertJsonCount(1, 'data')
      ->assertJsonPath('data.0.slug', 'beta');
  }

  public function test_show_returns_404_for_missing_resource(): void
  {
    $this->getJson('/api/v1/services/9999')
      ->assertNotFound()
      ->assertJsonPath('success', false);
  }

  public function test_store_requires_authentication(): void
  {
    $this->postJson('/api/v1/services', ['name' => 'X'])
      ->assertUnauthorized();
  }

  public function test_authenticated_admin_can_create_update_and_delete_service(): void
  {
    Sanctum::actingAs($this->admin());

    $create = $this->postJson('/api/v1/services', [
      'name' => 'API Service',
      'short_description' => 'Dibuat via API.',
      'starting_price' => 1000000,
      'is_active' => true,
    ])->assertCreated()
      ->assertJsonPath('success', true)
      ->assertJsonPath('data.slug', 'api-service');

    $id = $create->json('data.id');

    $this->putJson("/api/v1/services/{$id}", ['name' => 'API Service Updated'])
      ->assertOk()
      ->assertJsonPath('data.name', 'API Service Updated');

    $this->deleteJson("/api/v1/services/{$id}")->assertOk();
    $this->assertDatabaseMissing('services', ['id' => $id]);
  }

  public function test_validation_errors_use_consistent_envelope(): void
  {
    Sanctum::actingAs($this->admin());

    $this->postJson('/api/v1/services', [])
      ->assertStatus(422)
      ->assertJsonPath('success', false)
      ->assertJsonStructure(['message', 'errors' => ['name']]);
  }

  public function test_public_inquiry_submission_creates_record(): void
  {
    $service = Service::create(['name' => 'Web', 'slug' => 'web']);

    $this->postJson('/api/v1/inquiries', [
      'name' => 'Calon Klien',
      'email' => 'klien@example.com',
      'message' => 'Saya tertarik membuat website.',
      'services' => [$service->id],
    ])->assertCreated()
      ->assertJsonPath('success', true);

    $inquiry = Inquiry::where('email', 'klien@example.com')->firstOrFail();
    $this->assertSame('new', $inquiry->status);
    $this->assertTrue($inquiry->services->contains($service));
  }

  public function test_inquiry_listing_is_protected(): void
  {
    $this->getJson('/api/v1/inquiries')->assertUnauthorized();
  }

  public function test_admin_can_list_inquiries(): void
  {
    Sanctum::actingAs($this->admin());
    Inquiry::create(['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hi', 'status' => 'new']);

    $this->getJson('/api/v1/inquiries')
      ->assertOk()
      ->assertJsonCount(1, 'data');
  }

  public function test_tags_and_technologies_relations_are_exposed(): void
  {
    $tag = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
    $category = PostCategory::create(['name' => 'News', 'slug' => 'news']);
    $post = Post::create([
      'post_category_id' => $category->id,
      'title' => 'Hello',
      'slug' => 'hello',
      'content' => 'Body',
      'status' => 'published',
      'published_at' => now(),
    ]);
    $post->tags()->attach($tag);

    $this->getJson("/api/v1/posts/{$post->id}")
      ->assertOk()
      ->assertJsonPath('data.tags.0.slug', 'laravel');

    $technology = Technology::create(['name' => 'Laravel', 'slug' => 'laravel-tech']);
    $portfolio = Portfolio::create(['title' => 'P1', 'slug' => 'p1', 'is_published' => true]);
    $portfolio->technologies()->attach($technology);

    $this->getJson("/api/v1/portfolios/{$portfolio->id}")
      ->assertOk()
      ->assertJsonPath('data.technologies.0.name', 'Laravel');
  }

  public function test_pricing_package_includes_features(): void
  {
    $package = PricingPackage::create(['name' => 'Basic', 'slug' => 'basic', 'is_active' => true]);
    PricingFeature::create(['pricing_package_id' => $package->id, 'feature' => 'Hosting', 'is_included' => true]);

    $this->getJson('/api/v1/pricing-packages')
      ->assertOk()
      ->assertJsonPath('data.0.features.0.feature', 'Hosting');
  }

  public function test_faq_and_testimonial_public_endpoints(): void
  {
    Faq::create(['question' => 'Q', 'answer' => 'A', 'is_active' => true]);
    Testimonial::create(['client_name' => 'Budi', 'message' => 'Mantap', 'rating' => 5, 'is_published' => true]);

    $this->getJson('/api/v1/faqs')->assertOk()->assertJsonCount(1, 'data');
    $this->getJson('/api/v1/testimonials')->assertOk()->assertJsonCount(1, 'data');
  }

  public function test_login_returns_token(): void
  {
    $user = $this->admin();

    $response = $this->postJson('/api/v1/auth/login', [
      'email' => $user->email,
      'password' => 'password',
    ])->assertOk()
      ->assertJsonPath('success', true)
      ->assertJsonStructure(['data' => ['token', 'token_type', 'user']]);

    $token = $response->json('data.token');
    $this->assertNotEmpty($token);

    // The token must grant access to the admin "me" endpoint.
    $this->withHeader('Authorization', "Bearer {$token}")
      ->getJson('/api/v1/auth/me')
      ->assertOk()
      ->assertJsonPath('data.email', $user->email);
  }

  public function test_login_fails_with_wrong_password(): void
  {
    $user = $this->admin();

    $this->postJson('/api/v1/auth/login', [
      'email' => $user->email,
      'password' => 'nope',
    ])->assertUnauthorized()->assertJsonPath('success', false);
  }

  public function test_per_page_is_clamped(): void
  {
    Service::create(['name' => 'A', 'slug' => 'a']);

    $this->getJson('/api/v1/services?per_page=5000')
      ->assertOk()
      ->assertJsonPath('meta.per_page', 100);
  }
}
