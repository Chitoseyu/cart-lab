<?php
$current_url = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow px-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('storage/images/net_shop.png') }}" alt="購物車" style="height: 1.5em; margin-right: 0.5em;">
            購物網站
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav nav-tabs me-auto" style="border-bottom: none;">
                <li class="nav-item">
                    <a class="nav-link text-light <?php if ($current_url === '/') echo 'active text-dark'; ?>" aria-current="page" href="/">首頁</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light <?php if (strpos($current_url, '/product/list') !== false) echo 'active text-dark'; ?>" href="/product/list">商品列表</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-light <?php if ($current_url === '/product') echo 'active text-dark'; ?>" href="/product">商品管理</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light <?php if (strpos($current_url, '/orders/list') !== false) echo 'active text-dark'; ?>" href="/orders/list">訂單管理</a>
                </li>
            </ul>
            <a href="/shopcart/login" class="btn btn-outline-light">登入</a>
        </div>
    </div>
</nav>