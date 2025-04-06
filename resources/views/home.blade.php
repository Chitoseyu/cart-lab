@extends('layouts.app')

@section('title', '首頁')

<style>
.toast-custom {
    position: fixed;
    top: 10%;
    right: 1%;
}
</style>
@section('content')
    <div class="container mt-5" style="min-height:80vh;">
        <div class="jumbotron text-center">
            <h1 class="display-4">歡迎來到我們的購物網站！</h1>
            <img src="{{ asset('storage/images/net_shop.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="網路購物">
            <p class="lead">在這裡，您可以找到各種優質商品，享受愉快的購物體驗。</p>
            <hr class="my-4">
            <p>瀏覽我們的最新商品，或查看熱門商品推薦。</p>
            <a class="btn btn-primary btn-lg" href="/product/list" role="button">開始購物</a>
        </div>

        <div class="mt-5 text-center">
            <a href="/product/list" class="btn btn-secondary btn-lg">瀏覽所有商品</a>
        </div>
    </div>
    @if(session('message'))
        <script>
            toastr.options = {
                positionClass: 'toast-custom', // 設定顯示位置
                timeOut: 3000, // 設定訊息顯示時間
                extendedTimeOut: 3000, // 設定滑鼠懸停時的顯示時
            };
            toastr.{{ session('type', 'info') }}('{{ session('message') }}');
        </script>
    @endif
@endsection