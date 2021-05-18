@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">パスワードの変更</h1>
				</div>
				<div class="stepBar">パスワード再設定</div>
				<div class="mainTxtArea">
					<p>現在のパスワード(変更を確認するため、現在使用中のパスワードが必要です)と新しいパスワードを入力してください。「変更する」ボタンを押すと、パスワードが変更されます。</p>
				</div>

				<!-- formここから -->
				<form name="passChgActionForm" method="post" action="">
					@csrf
					<div class="formArea">
						<div class="formBox">
							<p class="formTtl">現在のパスワード</p>
							<input type="password" name="mypass" maxlength=24 placeholder="&nbsp;&nbsp;現在のパスワード" value="">
							@if (!empty($ary_error_msg['mypass']))
								<p class="formError">{{ $ary_error_msg['mypass'] }}</p>
							@endif
						</div>
						<div class="formBox">
							<p class="formTtl">新しいパスワード</p>
							<input type="password" name = "newpass" maxlength=24 value="">
							<p class="attention">※半角英数字・8文字以上24文字以下</p>
							@if (!empty($ary_error_msg['newpass']))
								<p class="formError">{{ $ary_error_msg['newpass'] }}</p>
							@endif
						</div>
						<div class="formBox">
							<p class="formTtl">新しいパスワード(確認)</p>
							<input type="password" name = "newpass_confirmation" maxlength=24 value="">
							<p class="attention">確認のためもう一度入力してください。</p>
							@if (!empty($ary_error_msg['newpass_confirmation']))
								<p class="formError">{{ $ary_error_msg['newpass_confirmation'] }}</p>
							@endif
						</div>
					</div>
					<div class="btnArea">
						<div class="btn-left"><button type="button" class="btn btnW" onClick="location.href='{{ route('mypageMember') }}'">キャンセル</button></div>
						<div class="btn-right">
							<input type="hidden" name="mode" value="password_change">
							<button type="button" class="btn btnM" onclick="form.submit()">変更する</button>
						</div>
					</div>
				</form>
				<!-- /formここまで -->
			</div>
		</section>
	</main>
@endsection