@extends('common.mypage_base')

@section('content')
    <main class="container">
        <div class="popup" id="contact-popup"><!-- ポップアップ -->
            <div class="popup-inner">
                <div class="close-btn" id="contact-close-btn"><i class="fas fa-times"></i></div>
                <p class="pop-title fc-sp"><i class="fas fa-exclamation-triangle"></i>注意事項</p>
                <p class="pop-text">下記の内容のお問い合わせは、お問い合わせフォームでは承れません。予めご了承ください。</p>
                <ul class="pop-list">
                    <li>ご解約・クーリングオフの受付</li>
                    <li>ご返金・キャッシュバック対応の確認</li>
                    <li>ご予約の取得・変更・キャンセル・遅刻</li>
                    <li>ローン残高の照会</li>
                </ul>
            </div>
            <div class="black-background" id="contact-black-bg"></div>
        </div>

        <section class="main">
            <div class="main-content">
                <div class="page-title">
                    <h1 class="title">お問い合わせ</h1>
                </div>
                <div class="information">
                    <form name="contact_form" method="post" action="{{ route('mypageContactProcess') }}" id="contact_form" >
                        @csrf
                        <input type="hidden" name="contactFlg" value="true">
                        <div class="">
                            <h2 class="stepBar">お問い合わせ内容入力</h2>
                            <div class="mainTxtArea">
                                <p>施術の予約取得・変更・キャンセル・確認はこちらでは承れません。恐れ入りますがマイページトップ画面またはお電話にてお願い致します。また、施術の遅刻ご連絡はコールセンターまでお電話くださいませ。</p>
                            </div>
                            <div>
                                <p class="formTtl">該当するお問い合わせカテゴリーのボタンを押してください</p>
                                <div class="toggle-buttons contract-select-area pb-20">
                                  <label>
                                    <input type="radio" name="contract_type" class="contract-btn contract-btn-01 no-select" value="1" checked disabled>
                                    <span class="button right">脱毛</span>
                                  </label>
                                  <label>
                                    <input type="radio" name="contract_type" class="contract-btn contract-btn-02 no-select" value="2">
                                    <span class="button left">エステ・整体</span>
                                  </label>
                                  <input type="hidden" id="contract_type" name="contract_type" value="1">
                                </div>
                                <!-- <div class="contract-select-area pb-20">
                                    <button type="button" class="contract-btn contract-btn-01 no-select" value="脱毛について"><div style="visibility: hidden;" class="contract-select-btn">脱毛</div></button>
                                    <button type="button" class="contract-btn contract-btn-02 no-select" value="エステ・整体について"><div style="visibility: hidden;" class="contract-select-btn">エステ・整体</div></button>
                                    <input type="hidden" id="contract_type" name="contract_type" value="">
                                </div> -->
                            </div>
                            <div>
                                <p class="formTtl">該当するお問い合わせ内容のボタンを押してください</p>
                                <div class="contact-select-area pb-20">
                                    <button type="button" class="contact-btn contact-btn-01 no-select" value="施術について"><div style="visibility: hidden;" class="contact-select-btn">施術について</div></button>
                                    <button type="button" class="contact-btn contact-btn-02 no-select" value="お支払いについて"><div style="visibility: hidden;" class="contact-select-btn">お支払いについて</div></button>
                                    <button type="button" class="contact-btn contact-btn-03 no-select" value="プランについて"><div style="visibility: hidden;" class="contact-select-btn">プランについて</div></button>
                                    <button type="button" class="contact-btn contact-btn-04 no-select" value="お友達紹介について"><div style="visibility: hidden;" class="contact-select-btn">お友達紹介について</div></button>
                                    <input type="hidden" name="contact_type" id="contact_type" value="">
                                </div>
                            </div>
                            <div>
                                <p class="formTtl contact-textarea">お問い合わせ内容の詳細をご入力ください</p>
                                <textarea id="contact_input" name="contact_input" placeholder="こちらにお問い合わせ内容の詳細をご入力ください"></textarea>
                                <p class="attention-s-red">※お急ぎの方はお電話にてお問い合わせください。</p>
                            </div>
                            <div class="formail">
                                <div class="trigger">画像を送信される方はこちら</div>
                                <div class="accordion">
                                    こちらからメールをお送りください。
                                    <p>
                                      <a class="epilation" href="mailto:info＠kireimo.jp">{{ config('env_const.info_address') }}</a>
                                      <a class="beauty hide" href="mailto:info＠kireimo.jp">{{ config('env_const.info_address_premium') }}</a>
                                    </p>
                                    ※本文に会員番号・お名前をご記載ください。
                                </div>
                            </div>
                        </div>
                        <div class="contact-info">
                            <h2 class="stepBar">お客様情報</h2>
                            <div class="formArea">
                                <div class="formBox">
                                    <p class="formTtl">お客様コード</p>
                                    <p class="form-text form-read">{{ $ary_customer['customerNo'] }}</p>
                                </div>
                                <div class="formBox">
                                    <p class="formTtl">お名前</p>
                                    <p class="form-text form-read">{{ $ary_customer['nameKana1'] }} {{ $ary_customer['nameKana2'] }} 様</p>
                                </div>
                            </div>
                        </div>
                        <div class="btnArea">
                            <div class="btn-left"><button type="button" class="btn btnW" onclick="history.back()">キャンセル</button></div>
                            <div class="btn-right"><button type="button" id="confirm_btn" class="btn btnM">内容確認</button></div>
                        </div>

                        <div id="confirm_modal">
                            <div id="modal_box">
                                <div id="modal_ttl">お問合せ内容</div>
                                <div class="modal-contents">
                                    <div id="category_reflection"></div>
                                    <div id="type_reflection"></div>
                                    <div id="input_reflection"></div>
                                    <div class="contacta-btnArea">
                                        <div class="btn-left"><button type="button" id="close_button" class="btn btnW">戻る</button></div>
                                        <div class="btn-right"><button type="submit" name="submit" value="send" class="btn btnM" id="submit">送信する</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="{{ asset('js/contact.js') }}"></script>
    <script src="{{ asset('js/popup.js') }}"></script>
    <script src="{{ asset('js/accordion.js') }}"></script>
@endsection
