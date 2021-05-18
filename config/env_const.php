<?php

//**********************************************************************
// 環境別定数
//**********************************************************************

switch ($_SERVER['SERVER_NAME'] ?? '') {
	// product
	case 'mypage.kireimo.jp':
		return [
			// 送信元アドレス
			'from_address'	=> 'noreply@kireimo.jp',
			// 送信元アドレス
			'from_address_premium'	=> 'noreply@kireimo-premium.jp',
			// infoメールアドレス
			'info_address'	=> 'info@kireimo.jp',
			// infoメールアドレス（プレミアム）
			'info_address_premium'	=> 'info@kireimo-premium.jp',
			// ベースURL
			'base_url'	=> 'https://mypage.kireimo.jp/',
			// kireimoトップ
			'kireimo_top'	=> 'https://kireimo.jp/',
			// kireimo premiumトップ
			'kireimo_premium_top'	=> 'https://kireimo-premium.jp/',

			// kireimo電話
			'kireimo_tel_disp'			=> '0120-444-680',
			'kireimo_tel'				=> '0120444680',
			'kireimo_reserve_tel_disp'	=> '0120-489-375',
			'kireimo_reserve_tel'		=> '0120489375',

			// kireimo premium電話
			'kireimo_premium_tel_disp'	=> '0120-888-264',
			'kireimo_premium_tel'		=> '0120888264',

			// samesite設定
			'same_site_secure'		=>	true,	// secure
			'same_site_http_only'	=>	true,	// http_only
			'same_site_cookies'		=>	'none',	// cookies
		];
		break;
	// stg
	case 'mypage.kireimo-stage.jp':
		return [
			// 送信元アドレス
			'from_address'	=> 'noreply@kireimo-stage.jp',
			// 送信元アドレス
			'from_address_premium'	=> 'noreply@kireimo-premium-stage.jp',
			// infoメールアドレス
			'info_address'	=> 'info@kireimo-stage.jp',
			// infoメールアドレス（プレミアム）
			'info_address_premium'	=> 'info@kireimo-premium-stage.jp',
			// ベースURL
			'base_url'	=> 'https://mypage.kireimo-stage.jp/',
			// kireimoトップ
			'kireimo_top'	=> 'https://kireimo.jp/',
			// kireimo premiumトップ
			'kireimo_premium_top'	=> 'https://kireimo-premium.jp/',

			// kireimo電話
			'kireimo_tel_disp'			=> '0120-444-680',
			'kireimo_tel'				=> '0120444680',
			'kireimo_reserve_tel_disp'	=> '0120-489-375',
			'kireimo_reserve_tel'		=> '0120489375',

			// kireimo premium電話
			'kireimo_premium_tel_disp'	=> '0120-888-264',
			'kireimo_premium_tel'		=> '0120888264',

			// samesite設定
			'same_site_secure'		=>	true,	// secure
			'same_site_http_only'	=>	true,	// http_only
			'same_site_cookies'		=>	'none',	// cookies
		];
		break;
	// dev
	case 'mypage.kireimo-dev.jp':
		return [
			// 送信元アドレス
			'from_address'		=> 'noreply@kireimo-dev.jp',
			// 送信元アドレス
			'from_address_premium'	=> 'noreply@kireimo-premium-dev.jp',
			// infoメールアドレス
			'info_address'	=> 'info@kireimo-dev.jp',
			// infoメールアドレス（プレミアム）
			'info_address_premium'	=> 'info@kireimo-premium-dev.jp',
			// ベースURL
			'base_url'	=> 'https://mypage.kireimo-dev.jp/',
			// kireimoトップ
			'kireimo_top'	=> 'https://kireimo.jp/',
			// kireimo premiumトップ
			'kireimo_premium_top'	=> 'https://kireimo-premium.jp/',

			// kireimo電話
			'kireimo_tel_disp'			=> '0120-444-680',
			'kireimo_tel'				=> '0120444680',
			'kireimo_reserve_tel_disp'	=> '0120-489-375',
			'kireimo_reserve_tel'		=> '0120489375',

			// kireimo premium電話
			'kireimo_premium_tel_disp'	=> '0120-888-264',
			'kireimo_premium_tel'		=> '0120888264',

			// samesite設定
			'same_site_secure'		=>	true,	// secure
			'same_site_http_only'	=>	true,	// http_only
			'same_site_cookies'		=>	'none',	// cookies
		];
		break;
	default:
		return [
			// 送信元アドレス
			'from_address'		=> 'noreply@kireimo-local.jp',
			// 送信元アドレス
			'from_address_premium'	=> 'noreply@kireimo-premium-local.jp',
			// infoメールアドレス
			'info_address'	=> 'info@kireimo-local.jp',
			// infoメールアドレス（プレミアム）
			'info_address_premium'	=> 'info@kireimo-premium-local.jp',
			// ベースURL
			'base_url'	=> 'http://127.0.0.1:8001/',
			// kireimoトップ
			'kireimo_top'	=> 'https://kireimo.jp/',
			// kireimo premiumトップ
			'kireimo_premium_top'	=> 'https://kireimo-premium.jp/',

			// kireimo電話
			'kireimo_tel_disp'			=> '0120-444-680',
			'kireimo_tel'				=> '0120444680',
			'kireimo_reserve_tel_disp'	=> '0120-489-375',
			'kireimo_reserve_tel'		=> '0120489375',

			// kireimo premium電話
			'kireimo_premium_tel_disp'	=> '0120-888-264',
			'kireimo_premium_tel'		=> '0120888264',

			// samesite設定
			'same_site_secure'		=>	false,	// secure
			'same_site_http_only'	=>	false,	// http_only
			'same_site_cookies'		=>	'lax',	// cookies

		];
		break;
}
