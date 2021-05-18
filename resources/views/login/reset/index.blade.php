@extends('common.reset_base')

@section('content')
	<div class="page-title">
		<h1><img src="{{  asset('img/mypage-logo.svg') }}"></h1>
	</div>
	<p class="top-text">こちらでは紛失したパスワードのリセット、お客様コードの確認が行えます。</p>
	<form name="loginActionForm" id="" method="post" action="" class="reset-mail">
		@csrf
		<div class="form-area">
			<div class="form-inner">
				<input type="hidden" name="mode" value="login">
				<h2 class="inner-title reset-title">ログインできませんか？</h2>
				<p class="inner-text">ご登録済みのメールアドレスと生年月日からアカウントがあなたご自身のものであることを確認し、パスワード再設定用URLとお客様コードをメールでお知らせいたします。<br>
					ご登録済みのメールアドレスと生年月日を入力し、送信ボタンをクリックしてください。<br>
					<br>
					メールが届かないお客様は、以下をご確認ください。<br>
					・メールアドレスが無効になっていませんか？<br>
					・ドメイン「@kireimo.jp」からのメールを受信しない設定になっていませんか？<br>
					・URL付きのメールを受信拒否をしていませんか？<br>
					・迷惑メールとして振り分けられていませんか？<br>
					<br>
					それでもメールが届かない場合は、ご入力いただいたアドレスと生年月日に誤りがないか、ご確認ください。</p>
				<p class="pb-10 bold">登録済みのメールアドレス</p>
				<input type="email" name="email" placeholder="例) hanako.kireimo@kireimo.jp" value="{{ old('email') }}">
				@if (!empty($ary_error_message['email']))
					<p class="formError">{{ $ary_error_message['email'] }}</p>
				@endif
				<p class="pb-10 bold">生年月日</p>
				<div class="select-date">
					<select name="birthday_y" class='year' required>
						@for ($i = $birth_year_end; $i >= $birth_year_start; $i--)
							<option value="{{ $i }}" @if(old('birthday_y')==$i) selected  @endif >{{ $i }}</option>
						@endfor
					</select>　年
					<select name="birthday_m" class='month' required>
						@for ($i = 1; $i <= 12; $i++)
							<option value="{{ $i }}" @if(old('birthday_m')==$i) selected  @endif >{{ $i }}</option>
						@endfor
					</select>　月
					<select name="birthday_d" class='day' required>
						@for ($i = 1; $i <= $lastday; $i++)
							<option value="{{ $i }}" @if(old('birthday_d')==$i) selected  @endif >{{ $i }}</option>
						@endfor
					</select>　日
				</div>
				<div class="btn-area">
					<input type="hidden" name="mode" value="reset_mail">
					<button type="button" class="btn btnL" onclick="form.submit()">送信する</button>
				</div>
			</div>
		</div>
	</form>
	<br />
	<br />
	<script src="{{ asset('js/reset-pass.js') }}"></script>
@endsection
