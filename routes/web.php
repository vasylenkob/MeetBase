<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin;

// Logout (для сторінки 403 де Livewire недоступний)
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Публічні сторінки
Route::view('/', 'welcome')->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Auth routes
require __DIR__.'/auth.php';

// Авторизовані маршрути
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard (перенаправляє залежно від ролі)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::view('/profile', 'profile')->name('profile');

    // Квиток
    Route::get('/tickets/{registration}', [RegistrationController::class, 'ticket'])->name('tickets.show');

    // Реєстрація та скасування
    Route::post('/events/{event:slug}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::patch('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');

    // Коментарі
    Route::post('/events/{event:slug}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Оплата
    Route::get('/payment/{event:slug}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{event:slug}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

    // Кабінет організатора
    Route::middleware('role:organizer,admin')->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/events', [EventController::class, 'myEvents'])->name('events');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}', [EventController::class, 'showOrganizerEvent'])->name('events.show');
        Route::get('/events/{event}/edit', [EventController::class, 'editEvent'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroyEvent'])->name('events.destroy');
    });

    // Адмін-панель
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [Admin\UserController::class, 'index'])->name('users');
        Route::patch('/users/{user}/role', [Admin\UserController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/block', [Admin\UserController::class, 'toggleBlock'])->name('users.block');

        Route::get('/events', [Admin\EventController::class, 'index'])->name('events');
        Route::patch('/events/{event}/publish', [Admin\EventController::class, 'publish'])->name('events.publish');
        Route::delete('/events/{event}', [Admin\EventController::class, 'destroy'])->name('events.destroy');

        Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/comments', [Admin\CommentController::class, 'index'])->name('comments');
        Route::patch('/comments/{comment}/approve', [Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::patch('/comments/{comment}/reject', [Admin\CommentController::class, 'reject'])->name('comments.reject');
        Route::delete('/comments/{comment}', [Admin\CommentController::class, 'destroy'])->name('comments.destroy');
    });
});
