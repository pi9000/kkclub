@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container px-1">

            <!-- TOP BANNER -->
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <a class="previous-link" onclick="showLoading()" href="{{ route('profile') }} ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
                <div class="title cus-title ">@lang('public.my_profile')</div>
            </div>
            <div class="form-wrapper px-15 py-15 bg-form">
                <form>
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.usernameLogin')</div>
                        <input type="text" class="read-only" name="username" readonly value="{{ auth()->user()->username }}">
                    </div>
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.nameReg')</div>
                        <input type="text" class="read-only" readonly value="{{ auth()->user()->nama_lengkap }}">
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.contact_noReg')</div>
                        <input type="text" class="read-only" readonly value="60{{ maskPhone(auth()->user()->no_hp) }}">
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.reff_assigned')</div>
                        <input type="text" class="read-only" readonly value="{{ auth()->user()->refferal }}">
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.register_on')</div>
                        <input type="text" class="read-only" readonly value="{{ auth()->user()->created_at }}">
                    </div>
                </form>
            </div>
        </div>
    </section>
    @include('frontend.layouts.popup')
@endsection
