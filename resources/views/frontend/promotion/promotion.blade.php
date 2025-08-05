@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.sidenav')
<section class="default">
    <div class="container px-1">
        <div class="page-top-wrapper px-10 py-15 mb-15">
            <div class="title cus-title">Promotions</div>
        </div>
        <div class="px-10" style="display:flex;flex-wrap:wrap;gap:1rem">
            @foreach ($promotion as $item)
            <div class="promotion-card" data-value="{{ $item->id }}" show="{{ $item->id }}">
                <div class="top">
                    <img class="background" alt src="{{ env('AWS_URL') }}{{ $item->gambar }}">
                </div>

                <div class="promotion-content">
                    <div class="info">
                        <div class="title">{{ $item->judul }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Popup Content : Bonus More Details -->
@foreach ($promotion as $item)
<div class="popup cus-popup" id="promotion-details{{ $item->id }}">
    <div class="content bg-popup">
        <div class="content-top">
            <div class="close-popup">
                <div onclick="closePopup('promotion-details{{ $item->id }}')" class="btn_return">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0,0H24V24H0Z" fill="none" />
                        <path d="M18,6,6,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                        <path d="M6,6,18,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section class="default">
                <div class="media-wrap">
                    <img src="{{ env('AWS_URL') }}{{ $item->gambar }}">
                </div>
                {!! $item->text !!}
                <button class="btn btn-applybonus" onclick="showLoading();window.location.href='{{ route('deposit') }}'">Apply
                    Now</button>
            </section>
        </div>
        <div class="content-footer"></div>
    </div>
</div>
@endforeach


@include('frontend.layouts.popup')
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.promotion-card').forEach((x) => {
                x.addEventListener('click', (event) => {
                    const selected = x.getAttribute('show');
                    let target = selected;
                    openPopup('promotion-details' + selected);
                });
            });
        });
</script>
@endpush

@push('style')
<style>
    #bonus-details ul {
        padding-inline-start: 2rem !important;
    }

    #bonus-details li {
        margin-bottom: 1rem;
    }

    .btn-applybonus {
        padding: 1rem 1.5rem;
        width: 100%;
        margin-top: 1.5rem;
        background: var(--theme-grd-primary-active);
        color: black;
        border: var(--theme-brd-btn-active)
    }

    .promotion-card {
        width: 100%;
        filter: drop-shadow(0px 5px 8px rgba(0, 0, 0, 0.5));
    }

    .promotion-card>.top {
        width: 100%;
        height: auto;
        position: relative;
    }

    .promotion-card .top .pattern {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 0;
        width: 100%;
    }

    .promotion-card .top .background {
        width: 100%;
        border-radius: 0.5rem;
        filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.1));
    }

    .promotion-card .promotion-content {
        padding: 1rem;
    }

    .promotion-card .info .title {
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
        text-transform: uppercase;
    }

    .promotion-card .operation-time-wrap {
        font-size: 1.2rem;
        font-weight: var(--fw-regular);
        margin-bottom: 0.5rem;
    }

    .promotion-card .time-remaining {
        display: flex;
        gap: 0.5rem;
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        background: var(--theme-clr-base);
        padding: 0.5rem;
        box-sizing: border-box;
        border-radius: 0.5rem;
    }

    .promotion-card .time-remaining>div {
        font-size: 1.3rem;
        display: flex;
        gap: 0.5rem;
    }

    .promotion-card .time-remaining svg>* {
        color: yellow;
    }

    .promotion-card .time-remaining span {
        font-size: 1rem;
        color: #f1f1f1;
    }

    .promotion-detail a {
        text-decoration: underline;
        font-size: 1.25rem;
        font-weight: 600;
    }

    #promotion .s-title {
        margin: auto;
        text-align: center;
        font-weight: 600;
        padding: 1.5rem;
        text-transform: uppercase;
        animation: animatedShadow 3s infinite;
    }

    #eligible-games {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.5rem;
        justify-content: space-between;
        align-items: center;
        padding: 0 0.5rem;
    }

    #eligible-games img {
        width: 100%;
        transform: scale(0.9);
        filter: grayscale(1);
    }

    #eligible-games .active img {
        width: 100%;
        transform: scale(1);
        filter: grayscale(0) drop-shadow(0 0 5px gold);
    }

    .bonus-table {
        text-align: center;
        width: 100%;
        display: table;
        border-spacing: 0;
    }

    .bonus-table td,
    .bonus-table th {
        border-collapse: collapse;
        border: 1px solid #a5a5a5;
        padding: 0.8rem;
    }

    .bonus-table th {
        color: #613b00;
        font-size: 14px;
        text-align: center;
        text-transform: uppercase;
        font-weight: 600;
        background: var(--theme-grd-primary-active);
    }

    .bonus-table td {
        font-size: 1.3rem;
        font-weight: var(--fw-medium);
        background: rgba(255, 255, 255, 0.1);
    }

    .bonus-table th.danger {
        background: #a70000;
        color: white;
    }

    .bonus-table th.success {
        background: #009900;
        color: white;
    }
</style>
@endpush
