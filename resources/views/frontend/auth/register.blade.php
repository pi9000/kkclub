@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.sidenav')
<section class="default">
    <div class="container px-1">
        <div style="width:100%" class="mb-15">
            <img style="width:100%;border-radius: 8px; box-shadow: 0 0 15px 3px black"
                src="{{ asset('new_assets/images/sign/banner.png') }}" alt="" />
        </div>

        <div class="form-wrapper bg-form px-15 py-15">
            <div class="form-title mb-15">@lang('auth.reg-form')</div>
            <form name="login-form" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('auth.usernameReg')</div>
                    <input name="username" id="username" type="text" placeholder="@lang('auth.usernamePReg')"
                        class="default" value="" autocomplete="off" required>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label"> @lang('auth.passwordReg')</div>
                    <input id="password" id="password" type="password" class="default " name="password" value=""
                        autocomplete="new-password" placeholder="@lang('auth.passwordPReg')" required>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label"> @lang('auth.password_confirmationReg')</div>
                    <input id="password-confirm" type="password" class="default " name="password_confirmation" value=""
                        autocomplete="new-password" placeholder="@lang('auth.password_confirmationPReg')" required>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label"> @lang('auth.contact_noReg')</div>
                    <div class='inp-contact'>
                        <input name="contact_no" id="contact_no" type="number"
                            placeholder="@lang('auth.contact_noPReg')" value="" class="default" autocomplete="off"
                            required>
                        <span class="additional-content"><h5 style="color:black;">+60</h5></span>
                    </div>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('auth.nameReg')</div>
                    <input name="name" id="name" type="text" placeholder="@lang('auth.namePReg')" value=""
                        class="default" autocomplete="off" required>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('auth.referral_codeReg')</div>
                    <input name="referral_code" id="referral_code" type="text"
                        placeholder="@lang('auth.referral_codePReg')" value="" class="default">
                </div>

                <button type="submit" name="buttonLogin"
                    class="btn btn-full mx-w mb-15">@lang('auth.buttonReg')</button>
                <div class="back-link mb-15">
                    <span>@lang('auth.alreadyhaveaccount') <a
                            href="{{ route('login') }}">@lang('auth.login-form')</a></span>
                </div>
            </form>
        </div>
</section>
@include('frontend.layouts.popup')
@endsection

@push('style')
<style>
    #contact_no {
        padding-left: 5.5rem;
        position: relative;
    }

    .inp-contact {
        position: relative;
        display: grid;
    }

    .additional-content {
        content: '';
        position: absolute;
        left: 1rem;
        top: 50%;
        background: #ffffffbb;
        color: #000000bb;
        transform: translateY(-50%);
    }

    .additional-content img {
        height: 2rem;
    }
</style>
@endpush
