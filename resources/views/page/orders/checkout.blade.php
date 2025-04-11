@extends('layouts.app')

@section('title', '結帳頁面')

@section('content')
<div class="container py-4">
     <!-- 麵包屑 -->
     @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品列表', 'url' => url('/product/list')],
            ['label' => '購物車清單', 'url' => url('/orders/cartlist')],
            ['label' => '結帳頁面', 'url' => ''],
        ]
    ])
    <!-- <h1 class="text-center mb-4">🛒 結帳頁面</h1> -->

    @if (!isset($items) || !isset($totalPrice))
        <div class="alert alert-warning text-center">
            找不到有效的結帳資料，請重新操作。
        </div>
    @else
        <form action="{{ route('orders.submit') }}" method="POST">
            @csrf

            <div class="row">
                {{-- 商品明細 --}}
                <div class="col-md-7 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <strong>訂單明細</strong>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div><strong>{{ $item['model']->title }}</strong></div>
                                        <div class="text-muted small">數量：{{ $item['qty'] }}，單價：@price($item['model']->raw_price)</div>
                                    </div>
                                    <span>@price($item['model']->raw_price * $item['qty'])</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>總金額：</strong>
                                <strong class="text-success">@price($totalPrice)</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- 付款資訊表單 --}}
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <strong>收件與付款資訊</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">收件人姓名</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">聯絡電話</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}" required>
                                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">收件地址</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">付款方式</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">請選擇付款方式</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>信用卡</option>
                                    <option value="atm" {{ old('payment_method') == 'atm' ? 'selected' : '' }}>ATM 轉帳</option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>貨到付款</option>
                                </select>
                                @error('payment_method') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-1"></i> 確認並送出訂單
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection