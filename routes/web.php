<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\BasisController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\Clients\HomeController::class, 'index'])->middleware(['auth'])->name('clients.home');
Route::get('/manifest.json', [App\Http\Controllers\BasisController::class, 'manifest'])->name('manifest');
Route::get('/profile', [App\Http\Controllers\Clients\HomeController::class, 'profile'])->name('clients.profile')->middleware(['auth', 'password.confirm']);
Route::post('/profile', [App\Http\Controllers\Clients\HomeController::class, 'update'])->name('clients.profile.update')->middleware(['auth', 'password.confirm']);
Route::post('/profile/tfa', [App\Http\Controllers\Clients\HomeController::class, 'tfa'])->name('clients.profile.tfa')->middleware(['auth', 'password.confirm']);
Route::get('/change-password', [App\Http\Controllers\Clients\HomeController::class, 'password'])->name('clients.password.change-password')->middleware(['auth']);
Route::get('/tos', [App\Http\Controllers\BasisController::class, 'tos'])->name('tos');

Route::group(['prefix' => 'checkout'], function () {
    Route::get('/', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/config/{product}', [App\Http\Controllers\CheckoutController::class, 'config'])->name('checkout.config');
    Route::post('/config/{product}', [App\Http\Controllers\CheckoutController::class, 'configPost'])->name('checkout.config.post');
    Route::post('/', [App\Http\Controllers\CheckoutController::class, 'pay'])->name('checkout.pay')->middleware('auth');
    Route::post('/coupon', [App\Http\Controllers\CheckoutController::class, 'coupon'])->name('checkout.coupon');
    Route::post('/{id}', [App\Http\Controllers\CheckoutController::class, 'remove'])->name('checkout.remove');
    Route::post('/{product}/update', [App\Http\Controllers\CheckoutController::class, 'update'])->name('checkout.update');
    Route::get('/add/{product}', [App\Http\Controllers\CheckoutController::class, 'add'])->name('checkout.add');
});

Route::group(['prefix' => 'tickets', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Clients\TicketController::class, 'index'])->name('clients.tickets.index');
    Route::get('/create', [App\Http\Controllers\Clients\TicketController::class, 'create'])->name('clients.tickets.create');
    Route::post('/store', [App\Http\Controllers\Clients\TicketController::class, 'store'])->name('clients.tickets.store');
    Route::get('/{ticket}', [App\Http\Controllers\Clients\TicketController::class, 'show'])->name('clients.tickets.show');
    Route::post('{ticket}/reply', [App\Http\Controllers\Clients\TicketController::class, 'reply'])->name('clients.tickets.reply');
    Route::post('{ticket}/close', [App\Http\Controllers\Clients\TicketController::class, 'close'])->name('clients.tickets.close');
});

Route::group(['prefix' => 'invoices', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Clients\InvoiceController::class, 'index'])->name('clients.invoice.index');
    Route::get('/{invoice}', [App\Http\Controllers\Clients\InvoiceController::class, 'show'])->name('clients.invoice.show');
    Route::post('/{invoice}/pay', [App\Http\Controllers\Clients\InvoiceController::class, 'pay'])->name('clients.invoice.pay');
});

Route::group(['prefix' => 'announcements'], function () {
    Route::get('/', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'view'])->name('announcements.view');
});

Route::group(['prefix' => 'client/products', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Clients\ProductController::class, 'index'])->name('clients.active-products.index');
    Route::get('/{product}', [App\Http\Controllers\Clients\ProductController::class, 'index'])->name('clients.active-products.show');
});

Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {
    Route::get('/', [App\Http\Controllers\Clients\APIController::class, 'index'])->name('clients.api.index');
    Route::post('/', [App\Http\Controllers\Clients\APIController::class, 'create'])->name('clients.api.create');
    Route::delete('/{id}', [App\Http\Controllers\Clients\APIController::class, 'delete'])->name('clients.api.delete');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/extensions.php';

Route::get('/{slug?}/{product?}', [App\Http\Controllers\BasisController::class, 'products'])->name('products');
