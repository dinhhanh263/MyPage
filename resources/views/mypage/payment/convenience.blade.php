@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">コンビニでのお支払い<span class="bold">(スマートピット)</span></h1>
				</div>
				<h2 class="stepBar">スマートピット番号のご案内</h2>
				<div class="mainTxtArea">
					<p class="pb-14">{{ $ary_customer['customerNo'] }}　{{ $ary_customer['nameKana1'] }}　{{ $ary_customer['nameKana2'] }}様のコンビニでの支払いの際に必要なスマートピット番号は以下でございます。</p>
					<p class="pb-14">※こちらは{{ $ary_customer['nameKana1'] }}　{{ $ary_customer['nameKana2'] }}様専用のスマートピット番号です。<br />ご入力の際には、お間違いのない様ご注意ください。</p>
					<p class="attention-red">ローンのお支払いにはご利用になれません。ご注意ください。</p>
				</div>
				<div class="pb-30">
					<div class="pay-smartpit">
						<img src="{{ asset('img/pay-smartpit.png') }}" alt="コンビニ支払い(スマートピット)">
						<p class="pb-20">スマートピット番号:{{ empty($ary_customer['smartpitNo']) ? '' : $ary_customer['smartpitNo'] }}</p>
						<p class="attention-s-red">※お支払には、コンビニ支払い手数料として200円（税別）をご負担いただきますのでご了承ください。</p>
					</div>
				</div>

				<h2 class="stepBar">コンビニでのお支払い方法の詳細</h2>
				<p class="pb-20">下記URLをご確認ください。</p>
				<div class="mainTxtArea conveni-url-area">
					<div class="loson pb-20">
						<p>ローソン、ミニストップ「Loppi」</p>
						<a class="conveni-url" href="http://www.smartpit.jp/consumer/payment/loppi.html" target="_blank">http://www.smartpit.jp/consumer/payment/loppi.html</a>
					</div>
					<div class="famima pb-30">
						<p>ファミリーマート「Famiポート」</p>
						<a class="conveni-url" href="http://www.smartpit.jp/consumer/payment/familymart.html" target="_blank">http://www.smartpit.jp/consumer/payment/familymart.html</a>
					</div>
				</div>
				<h2 class="stepBar">お支払に関する注意事項</h2>
				<div class="mainTxtArea pb-150">
					<p>お支払後の料金はコンビニでは返金いたしません。</p>
					<p>スマートピットを利用した取扱い等に関しては、当社までお問い合わせください。</p>
				</div>
			</div>
		</section>
	</main>
@endsection