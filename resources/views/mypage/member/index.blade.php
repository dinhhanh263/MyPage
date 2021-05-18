@extends('common.mypage_base')

@section('content')
	<main class="container">
		<section class="main mmb-change">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">会員情報の確認・変更</h1>
				</div>
				@if ($ary_customer['ctype'] != 3)
					<div class="mainTxtArea">
						<p>お客様の会員情報を表示しています。</p>
						<p>変更したい項目を修正し、「変更内容を確認する」ボタンを押してください。</p>
					</div>
					<h2 class="stepBar">アカウント情報</h2>
					<!-- formここから -->
					<form name="memberChgActionForm" method="post" action="{{ route('mypageMemberConfirmPost') }}">
						@csrf
						<div class="formArea-first">
							<div class="formBox">
								<p class="formTtl">お客様コード</p>
								<p class="form-text form-read">{{ $ary_customer['customerNo'] }}</p>
							</div>
							<div class="formBox">
								<p class="formTtl">パスワード</p>
								<p class="form-text form-read">●●●●●●●●●●●●●●●●</p>
								<p class="form-text text-link">
									<i class="fas fa-pen"></i>
									パスワードの変更は<a name="password_change2" href="{{ route('mypageChangePassword') }}">こちら</a>
								</p>
							</div>
							<div class="formBox">
								<p class="formTtl">お名前</p>
								<p class="form-text form-read">{{ $ary_customer['name1'] }} {{ $ary_customer['name2'] }}&nbsp;様</p>
							</div>
							<div class="formBox">
								<p class="formTtl">お名前(フリガナ)</p>
								<p class="form-text form-read">{{ $ary_customer['nameKana1'] }} {{ $ary_customer['nameKana2'] }}&nbsp;様</p>
							</div>
							<div class="formBox">
								<p class="formTtl">性別</p>
								<p class="form-text form-read">{{ $ary_customer['sex'] == 2 ? '女性' : '男性' }}</p>
							</div>
						</div>
						<div class="formArea-second">
							<h2 class="stepBar">連絡先情報</h2>
							<div class="formBox">
								<p class="formTtl">電話番号</p>
								<input type="tel" name="tel1" placeholder="000" value="{{ empty($ary_session_confirm['request']['tel1']) ? (empty($ary_customer_edit['tel1']) ? null : $ary_customer_edit['tel1']) : $ary_session_confirm['request']['tel1'] }}">
								<span>-</span>
								<input type="tel" name="tel2" placeholder="0000" value="{{ empty($ary_session_confirm['request']['tel2']) ? (empty($ary_customer_edit['tel2']) ? null : $ary_customer_edit['tel2']) : $ary_session_confirm['request']['tel2'] }}">
								<span>-</span>
								<input type="tel" name="tel3" placeholder="0000" value="{{ empty($ary_session_confirm['request']['tel3']) ? (empty($ary_customer_edit['tel3']) ? null : $ary_customer_edit['tel3']) : $ary_session_confirm['request']['tel3'] }}">
								<!-- <p class="form-comment">※ログインの｢お客様コード｣の代わりとしてもご利用いただけます。</p> -->
								@if (!empty($ary_session_confirm['validate'][0]['error']['tel']))
									<p class="formError">{{ $ary_session_confirm['validate'][0]['error']['tel'] }}</p>
								@endif
							</div>
							<div class="formBox">
								<p class="formTtl">メールアドレス</p>
								<input type="email" name ="mail" placeholder="hanako.kireimo@kireimo.jp" value="{{ empty($ary_session_confirm['request']) ? (empty($ary_customer['mail']) ? null : $ary_customer['mail']) : $ary_session_confirm['request']['mail'] }}">
								@if (!empty($ary_session_confirm['validate'][0]['error']['mail']))
									<p class="formError">{{ $ary_session_confirm['validate'][0]['error']['mail'] }}</p>
								@endif
							</div>
							<div class="formBox">
								<p class="formTtl">郵便番号</p>
								<input class="postcode" id="postcode1" name="postcode1" type="text" value="{{ empty($ary_session_confirm['request']['postcode1']) ? (empty($ary_customer_edit['postcode1']) ? null : $ary_customer_edit['postcode1']) : $ary_session_confirm['request']['postcode1'] }}" onKeyUp="AjaxZip3.zip2addr('postcode1','postcode2','address1','address2')">
								<span>-</span>
								<input class="postcode" id="postcode2" name="postcode2" type="text" value="{{ empty($ary_session_confirm['request']['postcode2']) ? (empty($ary_customer_edit['postcode2']) ? null : $ary_customer_edit['postcode2']) : $ary_session_confirm['request']['postcode2'] }}" onKeyUp="AjaxZip3.zip2addr('postcode1','postcode2','address1','address2')">
								<p class="form-comment">※郵便番号を入力すると住所が自動入力されます。</p>
								@if (!empty($ary_session_confirm['validate'][0]['error']['postcode']))
									<p class="formError">{{ $ary_session_confirm['validate'][0]['error']['postcode'] }}</p>
								@endif
							</div>
							<div class="formBox">
								<p class="formTtl">都道府県</p>
								<div class="select">
									<select id="address1" name="address1" placeholder="東京都">
										@foreach(config('const.pref_cd') as $pref_key => $pref_val)
											<option value="{{ $pref_key }}" {{ empty($ary_session_confirm['request']) ? (!empty($ary_customer['prefCd']) && $ary_customer['prefCd'] == $pref_key ? 'selected' : '') : ($ary_session_confirm['request']['address1'] == $pref_key ? 'selected' : '') }}>{{ $pref_val }}</option>
										@endforeach
									</select>
									<span class="arrow"></span>
								</div>
								@if (!empty($ary_session_confirm['validate'][0]['error']['address1']))
									<p class="formError">{{ $ary_session_confirm['validate'][0]['error']['address1'] }}</p>
								@endif
							</div>
							<div class="formBox">
								<p class="formTtl">住所</p>
								<input id="address2" type="text" name="address2" placeholder="渋谷区道玄坂１" value="{{ $ary_customer['address'] }}">
								<p class="form-comment">※建物名まで入力してください。</p>
								@if (!empty($ary_session_confirm['validate'][0]['error']['address2']))
									<p class="formError">{{ $ary_session_confirm['validate'][0]['error']['address2'] }}</p>
								@endif
							</div>
						</div>
						<div class="btnArea-2">
							<div class="btn-left"><button type="button" class="btn btnM" onClick="location.href='{{ route('mypageTop') }}'">戻る</button></div>
							<div class="btn-right"><button type="button" class="btn btnM" onclick="form.submit()">変更内容を確認する</button></div>
						</div>
					</form>
					<!-- /formここまで -->
				@else
					<div class="mainTxtArea">
						<p>お客様の会員情報を表示しています。</p>
						<p>パスワード以外の情報変更は弊社担当者までご連絡ください。</p>
					</div>
					<div class="stepBar">アカウント情報</div>
					<!-- formここから -->
					<form name="memberChgActionForm" id="" method="post" action="">
						<input type="hidden" name="mode" value="member_change">
						<div class="formArea">
							<div class="formBox">
								<p class="formTtl">お客様コード</p>
								<p class="form-text form-read">{{ $ary_customer['customerNo'] }}</p>
							</div>
							<div class="formBox">
								<p class="formTtl">パスワード</p>
								<p class="form-text form-read">●●●●●●●●●●●●●●●●</p>
								<p class="form-text text-link">
									<i class="fas fa-pen"></i>
									パスワードの変更は<a name="password_change2" href="{{ route('mypageChangePassword') }}">こちら</a>
								</p>
							</div>
							<div class="formBox">
								<p class="formTtl">お名前</p>
								<p class="form-text form-read">{{ $ary_customer['name1'] }} {{ $ary_customer['name2'] }}&nbsp;様</p>
							</div>
							<div class="formBox">
								<p class="formTtl">お名前(フリガナ)</p>
								<p class="form-text form-read">{{ $ary_customer['nameKana1'] }} {{ $ary_customer['nameKana2'] }}&nbsp;様</p>
							</div>
						</div>
					</form>
					<!-- /formここまで -->
				@endif

				@if ($ary_customer['ctype'] != 3)
					<div class="bank box" style="margin: 0 7px 20px;">
						<h2 class="stepBar">お客様返金用口座情報登録</h2>
						<div class="boxInner accordion02" >
							<form id="bankRegister" method="post" action="{{ route('mypageMemberBank') }}">
								@csrf
								<div class="formBox">
									<p class="formTtl">銀行名</p>
									<input class="w090" type="text" name="bank_name" value="{{ empty($ary_bank_info['bank_name']) ? '' : $ary_bank_info['bank_name'] }}" maxlength="64" placeholder="正式名称をご入力ください">
									@if (!empty($ary_bank_info['error_msg']['bank_name']))
										<p class="formError pt-10" style="display:block">{{ $ary_bank_info['error_msg']['bank_name'] }}</p>
									@endif
								</div>
								<div class="formBox">
									<p class="formTtl">支店名</p>
									<input class="w090" type="text" name="bank_branch" value="{{ empty($ary_bank_info['bank_branch']) ? '' : $ary_bank_info['bank_branch'] }}" maxlength="64" placeholder="例:六本木支店">
									<p class="pt-10 pb-10">※ゆうちょ銀行の場合、支店名は数字3桁でご入力ください。</p>
									@if (!empty($ary_bank_info['error_msg']['bank_branch']))
										<p class="formError pt-10" style="display:block">{{ $ary_bank_info['error_msg']['bank_branch'] }}</p>
									@endif
								</div>
								<div class="formBox">
									<p class="formTtl">口座種別</p>
									<div class="select-account display-f">
										<span>
											<input type="radio" name="bank_account_type" id="radioTypeNormal" class="bank-radio" value="1" {{ empty($ary_bank_info['bank_account_type']) || $ary_bank_info['bank_account_type'] == 1 ? 'checked' : '' }} >
											<label for="radioTypeNormal" class="bank-label">普通</label>
										</span>
										<span>
											<input type="radio" name="bank_account_type" id="radioTypeCurrent" class="bank-radio" value="2" {{ !empty($ary_bank_info['bank_account_type']) && $ary_bank_info['bank_account_type'] == 2 ? 'checked' : '' }} >
											<label for="radioTypeCurrent" class="bank-label">当座</label>
										</span>
									</div>
									@if (!empty($ary_bank_info['error_msg']['bank_account_type']))
										<p class="formError w100">{{ $ary_bank_info['error_msg']['bank_account_type'] }}</p>
									@endif
								</div>
								<div class="formBox">
									<p class="formTtl">口座番号（半角数字7桁）</p>
									<input class="w090" type="text" name="bank_account_no" value="{{ empty($ary_bank_info['bank_account_no']) ? '' : $ary_bank_info['bank_account_no'] }}" maxlength="7" placeholder="例:1234567">
									<p class="pt-10 pb-10">
										※ゆうちょ銀行の場合、口座番号8桁目の1は入力しないでください。<br />
										※口座番号が6桁以下の場合は、頭に「0」をつけてください。
									</p>
									@if (!empty($ary_bank_info['error_msg']['bank_account_type']))
										<p class="formError pt-10" style="display:block;">{{ $ary_bank_info['error_msg']['bank_account_no'] }}</p>
									@endif
								</div>
								<div class="formBox">
									<p class="formTtl">口座名義（全角カナ）</p>
									<input class="w090" type="text" name="bank_account_name" value="{{ empty($ary_bank_info['bank_account_name']) ? '' : $ary_bank_info['bank_account_name'] }}" maxlength="64" placeholder="全角カナでご入力ください">
									@if (!empty($ary_bank_info['error_msg']['bank_account_name']))
										<p class="formError pt-10" style="display:block;">{{ $ary_bank_info['error_msg']['bank_account_name'] }}</p>
									@endif
									<span class="pt-14">口座名義とご契約者様名が異なる場合、確認のためご連絡を行う場合がございます。ご了承ください。</span>
								</div>
								<div class="btnArea clearfix">
									@if (empty($ary_bank_info))
										<input type="hidden" name="insert_button" value="">
										<button type="submit" class="btn btnM bankButton">登録</button>
									@else
										<input type="hidden" name="update_button" value="">
										<button type="submit" class="btn btnM bankButton">更新</button>
									@endif
								</div>
							</form>
						</div>
					</div>
				@endif
			</div>
		</section>
	</main>
	<div id="notify" class="hide"></div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
	<script src="//www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"
		integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
	<script>
		$(function() {
			var message = "";
			@if (!empty($ary_validate_bank_result['error']))
				message = "{{ $ary_validate_bank_result['error'] }}";
			@elseif (!empty($ary_validate_bank_result['success']))
				message = "{{ $ary_validate_bank_result['success'] }}";
			@endif

			if ( message != "" ) {

				if ( message.match(/失敗/) !== null || message.match(/ありません/) ) {
					$('#notify').html(message).addClass('notify error animated fadeIn').removeClass('hide');
				} else {
					$('#notify').html(message).addClass('notify success animated fadeIn').removeClass('hide');
				}

				setTimeout(function() {
					$('#notify').removeClass('fadeIn').addClass('fadeOut');
					setTimeout(function() {
						$('#notify').addClass('hide');
					}, 800);
				} , 1800);
			}
		});
	</script>
	<script>
	// 口座情報更新
	$('.bankButton').on('click', function(e) {
		// 押下できない様にする
		$(this).prop('disabled', true);
		// submitする
		$('#bankRegister').attr('action', '/member/bank');
		$('#bankRegister').submit();

		// setTimeout(function(){
		// 	$(this).prop('disabled', false);
		// }, 1000);
	});
	</script>
@endsection