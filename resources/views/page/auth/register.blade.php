@extends('layouts.app')

@section('title', '註冊頁面')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
        <div class="col-md-6">
            <form method="post" action="{{ route('shop.register') }}">
                @csrf
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/images/net_shop.png') }}" class="img-fluid" style="max-width: 200px;" alt="系統圖像">
                        </div>
                        <h3 class="text-center mb-4 fw-bold text-success">會員註冊</h3>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">姓名</label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">電子信箱</label>
                            <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">密碼</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                            </div>
                            <div class="form-text text-muted">密碼至少需為 6 碼</div>

                            <!-- 右上角顯示/隱藏按鈕 -->
                            <button type="button" class="btn btn-sm toggle-password position-absolute top-0 end-0 mt-2 me-2 p-0 border-0 bg-transparent">
                                <i class="fas fa-eye-slash"></i> 隱藏
                            </button>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">確認密碼</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success">註冊</button>
                        </div>

                        <div class="text-center">
                            <small>已有帳號？<a href="{{ route('shop.login_page') }}" class="text-decoration-none text-primary">登入這裡</a></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


