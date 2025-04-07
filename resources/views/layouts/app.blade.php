<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Tippy  -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

    @livewireStyles
    <title>購物網站 - @yield('title', '首頁')</title>
    <link rel="icon" href="{{ asset('images/net_shop.ico') }}" type="image/x-icon">
</head>
<body>
    @include('components.navbar')
    <div class="container mt-4" style="min-height:80vh;">
        @isset($slot)
        {{$slot}}
        @endisset
        @yield('content') 
    </div>
    @include('components.footer')
    @livewireScripts

    <button id="back-to-top" style="position: fixed; bottom: 20px; left: 20px; z-index: 1000; display: none; background-color: #f0f0f0; border: none; border-radius: 50%; padding: 10px; display: flex; justify-content: center; align-items: center;">
        <img src="{{ asset('storage/images/mark_arrow_up.svg') }}" alt="回上方" style="width: 20px; height: 20px;">
    </button>

    <script>
        $(document).ready(function() {
            // 捲動超過一定距離時顯示按鈕
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });
            $('#back-to-top').click(function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop : 0}, 300);
            });

            // 顯示隱藏密碼區塊
            $(document).on('click', '.toggle-password', function() {
                    const input = $($(this).data('target'));
                    const icon = $(this).find('i');
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
        });
       
    </script>

</body>
</html>
