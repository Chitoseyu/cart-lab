<div id="toggle-cart"
     style="position: fixed; bottom: 20px; right: 20px; width: 80px; height: 60px; cursor: pointer; border: 2px solid #007bff; border-radius: 5px; background-color: white; z-index: 1001; display: flex; flex-direction: column; justify-content: center; align-items: center;"
     wire:click="redirectToCart">
    <img src="{{ asset('images/shopping_cart.png') }}" style="width: 30px; height: 30px;">
    <span style="font-size: 0.8em;">購物車</span>
    @if ($cartTotal > 0)
        <span id="cart-count"
              style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; padding: 5px; font-size: 0.8em; min-width: 20px; min-height: 20px; text-align: center; line-height: 20px;">
            {{ $cartTotal }}
        </span>
    @endif
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

});
</script>