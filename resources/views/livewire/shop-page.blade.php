@section('title', '商品清單')

<div class="container mt-5" style="min-height:80vh;">
    <div class="row">
        <div class="col-md-6">
            <h2>商品列表</h2>
            <ul class="list-group">
                @foreach($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ url('images/product/'. $item->pic) }}" width="50" height="50" class="me-3" alt="{{ $item->title }}">
                            <span>{{ $item->title }} (${{ $item->price }})</span>
                        </div>
                        <div>
                            <a href="#" wire:click="addCart({{$item->id}})" class="btn btn-primary btn-sm">購買</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h2>訂單明細</h2>
            @php
                $cart = session('cart', ['items' => []]); // 取得 session 購物車
            @endphp

            @if(!empty($cart['items']))
                <ul class="list-group">
                    @foreach($cart['items'] as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span>{{ $item['title'] }} (${{ $item['price'] }})</span>
                                <div class="mt-2">
                                    <!-- 減少數量按鈕 -->
                                    <button wire:click="decreaseCart({{ $item['id'] }})" class="btn btn-danger btn-sm">-</button>
                                    <!-- 顯示目前數量 -->
                                    <span class="mx-2">{{ $item['qty'] }}</span>
                                    <!-- 增加數量按鈕 -->
                                    <button wire:click="increaseCart({{ $item['id'] }})" class="btn btn-success btn-sm">+</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    <li class="list-group-item">
                        <strong>總計:</strong> 
                        ${{ collect($cart['items'])->sum(fn($item) => $item['price'] * $item['qty']) }}
                    </li>
                </ul>
                <button wire:click="checkout" class="btn btn-success mt-3">前往結帳</button>
            @else
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <div>
                        購物車內沒有商品
                    </div>
                </div>
            @endif
        </div>
        
    </div>
</div>