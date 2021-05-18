@extends('common.reset_base')

@section('content')
	<div class="page-title">
		<h1><img src="{{  asset('img/mypage-logo.svg') }}"></h1>
	</div>
	<div class="form-area-comp mt-18">
		<div class="form-inner">
			<input type="hidden" name="mode" value="login">
			<h2 class="inner-title reset-title">メール送信完了</h2>
			<div class="mail-comp-img"><img src="{{  asset('img/icon-send.png') }}"></div>
			<span class="mail-comp-text">
				<p>ご登録のメールアドレス宛に、お客様コードおよびパスワード再設定用URLを記載したメールをお送りしました。<br>
					パスワードを変更する際は、メールに記載のURLから再設定してください。<br>
					<br>
					メールが届かないお客様は、以下をご確認ください。<br>
					・メールアドレスが無効になっていませんか？<br>
					・ドメイン「@kireimo.jp」からのメールを受信しない設定になっていませんか？<br>
					・URL付きのメールを受信拒否にしていませんか？<br>
					・迷惑メールとして振り分けられていませんか？<br>
					<br>
					それでもメールが届かない場合は、入力いただいたアドレスと生年月日に誤りがないか、ご確認ください。</p>
			</span>
			<div class="btn-area">
				<a href="{{ route('loginTop') }}" class="btn btnL">マイページログインへ</a>
			</div>
		</div>
	</div>
@endsection
