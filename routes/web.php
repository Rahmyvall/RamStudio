<?php

use App\Http\Controllers\Admin\AboutJourneyController;
use App\Http\Controllers\Admin\AboutOwnerController;
use App\Http\Controllers\Admin\AboutProfileController;
use App\Http\Controllers\Admin\AboutSocialLinkController;
use App\Http\Controllers\Admin\AboutStatisticController;
use App\Http\Controllers\Admin\AboutTeamController;
use App\Http\Controllers\Admin\AboutValueController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PortfolioCategoryController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\PortfolioImageController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PricingFeatureController;
use App\Http\Controllers\Admin\PricingPackageController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FaqController as FrontendFaqController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\PortfolioController as FrontendPortfolioController;
use App\Http\Controllers\Frontend\PricingController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website (frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('tentang-kami', [AboutController::class, 'index'])->name('about');

Route::get('layanan', [FrontendServiceController::class, 'index'])->name('services.index');
Route::get('layanan/{slug}', [FrontendServiceController::class, 'show'])->name('services.show');

Route::get('portofolio', [FrontendPortfolioController::class, 'index'])->name('portfolios.index');
Route::get('portofolio/{slug}', [FrontendPortfolioController::class, 'show'])->name('portfolios.show');

Route::get('harga', [PricingController::class, 'index'])->name('pricing');

Route::get('faq', [FrontendFaqController::class, 'index'])->name('faqs.index');

Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('kontak', [ContactController::class, 'index'])->name('contact');

Route::get('halaman/{slug}', [FrontendPageController::class, 'show'])->name('pages.show');

/*
|--------------------------------------------------------------------------
| Admin Dashboard (web, session authenticated)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::resource('service-categories', ServiceCategoryController::class)->except('show');
        Route::resource('services', ServiceController::class)->except('show');
        Route::resource('portfolio-categories', PortfolioCategoryController::class)->except('show');
        Route::resource('portfolios', PortfolioController::class)->except('show');
        Route::resource('portfolio-images', PortfolioImageController::class)->except('show');
        Route::resource('technologies', TechnologyController::class)->except('show');
        Route::resource('pricing-packages', PricingPackageController::class)->except('show');
        Route::resource('pricing-features', PricingFeatureController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('faqs', FaqController::class)->except('show');
        Route::resource('post-categories', PostCategoryController::class)->except('show');
        Route::resource('posts', PostController::class)->except('show');
        Route::resource('tags', TagController::class)->except('show');
        Route::resource('pages', PageController::class)->except('show');
        Route::resource('inquiries', InquiryController::class)->except('show');
        Route::resource('settings', SettingController::class)->except('show');

        /*
        |------------------------------------------------------------------
        | About / Company profile
        |------------------------------------------------------------------
        */
        Route::prefix('about')->name('about.')->group(function () {
            Route::get('profile', [AboutProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [AboutProfileController::class, 'update'])->name('profile.update');

            Route::resource('owners', AboutOwnerController::class)->except('show');
            Route::resource('teams', AboutTeamController::class)->except('show');
            Route::resource('values', AboutValueController::class)->except('show');
            Route::resource('journeys', AboutJourneyController::class)->except('show');
            Route::resource('statistics', AboutStatisticController::class)->except('show');
            Route::resource('social-links', AboutSocialLinkController::class)->except('show');
        });
    });
});
