@extends('common.mypage_base')

@section('content')
	<main class="container main-container" id="new_rsv">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">新規ご予約</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-step4.png') }}" alt="予約確定"></div>
				<h2 class="stepBar pt-30">予約内容確定</h2>
				<div class="mainTxtArea pb-20">
					<p>下記内容でご予約を確定しました。</p>
					<p>ご登録メールアドレス宛に、ご予約確定のメールをお送りしました。</p>
				</div>
				<div class="box">
					<h3 class="pt-10">ご予約内容</h2>
					<div class="boxInner pb-14">
						<p class="boxInnerItem fs-18"><span class="bold">日時</span>&emsp;&emsp;：{{ $ary_disp_data['hope_date'] }}&nbsp;{{ $ary_disp_data['hope_time'] }}～</p>
						<p class="boxInnerItem fs-18"><span class="bold">店舗名</span>&emsp;：{{ $ary_disp_data['target_shop'] }}</p>
					</div>
				</div>
				<div class="mainTxtArea">
					<p>ご予約ありがとうございます。</p>
					<p>お客様のご来店をお待ちしております。</p>
				</div>
				<div class="btnArea">
					<button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button>
				</div>
			</div>
		</section>
	</main>
@endsection