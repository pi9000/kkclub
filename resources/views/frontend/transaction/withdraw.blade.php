@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container transaction">
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <div class="title cus-title">@lang('public.withdrawFor') <b>@lang('public.main')</b>  @lang('public.wallet')</div>
            </div>

            <div class="form-wrapper px-15 py-15 bg-form">
                <form action="{{ route('transaksi.withdraw') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.withdrawAmount')</div>
                        <input type="number" min="{{ general()->min_wd }}" step="0.01" placeholder="Min: RM10" name="jumlah"
                            class="default" value="0" @if (auth()->user()->balance <= 0) readonly @endif>
                        <span style="font-size:0.7em;margin-top:4px;color:gold">@lang('public.currBalance')<b> RM {{ number_format(auth()->user()->balance,2) }}</b></span>
                    </div>
                    <div id="bank-details">
                        <div class='withdraw-account mx-w mb-15'>
                            <div class='row'>
                                <div class='data'>
                                    <input type='text' name="bank" value="{{ auth()->user()->nama_bank }} / {{ auth()->user()->nomor_rekening }} / {{ auth()->user()->nama_pemilik }}" hidden>
                                    <div class='label'>@lang('public.bank')</div>
                                    <input type="text" name="bank_name" class="default"
                                        style="width:100%;box-sizing:border-box" value="{{ auth()->user()->nama_bank }}" disabled>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='data'>
                                    <div class='label'>@lang('public.bank_account')</div>
                                    <input type="text" name="account_no" class="default" required
                                        style="width:100%;box-sizing:border-box" value="{{ auth()->user()->nomor_rekening }}" disabled>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='data'>
                                    <div class='label'>@lang('public.name')</div>
                                    <input type="text" name="full_name" class="default" required
                                        style="width:100%;box-sizing:border-box" value="{{ auth()->user()->nama_pemilik }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="notice-box mb-15">
                        <div class="top">
                            <div class="label"><img src="{{ asset('new_assets/images/important.png') }}" />Important
                                notes</div>
                            <div class="toggle-icon" onclick="toggleNotice(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M0,0H24V24H0Z" fill="none" />
                                    <path d="M6,15l6-6,6,6" fill="none" stroke="currentcolor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <div class="content">
                            <ul>
                                <li>
                                    <p>Some game provider requires 15 till 30 minutes of report sync time, kindly bear
                                        with us during the required sync time.</p>
                                </li>
                                <li>
                                    <p>Minimum withdraw amount is RM {{ number_format(general()->min_wd) }}.</p>
                                </li>
                                <li>
                                    <p>If there is any discrepancy or you may have any other further withdrawal
                                        inquiries, kindly contact our 24/7 LIVECHAT.</p>
                                </li>
                                <li>
                                    <p>Money withdrawal will be credit to account above within 20mins via online
                                        transfer.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button @if (auth()->user()->balance <= 0) type="button" @else type="submit" @endif id="btn-withdraw" class="btn btn-full mx-w" @if (auth()->user()->balance <= 0) disabled @endif>@lang('public.submit')</button>
                    <div class='winover-note' style="margin-top:1.5rem">
                        @lang('public.currentMinWd') {{ number_format(general()->min_wd) }}
                    </div>

                </form>
            </div>
        </div>
    </section>

    @include('frontend.layouts.popup')
@endsection

@push('style')
    <style>
        #btn-withdraw:disabled {
            filter: grayscale(1) brightness(0.5);
        }

        #btn-withdraw {
            border: var(--theme-brd-btn-active);
        }

        .winover-note {
            font-size: 1.4rem;
            color: red;
            font-weight: 500;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }
    </style>
@endpush
