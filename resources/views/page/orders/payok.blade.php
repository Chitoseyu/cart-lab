@extends('layouts.app')

@section('title', '付款情形')

@section('content')
<div class="container vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="jumbotron">
            <h1 class="display-4 mb-4">付款成功！</h1>
            <img src="{{ asset('storage/images/mark_ok.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="付款成功">
            <p class="lead mb-4">感謝您的購買，您的訂單已成功付款。</p>
            <hr class="my-4">
            <div class="d-flex justify-content-center mt-4">
                <a href="/orders/list" class="btn btn-primary btn-lg me-2" role="button">查看所有訂單</a>
                <a href="/" class="btn btn-success btn-lg" role="button">返回首頁</a>
            </div>
        </div>
    </div>
</div>
@endsection
