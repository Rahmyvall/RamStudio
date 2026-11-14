<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Post;
use App\Models\PostCategory;
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

class AdminDashboardTest extends TestCase
{
  use RefreshDatabase;

  private function admin(): User
  {
    return User::factory()->create(['role' => User::ROLE_ADMIN]);
  }

  public function test_guest_is_redirected_to_login(): void
  {
    $this->get('/admin/dashboard')->assertRedirect(route('admin.login'));
  }

  public function test_login_page_renders(): void
  {
    $this->get(route('admin.login'))
      ->assertOk()
      ->assertSee('Selamat datang kembali')
      ->assertSee('Masuk ke Dashboard');
  }

  public function test_admin_can_login_and_reach_dashboard(): void
  {
    $user = $this->admin();

    $this->post(route('admin.login.attempt'), [
      'email' => $user->email,
      'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->get(route('admin.dashboard'))->assertOk()->assertSee('Pesan Masuk');
  }

  public function test_login_rejects_bad_credentials(): void
  {
    $user = $this->admin();

    $this->post(route('admin.login.attempt'), [
      'email' => $user->email,
      'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
  }

  public function test_non_admin_role_cannot_login(): void
  {
    $user = User::factory()->create(['role' => 'editor']);

    $this->post(route('admin.login.attempt'), [
      'email' => $user->email,
      'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
  }

  public function test_admin_can_logout(): void
  {
    $this->actingAs($this->admin())
      ->post(route('admin.logout'))
      ->assertRedirect(route('admin.login'));

    $this->assertGuest();
  }

  public function test_every_crud_index_page_renders(): void
  {
    $modules = [
      'admin.service-categories.index',
      'admin.services.index',
      'admin.portfolio-categories.index',
      'admin.portfolios.index',
      'admin.portfolio-images.index',
      'admin.technologies.index',
      'admin.pricing-packages.index',
      'admin.pricing-features.index',
      'admin.testimonials.index',
      'admin.faqs.index',
      'admin.post-categories.index',
      'admin.posts.index',
      'admin.tags.index',
      'admin.pages.index',
      'admin.inquiries.index',
      'admin.settings.index',
    ];

    $this->actingAs($this->admin());

    foreach ($modules as $route) {
      $this->get(route($route))->assertOk();
    }
  }

  public function test_every_crud_create_page_renders(): void
  {
    $modules = [
      'admin.service-categories.create',
      'admin.services.create',
      'admin.portfolio-categories.create',
      'admin.portfolios.create',
      'admin.portfolio-images.create',
      'admin.technologies.create',
      'admin.pricing-packages.create',
      'admin.pricing-features.create',
      'admin.testimonials.create',
      'admin.faqs.create',
      'admin.post-categories.create',
      'admin.posts.create',
      'admin.tags.create',
      'admin.pages.create',
      'admin.inquiries.create',
      'admin.settings.create',
    ];

    $this->actingAs($this->admin());

    foreach ($modules as $route) {
      $this->get(route($route))->assertOk();
    }
  }

  public function test_service_crud_roundtrip(): void
  {
    $this->actingAs($this->admin());
    $category = ServiceCategory::create(['name' => 'Web', 'slug' => 'web']);

    $this->post(route('admin.services.store'), [
      'name' => 'Landing Page',
      'service_category_id' => $category->id,
      'short_description' => 'Landing page cepat.',
      'starting_price' => 1500000,
      'is_active' => '1',
    ])->assertRedirect(route('admin.services.index'));

    $service = Service::where('name', 'Landing Page')->firstOrFail();
    $this->assertSame('landing-page', $service->slug);
    $this->assertTrue($service->is_active);
    $this->assertTrue($service->category->is($category));

    $this->put(route('admin.services.update', $service->id), [
      'name' => 'Landing Page Pro',
      'service_category_id' => $category->id,
    ])->assertRedirect(route('admin.services.index'));

    $this->assertSame('Landing Page Pro', $service->fresh()->name);

    $this->delete(route('admin.services.destroy', $service->id))
      ->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseMissing('services', ['id' => $service->id]);
  }

  public function test_post_crud_syncs_tags_and_sets_slug(): void
  {
    $this->actingAs($this->admin());
    $category = PostCategory::create(['name' => 'News', 'slug' => 'news']);
    $tag = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);

    $this->post(route('admin.posts.store'), [
      'title' => 'Hello World',
      'post_category_id' => $category->id,
      'content' => 'Isi artikel',
      'status' => 'published',
      'tags' => [$tag->id],
    ])->assertRedirect(route('admin.posts.index'));

    $post = Post::where('slug', 'hello-world')->firstOrFail();

    $this->assertSame('published', $post->status);
    $this->assertNotNull($post->published_at);
    $this->assertTrue($post->tags->contains($tag));
    $this->assertSame($category->id, $post->post_category_id);
  }

  public function test_inquiry_status_can_be_updated(): void
  {
    $this->actingAs($this->admin());

    $inquiry = Inquiry::create([
      'name' => 'Test',
      'email' => 'test@example.com',
      'message' => 'Halo',
      'status' => 'new',
    ]);
    $service = Service::create(['name' => 'Web', 'slug' => 'web']);

    $this->put(route('admin.inquiries.update', $inquiry->id), [
      'name' => 'Test',
      'email' => 'test@example.com',
      'message' => 'Halo',
      'status' => 'contacted',
      'services' => [$service->id],
    ])->assertRedirect(route('admin.inquiries.index'));

    $inquiry->refresh();
    $this->assertSame('contacted', $inquiry->status);
    $this->assertTrue($inquiry->services->contains($service));
  }

  public function test_slug_is_made_unique_automatically(): void
  {
    $this->actingAs($this->admin());

    Faq::create(['question' => 'Q1', 'answer' => 'A1', 'sort_order' => 0, 'is_active' => true]);

    $this->post(route('admin.tags.store'), ['name' => 'PHP']);
    $this->post(route('admin.tags.store'), ['name' => 'PHP']);

    $this->assertSame(2, Tag::where('slug', 'like', 'php%')->count());
    $this->assertDatabaseHas('tags', ['slug' => 'php']);
    $this->assertDatabaseHas('tags', ['slug' => 'php-1']);
  }

  public function test_validation_errors_are_returned_on_invalid_input(): void
  {
    $this->actingAs($this->admin());

    $this->post(route('admin.services.store'), [])
      ->assertSessionHasErrors('name');

    $this->assertDatabaseCount('services', 0);
  }
}
