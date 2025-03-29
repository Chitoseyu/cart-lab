<?php
$current_url = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/net_shop.png') }}" alt="購物車" style="height: 1.5em; margin-right: 0.5em;">
            購物車
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_url === '/') echo 'active'; ?>" aria-current="page" href="/">首頁</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_url === '/shop') echo 'active'; ?>" href="/shop">商品清單</a>
                </li>
            </ul>
        </div>
    </div>
</nav>