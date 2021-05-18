<div class="modal">
	<div class="overlay js-modal--close"></div>
	<div class="modal__inner">
		<h2>ご来店の周期について</h2>
		<p class="fs-16 pb-10">お客様の来店周期をご確認の上、<span>ご予約をお願い致します。</span></p>
		<p class="close modal-close js-modal--close">×</p>
		<p class="fs-16">{{ $ary_customer['name1'] }}　{{ $ary_customer['name2'] }}さんの次回施術は</p>
		<p class="current-times">
			<span class="next_visit_times" style="color: {{ $ary_period_data['color'] }}">{{ $ary_period_data['next_visit_times'] }}</span>/{{ $ary_period_data['times'] == 0 ? '∞' : $ary_period_data['times'] }}回目
		</p>
		<img src="{{ asset('img/visit-cycle/' . $ary_period_data['img']) }}" alt="来店周期の画像">
		<p><span class="bold fs-18">{{ $reserve_conditions['start']['year'] }}/{{ $reserve_conditions['start']['month'] }}/{{ $reserve_conditions['start']['day'] }}〜{{ $reserve_conditions['end']['year'] }}/{{ $reserve_conditions['end']['month'] }}/{{ $reserve_conditions['end']['day'] }}</span><span class="bold fs-16">のご予約が可能です。</span></p>
		@if (empty($ary_contract['courseIntervalDate']) && $ary_contract['contractDate'] >= '2017-01-25')
			<p>※回数はプランにより異なります。</p>
		@endif
		<p class="close__btn fs-16"><a href="" class="js-modal--close">ウィンドウを閉じる</a></p>
	</div>
</div>
<style>
	.modal {
		position: fixed; /*　スクロールしても見えるよう固定表示　*/
		top: 0;
		width: 100%;
		height: 100vh; /*　ブラウザの高さと合わせる　*/
		z-index: 10;
		display: none; /*　初期状態　*/
	}
	.overlay {
		background: rgba(0,0,0,0.6);
		position: absolute;
		width: 100%;
		height: 100vh;
	}
	.modal .modal__inner {
		background-color: #ffffff;
		position: absolute;
		top: calc(50% + 40px);
		left: calc(100vw - 50%);
		transform: translate(-50%,-50%);
		width: 90%;
		padding: 40px 10px 10px;
		box-sizing: border-box;
		text-align:center;
	}
	.visit-cycle {
		width: 30%;
		height: 30%;
		margin: 10px 0;
	}
	.close {
		position:absolute;
		top:0%;
		right:5%;
		cursor: pointer;
	}
	.modal__inner img {
		width: 30%;
		height: 30%;
		margin: 10px 0;
	}
	.modal .modal__inner h2 { margin-bottom: 1em; }
	.modal .modal__inner h2 {
		display: inline-block;
		font-size: 20px;
		font-weight: bold;
		border-bottom: 1px solid #e62e8b;
		color: #e62e8b;
		padding: 0 0.5em 0.2em;
	}
	.modal .modal__inner p { line-height: 1.5;font-weight: bold; }
	.modal .modal__inner .close__btn {
		margin-top:2.5em;
		padding: 0.5em 0;
	}
	.modal .modal__inner .close__btn a {
		font-weight: bold;
		color: #e62e8b;
	}
	.next_visit_times {
		font-weight: bold;
		font-size: 30px;
	}
	.current-times {
		font-weight: bold;
		font-size: 18px;
	}
	@media screen and (max-width:767px) {
		.modal .modal__inner h2 {
			margin-bottom: 0em;
		}
		.modal .modal__inner {
			padding: 20px 10px 10px;
		}
		.modal__inner img {
			width: 80%;
			height: 100%;
		}
		.visit-cycle {
			width: 85%;
			height: 100%;
			margin: 0;
		}
		.next_visit_times {
			font-size: 25px;
		}
		.highlight {
			font-size: 16px;
		}

		/*　横向き　*/
		@media (orientation: landscape) {
			.modal {
				position: absolute;
				top: 0;
			}
			.overlay {
				height: 500vh;
			}
			.modal .modal__inner {
				top: calc(50% + 130px);
				font-size: 0.5em;
			}
		}
	}
</style>
@if (!strpos($user_agent, 'iPad'))
	<style>
		/*　縦向き　*/
		@media (orientation: portrait) {
			.modal .modal__inner {
				font-size: 0.5em;
				width: 90vw;
			}
			.overlay {
				height: 150vh;
			}
		}
	</style>
@endif
<script>
	$(function(){
		$(document).ready(function(){
			$('.modal').fadeIn();
			return false;
		});
		$('.js-modal--close').on('click',function(){
			$('.modal').fadeOut();
			return false;
		});
		function clickAction() {
			$('.modal').on('click',function(){
				$('.modal').fadeOut();
				return false;
			});
		}
		//3秒後にどこ押しても消える
		setTimeout(clickAction, 3000);
	});
</script>