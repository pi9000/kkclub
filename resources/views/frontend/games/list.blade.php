@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.sidenav')
<div class="game-list">
    @foreach ($games as $item)
    @php
    $rtp = generateRandomRTP();
    if ($rtp >= 80) {
    $rtpColor = '#28a745';
    } elseif ($rtp >= 70) {
    $rtpColor = '#ffc107';
    } else {
    $rtpColor = '#ffc107';
    }
    @endphp
    <div>
        <div class="btn-game" onclick="open_list_game('{{ url('gameIframes?gameType=' . $item->GameType . '&providerCode=' . $item->ProviderCode . '&gameCode=' . $item->GameCode . '&provider_id=' . $item->provider_id) }}')"
            style="display: flex;flex-direction: column;justify-content: center;align-items: center;">

            <img style="max-width:90%; border-radius: 0.5rem;" src="{{ $item->Game_image }}" />

            <div class="rtp-bar-custom" style="background-color: {{ $rtpColor }}">
                Win Rate: {{ $rtp }}%
            </div>
        </div>
    </div>
    @endforeach
</div>

@include('frontend.layouts.popup')
@endsection
@push('script')
<script>
    $('.top-menu').hide();

        function open_list_game(url) {
            showLoading();
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    console.log(data);
                    hideLoading();
                    if (data.msg == "success") {
                        window.open(data.url, "_blank");
                    }
                },
            })
        }
</script>
@endpush

@push('style')
<link rel="stylesheet" href="{{ asset('new_assets/css/custom-game.css') }}" />
<style>
    body {
        background: #252525 !important;
    }

    .game-list>div {
        flex: 0 0 33.333%;
        box-sizing: border-box;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .game-list button img {
        border-radius: 0.5rem;
        filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.5));
    }

    .game-list .playtech-game {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.4rem;
        font-weight: var(--fw-medium);
        text-align: center;
        padding: 0.5rem;
    }

    @-webkit-keyframes progress-bar-stripes {
        from {
            background-position: 1rem 0
        }

        to {
            background-position: 0 0
        }
    }

    @keyframes progress-bar-stripes {
        from {
            background-position: 1rem 0
        }

        to {
            background-position: 0 0
        }
    }

    .progress {
        display: -ms-flexbox;
        display: flex;
        height: 1rem;
        overflow: hidden;
        line-height: 0;
        font-size: .75rem;
        background-color: #e9ecef;
        border-radius: .25rem
    }

    .progress-bar {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        overflow: hidden;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        background-color: #007bff;
        transition: width .6s ease
    }

    @media (prefers-reduced-motion:reduce) {
        .progress-bar {
            transition: none
        }
    }

    .progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem
    }

    .progress-bar-animated {
        -webkit-animation: progress-bar-stripes 1s linear infinite;
        animation: progress-bar-stripes 1s linear infinite
    }

    @media (prefers-reduced-motion:reduce) {
        .progress-bar-animated {
            -webkit-animation: none;
            animation: none
        }
    }

    .rtp-bar-custom {
        width: 90%;
        padding: 4px 0;
        margin-top: 5px;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        border-radius: 4px;
        background-size: 20px 20px;
        background-image: repeating-linear-gradient(45deg,
                rgba(255, 255, 255, 0.1),
                rgba(255, 255, 255, 0.1) 10px,
                transparent 10px,
                transparent 20px);
    }

    .btn-game {
        padding-bottom: 10px;
    }
</style>
@endpush
