<div>
    @if ($visible)
        <div id="flex-alert" class="alert alert-success" style="position: fixed; top: 3%; left: 50%; transform: translateX(-50%); display: block;">
            <span id="flex-alert-message">{{ $message }}</span>
        </div>
    @endif
</div>

<script>
$(document).ready(function() {

    function showFlexAlert(message) {
        $("#flex-alert-message").text(message);
        $("#flex-alert").fadeIn();
        setTimeout(function() {
            $("#flex-alert").fadeOut();
        }, 3000);
    }

    Livewire.on('hideFlexAlert', function(message) {
        showFlexAlert(message);
    });

});
</script>
