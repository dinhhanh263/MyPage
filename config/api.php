<?php

//**********************************************************************
// api関連
//**********************************************************************

return [
	// apiのurl
	'api_url' => env('API_URL'),

	// apiのtoken
	'api_token'	=> env('API_TOKEN'),

	// google map api token
	'google_map_token'	=> 'AIzaSyBVSeERIZJaK_S9Td47E_AqZV14EZlhEIA',

	// api返却ステータスコード
	'api_status_code' =>
	[
		'SUCCESS'				=> 200,	// 成功
		'NO_CONTENT'			=> 204, // 成功したが、何も返すものがない
		'BAD_REQUEST'			=> 400,	// 失敗
		'UNAUTHRIZED'			=> 401, // 認証が必要
		'FORBIDDEN'				=> 403,	// アクセス権がない
		'NOT_FOUND'				=> 404,	// リソースが存在しない。URL不正
		'INTERNAL_SERVER_ERROR'	=> 500,	// サーバーエラー
	],

	'MSG_INTERNAL_SERVER_ERROR'	=> 'システムエラーが発生しました。情報システム部へ連絡してください。',
	'MSG_FORMAT_ERROR'			=> 'フォーマットチェックができませんでした。時間を置いて再度お試しください。',

	// apiエラーコード
	'api_error_code' =>
	[
		'E5000' => '認証エラー',
		'E5001' => 'システムエラー',
		'E5002' => '対象データが存在しません',
		'E5003' => 'DBデータ重複',
		'E9000' => '型が不正です',
		'E9001' => '必須項目が存在しません',
		'E9002' => 'フォーマットが不正です',
		'E9003' => '有効な値ではありません',
		'E9004' => '桁数が不正です',
		'E9005' => 'データが重複しています',
		'E9006' => '送信対象が0件です',
		'E9007' => 'メールステータスが不正です',
		'E9008' => '件数不正',
		'E9009' => '差し込み文字不整合',
		'E9010'	=> '同一人物チェックエラー',
		'E9011'	=> '予約不可日時エラー',
		'E9012'	=> '予約空きなし',
	],

	// 画面表示用メッセージ
	'ERROR_MSG_E9011_OR_E9012'		=> 'すでに予約が入っている または 予約が不可な日時となります。恐れ入りますが、予約する場所・日時の変更をお願いいたします。',
	'ERROR_MSG_OTHER'				=> 'システムエラーが発生しました。恐れ入りますが時間を置いてから再度予約を入れてください。',

	// api uri
	'api_sub' =>
	[
		'shops'					=> 'kireimo/shops',									// ショップリスト取得
		'rsrv_check'			=> 'kireimo/shop/treatmentReservableCheck',			// トリートメント予約空き状況確認
		'treatment_reserve'		=> 'kireimo/treatmentReservation',					// トリートメント予約登録
		'reservation_data'		=> 'kireimo/reservation',							// 予約情報取得
		'single_mail'			=> 'action/smtp/send',								// 1通メール配信
		'customer'				=> 'kireimo/customer',								// お客様情報取得
		'mypage_auth'			=> 'kireimo/auth/mypageAuth',						// マイページログイン認証
		'treatment_reserve_new'	=> 'kireimo/reservation',							// トリートメント新規予約
		'news'					=> 'kireimo/news',									// ニュース取得
		'nutrition_questionnaires'		=> 'kireimo/nutritionQuestion',				// 栄養アンケート取得
		'nutrition_questionnaires_add'	=> 'kireimo/nutritionQuestion/%d/answers',	// 栄養アンケート回答
	],
];