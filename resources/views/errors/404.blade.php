@extends('layouts.app')

@section('title', '404 頁面')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3">
                <span class="text-danger">找不到頁面</span>
            </p>
            <p class="lead">
                您要尋找的頁面暫時無法使用。
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary">返回首頁</a>
        </div>
    </div>
</div>
@endsection