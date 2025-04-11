<div>
    @php
        $cart = session('cart', ['items' => []]);
    @endphp

     <!-- 麵包屑 -->
     @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品列表', 'url' => url('/product/list')],
            ['label' => '購物車清單', 'url' => ''],
        ]
    ])

    @if (!empty($cart['items']))
        <ul class="list-group mb-3">
            @foreach ($cart['items'] as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/images/product/'. $item['pic']) }}" width="80" height="80" class="me-3" alt="{{ $item['title'] }}">
                        <div>
                            <a href="{{ url('product/detail/' . $item['id']) }}" class="btn btn-link p-0 text-decoration-none">
                                <h6 class="mb-1">{{ $item['title'] }}</h6>
                            </a>
                            <br>
                            <small class="text-muted">單價: @price($item['price'])</small>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center">
                            <button wire:click="decreaseCart({{ $item['id'] }})" class="btn btn-outline-danger btn-sm">-</button>
                            <span class="mx-2">{{ $item['qty'] }}</span>
                            <button wire:click="increaseCart({{ $item['id'] }})" class="btn btn-outline-success btn-sm">+</button>
                        </div>
                        <small class="text-muted mt-2">小計: @price($item['price'] * $item['qty'])</small>
                    </div>
                </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>總計:</strong>
                <span>@price(collect($cart['items'])->sum(fn($item) => $item['price'] * $item['qty']))</span>
            </li>
        </ul>
        <div class="d-flex justify-content-between">
            <button onclick="window.location='{{ url('/product/list') }}'" class="btn btn-secondary">再逛逛</button>
            <button wire:click="checkout" class="btn btn-success">前往結帳</button>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-shopping-cart me-2"></i>
            <div>購物車內尚未加入商品</div>
        </div>
        <button onclick="window.location='{{ url('/product/list') }}'" class="btn btn-secondary mt-3">再逛逛</button>
    @endif
</div>
<script>
$(document).ready(function() {

    Livewire.on('showAlert', function(info) {
            if (info) {
                Swal.fire({
                    icon: info[0]['type'],
                    title: '操作訊息',
                    html: info[0]['message'],
                    showConfirmButton: true
                });
            }
    });
});
</script>