@extends('common.mypage_base')

@section('content')
	<main class="container" id="fortune_info">
		<section class="main fortune">
			<img class="fortune-img pb-20" src={{  asset("img/horoscope/$seiza_title_img") }} alt="">
			{{--    ユーザーの星座の場合表示    --}}
			@if($param_matches_flg == true)
				<h1 class="title">{{ $ary_customer['name1'] . ' ' . $ary_customer['name2'] }}さん<br>今週の運勢</h1>
			@endif
			<div class="fortune-rating-container">
				<p class="this-week">{{ $this_week }}</p>
				<div>
					<p class="fortune-title">全体運</p>
					<img class="fortune-rating" src="{{  asset("img/horoscope/all-rating-$all_score") }}" alt="全体運のスコア">
				</div>
				<div>
					<p class="fortune-title">恋愛運</p>
					<img class="fortune-rating" src="{{  asset("img/horoscope/love-rating-$love_score") }}" alt="恋愛運のスコア">
				</div>
				<div>
					<p class="fortune-title">美容運</p>
					<img class="fortune-rating" src="{{  asset("img/horoscope/beauty-rating-$beauty_score") }}" alt="美容運のスコア">
				</div>
				<p class="fortune-detail">{!! $horoscope_info[0]['fortune'] !!}</p>
			</div>
			{{--    ユーザーの星座の場合表示    --}}
			@if ($param_matches_flg == true)
				<div class="lucky-item pb-30">
					<h2 class="title">キレイモ編集部セレクト<br>今週のラッキーアイテム！</h2>
					<img src="{{ asset("img/horoscope/lucky-item/$lucky_item_img") }}" alt="今日のラッキーアイテム">
				</div>
				<div class="ad pb-30">
					<p>
						あなたの運気が<br>
						もっと上がるかも！<br>
						お得な情報をチェック！<br>
						<span>▼</span>
					</p>
					<form class="banner-4" action="https://www.c-canvas.jp/signup/kireimo/" method="post" target="_blank">
						<input type="hidden" name="id" value="{{ $ary_customer['customerNo'] }}">
						<input class="img-btn" type="image" src="{{  asset('img/banner/kireimoplus.png') }}" alt="キレイモプラス">
					</form>
				</div>
			@else
				<div class="ad pb-30">
					<div>
						<a class="banner-2" href="{{ route('mypageFriends') }}"><img class="img-btn" src="{{  asset('img/banner/friends-campaign.jpg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/banner/friends-campaign.jpg')) }}" alt="おともだち紹介割"></a>
					</div>
				</div>
			@endif
			<div class="btnArea">
				<button type="button" class="btn btnM" onClick="location.href='{{ route('mypageHoroscope') }}';">運勢一覧を見る</button>
			</div>
		</section>
	</main>
@endsection
