@extends('common.mypage_base')

@section('content')
	<main class="container main-container">
		<section class="main">
			<div class="main-content">
			<div class="page-title">
				<h1 class="title">ご予約のキャンセル</h1>
			</div>
			<div class="mainTxtArea">
				<p>下記内容のご予約をキャンセルしました。</p>
				<p>ご登録メールアドレス宛に、キャンセル確定のメールをお送りしました。</p>
			</div>
			<div class="box">
				<h2 class="stepBar">キャンセル内容</h2>
				<div class="boxInner">
					<p class="boxInnerItem">日時&emsp;&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['hope_date'] }}&nbsp;{{ $ary_disp_data['hope_time'] }}〜&nbsp;</p>
					<p class="boxInnerItem">店舗名&emsp;&nbsp;：&nbsp;{{ $ary_disp_data['target_shop'] }}</p>
				</div>
			</div>
			<div class="btnArea">
				<button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button>
			</div>
		</section>
	</main>
@endsection