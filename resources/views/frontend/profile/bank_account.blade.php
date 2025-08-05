@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container px-1">
            @if (auth()->user()->nama_bank != '-')
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <a class="previous-link" onclick="showLoading()" href="{{ route('profile') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
                <div class="title cus-title">@lang('public.bank_account')</div>
            </div>
            @endif
            <div class="form-wrapper px-10 py-10 bg-form">
                <!-- Option: Bank -->
                @if (auth()->user()->nama_bank == '-')
                    <form id="bank-form" method="POST" action="{{ route('optionalBankCreate') }}">
                        @csrf
                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.bank') : </div>
                            <select class="default" name="chooseOptionalBank" required>
                                <option value="">@lang('public.select-bank')</option>
                                <option value="MayBank" data-max="15" data-min="10">
                                    MayBank
                                </option>
                                <option value="CIMB Bank" data-max="15" data-min="10">
                                    CIMB Bank
                                </option>
                                <option value="Public Bank" data-max="15" data-min="10">
                                    Public Bank
                                </option>
                                <option value="Hong Leong Bank" data-max="15" data-min="10">
                                    Hong Leong Bank
                                </option>
                                <option value="RHB Bank" data-max="15" data-min="10">
                                    RHB Bank
                                </option>
                                <option value="AmBank" data-max="15" data-min="10">
                                    AmBank
                                </option>
                                <option value="Affin Bank" data-max="15" data-min="10">
                                    Affin Bank
                                </option>
                                <option value="Bank Simpanan Nasional" data-max="15" data-min="10">
                                    Bank Simpanan Nasional
                                </option>
                                <option value="Bank Islam" data-max="15" data-min="10">
                                    Bank Islam
                                </option>
                                <option value="Bank Muamalat" data-max="15" data-min="10">
                                    Bank Muamalat
                                </option>
                                <option value="Bank Rakyat" data-max="15" data-min="10">
                                    Bank Rakyat
                                </option>
                                <option value="OCBC Bank" data-max="15" data-min="10">
                                    OCBC Bank
                                </option>
                                <option value="UOB Bank" data-max="15" data-min="10">
                                    UOB Bank
                                </option>
                                <option value="Alliance Bank" data-max="15" data-min="10">
                                    Alliance Bank
                                </option>
                                <option value="CITI Bank" data-max="15" data-min="10">
                                    CITI Bank
                                </option>
                                <option value="HSBC Bank" data-max="15" data-min="10">
                                    HSBC Bank
                                </option>
                                <option value="Standard Charted Bank" data-max="15" data-min="10">
                                    Standard Charted Bank
                                </option>
                                <option value="MBSB Bank" data-max="15" data-min="10">
                                    MBSB Bank
                                </option>
                                <option value="Bank Of China" data-max="15" data-min="10">
                                    Bank Of China
                                </option>
                                <option value="Kuwait Bank" data-max="15" data-min="10">
                                    Kuwait Bank
                                </option>
                                <option value="Merchantrade" data-max="15" data-min="10">
                                    Merchantrade
                                </option>
                                <option value="Touch N Go" data-max="15" data-min="10">
                                    Touch N Go
                                <option value="DBS Bank" data-max="15" data-min="10">
                                    DBS Bank
                                </option>
                            </select>
                        </div>

                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.acc_no') : </div>
                            <input type="text" class="default" id="account-no" placeholder="@lang('public.acc_no')" name="optAccountNumber" required>
                        </div>

                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.name') : </div>
                            <input type="text" class="default" id="account-name" placeholder="@lang('public.name')" name="optAccountName" value="{{ auth()->user()->nama_lengkap }}" readonly>
                        </div>
                        <button type="submit" onclick="showLoading()" class="btn btn-full mx-w">@lang('public.submit')</button>
                    </form>
                @else
                    <form id="bank-form">
                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.bank') : </div>
                            <input type="text" class="default" id="bank-name" disabled value="{{ auth()->user()->nama_bank }}">
                        </div>

                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.acc_no') : </div>
                            <input type="text" class="default" id="account-no" disabled value="{{ auth()->user()->nomor_rekening }}">
                        </div>

                        <div class="input-field default mx-w mb-15">
                            <div class="input-label">@lang('public.name') : </div>
                            <input type="text" class="default" id="account-name" disabled
                                value="{{ auth()->user()->nama_lengkap }}">
                        </div>

                        <div class="notice-box mb-15" id="4">
                            <div class="top">
                                <div class="label"><img src="{{ asset('new_assets/images/important.png') }}" />Important
                                    notes</div>
                                <div class="toggle-icon" onclick="toggleNotice(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path d="M0,0H24V24H0Z" fill="none" />
                                        <path d="M6,15l6-6,6,6" fill="none" stroke="currentcolor"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                                    </svg>
                                </div>
                            </div>
                            <!-- Important Note -->
                            <div class="content">
                                <ul>
                                    <li>
                                        <p>@lang('public.if_wan_change_bank')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </section>
    @include('frontend.layouts.popup')
@endsection
