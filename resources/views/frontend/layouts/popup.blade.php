@if(popupHome())
<div class="popup cus-popup" id="first-come">
    <div class="content bg-popup">
        <div class="content-top">
            <div class="close-popup">
                <div onclick="closePopup('first-come')" class="btn_return">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0,0H24V24H0Z" fill="none" />
                        <path d="M18,6,6,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                        <path d="M6,6,18,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="content-body" style="margin-bottom:1rem">
            <section class="default">
                <div class="media-wrap">
                    <img src="{{ popupHome()->gambar }}" alt="{{ popupHome()->title }}">
                </div>
                <div class="description">
                    <div class="title">{{ popupHome()->title }}</div>
                    <div>{{ popupHome()->description }}!</div>
                    <br>
                    <div style="font-size: 1.4rem; color: white; padding-right: 0.5rem;">
                    </div>
                </div>
            </section>
        </div>
        <div class="content-footer">
        </div>
    </div>
</div>
@endif

<!-- GAME POPUP -->
<div class="popup cus-popup" id="apk-game-info">
    <div class="content bg-popup">
        <div class="content-top">
            <div class="close-popup">
                <div onclick="closePopup('apk-game-info')" class="btn_return">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0,0H24V24H0Z" fill="none" />
                        <path d="M18,6,6,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                        <path d="M6,6,18,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="default info">
                <div class="game-logo"></div>
                <form>
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">Game Account:</div>
                        <div class="copy-field">
                            <input id="game-acc-id" type="text" class="default" disabled>
                            <div class="copy"><span>Copy</span></div>
                        </div>
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">Game Password:</div>
                        <div class="copy-field">
                            <input id="game-acc-ps" type="text" class="default" disabled>
                            <div class="copy"><span>Copy</span></div>
                        </div>
                    </div>
                </form>
                <div id="game-notice"></div>
                <div class="game-action-wrap">
                    <button type="button" class="btn btn-action" id="btn-deposit-apk">Transfer credits to
                        game</button>
                    <a class="btn" id="btn-download">Download</a>
                </div>
            </section>
        </div>
        <div class="content-footer"></div>
    </div>
</div>
<div class="popup cus-popup" id="emadaniguide">
    <div class="content">
        <div class="content-top">
            <div class="close-popup">
                <div onclick="closePopup('emadaniguide')" class="btn_return">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0,0H24V24H0Z" fill="none" />
                        <path d="M18,6,6,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                        <path d="M6,6,18,18" fill="none" stroke="currentcolor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="default info">
                <div class="step">
                    <div class="title">Step 1:</div>
                    <img src="../new_assets/100step1.jpg" alt="step1">
                </div>
                <div class="step">
                    <div class="title">Step 2:</div>
                    <img src="../new_assets/100step2.jpg" alt="step2">
                </div>
                <div class="step">
                    <div class="title">Step 3:</div>
                    <img src="../new_assets/100step3.jpg" alt="step3">
                </div>
                <div class="step">
                    <div class="title">Step 4:</div>
                    <img src="../new_assets/100step4.jpg" alt="step4">
                </div>
            </section>
        </div>
        <div class="content-footer"></div>
    </div>
</div>
