@extends('layouts.app')

@section('title', '商品資訊')

<style>
.toast-custom {
    position: fixed;
    top: 10%;
    right: 40%;
}
.product-image-fill {
    object-fit: contain;
    width: 100%;
    height: 100%;
    max-height: 400px;
}
.star-rating .star {
        font-size: 1.8rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

.star-rating .star.hover,
.star-rating .star.selected {
    color: #f39c12;
}
.star.fas {
    color: gold;
}

.star.far {
    color: gray;
}
.review-section {
    text-align: right;
}

.review-form-container,
.review-list,
.review-section h3 {
    display: inline-block;
    width: 60%;
    vertical-align: top;
    text-align: left;
}

.review-textarea {
    width: 100%;
    resize: none;
}

.review-item {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 10px;
}
</style>

@section('content')
<!-- 麵包屑 -->
@include('components.breadcrumb', [
    'breadcrumbs' => [
        ['label' => '🏠', 'url' => url('/')],
        ['label' => '商品列表', 'url' => url('/product/list')],
        ['label' => $product->title, 'url' => ''],
    ]
])

<div class="row mx-0" style="min-height: 70vh; border: 1px solid #ddd; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1); overflow: hidden;">

    <!-- 左側商品圖片 -->
    <div class="col-md-6 d-flex justify-content-center align-items-center p-4">
        <img src="{{ asset('storage/images/product/' . $product->pic) }}" 
            class="img-fluid rounded shadow product-image-fill" 
            alt="{{ $product->title }}">
    </div>
    
    <!-- 右側商品資訊 -->
    <div class="col-md-6 d-flex flex-column justify-content-between p-4">
        <div>
            <h1 class="mb-3">{{ $product->title }}</h1>

            <div class="mb-2">
                <span class="text-warning fs-5">
                    @for ($i = 1; $i <= 5; $i++)
                        <span>{{ $i <= round($averageRating) ? '★' : '☆' }}</span>
                    @endfor
                </span>
                <span class="text-muted">({{ $reviews->count() }} 筆評價)</span>
            </div>

            <div class="mb-2 d-flex align-items-center">
                @if ($product->discounted_price && $product->discount > 0)
                    <span class="text-danger me-2">
                        <i class="fas fa-tags me-1"></i> {{ $product->discount }}%
                    </span>
                    <span class="text-decoration-line-through text-muted me-2">
                        ${{ $product->price }}
                    </span>
                    <span class="text-success fw-bolder fs-5" style="letter-spacing: 1px;">
                        ${{ $product->discounted_price }}
                    </span>
                @else
                    <span class="text-success fw-bolder fs-5" style="letter-spacing: 1px;">
                        ${{ $product->price }}
                    </span>
                @endif
            </div>

            <p class="text-muted">{{ $product->desc }}</p>
        </div>

        <div class="d-flex justify-content-end gap-3">
            @if ($product->stock > 0)
                <form action="{{ route('orders.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-cart me-1"></i> 加入購物車
                    </button>
                </form>
                <form action="{{ route('orders.checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-bolt me-1"></i> 立即購買
                    </button>
                </form>
            @else
                <button type="submit" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-bell me-1"></i> 到貨通知我
                </button>
            @endif
        </div>
    </div>
</div>

<!-- 商品評論區塊 -->
<div class="mt-5 review-section">
    <h3 class="mb-3">商品評論</h3>
    @if ($hasPurchased)
        <div class="mb-3 review-form-container">
            <h5>留下你的評分</h5>
            <form id="review-form">
                @csrf
                <input type="hidden" name="item_id" value="{{ $product->id }}">
                <div class="mb-2">
                    <span class="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="star far fa-star" data-value="{{ $i }}"></i>
                        @endfor
                    </span>
                    <input type="hidden" name="rating" id="rating" value="0">
                </div>
                <div class="mb-2">
                    <textarea name="comment" class="form-control review-textarea" rows="2" placeholder="撰寫評論..."></textarea>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">送出評論</button>
            </form>
        </div>
    @endif

    <div class="mt-4 review-list">
        @forelse ($reviews as $review)
            <div class="border-bottom py-2 review-item">
                <div class="text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                    @endfor
                </div>
                <p class="mb-1">{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->user->name }} - {{ $review->updated_at->diffForHumans() }}</small>
            </div>
        @empty
            <p class="text-muted">尚無評論，成為第一位評論的買家吧！</p>
        @endforelse
    </div>
</div>
<script>
$(document).ready(function() {
 
    let currentRating = {{ $userReview ? $userReview->rating : 0 }};
    let userComment = "{{ $userReview ? $userReview->comment : '' }}";


    $('#rating').val(currentRating);
    highlightStars(currentRating);

    // 填入評論內容
    $('textarea[name="comment"]').val(userComment);

    // 滑鼠移入
    $('.star').on('mouseenter', function() {
        let value = $(this).data('value');
        highlightStars(value);
    });

    // 滑鼠移出
    $('.star-rating').on('mouseleave', function() {
        highlightStars(currentRating);
    });

    // 點擊星星
    $('.star').on('click', function() {
        currentRating = $(this).data('value');
        $('#rating').val(currentRating);
        highlightStars(currentRating);
    });

    function highlightStars(value) {
        $('.star').each(function() {
            let starVal = $(this).data('value');
            $(this).toggleClass('fas', starVal <= value); // 填滿星星
            $(this).toggleClass('far', starVal > value); // 空心星星
        });
    }

    // 評論提交
    $('#review-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('items.review.submit') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                toastr.success(res.message);
                setTimeout(() => location.reload(), 1000);
            },
            error: function(err) {
                let msg = err.responseJSON?.message || '送出失敗';
                toastr.error(msg);
            }
        });
    });
});
</script>

<!-- 購物車圖示 -->
@livewire('cart-icon')

@if(session('message'))
    <script>
        toastr.options = {
            positionClass: 'toast-custom',
            timeOut: 3000,
            extendedTimeOut: 3000,
        };
        toastr.{{ session('type', 'info') }}('{{ session('message') }}');
    </script>
@endif
@endsection
