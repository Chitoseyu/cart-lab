<div class="col-12"> <ul class="list-group">
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                <li class="list-group-item d-flex align-items-center">
                    <div class="d-flex align-items-center flex-grow-1">
                        <img src="{{ asset('storage/images/product/' . $item->pic) }}" width="80" height="80" class="me-3 rounded" alt="{{ $item->title }}">
                        <div>
                            <a href="{{ url('product/detail/' . $item->id) }}" class="text-decoration-none">
                                <h6 class="mb-1">
                                    {{ $item->title }}
                                    @if ($item->discount > 0)
                                        <span class="badge bg-danger ms-2" style="font-size: 0.75rem;">
                                            <i class="fas fa-tags me-1"></i>-{{ $item->discount }}%
                                        </span>
                                    @endif
                                </h6>
                            </a>
                            @if ($item->discount > 0)
                                <small class="text-muted d-block">
                                    <span class="text-danger fw-bold">單價：${{ $item->discounted_price }}</span>
                                </small>
                            @else
                                <small class="text-muted d-block">單價：${{ $item->price }}</small>
                            @endif

                            <small class="text-muted d-block mt-1">
                                庫存：
                                <span class="badge bg-{{ $item->stock > 0 ? 'secondary' : 'danger' }}">
                                    {{ $item->stock > 0 ? $item->stock . ' 件' : '已售完' }}
                                </span>
                            </small>
                        </div>
                    </div>
                    <div>
                        @if (session()->has('cart.items.' . $item->id))
                            <button wire:click="removeCart({{ $item->id }})" class="btn btn-outline-danger btn-sm me-2" data-tippy-content="移除">
                                <i class="fas fa-cart-arrow-down me-1"></i>
                            </button>
                        @endif

                        @if ($item->stock < 1)
                            <button class="btn btn-secondary btn-sm" disabled style="opacity: 0.6; cursor: not-allowed;">
                                <i class="fas fa-hourglass-half me-1"></i> 補貨中
                            </button>
                        @else
                            <button wire:click="addCart({{ $item->id }})" class="btn btn-outline-primary btn-sm" data-tippy-content="加入">
                                <i class="fas fa-cart-plus me-1"></i>
                            </button>
                        @endif
                    </div>
                </li>
            @endforeach

            @if ($items->hasPages())
                <li wire:ignore class="list-group-item d-flex justify-content-center mt-1">
                    {{ $items->links('vendor.pagination.bootstrap-4') }}
                </li>
            @endif
        @else
            <li class="list-group-item text-center text-muted">沒有找到符合條件的商品。</li>
        @endif
    </ul>

<script>
    $(document).ready(function() {
        $('[data-tippy-content]').each(function() {
            tippy(this, {
                content: $(this).data('tippy-content'),
                placement: 'top',
                theme: 'light',
            });
        });
    });
</script>
</div>