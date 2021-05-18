@extends('common.line_base')

@section('content')
<body>
	<main class="container" style="min-height: calc(100vh - 40px);">
		<section class="main">
			<div class="main-content" style="width: 95%;">
				<div class="page-title" style="padding-bottom: 30px;">
					<h1><img src="{{ asset('img/mypage-logo.svg') }}"></h1>
				</div>
				<form name="loginActionForm" id="" method="post" action="{{ route('cooperateLineProcess') }}">
					@csrf
					<div class="form-area">
						<div class="form-inner">
							<input type="hidden" name="mode" value="line_login">
							<h2 class="inner-title">KIREIMO認証</h2>
							<input type="text" name="mycode" maxlength=11 placeholder="お客様コード" value="{{ empty($ary_session['customerNo']) ? '' : $ary_session['customerNo'] }}">
							@if (!empty($ary_error['mycode']))
								<p class="formError" style="display:block;">
									{{ $ary_error['mycode'] }}
								</p>
							@endif
							<input type="password" name="password" maxlength=24 placeholder="パスワード" value="{{ empty($ary_session['password']) ? '' : $ary_session['password'] }}">
							@if (!empty($ary_error['password']))
								<p class="formError" style="display:block;">
									{{ $ary_error['password'] }}
								</p>
							@endif
							@if (!empty($ary_session['error']))
								<p class="formError" style="display:block;">
									{{ $ary_session['error'] }}
								</p>
							@endif
							<div class="btn-area">
								<button type="submit" class="btn">ログイン</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</section>
	</main>

@endsection
