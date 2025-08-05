@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container transaction">
            <!-- TOP BANNER -->
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <a class="previous-link" onclick="showLoading()" href="https://kkclub.live/en/profile ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 6l-6 6l6 6" />
                    </svg>
                </a>
                <div class="title cus-title">@lang('public.reset_password')</div>
            </div>


            <div class="form-wrapper px-15 py-15 bg-form">
                <form method="POST" action="{{ route('update.password') }}">
                    @csrf
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.current_password')</div>
                        <input type="password" name="current_password" placeholder="Your current password" class="default" required>
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.new_password')</div>
                        <input type="password" name="new_password" placeholder="Your new password" class="default" required>
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.new_password_confirmation')</div>
                        <input type="password" name="new_password_confirmation" placeholder="Re-enter your new password"
                            class="default" required>
                    </div>

                    <button type="submit" class="btn btn-full mx-w" onclick="showLoading()">Change Password</button>
                </form>
            </div>
        </div>
    </section>
    @include('frontend.layouts.popup')
@endsection
