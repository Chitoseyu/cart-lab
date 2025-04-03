@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<div class="container mt-5" style="min-height:70vh;">
     <!-- 麵包屑 -->
     @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品管理', 'url' => ''],
        ]
    ])
    <div class="row mt-5 mb-2">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="{{ route('items.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> 新增</a>
            <form method="GET" action="{{ route('items.index') }}" class="d-flex gap-2 align-items-center">
                <select name="filter_column" class="form-select w-auto">
                    <option value="title" {{ request('filter_column') == 'title' ? 'selected' : '' }}>名稱</option>
                    <option value="desc" {{ request('filter_column') == 'desc' ? 'selected' : '' }}>描述</option>
                    <option value="price" {{ request('filter_column') == 'price' ? 'selected' : '' }}>價格</option>
                </select>
                <input type="text" name="search" class="form-control" placeholder="輸入關鍵字">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow-sm text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 15%;">
                        <a href="{{ route('items.index', ['sort' => 'title', 'order' => request('sort') === 'title' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                            名稱 {!! request('sort') === 'title' ? (request('order') === 'asc' ? '🔼' : '🔽') : '' !!}
                        </a>
                    </th>
                    <th style="width: 10%;">照片</th>
                    <th style="width: 10%;">
                        <a href="{{ route('items.index', ['sort' => 'price', 'order' => request('sort') === 'price' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                            價格 {!! request('sort') === 'price' ? (request('order') === 'asc' ? '🔼' : '🔽') : '' !!}
                        </a>
                    </th>
                    <th style="width: 25%;">
                        <a href="{{ route('items.index', ['sort' => 'desc', 'order' => request('sort') === 'desc' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                            描述 {!! request('sort') === 'desc' ? (request('order') === 'asc' ? '🔼' : '🔽') : '' !!}
                        </a>
                    </th>
                    <th style="width: 10%;">
                        <a href="{{ route('items.index', ['sort' => 'enabled', 'order' => request('sort') === 'enabled' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none">
                            狀態 {!! request('sort') === 'enabled' ? (request('order') === 'asc' ? '🔼' : '🔽') : '' !!}
                        </a>
                    </th>
                    <th style="width: 20%;">修改時間</th>
                    <th style="width: 15%;">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="text-truncate" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $item->title }}
                        </td>
                        <td>
                            @if($item->pic)
                                <img src="{{ asset('storage/images/product/' . $item->pic) }}" 
                                     alt="{{ $item->title }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 70px; max-height: 70px; object-fit: cover;">
                            @endif
                        </td>
                        <td>${{ number_format($item->price, 0) }}</td>
                        <td class="text-truncate" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ mb_strimwidth($item->desc, 0, 30, '...') }}
                        </td>
                        <td>
                            @if($item->enabled)
                                <span class="badge bg-success">啟用</span>
                            @else
                                <span class="badge bg-secondary">停用</span>
                            @endif
                        </td>
                        <td>{{ $item->updated_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-warning btn-sm edit-item" data-id="{{ $item->id }}"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-sm delete-item" data-id="{{ $item->id }}"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">尚無商品</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- 分頁功能 -->
    <div class="mt-1">
        {{ $items->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>

<script>
$(document).ready(function() {

    @if(session('message'))
        Swal.fire({
            icon: "{{ session('type') }}",
            title: '操作訊息',
            text: "{{ session('message') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // 編輯
    $('.edit-item').click(function() {
        let itemId = $(this).data('id');
        window.location.href = "{{ route('items.edit', '') }}/" + itemId;
    });

    // 刪除
    $('.delete-item').click(function() {
        let itemId = $(this).data('id');

        Swal.fire({
            title: '確定要刪除此商品嗎？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = $('<form>', {
                    'action': "{{ route('items.destroy', '') }}/" + itemId,
                    'method': 'POST',
                    'style': 'display: none;'
                });

                form.append('@csrf');
                form.append('@method("DELETE")');

                $('body').append(form);
                form.submit();
            }
        });
    });

});
</script>
@endsection