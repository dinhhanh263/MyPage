@extends('common.mypage_base')

@section('content')
	<main class="container" id="change_rsv">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ご予約の変更</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-chg-step3.png') }}" alt="変更内容確認"></div>
				<h2 class="stepBar pt-30">変更内容の確認</h2>
				<div class="mainTxtArea">
					<p class="attention-red">※まだ変更は確定していません！</p>
					<p>変更内容にお間違いがないかご確認ください。「変更を確定する」ボタンを押すと、ご予約が確定します。</p>
				</div>
				<div class="border-dots"></div>
				<div class="box box-confirm pt-20 pb-30 chgArrow">
					<h3 class="boxTtl confirm-ttl">変更前のご予約内容</h3>
					<div class="boxInner">
						<p class="boxInnerItem">日時&emsp;&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['before_data']['target_date'] }}&nbsp;{{ $ary_disp_data['before_data']['target_time'] }}〜&nbsp;</p>
						<p class="boxInnerItem">店舗名&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['before_data']['target_shop'] }}</p>
					</div>
				</div>
				<div class="box box_confirm pb-30">
					<h3 class="boxTtl confirm-ttl bold">変更後のご予約内容</h3>
					<div class="boxInner">
						<p class="boxInnerItem bold">日時&emsp;&emsp;：{{ $ary_disp_data['target_date'] }}&nbsp;{{ $ary_disp_data['target_time'] }}～</p>
						<p class="boxInnerItem bold">店舗名&emsp;：{{ $ary_disp_data['target_shop'] }}</p>
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
						<form name="reservationActionForm1" id="" method="post" action="{{ route('mypageReserveChangeSearch') }}">
							@csrf
							<button type="button" class="btn btnW" onClick="submit();">日付選択に戻る</button>
						</form>
					</div>

					<div class="btn-right">
						@if ($ary_disp_data['cancel_flg'] == 1 || $ary_disp_data['change_flg'] == 1)
							<button type="submit" class="btn btnM js-modal-open">変更を確定する</button>
						@else
							<form method="post" action="{{ route('mypageReserveChangeProcess') }}">
								@csrf
								<button type="submit" class="btn btnM">変更を確定する</button>
							</form>
						@endif
					</div>
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
				<p class="modal-text">ご予約の変更を確定しますか？</p>
				<div class="modal-btn-area">
					<div class="modal-btn-left">
						<form method="post" action="{{ route('mypageReserveChangeSearch') }}">
							@csrf
							<button type="submit" class="btn btnWS">いいえ</button>
						</form>
					</div>
					<div class="modal-btn-right">
						<form method="post" action="{{ route('mypageReserveChangeProcess') }}">
							@csrf
							<button type="submit" class="btn btnS">はい</button>
						</form>
					</div>
				</div>
			</div><!--modal__inner-->
		</div><!--modal-->

		<script src="{{ asset('js/reserve.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/reserve.js')) }}"></script>
		<script src="{{ asset('js/moment.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/moment.js')) }}"></script>
@endsection