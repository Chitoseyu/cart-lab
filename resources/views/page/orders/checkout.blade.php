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

    @if (!isset($items) || !isset($totalPrice))
        <div class="alert alert-warning text-center">
            找不到有效的結帳資料，請重新操作。
        </div>
    @else
        <form action="{{ route('orders.submit') }}" method="POST">
            @csrf
            <div class="row">
                <!-- 商品明細區塊 -->
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

                <!-- 收件與付款資訊區塊 -->
                <div class="col-md-5">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <strong>收件與付款資訊</strong>
                        </div>
                        <div class="card-body">
                            <!-- 收件資料 -->
                            <div class="mb-3">
                                <label for="shipping_name" class="form-label">收件人姓名</label>
                                <input type="text" class="form-control" id="shipping_name" name="shipping_name"
                                    value="{{ old('shipping_name', auth()->user()->name ?? '') }}" required>
                                @error('shipping_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="shipping_phone" class="form-label">收件人電話</label>
                                <input type="text" class="form-control" id="shipping_phone" name="shipping_phone"
                                    value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}" required>
                                @error('shipping_phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">收件地址</label>
                                <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                                    value="{{ old('shipping_address', auth()->user()->address ?? '') }}" required>
                                @error('shipping_address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- 配送方式-->
                            <div class="mb-3">
                                <label class="form-label">配送方式</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input shipping-method" type="radio" name="shipping_method" id="delivery" value="delivery"
                                            {{ old('shipping_method', 'delivery') == 'delivery' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="delivery">宅配</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input shipping-method" type="radio" name="shipping_method" id="convenience_store" value="convenience_store"
                                            {{ old('shipping_method') == 'convenience_store' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="convenience_store">超商取貨</label>
                                    </div>
                                </div>
                                @error('shipping_method') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- 配送費用欄位：依據配送方式自動更新 -->
                            <div class="mb-3">
                                <label for="shipping_fee_display" class="form-label">配送費用</label>
                                <!-- 顯示用 -->
                                <p id="shipping_fee_display" class="form-control-plaintext fw-bold">
                                    ${{ old('shipping_fee') ? number_format(old('shipping_fee')) : '100' }}
                                </p>
                                <!-- 隱藏輸入欄位 -->
                                <input type="hidden" id="shipping_fee" name="shipping_fee" value="{{ old('shipping_fee') ? old('shipping_fee') : '100' }}">
                                @error('shipping_fee') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <!-- 付款方式部分 -->
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

<<script>
$(document).ready(function() {
    // 當配送方式 radio 按鈕改變時
    $('.shipping-method').on('change', function() {
        let method = $(this).val();
        let fee = (method === 'convenience_store') ? 60 : 100; // 超商取貨60，其他預設宅配100
        $('#shipping_fee').val(fee);
        $('#shipping_fee_display').text('$' + fee);
    });

    // 頁面載入時，若已有選取值，觸發一次以更新顯示
    $('.shipping-method:checked').trigger('change');
});
</script>
@endsection