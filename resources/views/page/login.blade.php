@extends('layouts.app')

@section('title', '登入頁面')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" action="">
                    @csrf
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <img src="{{ asset('images/home.png') }}" class="img-fluid" style="max-width: 300px;" alt="系統圖像">
                            </div>
                            <h3 class="card-title text-center mb-4">登入</h3>
                            <div class="mb-3">
                                <label for="username" class="form-label">帳號</label>
                                <input type="text" class="form-control form-control-sm" id="username" name="username" value="{{ old('username') }}" autocomplete="off" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">密碼</label>
                                <input type="password" class="form-control form-control-sm" id="password" name="password" autocomplete="off" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">登入</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
