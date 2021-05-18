<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta http-equiv="expires" content="0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-store">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="format-detection" content="telephone=no" />
		<meta http-equiv="cleartype" content="on">
		<meta name="robots" content="noindex,nofollow">

		<!-- フルスクリーン,ホーム画面アイコン -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="default">
		<meta name="apple-mobile-web-app-title" content="マイページ">
		<link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon-precomposed.png') }}">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

		<title>{{ $title }}</title>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/reset.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/font.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/notify.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}?date={{ date('YmdHis', filemtime(public_path() . '/css/common.css')) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}?date={{ date('YmdHis', filemtime(public_path() . '/css/style.css')) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/reservation.css') }}">
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link href="//use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="{{ asset('css/news-attention.css') }}">
		@if(Request::is('epilator'))
		<link rel="stylesheet" href="{{ asset('css/epilator.css') }}">
		@endif
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="{{ asset('js/datepicker01.js') }}"></script>
		<script src="{{ asset('js/datepicker-ja.js') }}"></script>
		<script src="{{ asset('js/secretaddress.js') }}"></script>
		<script src="{{ asset('js/navigation.js') }}"></script>
		<script src="{{ asset('js/main.js') }}"></script>
		@if(Request::is('epilator'))
		<script src="{{ asset('js/smooth-scroll.polyfills.min.js') }}"></script>
		<script src="{{ asset('js/smooth-scroll.min.js') }}"></script>
		@endif
		<!--郵便番号から住所の自動入力-->
		<script type="text/javascript" src="//ajaxzip3.github.io/ajaxzip3.js"></script>
		<!-- slick  -->
		<link rel="stylesheet" type="text/css" href="{{ asset('css/slick-theme.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/slick.css') }}">
		<script type="text/javascript" src="{{ asset('js/slick.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/slide.js') }}"></script>
		<!-- swiper -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css">
		<script src="//cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js"></script>

	</head>
	<body>
		<header>
			<div class="header-top">
				<div class="header_inner">
					<div class="header-left">
						<div class="customer-icon">
							<a href="{{ route('mypageMember') }}">
								@if (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.normal'))
									<!-- 通常 -->
									<img src="{{ asset('img/icon-setting.png') }}" alt="customer icon">
								@elseif (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.abnormal') || session()->get('contract_abnormal_flg') == config('const.contract_abnormal.payment_error') || session()->get('contract_abnormal_flg') == config('const.contract_abnormal.confirm'))
									<!-- 契約異常/支払異常/確認事項あり -->
									<img src="{{ asset('img/icon-setting-lg.png') }}" alt="customer icon">
								@elseif (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.preparation'))
									<!-- 準備中 -->
									<img src="{{ asset('img/icon-setting-lb.png') }}" alt="customer icon">
								@else
									<!-- 契約なし -->
									<img src="{{ asset('img/icon-setting-g.png') }}" alt="customer icon">
								@endif
							</a>
						</div>
						<div class="customer-cd">
							<p>お客様番号</p>
							<p>{{ $ary_customer['customerNo'] }}</p>
						</div>
					</div>
					<div class="header-center headerTtl">
						<h1><a href="{{ route('mypageTop') }}"><img src="{{ asset('img/mypage-logo.svg') }}" alt="KIREIMO"></a></h1>
					</div>
				</div>
				<div class="nav-content">
					<div id="menubtn">
						<a><div class="menu-btn menu-trigger">
							<div id="open" class="hamburger bright">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div></a>
					</div>
					<nav class="nav" id="navi">
						<ul class="nav-list">
							<li><a href="{{ route('mypageTop') }}" class="nav-list-inner">マイページトップ</a></li>
							<li><a href="{{ route('mypageMember') }}" class="nav-list-inner">会員情報</a></li>
							<li><a href="{{ route('mypageHoroscope') }}" class="nav-list-inner">キレイモ占い</a></li>
							@if (!empty($ary_customer['geneInfo']))
								<li><a href="{{ route('mypageGene') }}" class="nav-list-inner">遺伝子検査結果</a></li>
							@endif
							<li><a href="{{ route('mypagePayment') }}" class="nav-list-inner">お支払い方法について</a></li>
							<li><a href="{{ route('mypageContact') }}" class="nav-list-inner">お問い合わせ</a></li>
							<li><a href="{{ route('logoutTop') }}" class="nav-list-inner">ログアウト</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		@yield('content')

		<footer>
			<p class="copyRight">© KIREIMO</p>
			<div id="page-top"><a href="#"></a></div>
		</footer>

		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KMCLW7"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
		new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
		"//www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,"script","dataLayer","GTM-KMCLW7");</script>
		<!-- End Google Tag Manager -->

		<script src="{{ asset('js/popup.js') }}"></script>

		<!-- /フッター -->
	</body>
</html>
