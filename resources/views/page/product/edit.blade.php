@extends('layouts.app')

@section('title', isset($item) ? '編輯商品' : '新增商品')

@section('content')
<div class="container mt-5">
      <!-- 麵包屑 -->
      @include('components.breadcrumb', [
        'breadcrumbs' => [
            ['label' => '🏠', 'url' => url('/')],
            ['label' => '商品管理', 'url' => url('/product')],
            ['label' => '商品資料', 'url' => ''],
        ]
    ])
    <div class="row justify-content-start">
        <div class="col-md-6">
            <h2 class="mt-4 mb-4">{{ isset($item) ? '編輯' : '新增' }}</h2>

            <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($item))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="title" class="form-label">商品名稱</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">商品價格</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $item->price ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="desc" class="form-label">商品描述</label>
                    <textarea class="form-control" id="desc" name="desc" rows="3" required>{{ old('desc', $item->desc ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">商品狀態</label>
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="enabled" id="enabled1" value="1" {{ isset($item) && $item->enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="enabled1">
                                啟用
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="enabled" id="enabled0" value="0" {{ isset($item) && !$item->enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="enabled0">
                                停用
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pic" class="form-label">商品照片</label>
                    <input type="file" class="form-control" id="pic" name="pic" accept="image/*">
                    <div id="image-preview-container" class="mt-2">
                        <div id="new-image-preview"></div>
                        @if(isset($item) && $item->pic)
                            <div class="mt-2"><label class="form-text text-muted">目前照片：</label><img src="{{ asset('storage/images/product/' . $item->pic) }}" alt="{{ $item->title }}" width="150" height="150"></div>
                        @endif
                    </div>
                </div>

                @if(isset($item))
                    <div class="mb-3">
                        <label class="form-label">最後修改時間</label>
                        <input type="text" class="form-control" value="{{ $item->updated_at }}" disabled>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">{{ isset($item) ? '更新' : '新增' }}</button>
                <a href="{{ route('items.index') }}" class="btn btn-secondary">返回</a>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#pic').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    // 動態新增預覽圖片和清除按鈕
                    $('#new-image-preview').html('<div class="mt-2"><label class="form-text text-muted">預覽圖片：</label><img src="' + event.target.result + '" alt="預覽圖片" width="150" height="150"><button type="button" class="btn btn-danger btn-sm clear-preview ms-2" data-preview-id="new-image-preview"><i class="fas fa-trash-alt"></i></button></div>');
                }
                reader.readAsDataURL(file);
            } else {
                // 清除動態新增的預覽圖片
                $('#new-image-preview').empty();
            }
        });

        // 清除預覽圖片
        $(document).on('click', '.clear-preview', function() {
            const previewId = $(this).data('preview-id');
            $('#' + previewId).empty();
            $('#pic').val(''); // 清除檔案輸入欄位的值
        });
    });
</script>
@endsection