@extends('layouts.app')

@section('title', '首頁')

<style>
.toast-custom {
    position: fixed;
    top: 10%;
    right: 1%;
}
.top-products-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.product-card {
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
    background-color: #fff;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card a {
    text-decoration: none;
    color: inherit;
}

.product-card img {
    width: 100%;
    display: block;
}

.product-card .badge {
    font-size: 0.85rem;
}
.product-info {
    text-align: left;
   font-weight: bold;
}
.product-title-container {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.product-discount-badge {
    background:  #f0ad4e;
    color: #fff;
    padding: 0.3em 0.6em;
    border-radius: 3px;
    font-size: 1rem;
    margin-left: 0.5em;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: inline-flex;
    align-items: center;
}

/* 針對小螢幕調整樣式 */
@media (max-width: 768px) {
    .product-discount-badge {
        font-size: 0.9rem;
        padding: 0.3em 0.6em;
    }
}
.price-container {
    display: flex;
    margin-bottom: 5px;
}
.product-price {
    color: red;
    margin-right: 5px;
}

.product-discounted-price {
    text-decoration: line-through;
    color: #888;
}
</style>

@section('content')
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">歡迎來到我們的購物網站！</h1>
            <img src="{{ asset('storage/images/net_shop.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="網路購物">
            <p class="lead">在這裡，您可以找到各種優質商品，瀏覽我們的最新商品，或查看熱門商品推薦。</p>
            <hr class="my-4">
        </div>

        <div class="mt-5">
            <h3 class="text-center mb-4">熱門商品</h3>
            <div id="top-products" class="top-products-grid">
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="/product/list" class="btn btn-secondary btn-lg">瀏覽所有商品</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function loadTopProducts() {
                $.ajax({
                    url: "{{ route('orders.topProducts') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length < 3) {
                            $.ajax({
                                url: "{{ route('orders.randomProducts') }}",
                                type: "GET",
                                dataType: "json",
                                success: function(randomData) {
                                    renderProducts(randomData, true);
                                },
                                error: function() {
                                    $('#top-products').html('<p class="text-center text-muted">無法取得銷售資料。</p>');
                                }
                            });
                        } else {
                            renderProducts(data, false);
                        }
                    },
                    error: function() {
                        $('#top-products').html('<p class="text-center text-muted">無法取得銷售資料。</p>');
                    }
                });
            }

            loadTopProducts(); // 初次載入
            setInterval(loadTopProducts, 60000); // 每分鐘重新載入一次

            function renderProducts(data, isRandom) {
                let html = '';
                data.forEach(function(item, index) {
                    let formattedPrice = item.price ? `$${formatNumber(item.price)}` : '';
                    let formattedDiscountedPrice = (item.discounted_price && item.discount > 0) ? `$${formatNumber(item.discounted_price)}` : '';

                    html += `
                        <div class="card product-card shadow-sm">
                            <a href="/product/detail/${item.id}" class="text-decoration-none">
                                <div class="product-image-container">
                                    <img src="{{ asset('storage/images/product') }}/${item.pic}" class="card-img-top product-image" width="300" height="300" alt="${item.title}">
                                </div>
                                <div class="card-body product-info">
                                    <div class="product-title-container">
                                        <h5 class="product-title mb-1">${item.title}</h5>
                                        ${item.discount ? `<span class="product-discount-badge">-${item.discount}%</span>` : ''}
                                    </div>
                                    <div class="price-container">
                                        ${formattedPrice ? `<p class="product-price">${formattedPrice}</p>` : ''}
                                        ${formattedDiscountedPrice ? `<p class="product-discounted-price">${formattedDiscountedPrice}</p>` : ''}
                                    </div>
                                    <div class="product-rating">
                                        ${item.rating ? generateStars(item.rating) : ''}
                                    </div>
                                </div>
                            </a>
                        </div>
                    `;
                });
                $('#top-products').html(html);
            }
        });
        // 價格千分號顯示
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        // 評分畫面顯示
        function generateStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<i class="fas fa-star text-warning"></i>';
                } else {
                    stars += '<i class="far fa-star text-warning"></i>';
                }
            }
            return stars;
        }
    </script>

    @if(session('message'))
        <script>
            toastr.options = {
                positionClass: 'toast-custom', // 設定顯示位置
                timeOut: 3000, // 設定訊息顯示時間
                extendedTimeOut: 3000, // 設定滑鼠懸停時的顯示時
            };
            toastr.{{ session('type', 'info') }}('{{ session('message') }}');
        </script>
    @endif
@endsection