@extends('common.reset_base')

@section('content')
	<div class="page-title">
		<h1><img src="{{  asset('img/mypage-logo.svg') }}"></h1>
	</div>
	<div class="form-area mt-18">
		<div class="form-inner">
			<input type="hidden" name="mode" value="login">
			<h2 class="inner-title reset-title">パスワードの再設定完了</h2>
			<div class="reset-comp-img"><img src="{{  asset('img/icon-complete.png') }}"></div>
			<span class="reset-comp-text">
				<p>パスワードを再設定しました。</p>
				<p>新しいパスワードでマイページにログインできます。</p>
			</span>
			<div class="btn-area">
				<a href="{{ route('loginTop') }}" class="btn btnL">マイページログインへ</a>
			</div>
		</div>
	</div>
@endsection
