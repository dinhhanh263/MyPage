@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">月額プランの<span class="bold">お支払いに関して</span></h1>
				</div>
				<h2 class="stepBar">ご希望のお支払い方法を選択</h2>
				<div class="mainTxtArea">
					<p class="pb-14">各種手数料はお客様のご負担となります。ご了承ください。</p>
					<p class="attention-red">ローンのお支払いにはご利用になれません。ご注意ください。</p>
				</div>
				<div class="pay-select-area pb-30">
					<div class="btn-bank"><a href="{{ route('mypagePaymentBank') }}"><img class="payment-btn bank" src="{{ asset('img/btn-bank.png') }}" alt="銀行口座へのお振込み"></a></div>
					<div class="btn-conveni"><a href="{{ route('mypagePaymentConvenience') }}"><img class="payment-btn conveni" src="{{ asset('img/btn-conveni.png') }}" alt="コンビニ支払い(スマートピット)"></a></div>
				</div>
				<div class="pb-150">
					<div class="alertBox box_attention">
						<p class="alertBoxTtl">◆ご注意とお願い</p>
						<p>【コンビニでのお支払い（スマートピット）】は<br>
							<span class="fc-red">月額コースの契約者のお客様</span>で、<span class="red">毎月のお支払いで未払がある方</span>のみ使用が可能となります。<br>
							また、上記のお客様は別途ご登録のアドレスにメールでのお知らせがございます。</p>
						<p style="margin-bottom: 20px;">※予めキレイモからのメールを受け取れるよう受信設定、メールアドレスのご確認をお願いいたします。<br>
							<span class="fc-red">お知らせを確認以降、ご利用が可能</span>となります。<br>
							（事前のお知らせのない方は【コンビニでのお支払い】のご利用はいただけませんので、あらかじめご了承ください）</p>
						<br>
						<ul>
							<li><span class="fc-red">・ローン会社への毎月のお支払い、ローン延滞金</span>に関してはこちらでご案内のお支払い方法ではございません。<br>
							ご利用のローン会社に準じたお支払い方法にてお支払いただきますよう、お願いします。</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</main>
@endsection