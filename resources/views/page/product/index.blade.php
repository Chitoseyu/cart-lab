@extends('layouts.app')

@section('title', '商品管理')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">商品管理</h2>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('items.create') }}" class="btn btn-success">新增商品</a>
    </div>

    @if(session('message'))
        <div id="flex-alert" class="alert alert-success" style="position: fixed; top: 3%; left: 50%; transform: translateX(-50%);">
            <span id="flex-alert-message">{{ session('message') }}</span>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>照片</th>
                    <th>名稱</th>
                    <th>價格</th>
                    <th>描述</th>
                    <th>狀態</th>
                    <th>建立時間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($item->pic)
                                <img src="{{ asset('images/product/' . $item->pic) }}" alt="{{ $item->title }}" width="80">
                            @else
                                <span class="text-muted">無照片</span>
                            @endif
                        </td>
                        <td>{{ $item->title }}</td>
                        <td>${{ number_format($item->price, 0) }}</td>
                        <td>{{ Str::limit($item->desc, 30) }}</td>
                        <td>
                            @if($item->enabled)
                                <span class="badge bg-success">啟用</span>
                            @else
                                <span class="badge bg-secondary">停用</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">編輯</a>

                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline-block"
                                    onsubmit="return confirm('確定要刪除此商品嗎？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">尚無商品</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    // 顯示 FlexAlert 提示
    function showFlexAlert() {
        $("#flex-alert").fadeIn();
        setTimeout(function() {
            $("#flex-alert").fadeOut();
        }, 3000); // 3 秒後自動消失
    }

    // 頁面載入時顯示提示 (如果存在)
    @if(session('message'))
        showFlexAlert();
    @endif
});
</script>
@endsection