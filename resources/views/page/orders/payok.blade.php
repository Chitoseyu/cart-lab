@extends('layouts.app')

@section('title', '付款情形')

@section('content')
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">付款成功！</h1>
            <img src="{{ asset('images/mark_ok.png') }}" class="img-fluid mb-4" style="max-width: 50%;" alt="付款成功">
            <p class="lead">感謝您的購買，您的訂單已成功付款。</p>
            <hr class="my-4">

            <div class="d-flex justify-content-center">
                <a href="/" class="btn btn-primary btn-lg me-2" role="button">查看所有訂單</a>
                <a class="btn btn-success btn-lg" href="/" role="button">返回首頁</a>
            </div>
        </div>
    </div>
@endsection