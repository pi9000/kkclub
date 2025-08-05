@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <section class="default">
        <div class="container px-1">
            <div class="page-top-wrapper px-10 py-15 mb-15">
                <div class="title cus-title">@lang('sidenav.tutorials')</div>
            </div>

            <div class="form-wrapper toggle-group bg-form px-15 py-15">
                <div id="tutorial-option-wrap">
                    <div class="options">
                        <button class="option btn-toggle" target="tutorial-register" id="tregister">@lang('public.register')</button>
                        <button class="option btn-toggle" target="tutorial-deposit" id="tdeposit">@lang('public.deposit')</button>
                        <button class="option btn-toggle" target="tutorial-withdraw" id="twithdraw">@lang('public.withdraw')</button>
                    </div>
                </div>
                <div class="toggle-content" id="tutorial-register">
                    <div class="title mb-15">@lang('public.tutorial-register')</div>
                    <video width="100%" controls>
                        <source src="{{ general()->tutorial_register }}" type="video/mp4">
                        @lang('public.notSupportVideoTag')
                    </video>
                </div>
                <div class="toggle-content" id="tutorial-withdraw">
                    <div class="title mb-15">@lang('public.tutorial-withdraw')</div>
                    <video width="100%" controls>
                        <source src="{{ general()->tutorial_withdraw }}" type="video/mp4">
                        @lang('public.notSupportVideoTag')
                    </video>
                </div>
                <div class="toggle-content" id="tutorial-deposit">
                    <div class="title mb-15">@lang('public.tutorial-deposit')</div>
                    <video width="100%" controls>
                        <source src="{{ general()->tutorial_deposit }}" type="video/mp4">
                        @lang('public.notSupportVideoTag')
                    </video>
                </div>

            </div>
        </div>
    </section>
    @include('frontend.layouts.popup')
@endsection
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);

            // Get a specific parameter value by name
            const paramValue = urlParams.get('target');

            // Example usage
            if (paramValue !== null) {
                if (paramValue == "deposit") {
                    document.getElementById('tdeposit').click();
                }
                if (paramValue == "withdraw") {
                    document.getElementById('twithdraw').click();
                }
                if (paramValue == "register") {
                    document.getElementById('tregister').click();
                }
                console.log('Value of paramName:', paramValue);
            } else {
                document.querySelector('.btn-toggle').click();
            }
        });
    </script>
@endpush

@push('style')
    <style>
        #tutorial-option-wrap {
            margin-bottom: 2rem;
        }

        #tutorial-option-wrap .options {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
            overflow: scroll;
            justify-content: center;
        }

        #tutorial-option-wrap .option {
            font-size: 1.2rem;
            flex: 0 0 calc(25% - 0.5rem);
            padding: 0.8rem 1.3rem;
            background: var(--theme-clr-toggle-inactive);
            border-radius: 0.8rem;
            text-transform: capitalize;
        }

        #tutorial-option-wrap .selected {
            background-color: var(--theme-clr-toggle-active);
            font-weight: var(--fw-medium);
        }

        #tutorial-option-wrap button {
            border: none;
        }

        #tutorial-option-wrap .options::-webkit-scrollbar {
            width: 0 !important;
            height: 0 !important;
            background-color: transparent;
        }

        #tutorial-option-wrap .options::-webkit-scrollbar-thumb {
            width: 0 !important;
            height: 0 !important;
            background-color: transparent !important;
        }

        .toggle-content {
            margin: 0 5%;
        }

        video {
            border-radius: 0.5rem;
            box-shadow: 0 5px 10px 5px rgba(0, 0, 0, 0.3);
            border: 2px solid white;
        }
    </style>
@endpush
