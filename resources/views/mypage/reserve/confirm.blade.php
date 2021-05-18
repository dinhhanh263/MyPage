@extends('common.mypage_base')

@section('content')
	<main class="container" id="new_rsv">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">新規ご予約</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-step3.png') }}" alt="予約内容確認"></div>
				<h2 class="stepBar pt-30">予約内容の確認</h2>
				<div class="mainTxtArea">
					<p class="attention-red">※まだご予約は確定していません！</p>
					<p>ご予約内容にお間違いがないかご確認ください。「予約を確定する」ボタンを押すと、ご予約が確定します。</p>
				</div>
				<div class="border-dots"></div>
				<div class="box box-confirm pt-20 pb-30">
					<h3 class="boxTtl confirm-ttl">ご予約内容の確認</h3>
					<div class="boxInner">
						<p class="boxInnerItem fs-14">日時&emsp;&emsp;：{{ $ary_disp_data['target_date'] }}&nbsp;{{ $ary_disp_data['target_time'] }}～</p>
						<p class="boxInnerItem fs-14">店舗名&emsp;：{{ $ary_disp_data['target_shop'] }}</p>
					</div>
				</div>
			</div>

			<!-- パックコースの注意事項 -->
			<div class="alertBox" style="display:'block'">
				<p class="alertBoxTtl"><i class="fas fa-exclamation-triangle"></i>ご予約に関する注意事項</p>
				<p class="alertBoxAttention">※必ずご確認の上、ご予約ください。</p>
				<p class="alertBoxInner">
					<ul class="alertBoxList">
						<li>
							以下のお客様の施術はお断りさせていただいております。
							<ul class="innerUl">
								<li>ご妊娠中、もしくはご妊娠されている可能性のある方</li>
								<li>授乳中の方</li>
								<li>体内に異物が入っている方</li>
								<li>日焼けをされた方</li>
								<li>挙式を2週間以内に控えている方</li>
							</ul>
						</li>
						<li>生理中はVIO・ヒップを除いた箇所の施術が可能です。</li>
						@if ($ary_contract['courseType'] == config('const.course_type.pack'))
							<li>ご予約の当日のキャンセルは、当該1回分の施術を行ったものとさせていただきます。</li>
						@else
							<li>ご予約の2日前までのキャンセルは、当該1回分の施術を行ったものとさせていただきます。</li>
						@endif
						<li>ご予約の時間に遅刻をされた場合、時間内でできる限りの施術をさせていただきます。</li>
					</ul>
				</p>
			</div>

			<div class="btnArea">
				<div class="btn-left">
					<form name="reservationActionForm1" id="" method="post" action="{{ route('mypageNewReserveSearch') }}">
						@csrf
						<button type="button" class="btn btnW" onClick="submit();">日付選択に戻る</button>
					</form>
				</div>

				<div class="btn-right">
					@if ($ary_disp_data['cancel_flg'] == 1 || $ary_disp_data['change_flg'] == 1)
						<button type="submit" class="btn btnM js-modal-open">予約を確定する</button>
					@else
						<form method="post" id="reserveForm">
							@csrf
							<button type="submit" class="btn btnM reserveButton">予約を確定する</button>
						</form>
					@endif
				</div>
			</div>
		</section>
	</main>
	<div class="modal js-modal">
		<div class="modal__bg js-modal-close"></div>
		<div class="close-btn" id="js-modal-close"></div>
		<div class="modal__content">
			<div class="close-btn js-modal-close" id="js-modal-close"><i class="fas fa-times"></i></div>
				<p class="modal-text">
					@if (!empty($ary_disp_data['confirm_msg']))
						{{ $ary_disp_data['confirm_msg'] }}のご予約は変更{{ $ary_disp_data['cancel_flg'] == 1 ? '・キャンセル' : '' }}ができません。
					@else
						当日のご予約は変更{{ $ary_disp_data['cancel_flg'] == 1 ? '・キャンセル' : '' }}ができません。
					@endif
				</p>
				<br>
				<p class="modal-text">ご予約を確定しますか？</p>
				<div class="modal-btn-area">
					<div class="modal-btn-left">
						<form method="post" action="{{ route('mypageNewReserveSearch') }}">
							@csrf
							<button type="submit" class="btn btnWS">いいえ</button>
						</form>
					</div>
					<div class="modal-btn-right">
						<form method="post" id="reserveForm">
							@csrf
							<button type="submit" class="btn btnS reserveButton">はい</button>
						</form>
					</div>
				</div>
			</div><!--modal__inner-->
		</div><!--modal-->
		<script src="{{ asset('js/reserve.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/reserve.js')) }}"></script>
		<script src="{{ asset('js/moment.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/moment.js')) }}"></script>
@endsection