@extends('layouts.app')

@section('title', '商品資訊')

@section('content')
<div class="container mt-5 w-100" style="min-height:70vh;">
    <!-- 麵包屑 -->
    @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品列表', 'url' => url('/product/list')],
            ['label' => $product->title, 'url' => ''],
        ]
    ])

    <div class="row mx-0" style="min-height: 70vh; border: 1px solid #ddd; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">
    
        <!-- 左側商品圖片 -->
        <div class="col-md-6 d-flex justify-content-center align-items-center p-4">
            <img src="{{ asset('images/product/' . $product->pic) }}" 
                class="img-fluid rounded shadow" 
                alt="{{ $product->title }}" 
                style="max-width: 100%; max-height: 400px;">
        </div>
        
        <!-- 右側商品資訊 -->
        <div class="col-md-6 d-flex flex-column justify-content-between p-4">
            <div>
                <h1 class="mb-3">{{ $product->title }}</h1>

                <!-- 評價 -->
                <div class="mb-2">
                    <span class="text-dark fw-bold fs-4 me-2">5.0</span>
                    <span class="text-warning fs-4">
                        ★ ★ ★ ★ ★
                    </span>
                    
                </div>

                <p class="text-muted">{{ $product->desc }}</p>
            </div>
            <div class="d-flex justify-content-end gap-3">
                <button class="btn btn-outline-primary btn-lg">加入購物車</button>
                <button class="btn btn-outline-success btn-lg">立即購買</button>
            </div>
        </div>
    </div>




    <!-- 購物車圖示 -->
    @livewire('cart-icon')
    
</div>
@endsection
