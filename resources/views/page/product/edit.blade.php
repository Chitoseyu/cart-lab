@extends('layouts.app')

@section('title', isset($item) ? '編輯商品' : '新增商品')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">{{ isset($item) ? '編輯商品' : '新增商品' }}</h2>

    <form action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($item))
            @method('PUT')
        @endif

        <!-- 商品名稱 -->
        <div class="mb-3">
            <label for="title" class="form-label">商品名稱</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $item->title ?? '') }}" required>
        </div>

        <!-- 商品價格 -->
        <div class="mb-3">
            <label for="price" class="form-label">商品價格</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $item->price ?? '') }}" required>
        </div>

        <!-- 商品描述 -->
        <div class="mb-3">
            <label for="desc" class="form-label">商品描述</label>
            <textarea class="form-control" id="desc" name="desc" rows="3" required>{{ old('desc', $item->desc ?? '') }}</textarea>
        </div>

        <!-- 是否啟用 -->
        <div class="mb-3">
            <label class="form-label">是否啟用</label>
            <select class="form-control" name="enabled">
                <option value="1" {{ isset($item) && $item->enabled ? 'selected' : '' }}>啟用</option>
                <option value="0" {{ isset($item) && !$item->enabled ? 'selected' : '' }}>停用</option>
            </select>
        </div>

        <!-- 上傳商品照片 -->
        <div class="mb-3">
            <label for="pic" class="form-label">商品照片</label>
            <input type="file" class="form-control" id="pic" name="pic" accept="image/*">
            @if(isset($item) && $item->pic)
                <small class="form-text text-muted">目前照片：<img src="{{ asset('images/product/' . $item->pic) }}" alt="{{ $item->title }}" width="80"></small>
            @endif
        </div>

        @if(isset($item))
            <!-- 建立時間 -->
            <div class="mb-3">
                <label class="form-label">建立時間</label>
                <input type="text" class="form-control" value="{{ $item->created_at }}" disabled>
            </div>

            <!-- 最後修改時間 -->
            <div class="mb-3">
                <label class="form-label">最後修改時間</label>
                <input type="text" class="form-control" value="{{ $item->updated_at }}" disabled>
            </div>
        @endif

        <!-- 提交按鈕 -->
        <button type="submit" class="btn btn-primary">{{ isset($item) ? '更新商品' : '新增商品' }}</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">返回</a>
    </form>
</div>
@endsection
