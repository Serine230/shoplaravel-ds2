<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogue', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [HomeController::class, 'category'])->name('category.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);
    Route::get('/mot-de-passe/oublie', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/mot-de-passe/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/mot-de-passe/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/mot-de-passe/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email vérifié avec succès !');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Lien de vérification envoyé !');
    })->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/modifier', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Products (Seller)
    Route::get('/mes-produits', [ProductController::class, 'myProducts'])->name('products.mine');
    Route::get('/produits/creer', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produits', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produits/{product:slug}/modifier', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produits/{product:slug}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produits/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Cart
    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/panier/modifier/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/supprimer/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/panier/vider', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/commandes/passer', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/commandes', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/commandes/{order}/annuler', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews
    Route::post('/avis/{product}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/avis/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('produits', AdminProductController::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'show'    => 'products.show',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);

    // Categories
    Route::resource('categories', AdminCategoryController::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    // Users
    Route::get('utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('utilisateurs/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('utilisateurs/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('utilisateurs/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Orders
    Route::get('commandes', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('commandes/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('commandes/{order}/statut', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});
