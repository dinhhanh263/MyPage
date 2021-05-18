<?php

return [
	//ページタイトル
	'change_pass_title'	=> 'パスワードの変更',

	//パスワード変更失敗画面
	'change_pass_error_title'	=> 'パスワード変更失敗',
	'change_pass_error_body'	=> 'パスワードの変更に失敗しました。
									お手数ですが、しばらく時間をおいてから再度変更をお願いいたします。',

	// 新規予約
	'new_reserve_api_error'	=> '新規ご予約失敗',

	// 予約変更
	'change_reserve_api_error'	=> 'ご予約変更失敗',

	// キャンセル
	'cancel_reserve_api_error'	=> 'ご予約キャンセル失敗',

	// スタッフメール送信エラー
	'contact_error'	=> 'お問い合わせ失敗',

	// 遅刻連絡エラー
	'delay'	=>
		[
			'title'				=> 'KIREIMO 遅刻連絡',
			'page_title'		=> '遅刻連絡失敗',
			'error_category'	=> '遅刻連絡失敗',
			'body'				=> '遅刻連絡に失敗しました。恐れ入りますが、KIREIMOカスタマーセンター(0120-444-680)までお電話をお願いいたします。'
		],

	// アンケートエラー
	'question'	=>
		[
			'title'				=> 'KIREIMO 診断結果',
			'page_title'		=> 'アンケート回答失敗',
			'error_category'	=> 'アンケート回答失敗',
			'body'				=> 'アンケート回答に失敗しました。送信ボタンを連続して押下した場合、すでに回答済みである場合があります。',
		],

	// セッションエラー
	'session'	=>
	[
		'error_category'	=> 'セッションエラー',
		'body'				=> '不正な操作が行われました。恐れ入りますが、最初から予約をしなおしてください。',
	],
];
