<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\ProductImageController;

// ====================
// 🟢 მომხმარებლის მხარე
// ====================

// მთავარი გვერდი
Route::get('/', function () {
    $slides = \App\Models\Slide::active()->get();
    return view('welcome', compact('slides'));
})->name('home');

// პროდუქტის ძიება
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// პროდუქტის სია და დეტალი
Route::get('/products', [ProductController::class, 'allProducts'])->name('products');

Route::get('/compare', [ProductController::class, 'compare'])->name('products.compare');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// კატეგორიის ჩვენება - ორი route იგივე controller action-ისთვის
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('category.products');
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// შეფასების დამატება (მომხმარებლის მხრიდან)
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// გვერდები
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// =====================
// 📘 ბლოგის PUBLIC ნაწილი
// =====================
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/api/products/suggestions', [ProductController::class, 'getSuggestions'])->name('products.suggestions');
// ========================
// 🔐 ადმინის პანელი & აუთენთიკაცია
// ========================
Route::prefix('admin')->name('admin.')->group(function () {

    // ლოგინი/ლოგაუტი
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // 👨‍💻 დაცული admin routing — მხოლოდ ავტორიზებული admin-ებისთვის
    Route::middleware(['admin'])->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // კატეგორიების მართვა
        Route::resource('/categories', CategoryController::class)->names('categories');

        // პროდუქტების მართვა
        Route::resource('/products', ProductController::class)->names('products');

        // პროდუქტის გალერეა
        Route::post('/products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');

        // შეფასებების მართვა
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // ბლოგპოსტების CRUD (💡 ეს დამატებულია შენს მოთხოვნაზე)
        Route::resource('blog', BlogPostController::class);

        // ბანერის სლაიდები
        Route::resource('slides', SlideController::class)->names('slides');

        // ნახვების განულების მექანიზმი
        Route::get('/reset-views', function () {
            \App\Models\Product::query()->update(['views_count' => 0]);
            return redirect()->back()->with('status', 'ნახვების რაოდენობა წარმატებით განულდა!');
        })->name('resetViews');
    });
});