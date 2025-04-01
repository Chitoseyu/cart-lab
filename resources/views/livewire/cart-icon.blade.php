<div id="toggle-cart"
     style="position: fixed; bottom: 20px; right: 20px; width: 80px; height: 60px; cursor: pointer; border: 2px solid #007bff; border-radius: 10px; background-color: white; z-index: 1001; display: flex; flex-direction: column; justify-content: center; align-items: center; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);"
     wire:click="redirectToCart">
    <img src="{{ asset('images/shopping_cart.png') }}" style="width: 30px; height: 30px;">
    <span style="font-size: 0.8em; font-weight: 600; color: #333;">購物車</span>

    @if ($cartTotal > 0)
        <span id="cart-count"
              style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; border-radius: 50%; min-width: 22px; height: 22px; padding: 2px 6px; font-size: 0.75rem; font-weight: bold; display: flex; justify-content: center; align-items: center; text-align: center; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);">
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