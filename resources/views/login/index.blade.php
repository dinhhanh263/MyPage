@extends('common.base')

@section('content')
<body>
	<main class="container">
		<section class="main">
			<div class="main-content">

				<div class="page-title">
					<h1><img src="{{ asset('img/mypage-logo.svg') }}"></h1>
				</div>
				<p class="top-text">ご契約されたお客様はこちらからログインしてください</span></p>
				<form name="loginActionForm" method="post" action="">
					@csrf
					<div class="form-area">
						<div class="form-inner">
							<h2 class="inner-title">ログイン</h2>
							<input type="text" name="mycode" maxlength=11 placeholder="お客様コード" value="{{ empty($ary_request['mycode']) ? null : $ary_request['mycode'] }}">
							@if (!empty($ary_error['mycode']))
								<p class="formError" style="display:'block';">
									{{ $ary_error['mycode'] }}
								</p>
							@endif
							<input type="password" name="password" maxlength=24 placeholder="パスワード" value="{{ empty($ary_request['password']) ? null : $ary_request['password'] }}">
							@if (!empty($ary_error['password']))
								<p class="formError" style="display:'block';">
									{{ $ary_error['password'] }}
								</p>
							@endif
							<div class="btn-area">
								<button type="submit" class="btn">ログイン</button>
							</div>
						</div>
					</div>
				</form>
				<p class="reset-text">▼ログインできない場合</p>
				<p class="pb-10">お客様情報からマイページアカウントを復旧できます。</p>
				<div class="txtLinkR"><a href="{{ route('resetMailTop') }}" ontouchstart=""><span>お客様コードを確認/パスワード再設定</span></a></div>
				<div class="login-notice">
					<details>
						<summary>ご契約が終了したお客様へ</summary>
						<p>キレイモでご契約いただき誠にありがとうございました。<br>キレイモのマイページはご解約後、ご契約満了後もログインが可能です。引き続きコンテンツをご利用ください。</p>
					</details>
				</div>
			</div>
		</section>
	</main>
@endsection
