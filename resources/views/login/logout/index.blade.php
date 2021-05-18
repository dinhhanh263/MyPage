@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ログアウト</h1>
				</div>
				<div class="stepBar">ログアウトの確認</div>
				<div class="mainTxtArea pb-50">
					<p>ログアウトしてもよろしいですか？</p>
				</div>
				<hr>
				<div class="btnArea">
					<div class="btn-left"><button type="button" class="btn btnW" onClick="location.href='{{ route('mypageTop') }}'">キャンセル</button></div>
					<div class="btn-right">
						<form name="logoutActionForm" method="post" action="">
							@csrf
							<button type="submit" class="btn btnM">ログアウト</button>
						</form>
					</div>
				</div>
			</div>
		</section>
	</main>
@endsection
