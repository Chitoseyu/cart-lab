@extends('layouts.app')

@section('title', '付款資訊')

@section('content')
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">付款成功！</h1>
            <img src="{{ asset('images/mark_ok.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="付款成功">
            <p class="lead">感謝您的購買，您的訂單已成功付款。</p>
            <hr class="my-4">
            <p>您的訂單編號為：XXX </p>
            <p>我們會盡快處理您的訂單，並將商品寄送至您指定的地址。</p>
            <a class="btn btn-success btn-lg" href="/" role="button">返回首頁</a>
        </div>
    </div>
@endsection