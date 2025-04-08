@extends('layouts.app')

@section('title', '會員資料')

@section('content')
<div class="container mt-5" style="min-height: 80vh; max-width: 1000px;">
    <h2 class="mb-4">會員資料設定</h2>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills me-3" id="profileTab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active text-start" id="basic-tab" data-bs-toggle="pill" data-bs-target="#basic" type="button" role="tab">基本資料</button>
                <button class="nav-link text-start" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab">付款資料</button>
            </div>
        </div>

        <div class="col-9">
            <form method="post" action="{{ route('shop.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="tab-content" id="profileTabContent">
                    {{-- 基本資料分頁 --}}
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="card p-4 mb-4">
                            {{-- 頭像上傳區塊 --}}
                            <div class="mb-4 d-flex flex-column align-items-center position-relative">
                                <label for="avatar" class="position-relative" style="cursor: pointer;">
                                    <img id="avatar-preview" 
                                        src="{{ $user->avatar ? asset('storage/images/avatars/' . $user->avatar) : asset('storage/images/default_avatar.png') }}" 
                                        class="rounded-circle border" 
                                        width="150" height="150" 
                                        alt="頭像預覽">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none">
                                </label>

                                <button type="button" 
                                        class="btn btn-sm btn-danger mt-2 {{ $user->avatar ? '' : 'd-none' }}" 
                                        id="remove-avatar-btn" 
                                        title="移除頭像">
                                    <i class="fas fa-trash-alt me-1"></i> 移除頭像
                                </button>

                                <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">電子信箱</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">電話號碼</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">性別</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="">請選擇</option>
                                    <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>男性</option>
                                    <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>女性</option>
                                    <option value="other" {{ $user->gender === 'other' ? 'selected' : '' }}>其他</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="birthday" class="form-label">生日</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday', $user->birthday) }}">
                            </div>

                            <hr class="my-4">
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">新密碼（留空則不更改）</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                                </div>
                                <div class="form-text text-muted">密碼至少需為 6 碼</div>

                                <!-- 右上角顯示/隱藏按鈕 -->
                                <button type="button" class="btn btn-sm toggle-password position-absolute top-0 end-0 mt-2 me-2 p-0 border-0 bg-transparent">
                                    <i class="fas fa-eye-slash"></i> 隱藏
                                </button>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">確認新密碼</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 付款資料分頁 --}}
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="card p-4 mb-4">
                            <div class="mb-3">
                                <label for="zip_code" class="form-label">郵遞區號</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $user->zip_code) }}">
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">城市</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                            </div>

                            <div class="mb-3">
                                <label for="district" class="form-label">行政區</label>
                                <input type="text" class="form-control" id="district" name="district" value="{{ old('district', $user->district) }}">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">地址</label>
                                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">儲存變更</button>
                    <a href="/" class="btn btn-secondary">返回首頁</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#avatar').on('change', function(event) {
        const preview = $('#avatar-preview');
        const file = event.target.files[0];

        if (file) {
            preview.attr('src', URL.createObjectURL(file));
            $('#remove_avatar').val(0);
            $('#remove-avatar-btn').removeClass('d-none');
        }
    });

    $('#remove-avatar-btn').on('click', function() {
        const preview = $('#avatar-preview');
        preview.attr('src', "{{ asset('storage/images/default_avatar.png') }}");
        $('#avatar').val('');
        $('#remove_avatar').val(1);
        $(this).addClass('d-none');
    });
</script>
@endsection
