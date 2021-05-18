@extends('common.line_base')

@section('content')
<body>
	<main class="container main-container">
		<div class="page-title">
			<h1 class="title">LINE ID連携<h1>
		</div>
		<section class="main">
			<div class="line-container">
				<img src="{{ asset('img/icon-line-complete.png') }}" alt="完了" >
				<div class="mainTxtArea">
					<p class="line-text fc-lp">キレイモとLINEの<br>ID連携が完了しました。</p>
					<p class="line-text fc-lp">ブラウザを閉じて終了して下さい。<br>※ブラウザの「戻る」ボタンは使用しないでください。</p>
				</div>
			</div>
		</section>
	</main>
@endsection
