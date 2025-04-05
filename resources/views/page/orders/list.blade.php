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
/* 運送狀態區塊 End */

</style>

@section('content')
<div class="container mt-5" style="min-height:80vh;">
    <h2 class="mb-4">訂單管理</h2>


    <!-- 搜尋欄 -->
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('orders.list') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="輸入訂單編號或商品名稱" value="{{ request('search') }}">
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
                        <span class="fw-bold">訂單編號：#{{ $order->id }}</span>
                        <br>
                        <small class="text-muted">訂單日期：{{ $order->created_at->format('Y-m-d') }}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm delete-order" data-id="{{ $order->id }}" data-bs-toggle="tooltip" title="刪除訂單">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>總商品數量：</strong> {{ $order->items->sum('pivot.qty') }}</p>
                <p class="mb-3"><strong>訂單總金額：</strong> ${{ number_format($order->total_price, 0) }}</p>

                <!-- 運送狀態區塊 (原先流程) -->
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
                            <li class="timeline-item {{ $status >= $step ? 'active' : '' }}">
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
                <!-- End 運送狀態區塊 -->

                <hr>
                <h5 class="mb-3">商品明細：</h5>
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
    @empty
        <p class="text-muted text-center">目前沒有訂單</p>
    @endforelse

    <!-- 分頁功能 -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>

<!-- 刪除表單 -->
<form id="delete-order-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
$(document).ready(function() {

    // 當搜尋欄內容改變
    $('input[name="search"]').on('input', function() {
        let keyword = $(this).val().trim();
        // 如果內容為空，重置搜尋
        if (keyword === '') {
            $(this).closest('form').submit();
        }
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
});
</script>
@endsection