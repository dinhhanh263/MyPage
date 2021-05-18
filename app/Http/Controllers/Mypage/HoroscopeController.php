<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Models\Horoscope;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HoroscopeController extends Controller
{
	public $title = 'KIREIMO 星座占い';
	public $ary_customer	= [];

	public function __construct()
	{
		Common::loginCheck();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;
	}

	/**
	 * 星座占いトップ
	 */
	public function index ()
	{
		$seiza_imgs = config("horoscope_const.seiza_imgs"); //星座一覧の画像

		return view('mypage.horoscope.index',
			[
				'title'			=> $this->title,
				'ary_customer'	=> $this->ary_customer,
				'seiza_imgs'	=> $seiza_imgs
			]
		);
	}

	/**
	 * 各星座占い詳細
	 */
	public function show($zodiac_id)
	{
		$seiza_title_img	= config("horoscope_const.seiza_title_imgs.$zodiac_id");								//各星座ページのタイトル画像
		$monday_this_week	= new Carbon('monday this week');														//今週の月曜
		$sunday_this_week	= new Carbon('sunday this week');														//今週の月曜
		$this_week = $monday_this_week->format('Y年m月d日') .'～'. $sunday_this_week->format('Y年m月d日') .'の運勢';	//今週一週間

		// 今週の占い取得
		$horoscope_info = Horoscope::where([
			'constellation_id'  => $zodiac_id,
			'fortune_date'      => $monday_this_week
		])
		->get()
		->toArray();

		// ラッキーアイテム画像取得
		$lucky_item_img = $this->getLuckyItem($monday_this_week);
		// 各運勢のスコア取得
		$fortune_score = $this->getFortuneScore($horoscope_info);

		$user_zodiac_id	= "";
		$birthday		= new Carbon($this->ary_customer['birthday']);
		// ユーザーの誕生日から星座を特定
		if ($birthday != '0000-00-00')
		{
			// ユーザーの星座取得
			$user_zodiac_id = $this->getUserZodiac($birthday);
		}

		// パラメーターとユーザーの星座が一致する場合
		$param_matches_flg = false;
		if ($zodiac_id == $user_zodiac_id)
		{
			$param_matches_flg = true;
		}

		return view('mypage.horoscope.detail',
			[
				'title'				=> $this->title,
				'ary_customer'		=> $this->ary_customer,
				'param_matches_flg'	=> $param_matches_flg,
				'this_week'			=> $this_week,				// 今週
				'lucky_item_img'	=> $lucky_item_img,			// ラッキーアイテム
				'seiza_title_img'	=> $seiza_title_img,		// 星座タイトル画像
				'horoscope_info'	=> $horoscope_info,			// 星座情報
				'all_score'			=> $fortune_score['all'],	// 全体運
				'love_score'		=> $fortune_score['love'],	// 恋愛運
				'beauty_score'		=> $fortune_score['beauty']	// 美容運
			]
		);
	}

	/**
	 * ユーザーの星座取得
	 */
	private function getUserZodiac($birthday)
	{
		$user_zodiac_id	= "";
		$birth_month	= (int)$birthday->format("n");			// 誕生月
		$birth_day		= (int)$birthday->format("j");			// 誕生日
		$zodiacs		= config('horoscope_const.zodiacs');	// 12星座

		foreach ($zodiacs as $zodiac)
		{
			// list('星座ID', '星座名', 月, 日, 月, 日)
			list($id, $name, $start_m, $start_d, $end_m, $end_d) = $zodiac;
			if (($birth_month === $start_m && $birth_day >= $start_d) || ($birth_month === $end_m && $birth_day <= $end_d)) {
				$user_zodiac_id = $id;
			}
		}

		return $user_zodiac_id; // ユーザーの星座ID
	}

	/** ラッキーアイテム取得
	 * 【ラッキーアイテムIDの算出方法】
	 * 今週月曜日(Ymd)を逆さまにして会員IDを足し、それを12で割った余りに1を足す
	 * 例）(42800202(2020/08/24) + 132493) % 12 + 1
	 */
	private function getLuckyItem($monday_this_week)
	{
		$monday_for_calc	= $monday_this_week->format('Ymd');
		$reverse_monday		= strrev($monday_for_calc); 								// 逆さまにする
		$lucky_item_id		= ($reverse_monday + $this->ary_customer['id']) % 12 + 1;

		// ラッキーアイテム画像取得
		$lucky_item_img = config("horoscope_const.lucky_item_imgs.$lucky_item_id");

		return $lucky_item_img;
	}

	/**
	 * 各運勢のスコア取得
	 */
	private function getFortuneScore($horoscope_info)
	{
		// 小数点以下を0.5刻みにする
		$all_calc		= round($horoscope_info[0]['all_score'] * 2) / 2;
		$love_calc		= round($horoscope_info[0]['love_score'] * 2) / 2;
		$beauty_calc	= round($horoscope_info[0]['beauty_score'] * 2) / 2;

		$fortune_rating	= config('horoscope_const.fortune_rating');							// 各運勢のスコア
		$all_score		= $fortune_rating[number_format((float)$all_calc, 1, '.', '')];
		$love_score		= $fortune_rating[number_format((float)$love_calc, 1, '.', '')];
		$beauty_score	= $fortune_rating[number_format((float)$beauty_calc, 1, '.', '')];

		return [
			'all'		 => $all_score,
			'love'		=> $love_score,
			'beauty'	=> $beauty_score
		];
	}
}
