<!doctype html>
<html lang="{{ app()->getLocale() }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="UTF-8">
    <title>{{ general()->title }} </title>
    <meta name="title" content="{{ general()->title }}">
    <meta name="description" content="{{ general()->deskripsi }}">
    <meta name="keywords" content="{{ general()->keyword }}">
    <meta name="author" content="{{ general()->title }}">
    <link rel="canonical" href="{{ url('/') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ general()->icon_web }}" type="image/x-icon">
    <meta name="theme-color" content="#000000" />
    <link rel="stylesheet" type="text/css" href="{{ asset('new_assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('new_assets/css/swiper-bundle.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('new_assets/css/slider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('new_assets/css/popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('new_assets/css/menu.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,700,900">
    <style>
        #install-popup {
            display: none;
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #install-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #overlay1 {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark semi-transparent overlay */
            z-index: 999;
        }

        .popup-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #000;
            /* Black background */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 300px !important;
            border: 2px solid #ffd700;
            /* Gold border */
            border-radius: 8px;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .popup-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffd700;
            /* Gold text color */
        }

        .close-icon {
            cursor: pointer;
            font-size: 20px;
            color: #ffd700;
            /* Gold icon color */
        }

        .language-option {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: 10px;
            background-color: #000;
            /* Black background */
            border: 1px dotted #ffd700;
            /* Gold dotted border */
            padding: 8px;
            border-radius: 4px;
        }

        .language-option img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        /* #languageSelector {
        display: flex;
        box-sizing: border-box;
        background: white;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.5rem;
        border-radius: 2.5rem;
        width: auto;
        cursor: pointer;
        flex-direction: row;
    } */

        #languageSelector {
            /* display: flex;
        box-sizing: border-box;
        background: white;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.5rem;
        border-radius: 2.5rem;
        width: auto;
        cursor: pointer;
        flex-direction: row;
        margin: auto;
        margin-right: 0; */
        }

        #languageSelector div {
            color: #707070;
            font-size: 1.2rem;
            font-weight: 600;
            padding-right: 0.3rem;
        }

        @keyframes menu-icon-glow {
            50% {
                filter: drop-shadow(0 0 10px rgba(246, 214, 129, 1));
            }

            100% {
                filter: drop-shadow(0 0 5px rgba(246, 214, 129, 0.7));
            }
        }
    </style>

    <style>
        #game-notice {
            font-size: 1.6rem;
            text-align: center;
            color: #f44336;
            font-weight: var(--fw-semibold);
        }

        #free100guide {
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            background: #282c73;
            justify-content: space-between;
        }

        #btn-guide-100 {
            background: #fdbd0e;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 1.6rem;
            font-weight: 600;
            color: black;
        }

        #emadaniguide .step {
            margin-bottom: 2.5rem;
        }

        #emadaniguide .step img {
            width: 100%;
            border-radius: 0.8rem;
            margin: 1rem 0;
        }

        .lottery_action {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .lottery_action .btn {
            text-align: center;
            flex-basis: calc(50% - 10px);
            margin-bottom: 10px;
            box-sizing: border-box;
            text-decoration: none;
        }

        /* .game-tag{
        position: absolute;
        top: 10%;
        right: 1.5%;
        padding: 3px 5px;
        border-radius: 3px;
        font-weight: 600;
        font-size: 11px;
        line-height: 1;
        overflow: hidden;
        animation: game-tag 1s infinite;
    } */

        .game-tag {
            position: absolute;
            top: 10%;
            right: -3%;
            padding: 0;
            width: 1%;
            overflow: hidden;
            animation: game-tag 1s infinite;
            max-width: 65px;
        }

        @keyframes game-tag {
            0% {
                transform: scale(0.95);
            }

            50% {
                transform: scale(1);
            }

            100% {
                transform: scale(0.95);
            }
        }

        .game-tag:after {
            content: '';
            position: absolute;
            height: 300%;
            width: 10px;
            background: white;
            animation: slideAndRotate 1.75s infinite linear;
            opacity: 0.3;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
        }

        /* .disabled-game .game-tag:after{
        display:none !important;
    }

    .disabled-game .game-tag{
        background: #676767 !important;
        color: #9b9b9b !important;
        z-index: 3;
        animation: none;
        transform: scale(0.95);
    } */

        .disabled-game .game-tag:after {
            display: none !important;
        }

        .disabled-game .game-tag {
            z-index: 3;
            animation: none;
            transform: scale(0.95);
        }

        .tag-new {
            background: red
        }

        .tag-hot {
            background: #ff9813;
        }

        .tag-bonus,
        .tag-free {
            background: #00db00;
        }

        #header-left {
            width: 25%;
            display: flex;
        }

        #header-right {
            display: flex;
            gap: 5px;
            width: 25%;
            justify-content: flex-end;
        }

        #header-mid {
            flex: 50% 1 1;
        }

        .top-option {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            /* width: 40px; */
            text-decoration: none;
            cursor: pointer;
            gap: 3px;
        }

        .top-option span {
            font-size: 10px;
            color: #ecc154;
        }

        .top-option svg>* {
            color: #ecc154;
        }

        @media (max-width: 600px) {
            .lottery_action .btn {
                flex-basis: 100%;
            }
        }

        @media (max-width: 500px) {
            .game-tag {
                max-width: 50px;
            }
        }

        @media (max-width: 400px) {
            .game-tag {
                max-width: 45px;
            }
        }

        @media (max-width: 360px) {
            .game-tag {
                max-width: 40px;
            }

            .header {
                gap: 5px;
            }

            #header-mid {
                justify-content: flex-start;
            }

            #header-mid img {
                margin-left: 3px
            }

            #header-left {
                width: 36px;
                display: flex;
            }

            .top-option {
                width: auto;
                padding: 3px;
                transform: scale(0.95);
                border: 1px solid #ecc154;
            }

            .top-option span {
                display: none;
            }
        }
    </style>
    @stack('style')
    <div id="loading-screen" class="hide">
        <img src="{{ general()->logo }}" />
    </div>
    <script src="{{ asset('new_assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('new_assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('new_assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('new_assets/js/popup.js') }}"></script>
    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        // Function to get the value of a cookie by its name
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Function to handle the language change
        function changeLanguage(language) {
            try {
                document.getElementById('language').value = language;
                setCookie('selectedLanguage', language, 365); // Set a cookie with the selected language
                updateLanguageSelector(language);
                document.forms[0].submit();
            } catch (e) {
                console.log(e);
            }
        }

        // Function to update the language selector with the selected language flag
        function updateLanguageSelector(language) {
            try {
                var selectedFlag = document.getElementById('selectedFlag');
                selectedFlag.src = 'https://kkclublive.online/new_assets/images/language/flag_' + language + '.png';
                // const languageTxt = document.getElementById('language_short').innerHTML = language.toUpperCase();
            } catch (e) {
                console.log(e);
            }
        }

        // Function to toggle the language popup
        function togglePopup() {
            var overlay1 = document.getElementById('overlay1');
            var popup = document.getElementById('languagePopup');

            if (popup.style.display === 'none' || popup.style.display === '') {
                overlay1.style.display = 'block';
                popup.style.display = 'block';
            } else {
                overlay1.style.display = 'none';
                popup.style.display = 'none';
            }
        }

        // On page load, check if a language is already selected and update the language selector
        window.onload = function() {
            var selectedLanguage = getCookie('selectedLanguage');
            if (selectedLanguage) {
                updateLanguageSelector(selectedLanguage);
            }
        };

        function livechat() {
            LiveChatWidget.call('maximize');
            return false;
        }
    </script>

    {!! general()->costum_script !!}

</head>

<body style="margin:auto" class="">

    <!-- main page header -->
    @include('frontend.layouts.header')
    @yield('content')
    <footer>
        <div style="display:flex;flex-wrap:wrap;justify-content: center; gap:0.5rem">
            <img style="margin:auto; width:90%" src="{{ asset('new_assets/images/payment.png') }}" alt>
            <img style="height:auto; width:100%" src="{{ asset('new_assets/images/footer.png') }}">
        </div>
    </footer> <!-- Spacing -->
    <div class="m-elmnt" style="width:100%;height:10rem"></div>

    <!-- Navigation -->
    @include('frontend.layouts.navigation')
    <div class="overlay default" id="overlay"></div>
</body>
<script src="{{ asset('new_assets/js/slider.js') }}"></script>
<script src="{{ asset('new_assets/js/games-filter.js') }}"></script>
<script src="{{ asset('new_assets/js/script.js') }}"></script>
@stack('script')
<script>
    $(document).ready(function() {
        $('#btn-withdraw-apk-submit').on('click', function() {
            const game_acc = $('#game-acc-id').val();
            const game_ps = $('#game-acc-ps').val();
            const game_wd_amt = $('#game-acc-amount').val();
            const game_id = $('#game-game-id').val();

            if (game_wd_amt < 3) {
                setSwal(0, 'Minimum withdrawal amount is 3', 2000);
                $('#game-acc-amount').focus();
                return;
            }


            $('#btn-withdraw-apk-submit').prop('disabled', true);
            showLoading();

            var postData = {
                game_id: game_id,
                game_acc: game_acc,
                game_ps: game_ps,
                amount: game_wd_amt,
                type: 1,
                _token: '{{ csrf_token() }}',
            };
            $.ajax({
                url: "{{ url('apk_game_deposit') }}",
                method: 'POST',
                data: postData,
                success: function(data) {
                    closePopup('apk-game-info');
                    hideLoading();
                    if (data.status == 'success') {
                        setSwal(1, data.msg, 3500);
                    } else {
                        setSwal(0, data.msg, 5000);
                    }
                    $('#btn-withdraw-apk-submit').prop('disabled', false);
                    $('.amount_wd_games').hide();
                    $('#btn-deposit-apk').show();
                    $('#btn-withdraw-apk').show();
                    $('#btn-withdraw-apk-submit').hide();
                    $('.amount_wd_games').prop('required', false);
                },
            })
        });
    });

    function deposit_game(game_id) {
        showLoading();
        document.getElementById('btn-deposit-apk').disabled = true;
        const game_acc = document.getElementById('game-acc-id').value;
        const game_ps = document.getElementById('game-acc-ps').value;

        var postData = {
            game_id: game_id,
            game_acc: game_acc,
            game_ps: game_ps,
            type: 2,
            _token: '{{ csrf_token() }}',
        };
        $.ajax({
            url: "{{ url('apk_game_deposit') }}",
            method: 'POST',
            data: postData,
            success: function(data) {
                closePopup('apk-game-info');
                hideLoading();
                if (data.status == 'success') {
                    setSwal(1, data.msg, 3500);
                    if (data.open_game_link != null) {
                        window.location.href = data.open_game_link;
                    }
                } else {
                    setSwal(0, data.msg, 5000);
                }
                document.getElementById('btn-deposit-apk').disabled = false;
            },
        })
    }

    function withdraw_game(id) {
        $('.amount_wd_games').show();
        $('#btn-deposit-apk').hide();
        $('#btn-withdraw-apk').hide();
        $('#btn-withdraw-apk-submit').show();
        $('#game-game-id').val(id);
        $('.amount_wd_games').prop('required', true);
    }
</script>
@stack('footer')

@auth
    <script>
        function getLatestBalance() {
            showLoading();
            $.ajax({
                url: "{{ route('api.balance') }}",
                method: 'GET',
                success: function(res) {
                    hideLoading();
                    if (res.status == false) {
                        setSwal(0, res.msg, 5000);
                    } else {
                        var balance = document.getElementById("balance");
                        balance.innerHTML = res.balance;
                    }
                },
            })
        }
    </script>
@endauth

@if (session('error'))
    <script>
        setSwal(0, '{{ session('error') }}', 5000);
    </script>
@endif


@if (session('success'))
    <script>
        setSwal(1, '{{ session('success') }}', 5000);
    </script>
@endif

<script>
    $(document).ready(function() {




    });
</script>

</html>
