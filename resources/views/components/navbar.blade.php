<style>
/* 用戶區塊相關樣式 */
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

/* 統一下拉選單圖標與文字排列 */
.dropdown-item-with-icon {
    display: flex;
    align-items: center;
    gap: 8px;
}
.dropdown-item-with-icon i {
    font-size: 1rem;
}
.dropdown-menu {
    border: 1px solid #ddd;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}
.dropdown-item {
    padding: 10px 15px;
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* 鈴鐺通知樣式 */
.notification-bell {
    position: relative;
    margin-right: 15px;
    font-size: 1.25rem;
    color: #fff;
    cursor: pointer;
}
.notification-bell .badge {
    position: absolute;
    top: -5px;
    right: -10px;
    background-color: red;
    color: #fff;
    font-size: 0.65rem;
    border-radius: 50%;
    padding: 2px 5px;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow px-4">
    <div class="container-fluid">
        <!-- 品牌 logo -->
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('storage/images/net_shop.png') }}" alt="購物網站" style="height: 1.5em; margin-right: 0.5em;">
            購物網站
        </a>
        <!-- 畫面縮小時的選單按鈕 -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        @php
            $user = Auth::user();
            $avatar = $user->avatar ?? null;
            $avatarUrl = $avatar
                ? asset('storage/images/avatars/' . $avatar)
                : asset('storage/images/default_avatar.png');
        @endphp

        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <!-- 左側選單 -->
            <ul class="nav nav-tabs me-auto" style="border-bottom: none;">

            </ul>

            <!-- 右側用戶及通知區塊 -->
            @auth
                <div class="d-flex align-items-center">
                    <!-- 鈴鐺通知區塊 -->
                    <div class="notification-bell" data-bs-toggle="tooltip" title="通知">
                        <i class="fas fa-bell"></i>
                        <!-- <span class="badge">1</span> -->
                    </div>

                    <!-- 用戶下拉選單 -->
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
                            <!-- 基本會員功能 -->
                            <li>
                                <a class="dropdown-item dropdown-item-with-icon" href="{{ url('/shop/profile') }}">
                                    <i class="fas fa-user"></i> 個人資料
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item dropdown-item-with-icon" href="{{ url('/orders/list') }}">
                                    <i class="fas fa-receipt"></i> 訂單管理
                                </a>
                            </li>
                            @if($user->role_id == 1)
                                <!-- 管理者功能 -->
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item dropdown-item-with-icon" href="{{ url('/product') }}">
                                        <i class="fas fa-box-open"></i> 商品管理
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item dropdown-item-with-icon" href="{{ url('/user/manage') }}">
                                        <i class="fas fa-users"></i> 用戶管理
                                    </a>
                                </li>
                            @endif
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
                </div>
            @else
                <a href="/shop/login" class="btn btn-outline-light">登入</a>
            @endauth
        </div>
    </div>
</nav>
