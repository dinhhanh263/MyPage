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
		<link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}?date={{ date('YmdHis', filemtime(public_path() . '/css/login.css')) }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/reservation.css') }}">
		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<link href="//use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="{{ asset('css/news-attention.css') }}">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="{{ asset('js/datepicker01.js') }}"></script>
		<script src="{{ asset('js/datepicker-ja.js') }}"></script>
		<script src="{{ asset('js/secretaddress.js') }}"></script>
		<script src="{{ asset('js/navigation.js') }}"></script>
		<!--郵便番号から住所の自動入力-->
		<script type="text/javascript" src="//ajaxzip3.github.io/ajaxzip3.js"></script>
		<!-- slick  -->
		<link rel="stylesheet" type="text/css" href="{{ asset('css/slick-theme.css') }}">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/slick.css') }}">
		<script type="text/javascript" src="{{ asset('js/slick.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('js/slide.js') }}"></script>
	</head>

	@yield('content')
	<!-- フッターを呼び出し -->
	<footer>
		<p class="copyRight">© KIREIMO</p>
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

	</body>
</html>
