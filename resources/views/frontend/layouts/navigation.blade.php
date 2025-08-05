<section class="mobile-navigation m-elmnt">
    <div class="navigation-item" onclick="showLoading();location.href='{{ routeUrl(route('referral')) }}'">
        <img class="n-item" src="{{ asset('new_assets/images/menu/group.png') }}">
        <label>@lang('navigation.referral')</label>
    </div>
    <div class="navigation-item" onclick="showLoading();location.href='{{ route('promotion') }}'">
        <img class="n-item" src="{{ asset('new_assets/images/menu/event.png') }}">
        <label>@lang('navigation.promo')</label>
    </div>
    <div class="navigation-item mid" onclick="showLoading();location.href='{{ route('index') }}'">
        <img class="s-item" src="{{ general()->icon_web }}">
        <span>@lang('navigation.home')</span>
    </div>
    <div class="navigation-item" onclick="showLoading();location.href='{{ routeUrl(route('history')) }}'">
        <img class="n-item" src="{{ asset('new_assets/images/menu/history.png') }}">
        <label>@lang('navigation.history')</label>
    </div>
    <div class="navigation-item" onclick="window.open('https://wa.me/{{ contact()->no_whatsapp }}/', '_blank')" id="livechatID">
        <img class="n-item" src="{{ asset('new_assets/images/menu/chat.png') }}">
        <label>@lang('navigation.chat')</label>
    </div>
</section>
