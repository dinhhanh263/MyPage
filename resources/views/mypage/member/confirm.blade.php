@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">会員情報の確認・変更</h1>
				</div>
				<div class="stepBar">
					変更内容の確認
				</div>
				@if (!empty($ary_request['error_msg']))
					<div>
						<font color="red">{{ $ary_request['error_msg'] }}</font>
					</div>
				@endif
				<div class="mainTxtArea">
					<p class="attention">※まだ変更は確定していません！</p>
					<p>変更内容にお間違えがないかご確認ください。「変更を確定する」ボタンを押すと、会員情報が変更されます。</p>
				</div>
				<div class="formArea">
					<div class="formBox">
						<p class="formTtl">電話番号</p>
						<p class="form-text form-read">{{ $ary_request['tel1'] }}-{{ $ary_request['tel2'] }}-{{ $ary_request['tel3'] }}</p>
					</div>
					<div class="formBox">
						<p class="formTtl">メールアドレス</p>
						<p class="form-text form-read">{{ $ary_request['mail'] }}</p>
					</div>
					<div class="formBox">
						<p class="formTtl">郵便番号</p>
						<p class="form-text form-read">{{ $ary_request['postcode1'] }}-{{ $ary_request['postcode2'] }}</p>
					</div>
					<div class="formBox">
						<p class="formTtl">都道府県</p>
						<p class="form-text form-read">{{ config('const.pref_cd.' . $ary_request['address1']) }}</p>
					</div>
					<div class="formBox">
						<p class="formTtl">住所</p>
						<p class="form-text form-read">{{ $ary_request['address2'] }}</p>
					</div>
				</div>
				<div class="btnArea">
					<div class="btn-left">
							<button type="submit" class="btn btnM" onclick="location.href='{{ route('mypageMember') }}'">キャンセル</button>
					</div>
					<div class="btn-right">
						<button type="submit" class="btn btnM" onclick="location.href='{{ route('mypageMemberProcess') }}'">変更を確定する</button>
					</div>
				</div>
			</div>
		</section>
	</main>
@endsection