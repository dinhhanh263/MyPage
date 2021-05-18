@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<div class="page-title">
			<h1 class="title">お支払い方法について<h1>
		</div>
		<section class="main">
			<h2 class="stepBar">残金・月額等のお支払いが必要なお客様へ</h2>
			<div class="mainTxtArea">
				<p>ご契約プランの残金のお支払いや、月額プランのお支払いのお振込み口座やお支払い方法をご案内します。
					 お客様が該当するお支払いについてのボタンをクリックしてご確認ください。
				 </p>
			</div>
			<div class="pay-select-area pb-150">
				<div class="btn-contract"><a href="{{ route('mypagePaymentBank') }}"><img class="payment-btn contract" src="{{ asset('img/btn-contract.png') }}" alt="ご契約に関するお支払いはこちら"></a></div>
				<div class="btn-monthly"><a href="{{ route('mypagePaymentMonthly') }}"><img class="payment-btn monthly" src="{{ asset('img/btn-monthly.png') }}" alt="月額プランに関するお支払いはこちら"></a></div>
				<div class="btn-loan"><a href="{{ route('mypagePaymentLoan') }}"><img class="payment-btn loan" src="{{ asset('img/btn-loan.png') }}" alt="ローンのお支払い・延滞金等はこちら"></a></div>
				<div class="btn-cancel"><a href="{{ route('mypagePaymentBank') }}"><img class="payment-btn cancel" src="{{ asset('img/btn-cancel.png') }}" alt="ご解約に関するお支払いはこちら"></a></div>
			</div>
		</section>
	</main>
@endsection