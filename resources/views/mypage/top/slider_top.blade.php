<div class="slider-wrap">
	<div class="slider-area">
		<div class="slider">
			<div><!-- 脱毛器シェーバー -->
				<a href="{{ route('mypageEpilator') }}" target="_blank"><img src="{{ asset('img/banner/210126_epilator.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/210126_epilator.png')) }}" alt="キレイモ オリジナル脱毛器&シェーバー"></a>
			</div>
			<div><!-- おともだち紹介割 -->
				<a href="{{ route('mypageFriends') }}"><img src="{{ asset('img/banner/friends-campaign.jpg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/friends-campaign.jpg')) }}" alt="おともだち紹介割"></a>
			</div>
			<div><!-- オンラインショップ -->
				<a href="//store.kireimo.jp" target="_blank"><img src="{{ asset('img/banner/onlinestore.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/onlinestore.png')) }}" alt="キレイモ公式 ONLINE STORE"></a>
			</div>
			<div><!-- premium -->
				<a class="banner-1" href="{{ config('env_const.kireimo_premium_top') }}" target="_blank"><img src="{{ asset('img/banner/mypage-premium.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/mypage-premium.png')) }}" alt="premium"></a>
			</div>
			<div><!-- キレイモ+新規登録 -->
				<form action="https://www.c-canvas.jp/signup/kireimo/" method="post" target="_blank">
					<input type="hidden" name="id" value="{{ $ary_customer['customerNo'] }}">
					<input class="imgButton" type="image" src="{{ asset('img/banner/202004welcome.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/202004welcome.png')) }}" alt="送信する">
				</form>
			</div>
			<div><!-- キレイモプラス -->
				<form action="https://www.c-canvas.jp/signup/kireimo/" method="post" target="_blank">
					<input type="hidden" name="id" value="{{ $ary_customer['customerNo'] }}">
					<input class="imgButton" type="image" src="{{ asset('img/banner/202105petitgift.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/202104petitgift.png')) }}" alt="送信する">
				</form>
			</div>
		</div>
	</div>
</div>
