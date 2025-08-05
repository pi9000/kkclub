<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes'>
    <meta name="theme-color" content="#164a41">
    <meta name="msapplication-TileColor" content="#164a41">
    <meta name="msapplication-navbutton-color" content="#164a41">
    <meta name="apple-mobile-web-app-status-bar-style" content="#164a41">
    <title>Licensed not active</title>
    <link rel="preload" as="font" href="{{ asset('assets/themes/9/font/font-awesome/webfonts/fa-solid-900.woff2') }}"
        type="font/woff2" crossorigin="anonymous">
    <link rel="preload" as="font" href="{{ asset('assets/themes/9/font/font-awesome/webfonts/fa-brands-400.woff2') }}"
        type="font/woff2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/themes/9/css/global.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/themes/9/font/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" id="templateStyle" type="text/css" href="{{ asset('assets/custom/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/themes/9/sass/custom7ee6.css?v=2.0.1690') }}">
</head>

<body>

    <style>
        .img-forbiden {
            height: 200px;
        }

        .flag-padding {
            padding: 5px;
            filter: grayscale(0.9);
        }

        .flag-padding>a>img {
            width: 30px;
        }

        .content {
            padding: 5px;
            max-width: 500px;
        }

        .flag-holder {
            border-top: 1px solid #202124;
            padding-top: 15px;
            display: flex;
        }

        li.active.flag-padding {
            filter: drop-shadow(1px 2px 2px black);
        }

        .forbidden__holder {
            background-color: black;
            padding: 15px;
            border-radius: 30px;
        }

        .forbidden-page {
            background-image: url(https://images.linkcdn.cloud/global/error/bod_forbiden.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
    {!! getError() !!}
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
