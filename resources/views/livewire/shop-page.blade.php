@section('title', '商品清單')

<div class="container mt-5" style="min-height:80vh;">
    <div class="row">
        <div class="col-md-6">
            <h2>商品列表</h2>
            <ul class="list-group">
                @foreach($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ url('images/product/'. $item->pic) }}" width="80" height="80" class="me-3" alt="{{ $item->title }}">
                            <div>
                                <h6 class="mb-1">{{ $item->title }}</h6>
                                <small class="text-muted">單價: ${{ $item->price }}</small>
                            </div>
                        </div>
                        <div>
                            @if(session()->has('cart.items.' . $item->id))
                                <button wire:click="removeCart({{ $item->id }})" class="btn btn-outline-danger btn-sm">移除</button>
                            @endif
                            <button wire:click="addCart({{ $item->id }})" class="btn btn-outline-primary btn-sm">加入</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="toggle-cart" style="position: fixed; bottom: 20px; right: 20px; width: 80px; height: 60px; cursor: pointer; border: 2px solid #007bff; border-radius: 5px; background-color: white; z-index: 1001; display: flex; flex-direction: column; justify-content: center; align-items: center;"
             onclick="window.location.href='{{ route('page.orders.cartlist') }}'">
            <img src="{{ asset('images/shopping_cart.png') }}" style="width: 30px; height: 30px;">
            <span style="font-size: 0.8em;">購物車</span>
            <span id="cart-count" style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 5px; font-size: 0.8em; display: none; min-width: 20px; min-height: 20px; text-align: center; line-height: 20px; display: none;"></span>
        </div>
    </div>

    <div id="flex-alert" class="alert alert-success" style="position: fixed; top: 3%; left: 50%; transform: translateX(-50%); display: none;">
        <span id="flex-alert-message"></span>
    </div>
</div>

<script>
$(document).ready(function() {

    function updateCartCount(totalQty) {
        let cartCount = $("#cart-count");
        if (totalQty > 0) {
            cartCount.text(totalQty).css({
                "display": "inline-block",
                "visibility": "visible"
            });
        } else {
            cartCount.hide();
        }
    }

    // 更新購物車數量
    Livewire.on('cartTotalUpdated', function(totalQty) {
        setTimeout(() => updateCartCount(totalQty), 1);
    });

    // 確保 Livewire 更新後能正確選取購物車數量
    document.addEventListener("livewire:load", function () {
        Livewire.hook("message.processed", (message, component) => {
            let totalQty = parseInt($("#cart-count").text()) || 0;
            updateCartCount(totalQty);
        });
    });

    // 顯示 FlexAlert 提示
    function showFlexAlert(message) {
        $("#flex-alert-message").text(message);
        $("#flex-alert").fadeIn();
        setTimeout(function() {
            $("#flex-alert").fadeOut();
        }, 3000); // 3 秒後自動消失
    }

    // 加入購物車提示
    Livewire.on('itemAddedToCart', function(message) {
        setTimeout(() =>  showFlexAlert(message), 1);
    });

    // 移除購物車提示
    Livewire.on('itemRemovedFromCart', function(message) {
        setTimeout(() =>  showFlexAlert(message), 1);
    });
});
</script>