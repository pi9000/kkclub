<div id="side-navi">
    <div class="navi-container">
        <div class="navi-main">
            <div class="menu-profile">
                <div class="top">
                    <div onclick="closeNavi()" class="btn_return"
                        style="background: black; display: flex; padding: 3px; border-radius: 8px; border: 1px solid var(--theme-clr-event-active); align-items:center; justify-content:center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </div>
                </div>
                <div class="profile">
                    <img class="sidebar-logo" alt="" src="{{ general()->logo }}">
                    @guest
                    <div style="width:80%">
                        <button type="button" onclick="location.href='{{ route('login') }}'"
                            class="btn btn-sign">@lang('sidenav.btn-sign')</button>
                    </div>
                    @endguest
                    @auth
                    <div class="user-data">
                        <div class='user-name lbl-user-name'>{{ auth()->user()->username }}</div>
                        <div class='user-balance'>
                            <div><span class='currency'>RM</span>
                                <div class="lbl-user-balance" id="balance">
                                    {{ number_format(auth()->user()->balance, 2) }}</div>
                            </div>
                            <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24'>
                                <path d='M0,0H24V24H0Z' fill='none'></path>
                                <path d='M20,11A8.1,8.1,0,0,0,4.5,9M4,5V9H8' fill='none' stroke='currentcolor'
                                    stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'></path>
                                <path d='M4,13a8.1,8.1,0,0,0,15.5,2m.5,4V15H16' fill='none' stroke='currentcolor'
                                    stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5'></path>
                            </svg>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
            <div class="navi-section">
                <div class="navi-title">@lang('sidenav.all-games')</div>
                <div class="navi-items">
                    <a class="navi-item" href="{{ route('slot') }}">
                        <img src="{{ asset('new_assets/images/icons/slot.png') }}" />
                        <p>@lang('sidenav.slot')</p>
                    </a>
                    <a class="navi-item" href="{{ route('casino') }}">
                        <img src="{{ asset('new_assets/images/icons/live.png') }}" />
                        <p>@lang('sidenav.casino')</p>
                    </a>
                    <a class="navi-item" href="{{ route('sportsbook') }}">
                        <img src="{{ asset('new_assets/images/icons/sports.png') }}" />
                        <p>@lang('sidenav.sports')</p>
                    </a>
                    <a class="navi-item" href="{{ route('arcade') }}">
                        <img src="{{ asset('new_assets/images/icons/fish.png') }}" />
                        <p>@lang('sidenav.fishing')</p>
                    </a>
                    <a class="navi-item" href="{{ route('other') }}">
                        <img src="{{ asset('new_assets/images/icons/lottery.png') }}" />
                        <p>@lang('sidenav.other')</p>
                    </a>
                    <div class="navi-item"></div>
                </div>
            </div>

            <div class="navi-section">
                <div class="navi-title">@lang('sidenav.pages')</div>
                <div class="navi-items">
                    <a class="navi-item" href="{{ route('index') }}">
                        <img src="{{ asset('new_assets/images/icons/home.png') }}" />
                        <p>@lang('sidenav.home')</p>
                    </a>
                    <a class="navi-item" href="{{ routeUrl(route('history')) }}">
                        <img src="{{ asset('new_assets/images/icons/history.png') }}" />
                        <p>@lang('sidenav.history')</p>
                    </a>
                    <a class="navi-item" href="{{ routeUrl(route('profile')) }}">
                        <img src="{{ asset('new_assets/images/icons/profile.png') }}" />
                        <p>@lang('sidenav.profile')</p>
                    </a>
                    <a class="navi-item" href="{{ route('promotion') }}">
                        <img src="{{ asset('new_assets/images/icons/bonus.png') }}" />
                        <p>@lang('sidenav.promotions')</p>
                    </a>
                    <a class="navi-item" href="{{ routeUrl(route('referral')) }}">
                        <img src="{{ asset('new_assets/images/icons/referral.png') }}" />
                        <p>@lang('sidenav.referral')</p>
                    </a>
                    <a class="navi-item" href="{{ routeUrl(route('tutorial')) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-help" width="32"
                            height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="gold" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 17l0 .01" />
                            <path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4" />
                        </svg>
                        <p>@lang('sidenav.tutorials')</p>
                    </a>

                    <div class="navi-item"></div>
                    <div class="navi-item"></div>
                </div>
            </div>
        </div>
        @auth
        <div class="last-content">
            <button type="button" class="btn-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                ;>@lang('sidenav.logout')</button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        @endauth
    </div>
</div>
