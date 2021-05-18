@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ご予約の変更</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-chg-full.png') }}" alt="満席"></div>
				<div class="status">
					<img src="{{ asset('img/icon-full.png') }}" alt="満席です">
				</div>
				<div class="mainTxtArea">
					<p>ご希望の日時、店舗の予約枠が埋まったため、ご予約を確定できませんでした。</p>
					<p>恐れ入りますが、別の日時、店舗でのご予約をお願いいたします。</p>
				</div>
				<div class="btnArea">
					<div class="btn-left">
						<form name="reservationActionForm1" method="post" action="{{ route('mypageReserveChangeSearch') }}">
							@csrf
							<button type="button" class="btn btnW" onClick="submit();">空き状況検索に戻る</button>
						</form>
					</div>
					<div class="btn-right"><button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップに戻る</button></div>
				</div>
			</div>
		</section>
	</main>
@endsection