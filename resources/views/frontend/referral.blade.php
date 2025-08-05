@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default mb-15">
        <div class="container px-1">
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <div class="title cus-title">@lang('public.referral')</div>
            </div>
            <div class='referral-info'>
                <div class="row">
                    <div style="position:relative">
                        <div id="total-downline"><span>@lang('public.total-downline') </span>{{ number_format($upline) }}</div>
                    </div>
                </div>
                <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <div class="input-label" style="font-weight:600;text-align:center;font-size:1.4rem">@lang('public.reff-code')
                    </div>
                    <div style="text-align: center;font-weight: 600;font-size: 36px;margin-top: 5px;">{{ $reff->reff_code }}</div>
                </div>
                <div class="input-field default mx-w">
                    <div class="input-label" style="font-weight:600;text-align:center">@lang('public.reff-link')</div>
                    <div class="copy-field">
                        <input style="border-radius:0.5rem 0 0 0.5rem" type="text" id="referral_to_copy" class="default"
                            value="{{ route('referral.redirect',$reff->reff_code) }}" readonly>
                        <div class="copy" style="border-radius:0 0.5rem 0.5rem 0;" id="contentToCopy"><span>@lang('public.copy')</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="social-share" style="margin-bottom:1rem;">@lang('public.social-share')</div>
                    <div class="social-placing">
                        <div>
                            <img class="social-size" onclick="shareOnWhatsApp('{{ $reff->reff_code }}')" data-social-media="whatsapp"
                                src="{{ asset('new_assets/images/social/whatsapp.png') }}" alt="WhatsApp">
                        </div>
                        <div>
                            <img class="social-size" onclick="shareOnTelegram('{{ $reff->reff_code }}')" data-social-media="telegram"
                                src="{{ asset('new_assets/images/social/telegram.png') }}" alt="Telegram">
                        </div>
                        <div>
                            <img class="social-size" onclick="shareOnFacebook('{{ $reff->reff_code }}')" data-social-media="facebook"
                                src="{{ asset('new_assets/images/social/facebook.png') }}" alt="Facebook">
                        </div>
                        <div>
                            <img class="social-size" onclick="shareOnTwitter('{{ $reff->reff_code }}')" data-social-media="twitter"
                                src="{{ asset('new_assets/images/social/twitter.png') }}" alt="Twitter">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('frontend.layouts.popup')
@endsection
@push('script')
    <script>
        // onclick social media to share referral code
        //whatsapp
        function shareOnWhatsApp(referralCode) {
            var message = 'Register to get a RM10 free Angpow!!\n{{ url('reff') }}/' + encodeURIComponent(
                referralCode);
            var url = 'whatsapp://send?text=' + encodeURIComponent(message);
            window.location.href = url;
        }

        //telegram
        function shareOnTelegram(referralCode) {
            var message = 'Register to get a RM10 free Angpow!!\n{{ url('reff') }}/' + encodeURIComponent(
                referralCode);
            var url = 'https://telegram.me/share/url?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        }

        //facebook
        function shareOnFacebook(referralCode) {
            var message = 'Register to get a RM10 free Angpow!!\n{{ url('reff') }}/' + encodeURIComponent(
                referralCode);
            var url = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(message);
            window.open(url, '_blank');
        }

        //twitter
        function shareOnTwitter(referralCode) {
            var message = 'Register to get a RM10 free Angpow!!\n{{ url('reff') }}/' + encodeURIComponent(
                referralCode);
            var url = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(message);
            window.open(url, '_blank');
        }

        // function share_claim(socialMedia) {
        //     fetch("{{ url('share_claim') }}", {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': 'gpDsCtxMtzAuMlqKLw7qZgbUK1KP3LX5e0YHI6Fj',
        //         },
        //         body: JSON.stringify({
        //             social_media: socialMedia,
        //             referral_code: 'XFYUHT',
        //             // Add any other necessary data
        //         }),
        //     })
        // }

        document.querySelectorAll('.social-size').forEach((button) => {
            button.addEventListener('click', function() {
                const socialMedia = this.getAttribute('data-social-media');
                share_claim(socialMedia);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const copyableDiv = document.getElementById("contentToCopy");
            const referral_to_copy = document.getElementById("referral_to_copy");
            const originalText = copyableDiv.innerText;
            copyableDiv.addEventListener("click", function() {
                // Create a text area element to temporarily hold the text
                var textArea = document.createElement("textarea");
                textArea.value = referral_to_copy.value;
                document.body.appendChild(textArea);

                // Select and copy the text from the text area
                textArea.select();
                document.execCommand("copy");

                // Remove the temporary text area
                document.body.removeChild(textArea);

                // Indicate that the content has been copied (you can use a tooltip or other visual feedback)
                copyableDiv.innerText = "@lang('public.copy_success')";

                setTimeout(function() {
                    copyableDiv.innerText = originalText;
                }, 3000);
            });

            //openPopup('referral-details');
        });

        document.querySelectorAll('.copy').forEach((x) => {
            x.addEventListener('click', copyText);
        });

        function copyText() {
            let container = this.closest('.copy-field');
            const input = container.querySelector('input');

            // Copy the text inside the text field
            navigator.clipboard.writeText(input.value);
            chgCopyText(this);
        }

        async function chgCopyText(x) {
            const delay = (s) => new Promise((resolve) => setTimeout(resolve, s * 1000));
            //x.innerHTML = x.innerHTML == 'Copy' ? 'Copied' : 'Copy';
            let span = x.querySelector('span');
            span.innerHTML = "Copied";
            await delay(2);
            span.innerHTML = "Copy";
        }

        // function claim_bonus() {
        //     showLoading();
        //     $.ajax({
        //         url: "/claim_bonus",
        //         method: 'GET',
        //         success: function(data) {
        //             hideLoading();
        //             // console.log(data);
        //             if (data.success) {
        //                 setSwal(1, data.msg, 3500);
        //             } else {
        //                 setSwal(0, data.msg, 5000);
        //             }
        //         },
        //     })
        // }

        // function claim_count_bonus() {
        //     showLoading();
        //     $.ajax({
        //         url: "/claim_count_bonus",
        //         method: 'GET',
        //         success: function(data) {
        //             hideLoading();
        //             // console.log(data);
        //             if (data.success) {
        //                 window.location.reload();
        //                 setSwal(1, data.msg, 3500);
        //             } else {
        //                 setSwal(0, data.msg, 5000);
        //             }
        //         },
        //     })
        // }

        // function claim_monthly_bonus() {
        //     showLoading();
        //     $.ajax({
        //         url: "/claim_monthly_bonus",
        //         method: 'GET',
        //         success: function(data) {
        //             hideLoading();
        //             // console.log(data);
        //             if (data.success) {
        //                 setSwal(1, data.msg, 3500);
        //             } else {
        //                 setSwal(0, data.msg, 5000);
        //             }
        //         },
        //     })
        // }
    </script>
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset('new_assets/css/referral.css') }}" />
    <style>
        .vip-img-wrapper {
            padding: 1rem;
            border-radius: 0.8rem;
        }

        .vip-img-wrapper img {
            width: 100%;
            filter: drop-shadow(0px 4px 4px rgba(25, 25, 25));
        }

        /* .monthly-bonus{
            filter:brightness(0.3);
        }

        #monthly-bonus-container{
            position: relative;
        } */

        #monthly-bonus-container:after {
            content: 'COMING SOON';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -30%);
            color: white;
            font-weight: 600;
            font-size: 14px;
            background: linear-gradient(0deg, #915f0d 33%, #eec356 66%, #cea962);
            padding: 5px 10px;
            color: #573800;
            border-radius: 50px;
        }
    </style>
@endpush
