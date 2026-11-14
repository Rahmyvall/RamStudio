<?php

use App\Http\Controllers\Api\V1\AboutJourneyController;
use App\Http\Controllers\Api\V1\AboutOwnerController;
use App\Http\Controllers\Api\V1\AboutProfileController;
use App\Http\Controllers\Api\V1\AboutSocialLinkController;
use App\Http\Controllers\Api\V1\AboutStatisticController;
use App\Http\Controllers\Api\V1\AboutTeamController;
use App\Http\Controllers\Api\V1\AboutValueController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\InquiryController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PortfolioCategoryController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\PortfolioImageController;
use App\Http\Controllers\Api\V1\PostCategoryController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\PricingFeatureController;
use App\Http\Controllers\Api\V1\PricingPackageController;
use App\Http\Controllers\Api\V1\ServiceCategoryController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\TechnologyController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API v1
|--------------------------------------------------------------------------
|
| Public (read-only) endpoints are open to everyone. Every write operation
| and the private resources (inquiries, settings) require a Sanctum token
| belonging to an admin user.
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ------- Public authentication -------
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    // ------- Public reads -------
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');

    Route::get('service-categories', [ServiceCategoryController::class, 'index'])->name('service-categories.index');
    Route::get('service-categories/{serviceCategory}', [ServiceCategoryController::class, 'show'])->name('service-categories.show');

    Route::get('portfolios', [PortfolioController::class, 'index'])->name('portfolios.index');
    Route::get('portfolios/{portfolio}', [PortfolioController::class, 'show'])->name('portfolios.show');

    Route::get('portfolio-categories', [PortfolioCategoryController::class, 'index'])->name('portfolio-categories.index');
    Route::get('portfolio-categories/{portfolioCategory}', [PortfolioCategoryController::class, 'show'])->name('portfolio-categories.show');

    Route::get('technologies', [TechnologyController::class, 'index'])->name('technologies.index');
    Route::get('technologies/{technology}', [TechnologyController::class, 'show'])->name('technologies.show');

    Route::get('pricing-packages', [PricingPackageController::class, 'index'])->name('pricing-packages.index');
    Route::get('pricing-packages/{pricingPackage}', [PricingPackageController::class, 'show'])->name('pricing-packages.show');

    Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');

    Route::get('faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('faqs/{faq}', [FaqController::class, 'show'])->name('faqs.show');

    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

    Route::get('post-categories', [PostCategoryController::class, 'index'])->name('post-categories.index');
    Route::get('post-categories/{postCategory}', [PostCategoryController::class, 'show'])->name('post-categories.show');

    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/{page}', [PageController::class, 'show'])->name('pages.show');

    // ------- Public inquiry submission -------
    Route::post('inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

    // ------- Public about / company profile -------
    Route::get('about/profile', [AboutProfileController::class, 'show'])->name('about.profile.show');
    Route::get('about/owners', [AboutOwnerController::class, 'index'])->name('about.owners.index');
    Route::get('about/owners/{aboutOwner}', [AboutOwnerController::class, 'show'])->name('about.owners.show');
    Route::get('about/teams', [AboutTeamController::class, 'index'])->name('about.teams.index');
    Route::get('about/teams/{aboutTeam}', [AboutTeamController::class, 'show'])->name('about.teams.show');
    Route::get('about/values', [AboutValueController::class, 'index'])->name('about.values.index');
    Route::get('about/values/{aboutValue}', [AboutValueController::class, 'show'])->name('about.values.show');
    Route::get('about/journeys', [AboutJourneyController::class, 'index'])->name('about.journeys.index');
    Route::get('about/journeys/{aboutJourney}', [AboutJourneyController::class, 'show'])->name('about.journeys.show');
    Route::get('about/statistics', [AboutStatisticController::class, 'index'])->name('about.statistics.index');
    Route::get('about/statistics/{aboutStatistic}', [AboutStatisticController::class, 'show'])->name('about.statistics.show');
    Route::get('about/social-links', [AboutSocialLinkController::class, 'index'])->name('about.social-links.index');
    Route::get('about/social-links/{aboutSocialLink}', [AboutSocialLinkController::class, 'show'])->name('about.social-links.show');

    // ------- Authenticated admin endpoints -------
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

        Route::post('services', [ServiceController::class, 'store'])->name('services.store');
        Route::match(['put', 'patch'], 'services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

        Route::post('service-categories', [ServiceCategoryController::class, 'store'])->name('service-categories.store');
        Route::match(['put', 'patch'], 'service-categories/{serviceCategory}', [ServiceCategoryController::class, 'update'])->name('service-categories.update');
        Route::delete('service-categories/{serviceCategory}', [ServiceCategoryController::class, 'destroy'])->name('service-categories.destroy');

        Route::post('portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
        Route::match(['put', 'patch'], 'portfolios/{portfolio}', [PortfolioController::class, 'update'])->name('portfolios.update');
        Route::delete('portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');

        Route::post('portfolio-categories', [PortfolioCategoryController::class, 'store'])->name('portfolio-categories.store');
        Route::match(['put', 'patch'], 'portfolio-categories/{portfolioCategory}', [PortfolioCategoryController::class, 'update'])->name('portfolio-categories.update');
        Route::delete('portfolio-categories/{portfolioCategory}', [PortfolioCategoryController::class, 'destroy'])->name('portfolio-categories.destroy');

        Route::post('portfolio-images', [PortfolioImageController::class, 'store'])->name('portfolio-images.store');
        Route::match(['put', 'patch'], 'portfolio-images/{portfolioImage}', [PortfolioImageController::class, 'update'])->name('portfolio-images.update');
        Route::delete('portfolio-images/{portfolioImage}', [PortfolioImageController::class, 'destroy'])->name('portfolio-images.destroy');

        Route::post('technologies', [TechnologyController::class, 'store'])->name('technologies.store');
        Route::match(['put', 'patch'], 'technologies/{technology}', [TechnologyController::class, 'update'])->name('technologies.update');
        Route::delete('technologies/{technology}', [TechnologyController::class, 'destroy'])->name('technologies.destroy');

        Route::post('pricing-packages', [PricingPackageController::class, 'store'])->name('pricing-packages.store');
        Route::match(['put', 'patch'], 'pricing-packages/{pricingPackage}', [PricingPackageController::class, 'update'])->name('pricing-packages.update');
        Route::delete('pricing-packages/{pricingPackage}', [PricingPackageController::class, 'destroy'])->name('pricing-packages.destroy');

        Route::get('pricing-features', [PricingFeatureController::class, 'index'])->name('pricing-features.index');
        Route::get('pricing-features/{pricingFeature}', [PricingFeatureController::class, 'show'])->name('pricing-features.show');
        Route::post('pricing-features', [PricingFeatureController::class, 'store'])->name('pricing-features.store');
        Route::match(['put', 'patch'], 'pricing-features/{pricingFeature}', [PricingFeatureController::class, 'update'])->name('pricing-features.update');
        Route::delete('pricing-features/{pricingFeature}', [PricingFeatureController::class, 'destroy'])->name('pricing-features.destroy');

        Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::match(['put', 'patch'], 'testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

        Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::match(['put', 'patch'], 'faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::match(['put', 'patch'], 'posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

        Route::post('post-categories', [PostCategoryController::class, 'store'])->name('post-categories.store');
        Route::match(['put', 'patch'], 'post-categories/{postCategory}', [PostCategoryController::class, 'update'])->name('post-categories.update');
        Route::delete('post-categories/{postCategory}', [PostCategoryController::class, 'destroy'])->name('post-categories.destroy');

        Route::post('tags', [TagController::class, 'store'])->name('tags.store');
        Route::match(['put', 'patch'], 'tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');

        Route::post('pages', [PageController::class, 'store'])->name('pages.store');
        Route::match(['put', 'patch'], 'pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');

        Route::get('inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
        Route::match(['put', 'patch'], 'inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');
        Route::delete('inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('settings/{setting}', [SettingController::class, 'show'])->name('settings.show');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
        Route::match(['put', 'patch'], 'settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');

        // ------- About / company profile (admin writes) -------
        Route::match(['put', 'patch'], 'about/profile', [AboutProfileController::class, 'update'])->name('about.profile.update');

        Route::post('about/owners', [AboutOwnerController::class, 'store'])->name('about.owners.store');
        Route::match(['put', 'patch'], 'about/owners/{aboutOwner}', [AboutOwnerController::class, 'update'])->name('about.owners.update');
        Route::delete('about/owners/{aboutOwner}', [AboutOwnerController::class, 'destroy'])->name('about.owners.destroy');

        Route::post('about/teams', [AboutTeamController::class, 'store'])->name('about.teams.store');
        Route::match(['put', 'patch'], 'about/teams/{aboutTeam}', [AboutTeamController::class, 'update'])->name('about.teams.update');
        Route::delete('about/teams/{aboutTeam}', [AboutTeamController::class, 'destroy'])->name('about.teams.destroy');

        Route::post('about/values', [AboutValueController::class, 'store'])->name('about.values.store');
        Route::match(['put', 'patch'], 'about/values/{aboutValue}', [AboutValueController::class, 'update'])->name('about.values.update');
        Route::delete('about/values/{aboutValue}', [AboutValueController::class, 'destroy'])->name('about.values.destroy');

        Route::post('about/journeys', [AboutJourneyController::class, 'store'])->name('about.journeys.store');
        Route::match(['put', 'patch'], 'about/journeys/{aboutJourney}', [AboutJourneyController::class, 'update'])->name('about.journeys.update');
        Route::delete('about/journeys/{aboutJourney}', [AboutJourneyController::class, 'destroy'])->name('about.journeys.destroy');

        Route::post('about/statistics', [AboutStatisticController::class, 'store'])->name('about.statistics.store');
        Route::match(['put', 'patch'], 'about/statistics/{aboutStatistic}', [AboutStatisticController::class, 'update'])->name('about.statistics.update');
        Route::delete('about/statistics/{aboutStatistic}', [AboutStatisticController::class, 'destroy'])->name('about.statistics.destroy');

        Route::post('about/social-links', [AboutSocialLinkController::class, 'store'])->name('about.social-links.store');
        Route::match(['put', 'patch'], 'about/social-links/{aboutSocialLink}', [AboutSocialLinkController::class, 'update'])->name('about.social-links.update');
        Route::delete('about/social-links/{aboutSocialLink}', [AboutSocialLinkController::class, 'destroy'])->name('about.social-links.destroy');
    });
});
