@extends('common.mypage_base')

@section('content')
	<main class="l_main" ontouchstart="">
		<section id="top">
			@include ('mypage.top.slider_top')
			<div class="content-wrap">
				@include ('mypage.top.news')
				<div class="tab">
					<div class="tab-item tab-item-active">ご契約・ご予約情報</div>
					<div class="tab-item">サポート</div>
				</div>
				@include ('mypage.top.contract')
				@include ('mypage.top.support')
			</div>
		</section>
	</main>
@endsection
