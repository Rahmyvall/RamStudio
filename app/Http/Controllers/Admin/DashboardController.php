<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutJourney;
use App\Models\AboutOwner;
use App\Models\AboutSocialLink;
use App\Models\AboutStatistic;
use App\Models\AboutTeam;
use App\Models\AboutValue;
use App\Models\Faq;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\PricingPackage;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'services' => Service::query()->count(),
            'service_categories' => ServiceCategory::query()->count(),
            'portfolios' => Portfolio::query()->count(),
            'posts' => Post::query()->count(),
            'testimonials' => Testimonial::query()->count(),
            'pricing_packages' => PricingPackage::query()->count(),
            'faqs' => Faq::query()->count(),
            'pages' => Page::query()->count(),
            'inquiries' => Inquiry::query()->count(),
            'new_inquiries' => Inquiry::query()->where('status', 'new')->count(),
            'about_owners' => AboutOwner::query()->count(),
            'about_teams' => AboutTeam::query()->count(),
            'about_values' => AboutValue::query()->count(),
            'about_journeys' => AboutJourney::query()->count(),
            'about_statistics' => AboutStatistic::query()->count(),
            'about_social_links' => AboutSocialLink::query()->count(),
        ];

        $latestInquiries = Inquiry::query()
            ->with('services')
            ->latest('id')
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->with('category')
            ->latest('id')
            ->limit(5)
            ->get();

        $inquiryBreakdown = Inquiry::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact('stats', 'latestInquiries', 'latestPosts', 'inquiryBreakdown'));
    }
}
