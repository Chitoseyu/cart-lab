@extends('layouts.app')

@section('title', '訂單管理')

<style>
/* 運送狀態區塊 Start */
.timeline-container {
    position: relative;
    padding: 20px 0;
    z-index: 1;
}

.timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: space-between;
}

.timeline-item {
    position: relative;
    width: 20%;
    text-align: center;
    cursor: pointer;
}

.timeline-content {
    position: relative;
}

.timeline-dot {
    width: 12px;
    height: 12px;
    background-color: #ddd;
    border-radius: 50%;
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
}

.timeline-connector {
    width: 30%;
    height: 2px;
    background-color: #ddd;
    margin-top: -14px;
}

.timeline-item.active .timeline-dot,
.timeline-connector.active {
    background-color: #007bff;
}

.timeline-label {
    display: block;
    margin-bottom: 5px;
}

.timeline-date {
    display: block;
    font-size: 0.8em;
    color: #6c757d;
}
.timeline-item.disabled {
    pointer-events: none;
}
/* 運送狀態區塊 End */
/* 展開按鈕區塊樣式 */
.toggle-button {
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px 10px;
    color: #333;
    font-weight: bold;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
}

.toggle-button:hover {
    background-color: #e0e0e0;
}

.details-wrapper {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: flex-start;
}

.detail-box {
    flex: 1;
    min-width: 300px;
}
</style>

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">訂單管理</h2>

    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('orders.list') }}" id="search-form">
                <div class="input-group">
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="輸入訂單編號或商品名稱" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @forelse($orders as $order)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <span class="fw-bold">
                            訂單編號：{{ $order->created_at->format('YmdHis') }}
                        </span>
                        <br>
                        <small class="text-muted">訂單日期：{{ $order->created_at->format('Y-m-d') }}</small>
                        @if(Auth::user()->role_id == 1)
                            <br>
                            <small class="text-muted">下訂用戶：{{ $order->user->name }}</small>
                        @endif

                        <br>
                        <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                            <span class="badge bg-info text-dark">付款方式：{{ $order->payment_method_label }}</span>
                            @if($order->payment_status === '1')
                                <span class="badge bg-success">已付款</span>
                            @else
                                <span class="badge bg-secondary">未付款</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if(Auth::user()->role_id == 1 || Auth::user()->id == $order->user_id)
                    <button type="button" class="btn btn-outline-danger btn-sm delete-order" data-id="{{ $order->id }}" data-bs-toggle="tooltip" title="刪除訂單">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endif
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>總商品數量：</strong> {{ $order->items->sum('pivot.qty') }}</p>
                <p class="mb-3"><strong>訂單總金額：</strong> ${{ number_format($order->total_price, 0) }}</p>


                <!-- 訂單狀態時間軸 Start -->
                    @php
                        $status = $order->status ?? 1;
                        $steps = [
                            1 => '收到訂單',
                            2 => '備貨中',
                            3 => '已出貨',
                            4 => '已送達',
                        ];
                    @endphp

                    <div class="timeline-container my-5">
                        <ul class="timeline">
                            @foreach($steps as $step => $label)
                                <li class="timeline-item {{ $status >= $step ? 'active' : '' }} {{ Auth::user()->role_id != 1 ? 'disabled' : '' }}" data-order-id="{{ $order->id }}" data-step="{{ $step }}">
                                    <div class="timeline-content">
                                        <span class="timeline-label">{{ $label }}</span>
                                        <div class="timeline-dot"></div>
                                        @if ($status == $step && $order->updated_at)
                                            <span class="timeline-date">
                                                {{ $order->updated_at->format('m/d') }}<br>
                                                {{ $order->updated_at->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </li>
                                @if (!$loop->last)
                                    <li class="timeline-connector {{ $status >= ($step + 1) ? 'active' : '' }}"></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                <!-- 訂單狀態時間軸 End -->     

                <!-- 訂單資訊詳細表單 Start -->
                    <div class="d-flex flex-wrap mb-3">
                        <button class="toggle-button" type="button" data-bs-toggle="collapse" data-bs-target="#shippingInfo{{ $order->id }}">
                            <i class="fas fa-plus"></i> 配送資訊
                        </button>
                        <button class="toggle-button" type="button" data-bs-toggle="collapse" data-bs-target="#orderItems{{ $order->id }}">
                            <i class="fas fa-plus"></i> 商品明細
                        </button>
                    </div>

                    <div class="details-wrapper">
                        <div id="shippingInfo{{ $order->id }}" class="collapse detail-box">
                            <div class="card">
                                <div class="card-header">
                                    配送資訊
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"></h5>
                                    <p class="card-text"><strong>收件人：</strong> {{ $order->shipping_name }}</p>
                                    <p class="card-text"><strong>手機號碼：</strong> {{ $order->shipping_phone }}</p>
                                    <p class="card-text"><strong>收件地址：</strong> {{ $order->shipping_address }}</p>
                                    <p class="card-text"><strong>配送方式：</strong> {{ $order->delivery_method_label }}</p>
                                </div>
                            </div>
                        </div>

                        <div id="orderItems{{ $order->id }}" class="collapse show detail-box">
                            <div class="card">
                                <div class="card-header">
                                    商品明細
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($order->items as $item)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $item->title }}</strong>
                                                    <br>
                                                    單價：${{ number_format($item->pivot->order_price, 0) }} × 數量：{{ $item->pivot->qty }}
                                                </div>
                                                <span class="badge bg-primary">
                                                    ${{ number_format($item->pivot->order_price * $item->pivot->qty, 0) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- 訂單資訊詳細表單 End -->
            </div>
        </div>
    @empty
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-file-invoice-dollar me-2"></i>
            <div>目前沒有訂單</div>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<form id="delete-order-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
$(document).ready(function() {

    // 展開隱藏按鈕
    $('.toggle-button').each(function() {
        let $icon = $(this).find('i');
        let targetId = $(this).attr('data-bs-target');
        let $targetElement = $(targetId);

        $(this).click(function() {
            $targetElement.collapse('toggle');
        });

        $targetElement.on('shown.bs.collapse', function() {
            $icon.removeClass('fa-plus').addClass('fa-minus');
        });

        $targetElement.on('hidden.bs.collapse', function() {
            $icon.removeClass('fa-minus').addClass('fa-plus');
        });
    });

    // 初始化圖示狀態 (針對預設展開的區塊)
    $('.collapse.show').each(function() {
        let targetId = '#' + $(this).attr('id');
        $('button[data-bs-target="' + targetId + '"]').find('i').removeClass('fa-plus').addClass('fa-minus');
    });

    // 單筆刪除訂單
    $('.delete-order').click(function() {
        let orderId = $(this).data('id');

        Swal.fire({
            title: '確定要刪除此訂單嗎？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-order-form').attr('action', '/orders/' + orderId).submit();
            }
        });
    });

    // 更改訂單運送狀態
    $('.timeline-item').click(function() {
        let roleId = '{{ Auth::user()->role_id }}';

        if (roleId != 1) {
            return;
        }

        let orderId = $(this).data('order-id');
        let step = $(this).data('step');

        $.ajax({
            url: '/orders/' + orderId + '/status',
            type: 'PUT',
            data: {
                status: step,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    Swal.fire('錯誤', response.message || '更改訂單狀態失敗', 'error');
                }
            },
            error: function() {
                Swal.fire('錯誤', '更改訂單狀態失敗', 'error');
            }
        });
    });

    // 顯示操作訊息
    @if(session('message'))
        Swal.fire({
            icon: "{{ session('type') }}",
            title: '操作訊息',
            text: "{{ session('message') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // 清空搜尋欄位後自動重新整理頁面
    $('#search-input').on('input', function() {
        if (!$(this).val()) {
            window.location = '{{ route('orders.list') }}';
        }
    });
});
</script>
@endsection