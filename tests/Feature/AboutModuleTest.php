<?php

namespace Tests\Feature;

use App\Models\AboutJourney;
use App\Models\AboutOwner;
use App\Models\AboutProfile;
use App\Models\AboutSocialLink;
use App\Models\AboutStatistic;
use App\Models\AboutTeam;
use App\Models\AboutValue;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AboutModuleTest extends TestCase
{
  use RefreshDatabase;

  private function admin(): User
  {
    return User::factory()->create(['role' => User::ROLE_ADMIN]);
  }

  public function test_about_admin_pages_render(): void
  {
    $this->actingAs($this->admin());

    $routes = [
      'admin.about.profile.edit',
      'admin.about.owners.index',
      'admin.about.owners.create',
      'admin.about.teams.index',
      'admin.about.teams.create',
      'admin.about.values.index',
      'admin.about.values.create',
      'admin.about.journeys.index',
      'admin.about.journeys.create',
      'admin.about.statistics.index',
      'admin.about.statistics.create',
      'admin.about.social-links.index',
      'admin.about.social-links.create',
    ];

    foreach ($routes as $route) {
      $this->get(route($route))->assertOk();
    }
  }

  public function test_profile_can_be_created_and_updated(): void
  {
    $this->actingAs($this->admin());

    $this->put(route('admin.about.profile.update'), [
      'company_name' => 'RAMStudio',
      'tagline' => 'Creative Studio',
      'email' => 'hello@ramstudio.test',
      'founded_year' => 2018,
    ])->assertRedirect(route('admin.about.profile.edit'));

    $this->assertDatabaseHas('about_profiles', ['company_name' => 'RAMStudio']);

    $this->put(route('admin.about.profile.update'), [
      'company_name' => 'RAMStudio Updated',
    ])->assertRedirect(route('admin.about.profile.edit'));

    $this->assertSame(1, AboutProfile::query()->count());
    $this->assertSame('RAMStudio Updated', AboutProfile::current()->company_name);
  }

  public function test_owner_crud_roundtrip(): void
  {
    $this->actingAs($this->admin());

    $this->post(route('admin.about.owners.store'), [
      'name' => 'Rama Aditya',
      'position' => 'Founder',
      'is_active' => '1',
    ])->assertRedirect(route('admin.about.owners.index'));

    $owner = AboutOwner::where('name', 'Rama Aditya')->firstOrFail();
    $this->assertTrue($owner->is_active);

    $this->put(route('admin.about.owners.update', $owner->id), [
      'name' => 'Rama A.',
      'position' => 'CEO',
    ])->assertRedirect(route('admin.about.owners.index'));

    $this->assertSame('Rama A.', $owner->fresh()->name);

    $this->delete(route('admin.about.owners.destroy', $owner->id))
      ->assertRedirect(route('admin.about.owners.index'));

    $this->assertDatabaseMissing('about_owners', ['id' => $owner->id]);
  }

  public function test_team_value_journey_statistic_and_social_crud(): void
  {
    $this->actingAs($this->admin());

    $this->post(route('admin.about.teams.store'), ['name' => 'Sinta', 'position' => 'Designer'])
      ->assertRedirect(route('admin.about.teams.index'));
    $this->assertDatabaseHas('about_teams', ['name' => 'Sinta']);

    $this->post(route('admin.about.values.store'), [
      'title' => 'Visi',
      'type' => 'vision',
      'description' => 'Menjadi terbaik.',
    ])->assertRedirect(route('admin.about.values.index'));
    $this->assertDatabaseHas('about_values', ['title' => 'Visi', 'type' => 'vision']);

    $this->post(route('admin.about.journeys.store'), [
      'year' => 2018,
      'title' => 'Didirikan',
    ])->assertRedirect(route('admin.about.journeys.index'));
    $this->assertDatabaseHas('about_journeys', ['year' => 2018]);

    $this->post(route('admin.about.statistics.store'), [
      'label' => 'Proyek',
      'value' => '120',
      'suffix' => '+',
    ])->assertRedirect(route('admin.about.statistics.index'));
    $this->assertDatabaseHas('about_statistics', ['label' => 'Proyek']);

    $this->post(route('admin.about.social-links.store'), [
      'platform' => 'Instagram',
      'url' => 'https://instagram.com/ramstudio',
    ])->assertRedirect(route('admin.about.social-links.index'));
    $this->assertDatabaseHas('about_social_links', ['platform' => 'Instagram']);
  }

  public function test_about_validation_rejects_invalid_data(): void
  {
    $this->actingAs($this->admin());

    $this->post(route('admin.about.values.store'), [])
      ->assertSessionHasErrors(['title', 'type']);

    $this->post(route('admin.about.journeys.store'), ['year' => 'abc', 'title' => ''])
      ->assertSessionHasErrors(['year', 'title']);

    $this->post(route('admin.about.social-links.store'), ['platform' => 'X', 'url' => 'not-a-url'])
      ->assertSessionHasErrors('url');
  }

  public function test_public_about_api_endpoints(): void
  {
    AboutProfile::create(['company_name' => 'RAMStudio']);
    AboutOwner::create(['name' => 'Rama', 'is_active' => true]);
    AboutTeam::create(['name' => 'Sinta', 'is_active' => true]);
    AboutValue::create(['title' => 'Visi', 'type' => 'vision', 'is_active' => true]);
    AboutJourney::create(['year' => 2018, 'title' => 'Didirikan', 'is_active' => true]);
    AboutStatistic::create(['label' => 'Proyek', 'value' => '120', 'is_active' => true]);
    AboutSocialLink::create(['platform' => 'Instagram', 'url' => 'https://instagram.com/x', 'is_active' => true]);

    $this->getJson('/api/v1/about/profile')
      ->assertOk()
      ->assertJsonPath('data.company_name', 'RAMStudio');

    foreach (['owners', 'teams', 'values', 'journeys', 'statistics', 'social-links'] as $resource) {
      $this->getJson("/api/v1/about/{$resource}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data');
    }
  }

  public function test_about_api_writes_require_authentication(): void
  {
    $this->postJson('/api/v1/about/owners', ['name' => 'X'])->assertUnauthorized();
    $this->putJson('/api/v1/about/profile', ['company_name' => 'X'])->assertUnauthorized();
  }

  public function test_admin_can_write_about_via_api(): void
  {
    Sanctum::actingAs($this->admin());

    $this->postJson('/api/v1/about/owners', ['name' => 'Rama', 'position' => 'Founder'])
      ->assertCreated()
      ->assertJsonPath('data.name', 'Rama');

    $this->putJson('/api/v1/about/profile', ['company_name' => 'RAMStudio API'])
      ->assertOk()
      ->assertJsonPath('data.company_name', 'RAMStudio API');
  }

  public function test_post_featured_image_url_is_host_relative(): void
  {
    $category = PostCategory::create(['name' => 'News', 'slug' => 'news']);
    $post = Post::create([
      'post_category_id' => $category->id,
      'title' => 'Hello',
      'slug' => 'hello',
      'content' => 'Body',
      'status' => 'published',
      'published_at' => now(),
      'featured_image' => 'uploads/posts/example.png',
    ]);

    $this->getJson("/api/v1/posts/{$post->id}")
      ->assertOk()
      ->assertJsonPath('data.featured_image', '/storage/uploads/posts/example.png');
  }
}
