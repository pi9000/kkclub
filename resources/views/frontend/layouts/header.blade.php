<div class="header">
    <div id="header-left">
        <svg onclick="openNavi()" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-menu-2">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 6l16 0" />
            <path d="M4 12l16 0" />
            <path d="M4 18l16 0" />
        </svg>
    </div>
    <div class="site-logo" id="header-mid">
        <img onclick="showLoading();window.location.href='{{ route('index') }}'" class="logo" alt
            src="{{ general()->logo }}">
    </div>

    <div id="header-right" style="gap: 10px">
        <div id="languageSelector" class="top-option" onclick="togglePopup()">
            <img style="width:21px; height:21px;"
                src="{{ asset('new_assets/images/language/flag_' . app()->getLocale() . '.png') }}"
                alt="Selected Language Flag">
            <span>@lang('public.language')</span>
        </div>

        <a class="top-option" href="{{ route('tutorial') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help-hexagon" width="22"
                height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentcolor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                <path d="M12 16v.01" />
                <path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
            </svg>
            <span>@lang('public.tutorial')</span>
        </a>
    </div>
</div>
<div class="overlay default" id="overlay"></div>
<div id="overlay1"></div>
<div id="languagePopup" class="popup-container">
    <div class="popup-header">
        <div class="popup-title">@lang('public.select-lang')</div>
        <div class="close-icon" onclick="togglePopup()">✖</div>
    </div>
    <a class="language-option" href="{{ route('setlang', 'en') }}">
        <img src="{{ asset('new_assets/images/language/flag_en.png') }}" alt="English">
        <div>@lang('public.language_en')</div>
    </a>
    <a class="language-option" href="{{ route('setlang', 'bm') }}">
        <img src="{{ asset('new_assets/images/language/flag_bm.png') }}" alt="Malay">
        <div>@lang('public.language_bm')</div>
    </a>
    <a class="language-option" href="{{ route('setlang', 'bg') }}">
        <img src="{{ asset('new_assets/images/language/flag_bg.png') }}" alt="Malay">
        <div>@lang('public.language_bg')</div>
    </a>
    <a class="language-option" href="{{ route('setlang', 'cn') }}">
        <img src="{{ asset('new_assets/images/language/flag_cn.png') }}" alt="Chinese">
        <div>@lang('public.language_cn')</div>
    </a>
</div>
@auth
    @if (auth()->user()->verified == 1)
        <div class="top-menu theme">
            <div class="upper">
                <div class="wallet">
                    <div class="wallet-amount">@lang('public.wallet-amount')<div class="user-balance" id="balance">
                            {{ number_format(auth()->user()->balance, 2) }}</div>
                    </div>
                </div>

                <div id="profile-wrapper" onclick="showLoading();location.href='{{ route('profile') }}'">
                    <img src="{{ asset('new_assets/images/profile.png') }}">
                    <div>{{ auth()->user()->username }}</div>
                </div>
            </div>
            <div class="options-wrapper">
                <div class="options">
                    <div class="option" onclick="getLatestBalance()">
                        <img src="{{ asset('new_assets/images/refresh.png') }}" alt="Refresh">
                        <label>@lang('public.refresh')</label>
                    </div>
                    <div class="option" onclick="showLoading(); window.location.href='{{ routeUrl(route('deposit')) }}'">
                        <img src="{{ asset('new_assets/images/deposit.png') }}" alt="Deposit">
                        <label>@lang('public.deposit')</label>
                    </div>

                    <div class="option" onclick="showLoading(); window.location.href='{{ routeUrl(route('withdraw')) }}'">
                        <img src="{{ asset('new_assets/images/withdraw.png') }}" alt="Withdraw">
                        <label>@lang('public.withdraw')</label>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth
