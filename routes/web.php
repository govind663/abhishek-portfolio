<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ===== Middleware
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\PreventCitizenBackHistoryMiddleware;
use App\Http\Middleware\OptimizeImagesMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;

// ===== Frontend Controllers
use App\Http\Controllers\frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\frontend\AiChatController;

// ===== Backend Controllers
use App\Http\Controllers\backend\Auth\RegisterController;
use App\Http\Controllers\backend\Auth\LoginController;
use App\Http\Controllers\backend\Auth\ForgotPasswordController;
use App\Http\Controllers\backend\Auth\ResetPasswordController;
use App\Http\Controllers\backend\HomeController as BackendHomeController;
use App\Http\Controllers\backend\SeoSettingController;
use App\Http\Controllers\backend\HeroController;
use App\Http\Controllers\backend\BrandDescriptionController;
use App\Http\Controllers\backend\SocialLinkController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\CopyrightController;
use App\Http\Controllers\backend\PageTitleController;
use App\Http\Controllers\backend\AboutController;
use App\Http\Controllers\backend\StatController;
use App\Http\Controllers\backend\SkillController;
use App\Http\Controllers\backend\FeatureController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Root → Home
Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.home');

// Login Redirect Logic
Route::get('/login', function () {
    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard')->with('success', 'Already logged in!');
    } else {
        return redirect()->route('admin.login')->with('info', 'Please login first!');
    }
})->name('login');


/*
|--------------------------------------------------------------------------
| Frontend Routes (SEO Optimized)
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => '',
    'middleware' => [
        PreventCitizenBackHistoryMiddleware::class,
        OptimizeImagesMiddleware::class
    ]
], function () {

    // ===== About
    Route::get('/about-us', [FrontendHomeController::class, 'about'])->name('frontend.about');

    // ===== Resume
    Route::get('/resume', [FrontendHomeController::class, 'resume'])->name('frontend.resume');

    // ===== Services
    Route::prefix('services')->name('frontend.services.')->group(function () {

        Route::get('/', [FrontendHomeController::class, 'services'])->name('list');

        Route::get('/{slug}', [FrontendHomeController::class, 'serviceDetails'])->name('details');
    });

    // ===== Portfolio
    Route::prefix('portfolio')->name('frontend.portfolio.')->group(function () {

        Route::get('/', [FrontendHomeController::class, 'portfolio'])->name('list');

        Route::get('/{slug}', [FrontendHomeController::class, 'portfolioDetails'])->name('details');
    });

    // ===== Contact
    Route::get('/contact-us', [FrontendHomeController::class, 'contact'])->name('frontend.contact');
    Route::post('/contact-us/store', [FrontendHomeController::class, 'storeContact'])->name('frontend.contact.store');

    // ===== Utility Pages
    Route::get('/under-construction', [FrontendHomeController::class, 'underConstruction'])->name('frontend.under-construction');
    Route::get('/thank-you', [FrontendHomeController::class, 'thankYou'])->name('frontend.thank-you');
});


/*
|--------------------------------------------------------------------------
| Admin Guest Routes (Login / Register / Forgot Password)
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'admin',
    'middleware' => [RedirectIfAuthenticatedCustom::class, OptimizeImagesMiddleware::class]
], function () {

    // ===== Register
    Route::get('register', [RegisterController::class, 'register'])->name('admin.register');
    Route::post('register/store', [RegisterController::class, 'store'])->name('admin.register.store');

    // ===== Login
    Route::get('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('login/store', [LoginController::class, 'authenticate'])->name('admin.login.store');

    // ===== Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.forget-password.request');
    Route::post('forgot-password/send-email-link', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.forget-password.send-email-link.store');

    // ===== Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('admin.password.update');
});


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:web', PreventBackHistoryMiddleware::class, OptimizeImagesMiddleware::class]
], function () {

    // ===== Dashboard
    Route::get('/dashboard', [BackendHomeController::class, 'adminHome'])->name('admin.dashboard');

    // ===== Profile
    Route::get('/profile', [BackendHomeController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/profile/update', [BackendHomeController::class, 'updateAdminProfile'])->name('admin.profile.update');

    // ===== Change Password
    Route::get('/change-password', [BackendHomeController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [BackendHomeController::class, 'updatePassword'])->name('update-password');

    // ===== Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // ===== SEO Settings Resource
    Route::resource('seo-settings', SeoSettingController::class);

    // ===== Hero Resource
    Route::resource('hero', HeroController::class);

    // ===== Page Titles Resource
    Route::resource('page-titles', PageTitleController::class);
    
    // ===== About Resource
    Route::resource('about', AboutController::class);

    // ==== Stats Resource
    Route::resource('stats', StatController::class);
    
    // ==== Skills Resource
    Route::resource('skills', SkillController::class);

    // ===== Features Resource
    Route::resource('features', FeatureController::class);
    
    // ===== Brand Description Resource
    Route::resource('brand-description', BrandDescriptionController::class);

    // ===== Social Links Resource
    Route::resource('social-links', SocialLinkController::class);                                                

    // ===== Contacts Resource
    Route::resource('contacts', ContactController::class);
    
    // ===== Copyrights Resource
    Route::resource('copyrights', CopyrightController::class);
});


/*
|--------------------------------------------------------------------------
| SEO / Utility Routes
|--------------------------------------------------------------------------
*/

// ===== Robots.txt
Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:", 200)
        ->header('Content-Type', 'text/plain');
});

// ===== Sitemap
Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')
        ->header('Content-Type', 'application/xml');
});


/*
|--------------------------------------------------------------------------
| AI Chat Bot
|--------------------------------------------------------------------------
*/
Route::post('/ai-chat', [AiChatController::class,'chat'])->name('ai.chat')->middleware('web');


/*
|--------------------------------------------------------------------------
| Chrome DevTools Manifest
|--------------------------------------------------------------------------
*/
Route::get('/.well-known/appspecific/com.chrome.devtools.json', function () {
    return response()->json([]);
});