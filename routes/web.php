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

Route::group(['prefix' => 'shop'], function () {
    Route::get('/login', [UserController::class, 'login_page'])->name('shop.login_page');
    Route::post('/login', [UserController::class, 'user_login'])->name('shop.login');

    Route::get('/register', [UserController::class, 'register_page'])->name('shop.register_page');   
    Route::post('/register', [UserController::class, 'user_register'])->name('shop.register');

    Route::post('/logout', [UserController::class, 'user_logout'])->name('shop.logout');

    Route::get('/profile', [UserController::class, 'edit_profile'])->name('shop.profile');
    Route::post('/profile', [UserController::class, 'update_profile'])->name('shop.profile.update');


});
Route::group(['prefix' => 'product'], function () {
    
    // 未登入權限

    // 商品清單
    Route::get('/list',[ItemController::class, 'list'])->name('product.list');
    // 商品詳細資料
    Route::get('/detail/{id}', [ItemController::class, 'detail'])->name('product.detail');

    // 登入權限
    Route::group(['middleware' => 'user.login'], function () {

        /* 商品管理功能  Start */
        // 清單顯示
        Route::get('/', [ItemController::class, 'index'])->name('items.index');
        // 新增頁面
        Route::get('/create', [ItemController::class, 'form'])->name('items.create');
        // 編輯頁面
        Route::get('/edit/{id}', [ItemController::class, 'form'])->name('items.edit');
        // 新增
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        // 編輯
        Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
        // 啟用停用開關
        Route::post('/items/toggle-status/{id}', [ItemController::class, 'toggleStatus']);
        // 刪除
        Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
        /* 商品管理功能  End */
    });

});

Route::group(['prefix' => 'orders'], function () {
    // 未登入權限
    Route::get('/cartlist', function () { return view('page.orders.cartlist'); })->name('orders.cartlist');
    Route::post('/cartadd', [ItemController::class, 'addToCart'])->name('orders.cart.add');

    // 登入權限
    Route::group(['middleware' => 'user.login'], function () {
        // 檢視訂單
        Route::get('/list', [OrderController::class, 'list'])->name('orders.list');
        // 刪除訂單
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        // 直接購買
        Route::post('/checkout', [ItemController::class, 'directCheckout'])->name('orders.checkout');
        // 付款完成
        Route::get('/payok', [CartComponent::class, 'payOk'])->name('orders.payok');
      
        // Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulkDelete');
     });


});

