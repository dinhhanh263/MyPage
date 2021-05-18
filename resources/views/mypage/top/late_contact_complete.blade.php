@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">遅刻のご連絡</h1>
				</div>
				<h2 class="stepBar text-center pt-30">遅刻連絡の送信完了</h2>
				<div class="complete-img">
					<img src="{{ asset('img/icon-latetime.png') }}">
				</div>
				<div class="information">
					<p class="arrival-delay">
						遅刻連絡の送信が完了しました。<br>
						お気をつけてお越しくださいませ。
					</p>
				</div>
				<p class="btnArea pb-150">
					<button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button>
				</p>
			</div>
		</section>
	</main>
@endsection
