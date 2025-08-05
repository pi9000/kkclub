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
            <div class="form-title mb-15">@lang('auth.login-form')</div>
            <form name="login-form">
                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('auth.usernameLogin')</div>
                    <input id="username" name="usernameLogin" type="text" placeholder="Your username"
                        value="" class="default" autocomplete="username" required>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('auth.passwordLogin')</div>
                    <input id="password" name="passwordLogin" type="password" placeholder="Your password" value=""
                        class="default " required autocomplete="current-password">
                </div>

                <button name="buttonLogin" type="submit" aria-label="submit" class="btn btn-full mx-w mb-15">@lang('auth.buttonLogin')</button>
                <div class="back-link mb-15">
                    <span>@lang('auth.donthaveaccount') <a href="{{ route('register') }}">@lang('auth.registerNow')</a></span>
                </div>
            </form>
        </div>
    </div>
</section>
@include('frontend.layouts.popup')
@endsection
@push('script')
<script>
    $("form[name=login-form]").on('submit', function(e) {
            showLoading();
            e.preventDefault();
            let formData = {};
            $.each($(this).serializeArray(), function(i, val) {
                formData[val.name] = val.value
            });
            formData.usernameLogin = formData.usernameLogin.replace(/\s/g, '');
            let btnTxt = $("button[name=buttonLogin]").html()
            $.ajax({
                url: "{{ route('login') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    username: formData.usernameLogin,
                    password: formData.passwordLogin
                },
                beforeSend: function() {
                    $("input[name='usernameLogin']").attr('readonly', true)
                    $("input[name='passwordLogin']").attr('readonly', true)
                    $("button[name=buttonLogin]").attr('disabled', true)
                    $("button[name=buttonLogin]").html('Loading...');
                },
                success: function(data) {
                    if (data.code == 200) {
                        let msg = '';
                        setSwal(1, '@lang('public.login_success')', 5000);
                        if (msg == '') {
                            setTimeout(function() {
                                location.reload();
                            }, 300);
                        }
                    } else {
                        let msg = '';
                        setSwal(0, data.message, 5000);
                        if (msg == '') {
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                        $("input[name='usernameLogin']").removeAttr('readonly')
                        $("input[name='passwordLogin']").removeAttr('readonly')
                        $("input[name='passwordLogin']").val('')
                        $("button[name=buttonLogin]").removeAttr('disabled')
                        $("button[name=buttonLogin]").html(btnTxt)
                    }
                    hideLoading();
                },
                error: function(data) {
                    let msg = '';
                    setSwal(0, '@lang('public.wrong_password')', 5000);
                    if (msg == '') {
                        location.reload();
                    }
                    $("input[name='usernameLogin']").removeAttr('readonly')
                    $("input[name='passwordLogin']").removeAttr('readonly')
                    $("input[name='usernameLogin']").val('')
                    $("input[name='passwordLogin']").val('')
                    $("button[name=buttonLogin]").removeAttr('disabled')
                    $("button[name=buttonLogin]").html(btnTxt)
                    hideLoading();
                }
            });
        });
</script>
@endpush
