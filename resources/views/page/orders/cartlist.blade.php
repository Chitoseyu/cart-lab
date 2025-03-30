@extends('layouts.app')

@section('title', '訂單明細')

@section('content')
<div class="container mt-5" style="min-height:80vh;">
    @livewire('cart-component')
</div>
@endsection
