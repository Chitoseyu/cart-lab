<div class="col-12"> <ul class="list-group">
        @if ($items->isNotEmpty())
            @foreach ($items as $item)
                <li class="list-group-item d-flex align-items-center">
                    <div class="d-flex align-items-center flex-grow-1">
                        <img src="{{ asset('storage/images/product/' . $item->pic) }}" width="80" height="80" class="me-3 rounded" alt="{{ $item->title }}">
                        <div>
                            <a href="{{ url('product/detail/' . $item->id) }}" class="text-decoration-none">
                                <h6 class="mb-1">{{ $item->title }}</h6>
                            </a>
                            <small class="text-muted d-block">單價: ${{ $item->price }}</small>
                            <small class="text-muted d-block">
                                庫存：
                                <span class="badge bg-{{ $item->stock > 0 ? 'secondary' : 'danger' }}">
                                    {{ $item->stock > 0 ? $item->stock . ' 件' : '已售完' }}
                                </span>
                            </small>
                        </div>
                    </div>
                    <div>
                        @if (session()->has('cart.items.' . $item->id))
                            <button wire:click="removeCart({{ $item->id }})" class="btn btn-outline-danger btn-sm me-2">移除</button>
                        @endif
                        @if ($item->stock < 1)
                            <button class="btn btn-secondary btn-sm" disabled style="opacity: 0.6; cursor: not-allowed;">
                                無庫存
                            </button>
                        @else
                            <button wire:click="addCart({{ $item->id }})" class="btn btn-outline-primary btn-sm">加入</button>
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
</div>