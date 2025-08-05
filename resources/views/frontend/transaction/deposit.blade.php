@extends('frontend.layouts.main')
@section('content')
@include('frontend.layouts.sidenav')
<section class="default">
    <div class="container transaction">
        <div class="page-top-wrapper px-10 py-15 mb-15">
            <div class="title cus-title">@lang('public.depositTo') <b>@lang('public.main')</b> @lang('public.wallet')
            </div>
        </div>


        <div class="toggle-group form-wrapper px-15 py-15 bg-form">
            <!-- Deposit Options -->
            <div class="input-field default mx-w mb-15">
                <div class="input-label">@lang('public.depositOptions')</div>
                <!-- Toggle Options -->
                <div class="toggle-options deposit-methods">
                    <button type="button" class="btn-toggle method" id="tgl-bank" target="deposit-bank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M0,0H24V24H0Z" fill="none" />
                            <path d="M3,21H21" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M3,10H21" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M5,6l7-3,7,3" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M4,10V21" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M20,10V21" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M8,14v3" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M12,14v3" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M16,14v3" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                        </svg>
                        @lang('public.bank_in')
                    </button>
                    <button type="button" class="btn-toggle method" id="tgl-ewallet" target="deposit-ewallet">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-wallet" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" />
                            <path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />
                        </svg>
                        @lang('public.e-Wallet')
                    </button>

                    <button type="button" class="btn-toggle method" id="tgl-telco" target="deposit-telco">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M0,0H24V24H0Z" fill="none" />
                            <path d="M12,12m-1,0a1,1,0,1,0,1-1,1,1,0,0,0-1,1" fill="none" stroke="currentcolor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M16.616,13.924a5,5,0,1,0-9.23,0" fill="none" stroke="currentcolor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M20.307,15.469a9,9,0,1,0-16.615,0" fill="none" stroke="currentcolor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M9,21l3-9,3,9" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                            <path d="M10,19h4" fill="none" stroke="currentcolor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="1.5" />
                        </svg>
                        @lang('public.tng-reload')
                    </button>
                </div>
            </div>

            <!-- Option: Bank -->
            <form action="{{ route('transaksi.deposit') }}" method="post" enctype="multipart/form-data"
                class="toggle-content" id="deposit-bank">
                @csrf
                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.select-bank')</div>
                    <select class="default" id='bank_setting_id' name="metode"
                        onchange="changeDepositAccount(this.value)">
                        @foreach ($bank as $item)
                        <option value="{{ $item->id }}" account_no-attr="{{ $item->nomor_rekening }}"
                            bank_name-attr="{{ $item->nama_bank }}" owner_name-attr="{{ $item->nama_pemilik }}">{{
                            $item->nama_bank }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="dari_bank" id="dari_bank"
                    value="{{ auth()->user()->nama_bank }} / {{ auth()->user()->nomor_rekening }} / {{ auth()->user()->nama_pemilik }}"
                    hidden>
                <div class="deposit-account mx-w mb-15" id="bank-account">
                    <div class="row">
                        <div class="data">
                            <div class="label">@lang('public.bank')</div>
                            <div class="value" id="column1">{{ $bank_first->nama_bank }}</div>
                        </div>
                        <div class="btn-copy" onclick="copyToClipboard(1)">@lang('public.copy')</div>
                    </div>
                    <div class="row">
                        <div class="data">
                            <div class="label">@lang('public.acc_no')</div>
                            <div class="value" id="column2">{{ $bank_first->nomor_rekening }}</div>
                        </div>
                        <div class="btn-copy" onclick="copyToClipboard(2)">@lang('public.copy')</div>
                    </div>
                    <div class="row">
                        <div class="data">
                            <div class="label">@lang('public.name')</div>
                            <div class="value" id="column3">{{ $bank_first->nama_pemilik }}</div>
                        </div>
                        <div class="btn-copy" onclick="copyToClipboard(3)">@lang('public.copy')</div>
                    </div>
                </div>
                <!--include here for call out admin bank detail-->

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.enter_amount')</div>
                    <input type="text" placeholder="@lang('public.deposit_amount')" name="nominal" id="amount"
                        class="default deposit-amount" required>
                </div>


                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.receipt')</div>
                    <div class="input-file">
                        <div style="position:relative">
                            <label for="fileToUpload" class="upload-label">@lang('public.receiptBrowser')</label>
                            <input type="file" name="gambar" id="fileToUpload" accept="image/*" required></input>
                        </div>
                    </div>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.bonus')</div>
                    <select class="default" id='bonus_event_id' name="bonus" onchange="changeBonusChecking(this.value)">
                        <option value="tanpabonus">@lang('public.no_bonus')</option>
                        @foreach ($bonus as $bonuse)
                        <option value="{{ $bonuse->id }}" deposit_min-attr="{{ $bonuse->minimal_deposit }}" {{
                            bonusecheck($bonuse->id, auth()->user()->id) }}>
                            {{ $bonuse->judul }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- NOTICE BOX -->
                <div class="notice-box mb-15">
                    <div class="top">
                        <div class="label"><img src="{{ asset('new_assets/images/important.png') }}" />Important
                            notes</div>
                        <div class="toggle-icon" onclick="toggleNotice(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path d="M0,0H24V24H0Z" fill="none" />
                                <path d="M6,15l6-6,6,6" fill="none" stroke="currentcolor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                    <!-- Important Note -->
                    <div class="content">
                        <ul>
                            <li>
                                <p>Always check for the latest active deposit bank details before making a deposit.</p>
                            </li>
                            <li>
                                <p>Please make the transfer before submit the transaction to avoid the transaction is
                                    delay.</p>
                            </li>
                            <li>
                                <p>Note that all deposit transactions will have <strong>x<span
                                            id="winover-rate">1.5</span> WINOVER rate.</strong></p>
                            </li>
                            <li>
                                <p>Minimum Deposit : <strong>RM <span id="min_deposit">{{ general()->min_depo }}</span></strong></p>
                            </li>
                            <li>
                                <p>Please fill your <strong>phone number in the recipient reference or remark
                                        field.</strong></p>
                            </li>
                            <li>
                                <p><strong style="color:red;">Please do not fill in sensitive keywords such as pussy888
                                        and mega888 in the transaction remarks. We do not respond to bank account
                                        freezes.</strong></p>
                            </li>
                        </ul>
                    </div>
                </div>

                <button type="submit" onclick="showLoading()" class="btn btn-full mx-w">@lang('public.submit')</button>
            </form>

            <form action="{{ route('transaksi.deposit.auto') }}" method="post" enctype="multipart/form-data"
                class="toggle-content" id="deposit-ewallet">
                @csrf
                <div class="toggle-group">
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.deposit_channel')</div>
                        <!-- Toggle Options -->
                        <input type="text" name="dari_bank" id="user_wallet_id"
                            value="{{ auth()->user()->nama_bank }} / {{ auth()->user()->nomor_rekening }} / {{ auth()->user()->nama_pemilik }}"
                            hidden>
                        <div class="toggle-options chn-wrap">
                            <button type="button" class="btn-toggle method btn-chn selected" target="chn-amount"
                                onclick="changeDuitnowInput(3)">
                                <img src="{{ asset('new_assets/images/ompay.png') }}" alt>
                                OmPay
                            </button>
                            <input type="text" name="payment_gateway_id" id="payment_gateway_id2" value="3" hidden>
                        </div>
                    </div>
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.enter_amount')</div>
                        <input type="text" placeholder="@lang('public.deposit_amount')" name="nominal" id="amount"
                            class="default deposit-amount" required>
                    </div>

                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.bonus')</div>
                        <select class="default" id='bonus_event_id' name="bonus"
                            onchange="changeBonusChecking(this.value)">
                            <option value="tanpabonus">@lang('public.no_bonus')</option>
                            @foreach ($bonus as $bonuse)
                            <option value="{{ $bonuse->id }}" deposit_min-attr="{{ $bonuse->minimal_deposit }}" {{
                                bonusecheck($bonuse->id, auth()->user()->id) }}>
                                {{ $bonuse->judul }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="notice-box mb-15">
                        <div class="top">
                            <div class="label"><img src="{{ asset('new_assets/images/important.png') }}" />Important
                                notes</div>
                            <div class="toggle-icon" onclick="toggleNotice(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M0,0H24V24H0Z" fill="none" />
                                    <path d="M6,15l6-6,6,6" fill="none" stroke="currentcolor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5" />
                                </svg>
                            </div>
                        </div>
                        <!-- Important Note -->
                        <div class="content">
                            <ul>
                                <li>
                                    <p>Note that all deposit transactions will have <strong>x<span
                                                id="winover-rate-3">1.5</span> WINOVER</strong> rate.</p>
                                </li>
                                <li>
                                    <p>Minimum Deposit : <strong>RM <span id="min_deposit_3">{{ general()->min_depo }}</span></strong></p>
                                </li>
                                <li>
                                    <p>Bigpay Minimum Deposit : <strong>RM <span>{{ general()->min_depo }}</span></strong></p>
                                </li>
                                <li>
                                    <p>Please note that some of the payment will have processing fee. For example if you
                                        fill in 10, you will need to pay extra 0.10 - 1.00 processing fee.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button type="submit" onclick="showLoading()"
                        class="btn btn-full mx-w mb-15">@lang('public.submit')</button>
                </div>
            </form>

            <!-- Option: Telco -->
            <form action="{{ route('transaksi.deposit.reload') }}" method="post" enctype="multipart/form-data"
                class="toggle-content" id="deposit-telco">
                @csrf
                <input type="text" name="dari_bank" id="user_wallet_id"
                    value="{{ auth()->user()->nama_bank }} / {{ auth()->user()->nomor_rekening }} / {{ auth()->user()->nama_pemilik }}"
                    hidden>
                <div class="toggle-group">
                    <div class="input-field default mx-w mb-15">
                        <div class="input-label">@lang('public.deposit_channel')</div>
                        <!-- Toggle Options -->
                        <div class="toggle-options chn-wrap">
                            <button type="button" class="btn-toggle method btn-chn" onclick="changeTelcoPayInput(3)">
                                TNGPin</button>
                            <input type="text" name="telco_pay_id" id="telco_pay_id" value="3" hidden>
                        </div>
                    </div>
                </div>
                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.telco_type'):</div>
                    <select class="default" name="bank" id="telco_type">
                        <option value="Touch N Go">Touch N Go</option>
                    </select>
                </div>

                <div class="input-field default mx-w mb-15">
                    <div class="input-label">@lang('public.reload_pin')</div>
                    <input type="text" name="pin" placeholder="e.g 756812387164812" class="default">
                </div>

                <!-- NOTICE BOX -->
                <div class="notice-box mb-15">
                    <div class="top">
                        <div class="label"><img src="{{ asset('new_assets/images/important.png') }}" />Important
                            notes</div>
                        <div class="toggle-icon" onclick="toggleNotice(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path d="M0,0H24V24H0Z" fill="none" />
                                <path d="M6,15l6-6,6,6" fill="none" stroke="currentcolor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                    <!-- Important Note -->
                    <div class="content">
                        <ul>
                            <li>
                                <p>Telco & TNG Pin ❌<strong>Bonus</strong>, Only can play <strong style="color:red">slot
                                        game</strong></p>
                            </li>
                            <li>
                                <p>Mininum withdraw RM72 x 70% = <strong>RM50</strong></p>
                            </li>
                            <li>
                                <p>Telco withdraw <strong>70%</strong> / TNGpin withdraw <strong>80%</strong></p>
                            </li>
                            <li>
                                <p>Reload RM10 or RM20 maximum withdraw <strong>RM500</strong></p>
                            </li>
                            <li>
                                <p>Reload RM30 and above maximum wash <strong>RM1000</strong></p>
                            </li>
                        </ul>
                    </div>
                </div>
                <button class="btn btn-full mx-w submit-telco" onclick="submitTelco()">@lang('public.submit')</button>
            </form>
        </div>

    </div>
</section>

@include('frontend.layouts.popup')
@endsection
@push('script')
<script>
    function submitTelco(){
            showLoading();
            var form = document.getElementById("deposit-telco");
            form.submit();
        }

        function showNotification(parent){
            try{
                parent.querySelector('.notice-box ').classList.remove('minimize');
            }
            catch(e){
                console.log('...');
            }
        }

        function changeBonusChecking(){
            var deposit_min = $("#bonus_event_id").find("option:selected").attr('deposit_min-attr');
            var deposit_max = $("#bonus_event_id").find("option:selected").attr('deposit_max-attr');
            var winover_rate = $("#bonus_event_id").find("option:selected").attr('deposit-winover-rate');
            document.getElementById('winover-rate').textContent = winover_rate;
            document.getElementById('min_deposit').textContent = deposit_min;

            var amount = document.getElementById('amount');
                amount.min = deposit_min;
            if(deposit_max>0){
                amount.max = deposit_max;
            }
            var parent = event.target.closest('form');
            showNotification(parent);
        }

        function changeBonusChecking2(){
            var deposit_min = $("#bonus_event_id_2").find("option:selected").attr('deposit_min-attr');
            var deposit_max = $("#bonus_event_id_2").find("option:selected").attr('deposit_max-attr');
            var winover_rate = $("#bonus_event_id_2").find("option:selected").attr('deposit-winover-rate');
            document.getElementById('winover-rate-2').textContent = winover_rate;
            document.getElementById('min_deposit_2').textContent = deposit_min;
            var amount = document.getElementById('amount_2');
                amount.min = deposit_min;
            if(deposit_max>0){
                amount.max = deposit_max;
            }
            var parent = event.target.closest('form');
            showNotification(parent);
        }

        function changeBonusChecking3(){
            var deposit_min = $("#bonus_event_id_3").find("option:selected").attr('deposit_min-attr');
            if(typeof deposit_min ==="undefined"){
                deposit_min = 30;
            }
            if(deposit_min <30){
                deposit_min = 30;
            }
            var deposit_max = $("#bonus_event_id_3").find("option:selected").attr('deposit_max-attr');
            var winover_rate = $("#bonus_event_id_3").find("option:selected").attr('deposit-winover-rate');
            document.getElementById('winover-rate-3').textContent = winover_rate;
            document.getElementById('min_deposit_3').textContent = deposit_min;
            var amount = document.getElementById('amount_3');
                // console.log(deposit_min);
                amount.min = deposit_min;
            if(deposit_max>0){
                amount.max = deposit_max;
            }
            var parent = event.target.closest('form');
            showNotification(parent);
        }

        function changeDepositAccount(x){
            var account_no = $("#bank_setting_id").find("option:selected").attr('account_no-attr');
            var bank_name = $("#bank_setting_id").find("option:selected").attr('bank_name-attr');
            var owner_name = $("#bank_setting_id").find("option:selected").attr('owner_name-attr');
            console.log(account_no);

            var random_no = generateString(8);
            var parent = document.getElementById('bank-account');
            parent.innerHTML = '';
            parent.innerHTML+='<div class="row"><div class="data"><div class="label">Bank</div><div class="value" id="column1">'+bank_name+'</div></div><div class="btn-copy" value="bank_name" onclick="copyToClipboard(1)">Copy</div></div><div class="row"><div class="data"><div class="label">Account</div><div class="value" id="column2">'+account_no+'</div></div><div class="btn-copy" onclick="copyToClipboard(2)">Copy</div></div><div class="row"><div class="data"><div class="label">Name</div><div class="value" id="column3">'+owner_name+'</div></div><div class="btn-copy" onclick="copyToClipboard(3)">Copy</div></div>';
            // '<div class="row"><div class="data"><div class="label">Code</div><div class="value" id="column4">'+random_no+'</div></div><div class="btn-copy" onclick="copyToClipboard(4)">Copy</div></div><p class="note">*Please leave the code on the transaction remark for further validation.</p>';
        }

        function changeQRCode(x){
            var path = $("#qrcode_id").find("option:selected").attr('path-attr');
            var name = $("#qrcode_id").find("option:selected").attr('name-attr');
            var parent = document.getElementById('qrcode-account');
            parent.innerHTML = '';
           parent.innerHTML+=`<img id="cdm-qrcode" src="https://kkbo.kkclublive.online/storage/${path}"><div class="qrcode-name">${name}</div><button type="button" id="btn-download-qrcode" onclick='saveQRcode()'>Save QR Code</button>`;
        }

        const characters ='abcdefghijklmnopqrstuvwxyz0123456789';

        function generateString(length) {
            let result = ' ';
            const charactersLength = characters.length;
            for ( let i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        function copyToClipboard(value) {
            var parent = document.getElementById('column'+value);
            console.log(parent.innerText);
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(parent.innerText).select();
            document.execCommand("copy");
            $temp.remove();
        }

        function changePaymentGatewayInput(id){
            document.getElementById('payment_gateway_id').value = id;
            if(id == 1){
                document.getElementById('surepay_dropdown').style.display = "block";
                document.getElementById('bigpay_dropdown').style.display = "none";
            }else if(id == 5){
                document.getElementById('surepay_dropdown').style.display = "none";
                document.getElementById('bigpay_dropdown').style.display = "block";
            }else{
                document.getElementById('surepay_dropdown').style.display = "none";
                document.getElementById('bigpay_dropdown').style.display = "none";
            }
        }

        function changeDuitnowInput(id){
            document.getElementById('payment_gateway_id2').value = id;
            if(id == 2){
                document.getElementById('duitnow_dropdown').style.display = "block";
            }else{
                document.getElementById('duitnow_dropdown').style.display = "none";
            }
        }

        function changeTelcoPayInput(id){
            document.getElementById('telco_pay_id').value = id;

            var telco_type = document.getElementById('telco_type');
            if(id == 3){
                telco_type.innerHTML = '';
                telco_type.innerHTML+='<option value="Touch N Go">Touch N Go</option>';
            } else{
                telco_type.innerHTML = '';
            }
        }

        function setWinover(x){
            const parent = x.closest('form');
            let winover = 1.5;
            let target = false;
            if(parent.getAttribute('id') == 'deposit-bank'){
                if(x.value < 30){
                    winover = 2;
                }
                target = 'winover-rate';
            }

            else if(parent.getAttribute('id') == 'deposit-online'){
                if(x.value < 30){
                    winover = 2;
                }
                target = 'winover-rate-2';
            }
            else{
                return;
            }
            document.getElementById(target).textContent = winover;
            showNotification(parent);
        }

        document.addEventListener("DOMContentLoaded",function(){


            try{
                if(document.getElementById('prev-page')){
                    if(urlParams.get('id')){
                        document.querySelectorAll('.notice-box').forEach((x)=>{
                            x.querySelectorAll('.default-note').forEach((y)=>{
                                y.classList.add('hide');
                            });
                        });
                    }
                    else{
                        document.querySelectorAll('.notice-box').forEach((x)=>{
                            x.querySelectorAll('.event-note').forEach((y)=>{
                                y.classList.add('hide');
                            });
                        });
                    }
                }

                document.querySelectorAll('.set-amount').forEach((x)=>{
                    x.addEventListener('click',()=>{
                        let parent = x.closest('form');
                        parent.querySelector('.deposit-amount').value = x.getAttribute('value');
                    });
                });

                if(document.getElementById('tgl-telco')){
                    document.getElementById('tgl-telco').addEventListener('click',()=>{
                        document.querySelectorAll('.chn-wrap').forEach((x)=>{
                            x.querySelector('.btn-chn').click();
                        });
                    });
                }

                if(document.getElementById('tgl-online')){
                    document.getElementById('tgl-online').addEventListener('click',()=>{
                        document.querySelectorAll('.chn-wrap').forEach((x)=>{
                            x.querySelector('.btn-chn').click();
                        });
                    });
                }

                document.querySelectorAll('.deposit-amount').forEach((x)=>{
                    x.addEventListener('change',()=>{
                        setWinover(x);
                    });
                });
            } catch(e){
                console.log(e);
                return;
            }

            document.querySelector('.deposit-methods .btn-toggle').click();
        });


        function saveQRcode(){
            // Create an anchor element
            const a = document.createElement('a');
            a.href = "{{ asset('new_assets/tngqr.png') }}";
            a.download = 'downloaded_image.jpg';

            // Append the anchor element to the document
            document.body.appendChild(a);

            // Trigger a click on the anchor element to start the download
            a.click();

            // Remove the anchor element from the document
            document.body.removeChild(a);
            setSwal(1, 'QR code saved', false);
        }
</script>
@endpush

@push('style')
<style>
    #qrcode-account {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #qrcode-account img {
        width: 60%;
        margin: 1.5rem auto;
    }

    #qrcode-account div {
        font-weight: var(--fw-medium);
        font-size: 1.8rem;
    }

    #btn-download-qrcode {
        border-radius: 0.5rem;
        background: black;
        border: none;
        color: var(--clr-solid-gold);
        font-size: 1.4rem;
        padding: 0.8rem 1.5rem;
        font-weight: var(--fw-semibold);
        margin-top: 1.5rem;
    }

    #tgl-qrcode {
        position: relative;
    }

    #tgl-qrcode span {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #232323;
        color: #979797;
        padding: 3px 5px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 500;
    }

    #tgl-qrcode.selected span {
        background-color: red;
        color: white;
    }
</style>
@endpush
