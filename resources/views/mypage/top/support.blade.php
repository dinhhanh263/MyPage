<div class="main-slider main-slider-2">
	<div class="swiper-container tab-contents">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<ul class="support">
					<li><a href="{{ route('mypageHoroscope') }}" class="horoscope-link"><img src="{{ asset('img/icon-horoscope.png') }}"><p>キレイモ占い</p></a></li>
					@if (!empty($ary_customer['geneInfo']))
						<li><a href="{{ route('mypageGene') }}" class="horoscope-link"><img src="{{ asset('img/icon-gene.png') }}"><p>遺伝子検査結果</p></a></li>
					@endif
					<li><form class="kireimo-plus-link" action="https://www.c-canvas.jp/signup/kireimo/" method="post" target="_blank">
							<label style="cursor: pointer;">
								<input type="hidden" name="id" value="{{ $ary_customer['customerNo'] }}">
								<input type="image" src="{{ asset('img/icon-kireimoplus.png') }}">
								<p>会員限定<span class="sp">優待サービス</span></p>
							</label>
						</form>
					</li>
					<li><a href="{{ route('mypageFriends') }}"><img src="{{ asset('img/icon-friends.png') }}"><p>おともだち<span>紹介</span></p></a></li>
					<li><a href="{{ route('mypagePayment') }}" class="payment-link"><img src="{{ asset('img/icon-fee.png') }}"><p>お支払いに<span>ついて</span></p></a></li>
					<li><a href="{{ route('mypageGuide') }}"><img src="{{ asset('img/icon-caution.png') }}"><p>施術に関する<span>注意事項</span></p></a></li>
					<li><a href="{{ config('env_const.kireimo_top') }}qa/members/" target="_blank"><img src="{{ asset('img/icon-qa.png') }}"><p>FAQ<span>(脱毛)</span></a></p></li>
					<li><a href="{{ config('env_const.kireimo_premium_top') }}faq" target="_blank"><img src="{{ asset('img/icon-premium-qa.png') }}"><p>FAQ<span>(エステ・整体)</span></a></p></li>
					<li><a href="{{ config('env_const.kireimo_top') }}help" target="_blank"><img src="{{ asset('img/icon-help.png') }}"><p>マイページの<span>使い方</span></p></a></li>
					<li><a href="{{ route('mypageContact') }}" class="contact-link"><img src="{{ asset('img/icon-contact.png') }}"><p>お問い合わせ</p></a></li>
					<li>
						<div class="popup" id="js-popup">
							<div class="popup-inner">
								<div class="close-btn" id="js-close-btn"><i class="fas fa-times"></i></div>
								<p  class="pop-title">お困りですか？</p>
								<p class="pop-text">お電話でお問い合わせの前に｢よくあるご質問｣をご確認ください。</p>
								<p class="pop-text">
									それでも解決しない場合にはこちらへご連絡ください。<br>
									※番号非通知、および海外からはご利用できません。<br>
								</p>
								<span class="pt-14">
									<p>【キレイモコールセンター】</p>
									<p class="fc-333">予約直通ダイヤル（施術専用）：<br /><a href="tel:{{ config('env_const.kireimo_reserve_tel') }}"><img class="tel-icon" src="{{ asset('img/icon-tel.png') }}" style="width:20px;height:20px;">{{ config('env_const.kireimo_reserve_tel_disp') }}</a>　受付時間：11:00〜20:00（年末年始を除く）</p>
									<p class="fc-333">お問い合わせダイヤル：<br /><a href="tel:{{ config('env_const.kireimo_tel') }}"><img class="tel-icon" src="{{ asset('img/icon-tel.png') }}" style="width:20px;height:20px;">{{ config('env_const.kireimo_tel_disp') }}</a>　受付時間：11:00〜18:00（年末年始を除く）</p>
								</span>

								<span class="pt-14">
									<p>【キレイモプレミアムコールセンター】</p>
									<p class="fc-333">お問い合わせダイヤル：<br /><a href="tel:{{ config('env_const.kireimo_premium_tel') }}"><img class="tel-icon" src="{{ asset('img/icon-tel.png') }}" style="width:20px;height:20px;">{{ config('env_const.kireimo_premium_tel_disp') }}</a>　受付時間：11:00〜20:00（年末年始を除く）</p>
									<p>※一部の回線の営業は、11：00〜18：00</p>
								</span>
							</div>
							<div class="black-background" id="js-black-bg"></div>
						</div>
						<div id="js-show-popup" style="cursor: pointer;"><img src="{{ asset('img/icon-call.png') }}">
						<p>お電話での<br>お問い合わせ</p></div>
					</li>

				</ul>
			</div>
		</div>
	</div>
</div>