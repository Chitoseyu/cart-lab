@extends('layouts.app')

@section('title', '首頁')

@section('content')
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">歡迎來到我們的購物網站！</h1>
            <img src="{{ asset('images/net_shop.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="網路購物">
            <p class="lead">在這裡，您可以找到各種優質商品，享受愉快的購物體驗。</p>
            <hr class="my-4">
            <p>瀏覽我們的最新商品，或查看熱門商品推薦。</p>
            <a class="btn btn-primary btn-lg" href="/shop" role="button">開始購物</a>
        </div>

        <div class="mt-5 text-center">
            <a href="/shop" class="btn btn-secondary btn-lg">瀏覽所有商品</a>
        </div>
    </div>
@endsection