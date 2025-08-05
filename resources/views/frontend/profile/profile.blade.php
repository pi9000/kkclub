@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="profile-options-wrapper">
        <div class="options">
            <a class="option" onclick="showLoading()" href="{{ route('profile_information') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="32" height="32"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentcolor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                </svg>
                <span>@lang('public.my_profile')</span>
            </a>

            <a class="option" onclick="showLoading()" href="{{ route('change_password') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock" width="32"
                    height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentcolor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                    <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                    <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
                </svg>
                <span>@lang('public.change_password')</span>
            </a>
            <a class="option" onclick="showLoading()" href="{{ route('history') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-text" width="32"
                    height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentcolor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                    <path d="M9 12h6" />
                    <path d="M9 16h6" />
                </svg>
                <span>@lang('sidenav.history')</span>
            </a>
            <a class="option" onclick="showLoading()" href="{{ route('bank_account') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card" width="32"
                    height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentcolor" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                    <path d="M3 10l18 0" />
                    <path d="M7 15l.01 0" />
                    <path d="M11 15l2 0" />
                </svg>
                <span>@lang('public.bank_account')</span>
            </a>
        </div>
    </section>

    <section>
        <div class="container logout-wrapper">
            <button type="button" class="btn-logout"
                onclick="showLoading();event.preventDefault();document.getElementById('logout-form').submit();">@lang('sidenav.logout')</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </section>
    @include('frontend.layouts.popup')
@endsection
@push('style')
    <style>
        .logout-wrapper {
            padding: 0 1.5rem 1.5rem;
        }

        @media (max-height:650px) {
            .logout-wrapper {
                padding: 0 1.5rem 11.5rem;
            }
        }
    </style>
@endpush
