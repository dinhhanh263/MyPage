@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">銀行口座へのお振込み</h1>
				</div>
				<h2 class="stepBar">お振込み銀行口座のご案内</h2>
				<div class="mainTxtArea">
					<p class="pb-14">{{ $ary_customer['customerNo'] }}　{{ $ary_customer['nameKana1'] }}　{{ $ary_customer['nameKana2'] }}様のお振込み口座は以下でございます。</p>
					<p class="pb-14">※こちらは{{ $ary_customer['nameKana1'] }}　{{ $ary_customer['nameKana2'] }}様専用のお振込み用口座です。お振込みの際には、お間違いのない様ご注意ください。</p>
					<p class="attention-red">※ローン延滞によるご入金はご契約頂いている信販（ローン）会社のお支払方法に準じますので、各信販会社へお問い合わせください。</p>
				</div>
				<div class="pb-30">
					<div class="pay-smartpit">
						<img src="{{ asset('img/pay-bank.png') }}" alt="お振込み銀行口座">
						<ul class="transfer-account pb-20">
							<li>銀行名&emsp; : 三井住友銀行(009)</li>
							<li>支店名&emsp; : {{ empty($ary_customer['virtualBank']['branchName']) ? '' : $ary_customer['virtualBank']['branchName'] }}（{{ empty($ary_customer['virtualBank']['branchNo']) ? '' : $ary_customer['virtualBank']['branchNo'] }}）</li>
							<li>預金種別 : 普通預金</li>
							<li>口座番号 : {{ empty($ary_customer['virtualBank']['virtualNo']) ? '' : $ary_customer['virtualBank']['virtualNo'] }}</li>
							<li>口座名義 : 株式会社ヴィエリス</li>
						</ul>
						<p class="attention-s-red">※お振込の際の振込依頼人欄にはお客様の会員番号、お名前(カタカナ)をご入力ください。【例：SJ0123456 ｷﾚｲﾓﾊﾅｺ】</p>
					</div>
				</div>

				<h2 class="stepBar">上記に口座が表示されていない場合</h2>
				<div class="mainTxtArea pb-150">
					<p class="pb-30">下記の銀行口座へお振込みください。また、お振込の際の振込み依頼人欄にはお客様の会員番号、お名前(カタカナ)をご入力ください。【例：SJ0123456 ｷﾚｲﾓﾊﾅｺ】</p>
					<ul class="transfer-account-2">
						<li>銀行名&emsp; : 三井住友銀行(009)</li>
						<li>支店名&emsp; : 六本木支店(619)</li>
						<li>預金種別 : 普通預金</li>
						<li>口座番号 : 7581244</li>
						<li>口座名義 : 株式会社ヴィエリス</li>
					</ul>
				</div>
			</div>
		</section>
	</main>
@endsection