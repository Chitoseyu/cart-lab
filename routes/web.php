<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
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
Route::group(['prefix' => 'product'], function () {
    
    Route::get('/list',[ItemController::class, 'list'])->name('product.list');
    Route::get('/detail/{id}', [ItemController::class, 'detail'])->name('product.detail');


    Route::get('/', [ItemController::class, 'index'])->name('items.index');
    Route::get('/create', [ItemController::class, 'form'])->name('items.create');
    Route::get('/edit/{id}', [ItemController::class, 'form'])->name('items.edit');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/toggle-status/{id}', [ItemController::class, 'toggleStatus'])->name('items.toggleStatus');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

   

});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/payok', [CartComponent::class, 'payOk'])->name('orders.payok');
    Route::get('/cartlist', function () { return view('page.orders.cartlist'); })->name('orders.cartlist');
    Route::get('/list', [OrderController::class, 'list'])->name('orders.list');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    // Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');

    Route::post('/cartadd', [ItemController::class, 'addToCart'])->name('orders.cart.add');
    Route::post('/checkout', [ItemController::class, 'directCheckout'])->name('orders.checkout');
});

