@extends('layouts.app')

@section('title', '登入頁面')

<style>
.toast-bottom-center-custom {
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
}
</style>
@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <form method="post" action="{{ route('shop.login') }}">
                @csrf
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/images/net_shop.png') }}" class="img-fluid" style="max-width: 200px;" alt="系統圖像">
                        </div>
                        <h3 class="text-center mb-4 fw-bold text-primary">會員登入</h3>

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
                            <label for="email" class="form-label">帳號（Email）</label>
                            <input type="text" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-4 position-relative">
                            <label for="password" class="form-label">密碼</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                            </div>
                            <button type="button" class="btn btn-sm toggle-password position-absolute top-0 end-0 mt-2 me-2 p-0 border-0 bg-transparent">
                                <i class="fas fa-eye-slash"></i> 隱藏
                            </button>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-info">登入</button>
                        </div>

                        <div class="text-center">
                            <small>還沒有帳號嗎？<a href="{{ route('shop.register_page') }}" class="text-decoration-none text-primary">立即註冊</a></small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(session('message'))
        <script>
            toastr.options = {
                positionClass: 'toast-bottom-center-custom', // 設定顯示位置
                progressBar: false, // 顯示隱藏時間進度條
                timeOut: 3000, // 設定訊息顯示時間
                extendedTimeOut: 300, // 設定滑鼠懸停時的顯示時
            };
            toastr.{{ session('type', 'info') }}('{{ session('message') }}');
        </script>
    @endif
@endsection
