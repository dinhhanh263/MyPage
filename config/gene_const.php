<?php

//**********************************************************************
// 遺伝子検査結果関連の定数
//**********************************************************************

return [

	// マスタデータ
	'gene_master'	=>
		[
			1	=>
				[
					'name'				=>	'白鳥',
					'img'				=>	'img/gene/type_1.png',
					'description'		=>	'水面に美しく羽を広げる白鳥タイプのあなたは、生まれつきボディメイクに優れた遺伝子を持ち合わせています。' .
											'糖質や脂質を多めに摂取してもカラダは分解してくれます。たんぱく質は吸収されやすいため、筋肉がつきやすい体質です。',
				],
			2	=>
				[
					'name'				=>	'うさぎ',
					'img'				=>	'img/gene/type_2.png',
					'description'		=>	'大人しく寂しがりやなうさぎタイプのあなたは、脂っぽいものは平気だけど、太るとやせにくい遺伝子を持ち合わせています。' .
											'脂質の分解は得意ですが、糖質の分解とたんぱく質の吸収が少し苦手で、筋肉がつきにくい体質です。',
				],
			3	=>
				[
					'name'				=>	'ねこ',
					'img'				=>	'img/gene/type_3.png',
					'description'		=>	'気まぐれで柔軟性と瞬発力に優れたねこタイプのあなたは、糖質にさえ注意すればボディメイクに適切な遺伝子を持ち合わせています。' .
											'糖質の分解は苦手ですが、脂質の分解が得意でたんぱく質も吸収しやすいため筋肉がつきやすい体質です。',
				],
			4	=>
				[
					'name'				=>	'イルカ',
					'img'				=>	'img/gene/type_4.png',
					'description'		=>	'海を華麗に泳ぐイルカタイプのあなたは、お腹まわりに脂肪を蓄えやすく、食事や運動に注意が必要な遺伝子を持ち合わせています。' .
											'脂質の分解は得意としていますが、糖質の分解は苦手です。また、たんぱく質を吸収しづらいため、筋肉がつきにくい体質です。',
				],
			5	=>
				[
					'name'				=>	'クマ',
					'img'				=>	'img/gene/type_5.png',
					'description'		=>	'たくましく筋肉質なくまタイプのあなたは、代謝は良いが脂肪がつきやすい遺伝子を持ち合わせています。' .
											'糖質と脂質の分解が苦手で、脂肪を蓄えやすいのですが、たんぱく質は吸収されやすく、筋肉がつきやすい体質です。',
				],
			6	=>
				[
					'name'				=>	'こじか',
					'img'				=>	'img/gene/type_6.png',
					'description'		=>	'天真爛漫に草原を走るこじかタイプのあなたは、引き締まったボディラインをつくりやすい遺伝子を持ち合わせています。' .
											'脂質の分解は少しだけ苦手ですが、糖質の分解は得意でたんぱく質も吸収されやすく、筋肉がつきやすいカラダです。',
				],
			7	=>
				[
					'name'				=>	'きつね',
					'img'				=>	'img/gene/type_7.png',
					'description'		=>	'用心深く動きが素早いきつねタイプのあなたは、脂質にさえ注意すれば、ダイエットには最適な遺伝子を持ち合わせています。' .
											'糖質を摂取してもあまり太りませんが、脂質を溜めやすい体質です。たんぱく質は吸収されやすく、筋肉がつきやすいです。',
				],
			8	=>
				[
					'name'				=>	'リス',
					'img'				=>	'img/gene/type_8.png',
					'description'		=>	'木登りが大好きなリスタイプのあなたは、食事に注意が必要で、カラダが冷えやすい遺伝子を持ち合わせています。' .
											'糖質の分解は得意ですが、脂質の分解、たんぱく質の吸収を苦手としており、筋肉がつきにくい体質です。',
				],
			9	=>
				[
					'name'				=>	'コアラ',
					'img'				=>	'img/gene/type_9.png',
					'description'		=>	'木の上でゆったりと過ごすマイペースなコアラタイプのあなたは、お腹まわりに脂肪を蓄えやすく、食事や運動に注意が必要な遺伝子を持ち合わせています。' .
											'脂質の分解は得意としていますが、糖質の分解は少し苦手です。また、たんぱく質を吸収しづらいため、筋肉がつきにくい体質です。',
				],
			10	=>
				[
					'name'				=>	'いぬ',
					'img'				=>	'img/gene/type_10.png',
					'description'		=>	'いつも活発に走りまわるいぬタイプのあなたは、太りにくい体質ではありますが、筋肉がつきづらい遺伝子を持ち合わせています。' .
											'糖質や脂質を摂取しても分解できますが、たんぱく質の吸収が苦手で、引き締まったカラダをキープするのには努力が必要です。',
				],
			11	=>
				[
					'name'				=>	'ひつじ',
					'img'				=>	'img/gene/type_11.png',
					'description'		=>	'群の中でのんびり行動するひつじタイプのあなたは、太りにくいが、一度太ってしまうとやせづらい遺伝子を持ち合わせています。' .
											'糖質の分解は得意としていますが、脂質の分解は少し苦手です。また、たんぱく質は吸収しづらいため、筋肉がつきにくい体質です。',
				],
			12	=>
				[
					'name'				=>	'パンダ',
					'img'				=>	'img/gene/type_12.png',
					'description'		=>	'のんびり屋で、食べることが大好きなパンダタイプのあなたは、ボディメイクにもっとも努力が必要な遺伝子を持ち合わせています。' .
											'糖質と脂質の分解が苦手で脂肪を蓄えやすく、たんぱく質の吸収も苦手なため、太りやすく筋肉がつきにくい体質です。',
				],
		],

	// 肥満リスクレベル画像
	'obesity_risk_img'	=>
		[
			1	=> 'img/gene/risk_1.jpg',
			2	=> 'img/gene/risk_2.jpg',
			3	=> 'img/gene/risk_3.jpg',
			4	=> 'img/gene/risk_4.jpg',
			5	=> 'img/gene/risk_5.jpg',
			6	=> 'img/gene/risk_6.jpg',
			7	=> 'img/gene/risk_7.jpg',
			8	=> 'img/gene/risk_8.jpg',
			9	=> 'img/gene/risk_9.jpg',
			10	=> 'img/gene/risk_10.jpg',
			11	=> 'img/gene/risk_11.jpg',
			12	=> 'img/gene/risk_12.jpg',
		],

	// 肥満リスクId
	'obesity_risk_id'	=>
		[
			1	=> '普通',
			2	=> 'やや高い',
			3	=> '高い',
			4	=> '非常に高い',
		],

	// 肥満パターン
	'obesity_pattern'	=>
		[
			1	=> 'なし',
			2	=> '全身',
			3	=> 'お腹周り',
			4	=> '下半身',
			5	=> 'お腹周りあるいは全身',
			6	=> 'お腹周りあるいは下半身',
			7	=> '下半身あるいは全身',
		],

	// 遺伝子評価
	'gene_evaluation'	=>
		[
			1	=>
				[
					'msg'				=> 'あなたのカラダは遺伝子的には太りづらいです。',	// 遺伝子評価
					'group'				=> 'NOリスク族',								// 遺伝子グループ
					'eat_order_img_1'	=> 'img/gene/eat-salad-soup.jpg',			// 食べる順番1
					'eat_order_alt_1'	=> 'サラダ・スープ',							// 食べる順番キャプション1
					'eat_order_img_2'	=> 'img/gene/eat-side.jpg',					// 食べる順番2
					'eat_order_alt_2'	=> 'おかず',									// 食べる順番キャプション2
					'eat_order_img_3'	=> 'img/gene/eat-main.jpg',					// 食べる順番3
					'eat_order_alt_3'	=> '主食',									// 食べる順番キャプション3
				],
			2	=>
				[
					'msg'				=> 'あなたのカラダは糖質の代謝を一番苦手としています。',	// 遺伝子評価
					'group'				=> '糖質危険族',									// 遺伝子グループ
					'eat_order_img_1'	=> 'img/gene/eat-salad-soup.jpg',				// 食べる順番1
					'eat_order_alt_1'	=> 'サラダ・スープ',								// 食べる順番キャプション1
					'eat_order_img_2'	=> 'img/gene/eat-side.jpg',						// 食べる順番2
					'eat_order_alt_2'	=> 'おかず',										// 食べる順番キャプション2
					'eat_order_img_3'	=> 'img/gene/eat-main.jpg',						// 食べる順番3
					'eat_order_alt_3'	=> '主食',										// 食べる順番キャプション3
				],
			3	=>
				[
					'msg'				=> 'あなたのカラダは脂質の代謝を一番苦手としています。',	// 遺伝子評価
					'group'				=> '脂質危険族',									// 遺伝子グループ
					'eat_order_img_1'	=> 'img/gene/eat-salad-soup.jpg',				// 食べる順番1
					'eat_order_alt_1'	=> 'サラダ・スープ',								// 食べる順番キャプション1
					'eat_order_img_2'	=> 'img/gene/eat-main.jpg',						// 食べる順番2
					'eat_order_alt_2'	=> '主食',										// 食べる順番キャプション2
					'eat_order_img_3'	=> 'img/gene/eat-side.jpg',						// 食べる順番3
					'eat_order_alt_3'	=> 'おかず',										// 食べる順番キャプション3
				],

			4	=>
				[
					'msg'				=> 'あなたのカラダは筋肉がつきづらい傾向にあります。',	// 遺伝子評価
					'group'				=> 'たんぱく質危険族',								// 遺伝子グループ
					'eat_order_img_1'	=> 'img/gene/eat-side.jpg',						// 食べる順番1
					'eat_order_alt_1'	=> 'おかず',										// 食べる順番キャプション1
					'eat_order_img_2'	=> 'img/gene/eat-salad-soup.jpg',				// 食べる順番2
					'eat_order_alt_2'	=> 'サラダ・スープ',								// 食べる順番キャプション2
					'eat_order_img_3'	=> 'img/gene/eat-main.jpg',						// 食べる順番3
					'eat_order_alt_3'	=> '主食',										// 食べる順番キャプション3
				],

			5	=>
				[
					'msg'				=> 'あなたのカラダは遺伝子的には太りやすいです。',	// 遺伝子評価
					'group'				=> 'ALL NG危険族',							// 遺伝子グループ
					'eat_order_img_1'	=> 'img/gene/eat-salad-soup.jpg',			// 食べる順番1
					'eat_order_alt_1'	=> 'サラダ・スープ',							// 食べる順番キャプション1
					'eat_order_img_2'	=> 'img/gene/eat-side.jpg',					// 食べる順番2
					'eat_order_alt_2'	=> 'おかず',									// 食べる順番キャプション2
					'eat_order_img_3'	=> 'img/gene/eat-main.jpg',					// 食べる順番3
					'eat_order_alt_3'	=> '主食',									// 食べる順番キャプション3
				],
		],

	// 糖質代謝画像
	'suger_risk_img'	=>
		[
			1	=> 'img/gene/carbohydrate_1.png',
			2	=> 'img/gene/carbohydrate_2.png',
			3	=> 'img/gene/carbohydrate_3.png',
		],

	// 脂質代謝画像
	'fat_risk_img'	=>
	[
		1	=> 'img/gene/lipid_1.png',
		2	=> 'img/gene/lipid_2.png',
		3	=> 'img/gene/lipid_3.png',
	],

	// 筋肉質画像
	'protein_risk_img'	=>
	[
		1	=> 'img/gene/protein_1.png',
		2	=> 'img/gene/protein_2.png',
		3	=> 'img/gene/protein_3.png',
	],

	// エステ機器名
	'treatment_equipments'	=>
		[
			1	=>
				[
					'name'			=> 'キャビテーション',
					'img'			=> 'img/gene/program_1.png',
					'description'	=> '超音波で脂肪を1秒間に30,000回振動させて脂肪を排泄物、水分と一緒に体外へ排出。',
				],
			2	=>
				[
					'name'			=> 'RF 吸引',
					'img'			=> 'img/gene/program_2.png',
					'description'	=> '体の中から温めると同時に吸引の力でセルライトをもみほぐし代謝を高め痩せやすいカラダへ。',
				],
			3	=>
				[
					'name'			=> 'バイポーラ',
					'img'			=> 'img/gene/program_3.png',
					'description'	=> '高周波で体内の深部温度を5～7度上げることにより脂肪を燃焼させます。',
				],
			4	=>
				[
					'name'			=> 'EMS',
					'img'			=> 'img/gene/program_4.png',
					'description'	=> '美しいボディラインを維持する為に必要な筋肉を効率よく鍛えられる万能マシン。5つのモード、50のレベルであなたの状態に合わせたケアができます。',
				],
		],

	// 栄養素摂取状況画像
	'nutrients_img'	=>
		[
			// ビタミンB1
			'vitamin_b1'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
			// ビタミンB2
			'vitamin_b2'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
			// ビタミンB6
			'vitamin_b6'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
			// パントテン酸
			'pantothenic_acid'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
			// L-カルニチン
			'l_arnitine'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
			// ナイアシン
			'niacin'	=>
				[
					0	=> 'img/gene/icon-nutrients-bad.png',
					1	=> 'img/gene/icon-nutrients-good.png',
				],
		],

	// おすすめの運動画像
	'recommended_motion'	=>
		[
			1	=>
				[
					'main_motion'		=> 'ウォーキング',
					'main_motion_img'	=> 'img/gene/exercise_1.jpg',
					'sub_motion'		=> '水泳',
					'sub_motion_img'	=> 'img/gene/exercise_2.jpg',
					'msg'				=>	'プロポーションを保つのに最適なタイプのあなた。' .
											'まずはウォーキングなどの日常生活に取り入れられそうな運動で動く量を「増やす」事を意識すると良いでしょう。' .
											'また、水泳等の全身運動もお勧めです。',
				],
			2	=>
				[
					'main_motion'		=> 'サイクリング',
					'main_motion_img'	=> 'img/gene/exercise_3.jpg',
					'sub_motion'		=> 'ウエイトトレーニング',
					'sub_motion_img'	=> 'img/gene/exercise_4.jpg',
					'msg'				=>	'タンパク質の分解吸収が特に苦手なタイプのあなたはウエイトトレーニングやサイクリングなどの運動で筋肉を「作る」ことにより効率よく脂肪を落とせるでしょう。' .
											'体重ではなくサイズ測定を行うことで客観的に自分の身体の状況を把握することができます。',
				],
			3	=>
				[
					'main_motion'		=> 'ホットヨガ',
					'main_motion_img'	=> 'img/gene/exercise_5.jpg',
					'sub_motion'		=> 'スクワット',
					'sub_motion_img'	=> 'img/gene/exercise_6.jpg',
					'msg'				=>	'脂質の代謝が特に苦手なタイプのあなたはホットヨガなどの身体を温めながら身体を動かす運動で脂肪を「ながす」ことにより効率良く脂肪を落とせるでしょう。' .
											'また、スクワットなどで大きな筋肉を温めることもお勧めです。',
				],
			4	=>
				[
					'main_motion'		=> 'フラフープ',
					'main_motion_img'	=> 'img/gene/exercise_7.jpg',
					'sub_motion'		=> 'ダンス',
					'sub_motion_img'	=> 'img/gene/exercise_8.jpg',
					'msg'				=>	'糖の代謝が特に苦手なタイプのあなたはフラフープやダンスなどの運動で脂肪を「ゆらす」事により効率よく気になる脂肪を落とせるでしょう。また、縄飛びやトランポリンなども脂肪をゆらすのでお勧めです。',
				],
		],


	// おすすめのサプリ情報
	'recommended_supplement'	=>
		[
			1	=>
				[
					'name'	=>	'moe＋（脂肪燃焼）',
					'msg'	=>	"難消化デキストリンやカルニチンなど、由来の違う脂肪燃焼、脂質代謝アップ成分をバランスよく配合しました。\n" .
								"効率よく脂肪を燃焼させるためのおすすめのサプリメントです。脂肪燃焼を促進するだけでなく、血糖値の上昇を抑制したり、便通を改善する働きがあり、美容と健康をサポートします。",
					'img'	=>	'img/gene/suppli-beauty.jpg',
				],
			2	=>
				[
					'name'	=>	'Carbo－（糖質カット）',
					'msg'	=>	"糖質の吸収を抑制することに特化したサラシアを中心に処方しました。\n" .
								"併せて中性脂肪の上昇を抑制するとともに、トレーニングサプリとしても使用される成分リンゴとブドウのポリフェノールも添加しています。\n" .
								"血行を促進し、抗酸化作用により体のコンディションを整え、サラシアの効果をさらにアップしてくれるサポート成分です。",
					'img'	=>	'img/gene/suppli-carb.jpg',
				],
			3	=>
				[
					'name'	=>	'fat－（脂質カット）',
					'msg'	=>	"中性脂肪、脂肪の蓄積に作用するアフリカマンゴノキを中心に処方しました。「痩せホルモン」と言われているアディポネクチンの分泌を促進。\n" .
								"直接脂肪にアタックします。さらに、アフリカマンゴノキの成分が働きやすいよう、脂肪の蓄積に作用しながら、血行促進、滋養強壮効果の黒ショウガ成分もプラス。\n" .
								"体のコンディションをアップしながら脂質代謝を促進させるサプリメントです。",
					'img'	=>	'img/gene/suppli-fat.jpg',
				],
			4	=>
				[
					'name'	=>	'yoku－（食欲抑制）',
					'msg'	=>	"食欲抑制成分として酵母ペプチドに加え、浮腫み軽減作用や抗酸化作用成分をバランスよく配合した食欲抑制サプリメントです。\n" .
								"グァガム、イナゴマメ抽出物、アカシア食物繊維、ザクロ抽出物、βカロテン、藍藻抽出物（ブルーグリーンアルジー）をブレンドしてダイエット原料として有名なレプチコアを中心に配合しております。",
					'img'	=>	'img/gene/suppli-beauty.jpg',
				],
			5	=>
				[
					'name'	=>	'beauty+（置き換え）',
					'msg'	=>	"ダイエット期間をストレスなく過ごしていただけるよう、置き換えドリンクとして美味しさにもこだわったシェイクです。\n" .
								"食物繊維、鉄分、たんぱく質、マグネシウム、ビタミンなどダイエット中の女性が不足しがちな成分を含んでいるスーパー食材を配合した栄養機能食品として、女性の美容と健康をサポートします。",
					'img'	=>	'img/gene/suppli-beauty.jpg',
				],
		],

	// 摂取制限
	'intake_restrictions'	=>
		[
			'carbohydrate'	=> 1,	// 糖質
			'lipid'			=> 2,	// 脂質
			'protein'		=> 3,	// タンパク質
		],
];