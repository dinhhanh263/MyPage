<?php

//**********************************************************************
// LINE用
//**********************************************************************

return [
	// LINE api base url
	'base_url'	=> 'https://access.line.me/oauth2/v2.1/authorize',
	// LINEアクセストークン取得用url
	'access_token_url'	=> 'https://api.line.me/oauth2/v2.1/token',
	// LINEプロフィール取得用url
	'profile_url'	=> 'https://api.line.me/v2/profile',

	// LINEuri
	'uri'	=> '?response_type=%s&client_id=%s&redirect_uri=%s&state=%s&scope=%s&bot_prompt=%s',

	// チャンネルID
	'channel_id'	=> env('LINE_CHANNEL_ID'),

	// シークレットキー
	'secret_key'	=> env('LINE_SECRET_KEY'),

	// リダイレクト先
	'redirect_url' => env('LINE_REDIRECT_URL'),


	// LINE用getパラメータ
	'respons_type_code'	=> 'code',	// レスポンスタイプ（code）

	'scope_profile'		=> 'profile',	// スコープ(profile)

	'bot_prompt_normal'		=> 'normal',		// ボットタイプ(normal)
	'bot_prompt_aggressive'	=> 'aggressive',	// ボットタイプ(aggressive)
];