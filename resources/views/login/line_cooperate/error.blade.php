@extends('common.line_base')

@section('content')
<body>
	<main class="container main-container">
		<div class="page-title">
			<h1 class="title">LINE ID連携<h1>
		</div>
		<section class="main">
			<div class="line-container">
				<img src="{{ asset('img/error.png') }}" alt="エラー" >
				<div class="mainTxtArea">
					<p class="line-text fc-lp">エラー</p>
					<p>ID連携が失敗しました。</p>
					<p>一度ブラウザを閉じ、少し時間を置いて再度お試し下さい。</p>
					<p>(※ブラウザの「戻る」ボタンは使用しないでください。)</p>
					<br>
					<p>再度試してもエラーになる場合は、お手数ですが下記にご連絡下さい。</p>
				</div>
			</div>
			<div class="customer-center">
				<a href="tel:{{ config('env_const.kireimo_tel') }}"><img src="{{ asset('img/customer-center.png') }}" alt=""></a>
			</div>
		</section>
	</main>
@endsection
