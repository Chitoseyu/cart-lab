@extends('layouts.app')

@section('title', '會員資料')

@section('content')
<div class="container mt-5" style="min-height: 80vh;">
    <h2 class="mb-4">編輯會員資料</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form method="post" action="{{ route('shop.profile.update') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">電子信箱</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
           
            <label for="password" class="form-label">新密碼（留空則不更改）</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">確認新密碼</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">儲存變更</button>
        <a href="/" class="btn btn-secondary">返回首頁</a>
    </form>
</div>
@endsection
