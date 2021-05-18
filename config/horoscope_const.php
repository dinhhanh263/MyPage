<?php

return [
    //12星座
    'zodiacs' => [
        // '星座ID', '星座名', 月, 日　～　月, 日
        array(1, '牡羊座', 3, 21, 4, 19),
        array(2, '牡牛座', 4, 20, 5, 20),
        array(3, '双子座', 5, 21, 6, 21),
        array(4, 'かに座', 6, 22, 7, 22),
        array(5, '獅子座', 7, 23, 8, 22),
        array(6, '乙女座', 8, 23, 9, 22),
        array(7, '天秤座', 9, 23, 10, 23),
        array(8, '蠍座', 10, 24, 11, 22),
        array(9, '射手座', 11, 23, 12, 21),
        array(10, '山羊座', 12, 22, 1, 19),
        array(11, '水瓶座', 1, 20, 2, 18),
        array(12, '魚座', 2, 19, 3, 20)
    ],

    //運勢のスコア
    'fortune_rating' => [
        '0.0' => '0_0.png',
        '0.5' => '0_5.png',
        '1.0' => '1_0.png',
        '1.5' => '1_5.png',
        '2.0' => '2_0.png',
        '2.5' => '2_5.png',
        '3.0' => '3_0.png',
        '3.5' => '3_5.png',
        '4.0' => '4_0.png',
        '4.5' => '4_5.png',
        '5.0' => '5_0.png'
    ],

    //星座一覧の画像
    //array(パラメータ, 画像名, alt属性)
    'seiza_imgs' => [
        array(1, 'seiza-01.png', 'おひつじ座'),
        array(2, 'seiza-02.png', 'おうし座'),
        array(3, 'seiza-03.png', 'ふたご座'),
        array(4, 'seiza-04.png', 'かに座'),
        array(5, 'seiza-05.png', 'しし座'),
        array(6, 'seiza-06.png', 'おとめ座'),
        array(7, 'seiza-07.png', 'てんびん座'),
        array(8, 'seiza-08.png', 'さそり座'),
        array(9, 'seiza-09.png', 'いて座'),
        array(10, 'seiza-10.png', 'やぎ座'),
        array(11, 'seiza-11.png', 'みずがめ座'),
        array(12, 'seiza-12.png', 'うお座')
    ],

    //各星座ページのタイトル画像
    'seiza_title_imgs' => [
        1 => 'seiza-title-01.png',
        2 => 'seiza-title-02.png',
        3 => 'seiza-title-03.png',
        4 => 'seiza-title-04.png',
        5 => 'seiza-title-05.png',
        6 => 'seiza-title-06.png',
        7 => 'seiza-title-07.png',
        8 => 'seiza-title-08.png',
        9 => 'seiza-title-09.png',
        10 => 'seiza-title-10.png',
        11 => 'seiza-title-11.png',
        12 => 'seiza-title-12.png'
    ],

    //ラッキーアイテム画像
    'lucky_item_imgs' => [
        1 => 'lucky-item-1.jpg',
        2 => 'lucky-item-2.jpg',
        3 => 'lucky-item-3.jpg',
        4 => 'lucky-item-4.jpg',
        5 => 'lucky-item-5.jpg',
        6 => 'lucky-item-6.jpg',
        7 => 'lucky-item-7.jpg',
        8 => 'lucky-item-8.jpg',
        9 => 'lucky-item-9.jpg',
        10 => 'lucky-item-10.jpg',
        11 => 'lucky-item-11.jpg',
        12 => 'lucky-item-12.jpg'
    ]
];
