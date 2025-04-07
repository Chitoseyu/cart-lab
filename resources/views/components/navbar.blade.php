<?php
$current_url = $_SERVER['REQUEST_URI'];
?>
<style>
.avatar-container {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 10px;
}
.avatar-image {
    width: 30px;
    height: 30px;
    border-radius: 50%;
}
.name-container {
    display: flex;
    align-items: center;
}
.dropdown-item-with-icon {
    display: flex;
    align-items: center;
    justify-content: start;
}
.dropdown-item-with-icon i {
    margin-right: 8px;
}
.dropdown-menu {
    border: 1px solid #ddd;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}
.dropdown-item {
    padding: 10px 15px;
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow px-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('storage/images/net_shop.png') }}" alt="購物車" style="height: 1.5em; margin-right: 0.5em;">
            購物網站
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            {{-- 左側選單 --}}
            <ul class="nav nav-tabs me-auto" style="border-bottom: none;">
                <li class="nav-item">
                    <a class="nav-link text-light {{ $current_url === '/' ? 'active text-dark' : '' }}" href="/">首頁</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light {{ str_contains($current_url, '/product/list') ? 'active text-dark' : '' }}" href="/product/list">商品列表</a>
                </li>

                @auth
                    @php
                        $user = Auth::user();
                    @endphp

                    <li class="nav-item">
                            <a class="nav-link text-light {{ str_contains($current_url, '/orders/list') ? 'active text-dark' : '' }}" href="/orders/list">訂單管理</a>
                    </li>
                    @if($user->role_id == 1)
                        <li class="nav-item">
                            <a class="nav-link text-light {{ $current_url === '/product' ? 'active text-dark' : '' }}" href="/product">商品管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light {{ str_contains($current_url, '/user/manage') ? 'active text-dark' : '' }}" href="/user/manage">用戶管理</a>
                        </li>
                    @endif
                      
                 
                @endauth
            </ul>

            {{-- 右側用戶區塊 --}}
            @auth
                @php
                    $avatar = $user->avatar ?? null;
                    $avatarUrl = $avatar
                        ? asset('storage/images/avatars/' . $avatar)
                        : asset('storage/images/default_avatar.png');
                @endphp

                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-light" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-container">
                            <img src="{{ $avatarUrl }}" alt="使用者頭像" class="avatar-image">
                        </div>
                        <div class="name-container">
                            <span>{{ $user->name }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item dropdown-item-with-icon" href="{{ url('/shop/profile') }}">
                                <i class="fas fa-user"></i> 個人資料
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('shop.logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item dropdown-item-with-icon">
                                    <i class="fas fa-sign-out-alt"></i> 登出
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="/shop/login" class="btn btn-outline-light">登入</a>
            @endauth
        </div>
    </div>
</nav>
