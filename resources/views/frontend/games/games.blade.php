@extends('frontend.layouts.main')
@section('content')
    @include('frontend.layouts.sidenav')
    <!-- GAMES -->
    <section id="games-wrapper">
        <div class="page-top-wrapper px-10 py-15 mb-15">
            <div class="title cus-title">{{ $pageTitle }}</div>
        </div>
        <div class="games">
            <div id="game-list">
                @foreach ($provider as $item)
                    <div class="game-card {{ strtolower($pageTitle) }} active">
                        <a onclick="open_game({{ $item->id }},'{{ $item->slug }}')">
                            <img src="{{ $item->banner }}" alt="{{ $item->slug }}" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('frontend.layouts.popup')
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
                            balance.innerHTML = "0.00";
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
@endpush
