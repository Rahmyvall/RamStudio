<?php

namespace Tests\Feature;

use App\Models\Faq;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FaqPageTest extends TestCase
{
  use RefreshDatabase;

  public function test_faq_page_renders_every_active_question(): void
  {
    Faq::create(['question' => 'Q1 aktif', 'answer' => 'A1', 'sort_order' => 1, 'is_active' => true]);
    Faq::create(['question' => 'Q2 aktif', 'answer' => 'A2', 'sort_order' => 2, 'is_active' => true]);
    Faq::create(['question' => 'Q3 nonaktif', 'answer' => 'A3', 'sort_order' => 3, 'is_active' => false]);

    $response = $this->get(route('faqs.index'));

    $response->assertOk()
      ->assertSee('Q1 aktif')
      ->assertSee('Q2 aktif')
      ->assertDontSee('Q3 nonaktif')
      ->assertSee('FAQPage');
  }

  public function test_faq_page_shows_the_empty_state_without_data(): void
  {
    $this->get(route('faqs.index'))
      ->assertOk()
      ->assertSee('Belum ada pertanyaan umum');
  }

  public function test_homepage_links_to_the_faq_page_when_more_questions_exist(): void
  {
    foreach (range(1, 8) as $i) {
      Faq::create(['question' => "Pertanyaan {$i}", 'answer' => "Jawaban {$i}", 'sort_order' => $i, 'is_active' => true]);
    }

    $this->assertCount(8, Faq::query()->active()->get());

    $this->get(route('home'))
      ->assertOk()
      ->assertSee(route('faqs.index'), false);
  }
}
