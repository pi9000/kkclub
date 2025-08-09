@extends('frontend.layouts.main')
@section('content')
    @guest
        <div class="sign-option f-semibold m-elmnt">
            <a onclick="showLoading()" href="{{ route('register') }}" class="link-wrap">
                <span>Register</span>
            </a>
            <a onclick="showLoading()" href="{{ route('login') }}" class="link-wrap">
                <span>Login</span>
            </a>
        </div>
    @endguest

    @include('frontend.layouts.sidenav')

    <!-- NOTIFICATION BAR -->
    <div class="notice-wrap">
        <div class="notice-line">
            <p>{{ general()->notif_bar }}</p>
        </div>
    </div>

    <!-- SLIDER -->
    <div class="container">
        <div class="swiper" id="main-swiper">
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach ($banner as $item)
                    <div class="swiper-slide"><img src="{{ $item->gambar }}" alt=""></div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <section id="games-wrapper">
        <div class="games">
            <div class="categories-container">
                <div class="game-categories">
                    <div class="game-category" category="slot">
                        <img src="{{ asset('new_assets/images/icons/slot.png') }}" alt="">
                        <label>@lang('sidenav.slot')</label>
                    </div>

                    <div class="game-category" category="casino">
                        <img src="{{ asset('new_assets/images/icons/live.png') }}" alt="">
                        <label>@lang('sidenav.casino')</label>
                    </div>

                    <div class="game-category" category="sportsbook">
                        <img src="{{ asset('new_assets/images/icons/sports.png') }}" alt="">
                        <label>@lang('sidenav.sports')</label>
                    </div>

                    <div class="game-category" category="arcade">
                        <img src="{{ asset('new_assets/images/icons/fish.png') }}" alt="">
                        <label>@lang('sidenav.fishing')</label>
                    </div>

                    <div class="game-category" category="other">
                        <img src="{{ asset('new_assets/images/icons/lottery.png') }}" alt="">
                        <label>@lang('sidenav.other')</label>
                    </div>


                    <div id="selected-category" class="hide">
                        <div class="box"></div>
                    </div>
                </div>
            </div>
            <div id="game-list">
                @foreach (providersList() as $item)
                    <div class="game-card {{ $item->type }} active">
                        <a @auth onclick="open_game({{ $item->id }},'{{ $item->slug }}')" @endauth
                            @guest
href="{{ route('login') }}" @endguest>
                            <img src="{{ $item->banner }}" alt="{{ $item->provider }}" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('frontend.layouts.popup')

    <section id="ranking" class="px-15">
        <div class="ranking-wrapper px-10 py-20">
            <div class="title">
                <span class="red-point"></span>
                <span class="red">Latest</span>
                transaction
            </div>

            <div class="ranking-filter">
                <div class="ranking-options toggle-group">
                    <button type="button" class="btn-toggle option selected"
                        onclick="showScoreTable('latest-deposit-wrapper')">
                        Deposit
                    </button>
                    <button type="button" class="btn-toggle option" onclick="showScoreTable('latest-withdraw-wrapper')">
                        Withdraw
                    </button>
                </div>
            </div>

            <div class="score-table" id="latest-deposit-wrapper">
                <table class="table-transactions">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="score-table hide" id="latest-withdraw-wrapper">
                <table class="table-transactions">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        showLoading();
        var autoreset = true;
        var app_game = ['mega888', '918kiss', '918kaya', 'scr888', 'pussy888', 'great_wall_99_2'];



        document.addEventListener("DOMContentLoaded", () => {
            // DEPOSIT APK CLICKED
            document.getElementById('btn-deposit-apk').addEventListener('click', (event) => {
                deposit_game(event.target.getAttribute('game'));
            });

            document.getElementById('btn-withdraw-apk').addEventListener('click', (event) => {
                withdraw_game(event.target.getAttribute('game'));
            });

            document.querySelectorAll('.close-sticky').forEach((x) => {
                x.addEventListener('click', () => {
                    const parent = x.closest('.sticky-element');
                    parent.classList.add('hide');
                });
            });
            // EXCLUDED GAME CLICK
            document.querySelectorAll('.excluded-by-bonus').forEach((x) => {
                x.addEventListener('click', (event) => {
                    setSwal(0, "The game is not included in the list of bonus-eligible games.");
                });
            });

            // UNDER MAINTENACE GAME CLICK
            document.querySelectorAll('.under-maintenance').forEach((x) => {
                x.addEventListener('click', (event) => {
                    setSwal(0, "The game is currently under maintenance");
                });
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

            document.querySelector('.game-categories .game-category').click();

            @if (popupHome())
                openPopup('first-come');
            @endif
            window.addEventListener("load", function() {
                hideLoading();
            });


            const urlParams = new URLSearchParams(window.location.search);
            console.log(urlParams);
            if (urlParams.has('referral')) {
                // Remove the referral parameter from the URL
                urlParams.delete('referral');

                // Update the URL in the browser's address bar (without reloading the page)
                window.history.replaceState({}, '', window.location.pathname);
            }

            if (localStorage.getItem('referral_code')) {
                console.log("abc");
                $('#referral_code').val(localStorage.getItem('referral_code'));
                $('#referral_code').attr('readonly', true);
            }
            console.log(localStorage);



        });

        function showLottery(game, tt_amount) {
            openPopup('apk-lottery-info');
            document.getElementById('play_game_id').value = game.id;
            document.getElementById('tt_wallet_amount').value = tt_amount;
        }

        function showGameAccount(user, game) {
            // DECLARE ELEMENTS
            const parent = document.getElementById('apk-game-info');
            const game_acc = document.getElementById('game-acc-id');
            const img_wrapper = parent.querySelector('.game-logo');
            const game_ps = document.getElementById('game-acc-ps');
            const btn_deposit = document.getElementById('btn-deposit-apk');
            const btn_download = document.getElementById('btn-download');

            // EMPTY IMG WRAPPER & INSERT NEW ELEMENT
            img_wrapper.innerHTML = '';
            const img = document.createElement('img');
            img.src = game.img;
            img_wrapper.appendChild(img);

            // ASSIGN VALUE
            game_acc.value = user.username;
            game_ps.value = user.password;
            btn_deposit.setAttribute('game', game.name);
            btn_download.href = game.download_link;
            openPopup('apk-game-info');
        }

        window.onresize = function() {
            try {
                setActivePosition(document.querySelector('.game-categories .game-category.selected').offsetLeft);
            } catch (e) {
                console.log('...');
            }
        }

        //
        function showScoreTable(id) {
            document.querySelectorAll('.score-table').forEach(table => {
                table.classList.add('hide');
            });
            document.getElementById(id).classList.remove('hide');

            // Update the selected button
            document.querySelectorAll('.btn-toggle').forEach(button => {
                button.classList.remove('selected');
            });
            document.querySelector(`.btn-toggle[onclick="showScoreTable('${id}')"]`).classList.add('selected');
        }

        function open_game(game_code, game_short_name) {
            showLoading();
            try {
                if (app_game.indexOf(game_short_name) !== -1) {
                    autoreset = false;
                } else {
                    autoreset = true;
                }
            } catch (e) {
                console.log(e);
            }

            $.ajax({
                url: `{{ url('game_list_click') }}/${game_code}/${game_short_name}`,
                method: 'GET',
                success: function(data) {
                    hideLoading();
                    if (data.success == true) {
                        if (data.msg == "Success") {
                            var balance = document.getElementById("balance");
                            window.open(data.url, "_blank");
                        }

                        if (data.msg === "User Credential") {
                            console.log(data.gameinfo.short_name);
                            if (data.gameinfo.short_name === 'pussy888s') {
                                document.getElementById('game-notice').textContent =
                                    "*** Fishing Game is Banned ***";
                            } else if (data.gameinfo.short_name === '918kiss' || data.gameinfo.short_name ===
                                'mega888') {
                                document.getElementById('game-notice').textContent =
                                    "Fishing Game Minimum x4 Withdraw";
                            } else {
                                document.getElementById('game-notice').textContent = "";
                            }

                            showGameAccount(data.credential, data.gameinfo);
                        }

                        if (data.msg == "Game List") {
                            window.open(data.url, "_blank");
                        }


                        if (data.msg == "Lottery") {
                            console.log(data);
                            showLottery(data.game, data.tt_amount);
                        }
                    } else {
                        setSwal(0, data.msg, 5000);
                    }
                },
            })
        }
    </script>
    <script>
        function getRandomName() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const numbers = '0123456789';
            const prefix = chars[Math.floor(Math.random() * chars.length)] + chars[Math.floor(Math.random() * chars.length)];
            const suffix = numbers[Math.floor(Math.random() * 10)] + numbers[Math.floor(Math.random() * 10)];
            const middle = '*'.repeat(4);
            return `${prefix}${middle}${suffix}`;
        }
    
        function getFormattedLocalDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
    
        function generateSortedTimestamps(count, minMinutesAgo = 5, maxMinutesAgo = 10) {
            const now = new Date();
            const timestamps = [];
    
            for (let i = 0; i < count; i++) {
                const minutesAgo = Math.random() * (maxMinutesAgo - minMinutesAgo) + minMinutesAgo;
                const timestamp = new Date(now.getTime() - minutesAgo * 60 * 1000);
                timestamps.push(timestamp);
            }
    
            // Sort from NEWEST to OLDEST
            return timestamps.sort((a, b) => b - a);
        }
    
        function getWeightedRandomAmount(ranges) {
            const rand = Math.random() * 100;
            let selectedRange;
    
            if (rand < 30) selectedRange = ranges[0];       // 30%
            else if (rand < 85) selectedRange = ranges[1];  // 55%
            else if (rand < 92) selectedRange = ranges[2];  // 7%
            else if (rand < 97) selectedRange = ranges[3];  // 5%
            else selectedRange = ranges[4];                 // 3%
    
            const min = selectedRange.min;
            const max = selectedRange.max;
            const step = 10;
    
            const random = Math.floor((Math.random() * ((max - min) / step + 1))) * step + min;
            return `RM ${random.toFixed(2)}`;
        }
    
        function getRandomDepositAmount() {
            const ranges = [
                { min: 30, max: 70 },
                { min: 70, max: 200 },
                { min: 200, max: 500 },
                { min: 500, max: 1000 },
                { min: 1000, max: 2000 },
            ];
            return getWeightedRandomAmount(ranges);
        }
    
        function getRandomWithdrawalAmount() {
            const ranges = [
                { min: 50, max: 70 },
                { min: 70, max: 200 },
                { min: 200, max: 500 },
                { min: 500, max: 1000 },
                { min: 1000, max: 30000 },
            ];
            return getWeightedRandomAmount(ranges);
        }
    
        function generateFakeTransactions() {
            const tableBody = document.querySelector("#latest-deposit-wrapper tbody");
            tableBody.innerHTML = '';
    
            const timestamps = generateSortedTimestamps(5, 5, 10); // NEWEST → OLDEST from last 5–10 mins
    
            timestamps.forEach(date => {
                const row = document.createElement("tr");
    
                const nameCell = document.createElement("td");
                nameCell.textContent = getRandomName();
    
                const amountCell = document.createElement("td");
                amountCell.textContent = getRandomDepositAmount();
    
                const dateCell = document.createElement("td");
                dateCell.textContent = getFormattedLocalDate(date);
    
                row.appendChild(nameCell);
                row.appendChild(amountCell);
                row.appendChild(dateCell);
    
                tableBody.appendChild(row);
            });
        }
    
        function generateFakeTransactionsWd() {
            const tableBody = document.querySelector("#latest-withdraw-wrapper tbody");
            tableBody.innerHTML = '';
    
            const timestamps = generateSortedTimestamps(5, 5, 10); // NEWEST → OLDEST from last 5–10 mins
    
            timestamps.forEach(date => {
                const row = document.createElement("tr");
    
                const nameCell = document.createElement("td");
                nameCell.textContent = getRandomName();
    
                const amountCell = document.createElement("td");
                amountCell.textContent = getRandomWithdrawalAmount();
    
                const dateCell = document.createElement("td");
                dateCell.textContent = getFormattedLocalDate(date);
    
                row.appendChild(nameCell);
                row.appendChild(amountCell);
                row.appendChild(dateCell);
    
                tableBody.appendChild(row);
            });
        }
    
        function runGeneratorsAndReschedule() {
            generateFakeTransactions();
            generateFakeTransactionsWd();
    
            const nextInterval = Math.floor(Math.random() * (10 - 5 + 1) + 5) * 60 * 1000; // 5–10 mins
            setTimeout(runGeneratorsAndReschedule, nextInterval);
        }
    
        runGeneratorsAndReschedule();
    </script>
@endpush

@push('footer')
    {!! general()->home_footer !!}
@endpush
