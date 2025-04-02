<div class="col-md-6">
    <ul class="list-group">
        @if ($items)
            @foreach ($items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/images/product/' . $item->pic) }}" width="80" height="80" class="me-3" alt="{{ $item->title }}">
                        <div>
                            <a href="{{ url('product/detail/' . $item->id) }}" class="btn btn-link p-0 text-decoration-none">
                                <h6 class="mb-1">{{ $item->title }}</h6>
                            </a>
                            <small class="text-muted">單價: ${{ $item->price }}</small>
                        </div>
                    </div>
                    <div>
                        @if (session()->has('cart.items.' . $item->id))
                            <button wire:click="removeCart({{ $item->id }})" class="btn btn-outline-danger btn-sm">移除</button>
                        @endif
                        <button wire:click="addCart({{ $item->id }})" class="btn btn-outline-primary btn-sm">加入</button>
                    </div>
                </li>
            @endforeach
        @else
            <p>沒有找到商品。</p>
        @endif
    </ul>
</div>