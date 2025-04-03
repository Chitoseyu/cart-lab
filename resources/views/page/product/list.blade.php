@extends('layouts.app')

@section('title', '商品列表')

@section('content')

<div class="container mt-5" style="min-height:80vh;">
    <!-- 麵包屑 -->
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品列表', 'url' => url('/product/list')],
        ]
    ])
  <div class="row">
        <!-- 商品清單 -->
        @livewire('shop-page')

        <!-- 購物車圖示 -->
        @livewire('cart-icon')
   </div>
   <!-- 畫面提示訊息 -->
   @livewire('flex-alert')
</div>

@endsection