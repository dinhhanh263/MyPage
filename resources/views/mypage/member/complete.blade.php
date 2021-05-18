@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">会員情報の確認・変更</h1>
				</div>
				<div class="stepBar stepBarEnd">
					会員情報の変更完了
				</div>
				<div class="complete-img">
					<img src="{{ asset('img/icon-send.png') }}">
				</div>
				<div class="mainTxtArea">
					<p>お客様の会員情報は変更されました。</p>
					<p>ご登録メールアドレス宛に、会員情報変更のメールをお送りしました。</p>
				</div>
				<hr>
				<div class="btnArea">
					<div class="btn-left"><button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button></div>
					<div class="btn-right"><button type="button" class="btn btnM" onclick="location.href='{{ route('mypageMember') }}'">会員情報の確認・変更へ</button></div>
				</div>
			</div>
		</section>
	</main>
@endsection