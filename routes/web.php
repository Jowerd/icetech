<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogPostController;

// ====================
// 🟢 მომხმარებლის მხარე
// ====================

// მთავარი გვერდი
Route::get('/', function () {
    return view('welcome');
})->name('home');

// პროდუქტის ძიება
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// პროდუქტის სია და დეტალი
Route::get('/products', function () {
    return view('products');
})->name('products');

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

        // ✅ ჩათის ადმინ პანელი - გამოსწორებული
        Route::get('/chat', function () {
            return view('admin.chat');
        })->name('chat');

        // კატეგორიების მართვა
        Route::resource('/categories', CategoryController::class)->names('categories');

        // პროდუქტების მართვა
        Route::resource('/products', ProductController::class)->names('products');

        // შეფასებების მართვა
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // ბლოგპოსტების CRUD (💡 ეს დამატებულია შენს მოთხოვნაზე)
        Route::resource('blog', BlogPostController::class);

        // ნახვების განულების მექანიზმი
        Route::get('/reset-views', function () {
            \App\Models\Product::query()->update(['views_count' => 0]);
            return redirect()->back()->with('status', 'ნახვების რაოდენობა წარმატებით განულდა!');
        })->name('resetViews');
    });
});