<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\ShopPage;
use App\Livewire\CartComponent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::group(['prefix' => 'shopcart'], function () {
    Route::post('/login', [UserController::class, 'login'])->name('shopcart.login');
    Route::get('/login', [UserController::class, 'login_page'])->name('shopcart.login_page');

});


Route::group(['prefix' => 'orders'], function () {
    Route::get('/shop',ShopPage::class)->name('livewire.shop-page');
    Route::get('/payok', [CartComponent::class, 'payOk'])->name('page.orders.payok');
    Route::get('/cartlist', function () { return view('page.orders.cartlist'); })->name('page.orders.cartlist');
});

