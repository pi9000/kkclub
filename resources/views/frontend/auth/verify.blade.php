@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container px-1">
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <div class="title cus-title">@lang('auth.verify_form')</div>
            </div>

            <div class="form-wrapper px-10 py-15 bg-form">
                <form action="{{ route('verify_otp') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="ipAddress" value="{{ request()->ip() }}">
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.contact_noReg')</div>
                        <div class="inp-contact">
                            <input name="contact_no" id="contact_no" type="number" placeholder="@lang('auth.contact_noPReg')"
                                value="{{ auth()->user()->no_hp }}" class="default" autocomplete="off" readonly>
                            <span class="additional-content">
                                <h5 style="color:black;">+60</h5>
                            </span>
                        </div>
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('auth.enterotpReg')</div>
                        <div class="cus-input">
                            <input name="otp" type="number" placeholder="@lang('auth.otpnumberReg')" class="default" required>
                            <button id="sendotp" onclick="resendCode();" type="button">@lang('auth.sendotpReg')</button>
                        </div>
                        <span class="error-message"></span>
                    </div>
                    <button type="submit" class="btn btn-full mx-w">@lang('auth.verifyPhone')</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        let countdownTimer, countdown = 180,
            otp = false;

        function resendCode() {
            const btn = document.getElementById("sendotp");
            if (btn.disabled) {
                return;
            }

            // Disable the send button
            btn.disabled = true;

            // Start the countdown
            countdown = 180;
            $.ajax({
                type: "GET",
                url: "{{ route('resend_code') }}",
                success: function(response) {
                    if (response.success == true) {
                        setSwal(1, 'The verification code has been sent', 1500);
                        btn.disabled = true;
                        updateCountdown();
                        countdownTimer = setInterval(updateCountdown, 1000);
                    } else {
                        setSwal(0, response.message, 5000);
                        btn.disabled = true;
                    }
                },
                complete: function() {
                    btn.disabled = false;
                }
            });
        }

        function updateCountdown() {
            const btn = document.getElementById("sendotp");
            countdown--;
            if (countdown <= 0) {
                clearInterval(countdownTimer);
                btn.textContent = "@lang('auth.sendotpReg')";
                btn.disabled = false;
            } else {
                // Update the countdown text on the button
                btn.textContent = countdown.toString();
            }
        }
    </script>
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
