@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ローンの支払い、<span class="bold">延滞料金等について</span><h1>
				</div>
				<h2 class="stepBar">ご利用のローン会社へご連絡お願い致します</h2>
				<div class="mainTxtArea">
					<p class="pb-14">毎月のお支払いや延滞金、早期完済等に関しては、お客様がご契約ローン会社に直接お問い合わせください。</p>
					<p class="attention-red">ローン会社へのお支払いについては、キレイモでは一切お取り扱いできません。</p>
				</div>
				<div class="mainTxtArea pb-150">
					<p>
						その他、ご不明点などがありましたら以下へご連絡ください。<br>
						※番号非通知、および海外からはご利用できません。<br>
					</p>
					<br>
					<p>
						【キレイモコールセンター】<br>
						お問い合わせダイヤル：{{ config('env_const.kireimo_tel_disp') }}<br>
						受付時間：11：00〜18：00（年末年始を除く）

						<br><br>

						【キレイモプレミアムコールセンター】<br>
						お問い合わせダイヤル：{{ config('env_const.kireimo_premium_tel_disp') }}<br>
						受付時間：11:00〜20:00（年末年始を除く）<br>
						※一部の回線の営業は、11：00〜18：00<br>
					</p>
				</div>
			</div>
		</section>
	</main>
@endsection