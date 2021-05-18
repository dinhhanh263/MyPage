@extends('common.reset_base')

@section('content')
	<div class="page-title">
		<h1><img src="{{  asset('img/mypage-logo.svg') }}"></h1>
	</div>
	@if (!empty($error_msg['system']))
		<p class="formError">{{ $error_msg['system'] }}</p>
	@endif
	<p class="top-text">新しいパスワードを入力してください。</p>
	<form name="loginActionForm" method="post">
		@csrf
		<div class="form-area">
			<div class="form-inner">
				<input type="hidden" name="mycode" value="{{ $mycode }}">
				<h2 class="inner-title passchg-title">新しい<span>マイページパスワード</span></h2>
				<input type="password" name="newpass" maxlength=24 placeholder="&nbsp;&nbsp;新しいパスワード" value="{{ old('newpass') }}">
				@if (!empty($error_msg['newpass']))
					<p class="formError">{{ $error_msg['newpass'] }}</p>
				@endif
				<input type="password" name="newpass_confirmation" maxlength="24" placeholder="&nbsp;&nbsp;パスワードの確認" value="{{ old('newpass_confirmation') }}">
				@if (!empty($error_msg['newpass_confirmation']))
					<p class="formError">{{ $error_msg['newpass_confirmation'] }}</p>
				@endif
				<span class="pass-term-area">
					<p class="pass-term">パスワードの条件</p>
					<p class="pass-term-img1"><img class="pass-term-img" src="{{  asset('img/icon-check.png') }}">半角英数字</p>
					<p class="pass-term-img2"><img class="pass-term-img" src="{{  asset('img/icon-check.png') }}">8文字以上・24文字以内</p>
				</span>
				<div class="btn-area">
					<input type="hidden" name="mode" value="reset_input">
					<button type="button" class="btn btnL" onclick="form.submit()">パスワードをリセット</button>
				</div>
			</div>
		</div>
	</form>
@endsection
