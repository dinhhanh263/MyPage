<?php

//**********************************************************************
// 定数
//**********************************************************************

return [

	// 都道府県コード
	'pref_cd' => [
		1	=> '北海道',
		2	=> '青森県',
		3	=> '岩手県',
		4	=> '宮城県',
		5	=> '秋田県',
		6	=> '山形県',
		7	=> '福島県',
		8	=> '茨城県',
		9	=> '栃木県',
		10	=> '群馬県',
		11	=> '埼玉県',
		12	=> '千葉県',
		13	=> '東京都',
		14	=> '神奈川県',
		15	=> '新潟県',
		16	=> '富山県',
		17	=> '石川県',
		18	=> '福井県',
		19	=> '山梨県',
		20	=> '長野県',
		21	=> '岐阜県',
		22	=> '静岡県',
		23	=> '愛知県',
		24	=> '三重県',
		25	=> '滋賀県',
		26	=> '京都府',
		27	=> '大阪府',
		28	=> '兵庫県',
		29	=> '奈良県',
		30	=> '和歌山県',
		31	=> '鳥取県',
		32	=> '島根県',
		33	=> '岡山県',
		34	=> '広島県',
		35	=> '山口県',
		36	=> '徳島県',
		37	=> '香川県',
		38	=> '愛媛県',
		39	=> '高知県',
		40	=> '福岡県',
		41	=> '佐賀県',
		42	=> '長崎県',
		43	=> '熊本県',
		44	=> '大分県',
		45	=> '宮崎県',
		46	=> '鹿児島県',
		47	=> '沖縄県',
	],

	// タイムコード
	'hope_time'	=> [
		1	=> '11:00',
		2	=> '11:30',
		3	=> '12:00',
		4	=> '12:30',
		5	=> '13:00',
		6	=> '13:30',
		7	=> '14:00',
		8	=> '14:30',
		9	=> '15:00',
		10	=> '15:30',
		11	=> '16:00',
		12	=> '16:30',
		13	=> '17:00',
		14	=> '17:30',
		15	=> '18:00',
		16	=> '18:30',
		17	=> '19:00',
		18	=> '19:30',
		19	=> '20:00',
		20	=> '20:30',
	],

	// 施術時間
	'length'	=> [
		1	=> '30分',
		2	=> '60分',
		3	=> '90分',
		4	=> '120分',
		5	=> '150分',
		6	=> '180分',
		10	=> '300分',
		18	=> '540分',
		20	=> '600分',
	],

	// 予約可能か
	'reservable_ng' => 0,
	'reservable_ok' => 1,

	// 予約タイプ
	'reservetion_type'	=> [
		'counseling'	=> 1,
		'treatment'		=> 2,
	],

	// コースタイプ
	'course_type' => [
		'pack'		=> 0,
		'monthly'	=> 1,
	],

	// 会員タイプ
	'customer_type' => [
		'general'	=> 1,	// 一般
		'vip'		=> 2,	// VIP
		'model'		=> 3,	// モデル系
		'special'	=> 4,	// 特殊紹介
		'test'		=> 5,	// テスト
		'test101'	=> 101,	// テスト
	],

	// 地域コード
	'area'	=> [
		1	=> '北海道・東北',
		2	=> '関東',
		3	=> '中部・甲信越',
		4	=> '近畿',
		5	=> '中国・四国',
		6	=> '九州・沖縄',
	],

	// サロンエリア区分
	'salon_area'	=> [
		1 => 'hokkaido_tohoku',
		2 => 'kanto',
		3 => 'chubu_koshinetsu',
		4 => 'kinki',
		5 => 'chugoku_shikoku',
		6 => 'kyushu_okinawa',
	],

	// 脱毛契約プラン
	'hair_loss_plan' => [
		'old_pack'				=> 1,	// 旧パック
		'new_pack'				=> 2,	// 新パック
		'new_pack_warranty_end'	=> 3,	// 新パック（保証期間終了）
		'monthly'				=> 4,	// 月額
		'old_sp'				=> 5,	// 旧通い放題
		'old_sp_warranty_end'	=> 6,	// 旧通い放題（保証期間終了）
		'new_sp'				=> 7,	// 新通い放題
		'new_sp_warranty_end'	=> 8,	// 新通い放題（保証期間終了）
		'under_19'				=> 9,	// U-19
		'free_plan'				=> 10,	// 1回無料プラン
	],

	// エステ契約プラン
	'esthetic_plan'	=> [
		'pack'				=> 1,	// パックプラン
		'pack_warranty_end'	=> 2,	// パックプラン（保証期間終了）
	],

	// 整体契約プラン
	'manipulative_plan'	=> [
		'pack'				=> 1,	// パックプラン
		'pack_warranty_end'	=> 2,	// パックプラン（保証期間終了）
	],

	// 施術タイプ
	'treatment_type'	=> [
		'hair_loss'		=> 0,	// 脱毛
		'esthetic'		=> 1,	// エステ
		'manipulative'	=> 2,	// 整体
	],

	// 施術名(日本語)
	'treatment_type_name_eng_ja'	=> [
		'hair_loss'		=> '脱毛',	// 脱毛
		'esthetic'		=> 'エステ',	// エステ
		'manipulative'	=> '整体',	// 整体
	],

	// 施術名(日本語)
	'treatment_type_name'	=> [
		'0'	=> '脱毛',	// 脱毛
		'1'	=> 'エステ',	// エステ
		'2'	=> '整体',	// 整体
	],

	// 施術名(日本語)
	'treatment_type_name_eng'	=> [
		'0'	=> 'hair_loss',		// 脱毛
		'1'	=> 'esthetic',		// エステ
		'2'	=> 'manipulative',	// 整体
	],

	// 店舗検索タイプ
	'shop_search_type'	=> [
		'all'			=> 0,	// 全て
		'counseling'	=> 1,	// カウンセリング
		'treatment'		=> 2,	// トリートメント
	],

	'hair_loss_reserve_max_cnt'	=> 1,	// 脱毛装填数
	'esthetic_reserve_max_cnt'	=> 3,	// エステ装填数(最大3)

	// メール関連
	'from_name'			=> 'キレイモ送信専用アドレス',				// 送信元名
	'from_name_premium'	=> 'キレイモプレミアム送信専用アドレス',		// 送信元名（プレミアム）
	'mime_type_html'	=> 'html',								// html形式
	'mime_type_plane'	=> 'plane',								// plane形式

	// お客様情報変更完了メール
	'subject_member_update_mail'	=> '【KIREIMO】会員情報のご変更が完了しました。',								// 件名
	'body_member_update_mail'		=> resource_path() . '/views/doc/mail_member_info_change.blade.php',	// 本文

	// お問い合わせ受付完了　ユーザー宛
	'subject_contact_mail'	=> '【KIREIMO】お問合せをお受付いたしました。',							// 件名
	'body_contact_mail'		=> resource_path() . '/views/doc/mail_contact_for_user.blade.php',	// 本文

	// お問い合わせ受付完了(プレミアム)　ユーザー宛
	'subject_contact_mail_premium'	=> '【KIREIMO PREMIUM】お問合せをお受付いたしました。',							// 件名
	'body_contact_mail_premium'		=> resource_path() . '/views/doc/mail_contact_for_user_premium.blade.php',	// 本文

	// お問い合わせ受付完了　スタッフ宛
	'subject_contact_mail_for_staff'	=> '【マイページ問合せ】',												// 件名(メールアドレス登録済み)
	'subject_contact_mail_no_address'	=> '【マイページ問合せ】※メールアドレスが存在しないお客様から※',				// 件名(メールアドレス未登録)
	'body_contact_mail_for_staff'		=> resource_path() . '/views/doc/mail_contact_for_staff.blade.php',	// 本文
	'contact_types'						=> ['施術について','お支払いについて','プランについて','お友達紹介について'],	// 問い合わせカテゴリ

	// パスワード再設定(パスワードを忘れた方)
	'subject_reset_password_mail'	=> '【KIREIMO】お客様コードおよびパスワード再設定用URLのお知らせ',			// 件名
	'body_reset_password_mail'		=> resource_path() . '/views/doc/mail_reset_password.blade.php',	// 本文

	// パスワード変更
	'subject_change_password_mail'	=> '【KIREIMO】パスワードのご変更が完了しました。',						// 件名
	'body_change_password_mail'		=> resource_path() . '/views/doc/mail_change_password.blade.php',	// 本文

	// 新規予約メール(脱毛)
	'subject_new_treatment'	=> '【KIREIMO】お手入れのご予約を承りました。',						// 件名
	'body_new_treatment'	=> resource_path() . '/views/doc/mail_new_treatment.blade.php',	// 本文

	// 新規予約メール(エステ)
	'subject_new_treatment_esthetic'	=> '【KIREIMO PREMIUM】お手入れのご予約を承りました。',							// 件名
	'body_new_treatment_esthetic'		=> resource_path() . '/views/doc/mail_new_treatment_esthetic.blade.php',	// 本文

	// 予約変更メール
	'subject_change_treatment'	=> '【KIREIMO】お手入れのご予約変更を承りました。',						// 件名
	'body_change_treatment'		=> resource_path() . '/views/doc/mail_change_treatment.blade.php',	// 本文

	// 予約変更メール(エステ)
	'subject_change_treatment_esthetic'	=> '【KIREIMO PREMIUM】お手入れのご予約変更を承りました。',						// 件名
	'body_change_treatment_esthetic'	=> resource_path() . '/views/doc/mail_change_treatment_esthetic.blade.php',	// 本文

	// 予約キャンセルメール
	'subject_cancel_treatment'	=> '【KIREIMO】お手入れのご予約キャンセルを承りました。',					// 件名
	'body_cancel_treatment'		=> resource_path() . '/views/doc/mail_cancel_treatment.blade.php',	// 本文

	// 予約キャンセルメール(エステ)
	'subject_cancel_treatment_esthetic'	=> '【KIREIMO PREMIUM】お手入れのご予約キャンセルを承りました。',					// 件名
	'body_cancel_treatment_esthetic'	=> resource_path() . '/views/doc/mail_cancel_treatment_esthetic.blade.php',	// 本文

	// 店舗地図
	'shop_map' => [
		1	=> 'map01.png',
		2	=> 'ikemap.png',
		3	=> 'shibuya_map.png',
		4	=> 'yokohama_map.png',
		5	=> 'oomiya_map.png',
		6	=> 'sinjyuku02_map.png#',
		7	=> 'ikemap02.png',
		8	=> 'ginza_map.png',
		9	=> 'nagoya_map.png',
		10	=> 'nigata_map.png',
		11	=> 'sapporo_map.png',
		12	=> 'tenjin_map.png',
		13	=> 'chiba_map.png',
		14	=> 'machida_map.png',
		15	=> 'shibuyamiyamasuzaka_map.png',
		16	=> 'shinsaibashi_map.png',
		17	=> 'kobemotomachi_map.png',
		18	=> 'utsunomiya_map.png',
		19	=> 'umeda_map.png',
		20	=> 'akihabara_map.png',
		21	=> 'kyoto_map.png',
		22	=> 'yokohama2_map.png',
		23	=> 'kumamoto_map.png',
		24	=> 'kagoshima_map.png',
		25	=> 'hiroshima_map.png',
		26	=> 'sendai_map.png',
		27	=> 'nanba_map.png',
		28	=> 'kichijoji_map.png',
		29	=> 'shizuoka_map.png',
		30	=> 'abeno_map.png',
		31	=> 'kawasaki_map.png',
		32	=> 'kinshicho.png',
		33	=> 'kita_senju.png',
		34	=> 'naha_omoromachi.png',
		35	=> 'tsudanuma.png',
		36	=> 'kashiwa_map.png',
		37	=> 'fujisawa_map.png',
		38	=> 'nagoya_ekimae_map.png',
		39	=> 'machida02_map.png',
		40	=> 'gotanda_map.png',
		41	=> 'shinjuku_nishiguchi_map.png',
		42	=> 'tachikawa_kitaguchi_ekimae_map.png',
		43	=> 'omiya_marui_map.png',
		44	=> 'hankyu_umeda_ekimae_map.png',
		45	=> 'sapporo_ekimae_map.png',
		46	=> 'kawagoe_map.png',
		47	=> 'hakata_ekimae_map.png',
		48	=> 'kanayama_ekimae_map.png',
		49	=> 'sannomiya_ekimae_map.png',
		50	=> 'ikemap03.png',
		51	=> 'karasuma_map.png',
		52	=> 'hachiouji_map.png',
		53	=> 'mizonoguchi_ekimae_map.png',
		54	=> 'himeji_ekimae_map.png',
		55	=> 'kokura_map.png',
		56	=> 'takasaki_map.png',
		57	=> 'shibuya_nishiguchi_map.png',
		58	=> 'yurakucho_map.png',
		59	=> 'shibuya_hachikouguchi_map.png',
		60	=> 'funabashi_ekimae_map.png',
		61	=> 'okayama_map.png',
		62	=> 'morioka.jpg',
		63	=> 'takamatsu_map.jpg',
		64	=> 'nagoya_sakuradoriguchi_map.jpg',
		65	=> 'machida_terminalguchi_map.jpg',
		66	=> 'hamamatsu_ekimae_map.jpg',
		67	=> 'kohrinbo_atrio_map.png',
		68	=> 'okinawa_parcocity_map.jpg',
		69	=> 'sendai_ekimae_map.jpg',
		70	=> 'shinjuku_oguard_map.png',
		// 71,72追加する
	],

	// 色
	'color'	=> [
		'red'		=> '#CE473D',	// 赤
		'green'		=> '#56AC41',	// 緑
		'orange'	=> '#DDAE3F',	// オレンジ
	],

	// 予約タイプ
	'reserve_type'	=> [
		'new'		=> 1,
		'change'	=> 2,
		'cancel'	=> 3,
	],

	// 曜日
	'week'	=> [
		'日',
		'月',
		'火',
		'水',
		'木',
		'金',
		'土',
	],

	// 契約ステータス
	'contract_status'	=> [
		'under_contract'				=> 0,	// 契約中
		'end_contract'					=> 1,	// 契約終了
		'cooling_off'					=> 2,	// クーリングオフ
		'cancel_middle'					=> 3,	// 中途解約
		'plan_change'					=> 4,	// プラン変更
		'loan_cancel'					=> 5,	// ローン取消
		'auto_cancel'					=> 6,	// 自動解約
		'contract_wait'					=> 7,	// 契約待ち
		'money_back_guarantee_expires'	=> 8,	// 返金保証回数終了
		'deadline_cancel'				=> 9,	// 期限切れ解約
		'end_minor_plan'				=> 10,	// 未成年プラン終了
		'monthly_recess'				=> 11,	// 月額休会中
	],

	// 契約異常フラグ
	'contract_abnormal'	=> [
		'normal'		=> 0,	// 正常
		'end'			=> 1,	// 契約なし
		'abnormal'		=> 2,	// 契約異常あり
		'payment_error'	=> 3,	// 支払い異常
		'confirm'		=> 4,	// 確認内容あり
		'preparation'	=> 5,	// 準備中
	],

	// 初期時間
	'initial_time' => '0000-00-00 00:00:00',

	// 遅刻時間
	'late_under_time'	=> 1,	// 10分未満
	'late_over_time'	=> 2,	// 10分以上

	// パスワード最低桁数
	'password_count_min'	=> 8,

	// 問い合わせ用フラグ
	'contact_flg'	=> [
		'normal'	=> 1,	// kireimo
		'premium'	=> 2,	// premium
	],

	// ニュース表示件数
	'news_disp_cnt' => 6
];
