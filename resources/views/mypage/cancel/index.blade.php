@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ご予約のキャンセル</h1>
				</div>
				<h2 class="stepBar pt-30">キャンセル内容の確認</h2>
				<p class="attention-red">※まだご予約はキャンセルされていません！</p>
				<div class="mainTxtArea">
					<p>キャンセルするご予約の内容にお間違いがないかご確認ください。「予約をキャンセルする」ボタンを押すと、キャンセルが確定します。</p>
				</div>
				<div class="border-dots"></div>
				<div class="box box_confirm pt-20 pb-30">
					<h3 class="boxTtl confirm-ttl">キャンセル内容</h3>
					<div class="boxInner">
						<p class="boxInnerItem">日時&emsp;&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['target_date'] }}&nbsp;{{ $ary_disp_data['target_time'] }}〜&nbsp;</p>
						<p class="boxInnerItem">店舗名&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['target_shop'] }}</p>
					</div>
				</div>
				<div class="btnArea">
					@if ($ary_disp_data['message_flg'])
						<div class="btn-left"><button type="button" class="btn btnM js-modal-open">予約をキャンセルする</button></div>
					@else
						<div class="btn-left">
							<form method="post" action="{{ route('mypageReserveCancelProcess') }}">
								@csrf
								<button type="submit" class="btn btnM">予約をキャンセルする</button>
							</form>
						</div>
					@endif
					<div class="btn-right"><button type="button" class="btn btnM " onClick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button></div>
				</div>
			</div>
		</section>
		<section>
			<div class="modal js-modal">
				<div class="modal__bg js-modal-close"></div>
				<div class="close-btn" id="js-modal-close"></div>
				<div class="modal__content">
					<div class="close-btn js-modal-close" id="js-modal-close"><i class="fas fa-times"></i></div>
					<div>
						<p class="alertBoxTtl"><i class="fas fa-exclamation-triangle"></i>ご注意ください</p>
						@if ($ary_disp_data['course_type'] == 0)
							<p class="alertBoxInner">ご予約をキャンセルされますと、1回分消化になります。<br>ご不明点はコールセンター（{{ session()->get('target_course_type') == config('const.treatment_type_name_eng.0') ? config('env_const.kireimo_reserve_tel_disp') : config('env_const.kireimo_premium_tel_disp') }}）までご連絡下さい。</p>
						@else
							<p class="alertBoxInner">{{ $ary_disp_data['message'] }}の場合、ご予約をキャンセルされますと、1回分消化になります。<br>ご不明点はコールセンター（{{ session()->get('target_course_type') == config('const.treatment_type_name_eng.0') ? config('env_const.kireimo_reserve_tel_disp') : config('env_const.kireimo_premium_tel_disp') }}）までご連絡下さい。</p>
						@endif
					</div>
					<br>
					<p class="modal-text">キャンセルをしますか？</p>
					<div class="modal-btn-area">
						<div class="modal-btn-left"><button type="button" class="btn btnWS" onClick="history.back()" >いいえ</button></div>
						<form method="post" action="{{ route('mypageReserveCancelProcess') }}">
							@csrf
							<div class="modal-btn-right"><button type="submit" class="btn btnS">はい</button></div>
						</form>
					</div>
				</div><!--modal__inner-->
			</div><!--modal-->
		</section>
	</main>
	<script src="{{ asset('js/reserve.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/reserve.js')) }}"></script>
@endsection