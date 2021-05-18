<?php

// ******************************
// 予約関連関数まとめ
// ******************************

namespace App\Library;

use Illuminate\Support\Facades\Redirect;

class CommonReserve
{
	/**
	 * 店舗取得
	 *
	 * @param	integer	$search_type	0:全て、1:カウンセリング、2:トリートメント
	 */
	public static function getArea($common_api, $search_type, $treatment_type)
	{
		// apiから対象店舗を取得
		$ary_param = [
			'type' => $search_type,
		];

		$ary_target_shop_tmp = $common_api->getApi($ary_param, 'shops');

		if ($ary_target_shop_tmp['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			return false;
		}

		// shopIdごとの配列にする
		$ary_target_shop	= []; // 成形後格納先
		$ary_area			= []; // エリア情報格納用

		foreach ($ary_target_shop_tmp['body'] as $val)
		{
			// 施術可能店舗を仕分ける
			if ($treatment_type == config('const.treatment_type_name_eng.1'))
			{
				// エステの場合フラグが立ってなかったら除外
				if (!$val['estheticsFlg'])
				{
					continue;
				}
			}
			else if ($treatment_type == config('const.treatment_type_name_eng.2'))
			{
				// 整体の場合フラグが立ってなかったら除外
				if (!$val['chiropracticFlg'])
				{
					continue;
				}
			}

			// 配列格納
			$ary_target_shop[$val['shopId']] = $val;

			// エリア格納
			$ary_area[] = $val['area'];
		}

		// セッションに対象の店舗情報を入れる
		session()->put('ary_search_shop_data', $ary_target_shop);

		// エリア重複削除
		$ary_area = array_unique($ary_area);

		// 不要なエリアを削除
		$ary_target_area = [];

		foreach ($ary_area as $area_val)
		{
			if (array_key_exists($area_val, config('const.area')))
			{
				$ary_target_area[$area_val] = config('const.area.' . $area_val);
			}
		}

		// ソートする
		ksort($ary_target_area);

		return $ary_target_area;
	}

	/**
	 * 条件チェック
	 */
	public static function validateCheck($ary_data)
	{
		// バリデーション後のデータ等格納用
		$ary_edit_data = [];

		// 日付チェック
		foreach ($ary_data['search_date'] as $val)
		{
			// 年を判定
			if ($val['month'] < date('m'))
			{
				// 指定された月が現在の月よりも数値が小さい場合翌年とする
				$year = date('Y', strtotime('+ 1 year'));
			}
			else
			{
				// 指定された月が現在の月以上の場合、今年を設定する
				$year = date('Y');
			}

			// 妥当性チェック
			if (!checkdate($val['month'], $val['day'], $year))
			{
				$ary_edit_data['error_msg'][] = config('msg.V1015');
				break;
			}

			$target_date = $year . '-' . $val['month'] . '-' . $val['day'];

			// 日付を配列に格納する（同じ日付は除外）
			if (empty($ary_edit_data['search_date']))
			{
				// 空の場合
				$ary_edit_data['search_date'][] = $target_date;
			}
			else
			{
				// 同じ日付は除外
				if (!in_array($target_date, $ary_edit_data['search_date'], true))
				{
					$ary_edit_data['search_date'][] = $target_date;
				}
			}
		}

		// エリアが選択されていることをチェック
		if (!($ary_data['search_shop_area'] > 0 && $ary_data['search_shop_area'] <= 6))
		{
			$ary_edit_data['error_msg'][] = config('msg.V1016');
			return $ary_edit_data;
		}

		$ary_edit_data['search_shop_area'] = $ary_data['search_shop_area'];

		// idが設定されているかチェック
		$search_shop_flg = false;
		foreach ($ary_data['search_shop'] as $shop_id)
		{
			if ($shop_id != 0)
			{
				// 一件でも設定値が存在すればOK
				$search_shop_flg = true;
				break;
			}
		}

		if (!$search_shop_flg)
		{
			$ary_edit_data['error_msg'][] = config('msg.V1017');
			return $ary_edit_data;
		}

		foreach ($ary_data['search_shop'] as $val)
		{
			// 0の場合は除外
			if ($val == 0)
			{
				continue;
			}

			if (empty($ary_edit_data['search_shop']))
			{
				$ary_edit_data['search_shop'][] = $val;
			}
			else
			{
				// 存在するidの場合は除外
				if (!in_array($val, $ary_edit_data['search_shop'], true))
				{
					$ary_edit_data['search_shop'][] = $val;
				}
			}
		}

		return $ary_edit_data;
	}

	/**
	 * 画面表示用にデータ成形(search)
	 */
	public static function searchDataModling($ary_data, $ary_shop)
	{
		// 表示内容格納用
		$ary_disp_data = [];

		// 日付
		foreach ($ary_data['search_date'] as $val)
		{
			$ary_tmp_date[] = date('Y年m月d日', strtotime($val)) . '(' . config('const.week.' . date('w', strtotime($val))) . ')';
		}

		// 日付をカンマ区切りにする
		$ary_disp_data['target_date'] = implode(',', $ary_tmp_date);

		// 店舗
		$ary_disp_data['target_shop'] = [];

		foreach ($ary_data['search_shop'] as $val)
		{
			if (!empty($ary_shop[$val]))
			{
				$ary_disp_data['target_shop'][$val] = $ary_shop[$val]['name'];
			}
		}

		return $ary_disp_data;
	}

	/**
	 * 空き情報を取得し、画面表示ように成形する
	 *
	 * @access	public
	 * @param	array	$ary_data
	 * @param	array	$ary_hair_loss_contract
	 * @param	integer	$reservation_id				0:新規, 1:変更
	 */
	public static function getReservableCheck($common_api, $ary_data, $ary_target_contract, $reservation_id = '')
	{
		// apiから取得したデータを格納
		$ary_get_data = [];

		// 一時格納用
		$ary_tmp_data = [];

		// まず日別
		foreach ($ary_data['search_date'] as $val)
		{
			// 店舗別
			foreach ($ary_data['search_shop'] as $val2)
			{
				// パラメータの設定
				$ary_param = [];
				$ary_param = [
					'reservationType'	=> '2',
					'shopId'			=> $val2,
					'date'				=> date('Y-m-d', strtotime($val)),
					'contractId'		=> $ary_target_contract['contractId'],
				];

				if (!empty($reservation_id))
				{
					$ary_param['selfReservationID'] = $reservation_id;
				}

				// api取得
				$ary_result = $common_api->getApi($ary_param, 'rsrv_check');

				if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
				{
					// 取得エラー
					$ary_get_data['error_msg'] = config('msg.A1009');
					return $ary_get_data;
				}
				else
				{
					$ary_tmp_data[$val2] = $ary_result['body'];
				}

				// 画面表示用にデータ成形
				$ary_get_data[date('Y-m-d', strtotime($val))] = self::modelingReserveData($ary_tmp_data);
			}
		}

		return $ary_get_data;
	}

	/**
	 * 画面表示用データに成形
	 */
	private static function modelingReserveData($ary_data)
	{
		$ary_tmp_data = [];

		foreach ($ary_data as $shop_id => $by_store_data)
		{
			foreach ($by_store_data as $key => $val)
			{
				if ($val['reservableRooms'] > 0)
				{
					$ary_tmp_data[$key][$shop_id] = config('const.reservable_ok');
				}
				else
				{
					$ary_tmp_data[$key][$shop_id] = config('const.reservable_ng');
				}
			}

		}

		return $ary_tmp_data;
	}

	/**
	 * 日付判定
	 */
	public static function checkDay($target_date)
	{
		$change_message	= null;
		$today			= date('Y-m-d');												// 今日
		$one_day_ago	= date('Y-m-d', strtotime('-1 day', strtotime($target_date)));	// 予約日前日
		$two_day_ago	= date('Y-m-d', strtotime('-2 day', strtotime($target_date)));	// 予約日前々日

		if (strtotime($today) == strtotime($target_date))
		{
			// 予約日当日の場合
			$change_message = '予約日当日';
		}
		else if (strtotime($today) == strtotime($one_day_ago))
		{
			// 予約日前日の場合
			$change_message = '予約日前日';
		}
		else if (strtotime($today) == strtotime($two_day_ago))
		{
			// 予約日前々日の場合
			$change_message = '予約日2日前';
		}

		return $change_message;
	}

	/**
	 * 周期モーダル表示設定
	 */
	public static function periodDisp($ary_contract_data)
	{
		// 返却配列
		$ary_return_data = [];

		// エラーなし、かつ検索結果画面から戻ってきていない
		if (session()->exists('new_reserve_request')) {
			// モーダル設定
			return $ary_return_data;
		}

		// 次回の来店回数
		$next_visit_times = $ary_contract_data['rTimes'] + 1;

		// 固定周期
		if ($ary_contract_data['courseIntervalDate'] == 45 || $ary_contract_data['contractDate'] < '2017-01-25')
		{
			$ary_return_data = [
				'times'	=> $ary_contract_data['times'],
				'img'	=> '45days-cycle.png',
				'color'	=> config('const.color.red'),
			];
		}
		else
		{
			// 変動周期
			// (契約日が2020-10-01以降 かつ 通い放題フラグが1) または 1011、1012
			if (($ary_contract_data['courseSalesStartDate'] >= '2020-10-01' && $ary_contract_data['courseZeroFlg'] == 1) || $ary_contract_data['courseId'] == 1011 || $ary_contract_data['courseId'] == 1012)
			{
				// 来店回数によって表示する画像を変更する
				if ($next_visit_times <= 12)
				{
					// 12回以下
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-unlimited-3_1.png',
						'color'	=> config('const.color.red'),
					];
				}
				else if ($next_visit_times <= 18)
				{
					// 18回以下
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-unlimited-3_2.png',
						'color'	=> config('const.color.orange'),
					];
				}
				else
				{
					// 上記以外
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-unlimited-3_3.png',
						'color'	=> config('const.color.green'),
					];
				}
			}
			else if ($ary_contract_data['courseId'] == 1005 || $ary_contract_data['courseId'] == 1010)
			{
				// キレイモ全身脱毛スペシャルプランと平日とく得スペシャルプラン(返金保証回数終了)
				$ary_return_data = [
					'times'	=> $ary_contract_data['times'],
					'img'	=> '90days-cycle.png',
					'color'	=> config('const.color.green'),
				];
			}
			// 返金保証回数終了(1001,1002,1004)
			else if ($ary_contract_data['times'] == 0)
			{
				// コース回数が0回
				$ary_return_data = [
					'times'	=> '-',
					'img'	=> 'visit-cycle-3_3.png',
					'color'	=> config('const.color.green'),
				];
			}
			else if ($ary_contract_data['times'] <= 6)
			{
				// 6回以下
				$ary_return_data = [
					'times'	=> $ary_contract_data['times'],
					'img'	=> 'visit-cycle-1_1.png',
					'color'	=> config('const.color.red'),
				];
			}
			else if ($ary_contract_data['times'] <= 12)
			{
				// 12回以下
				// 消化回数によって出しわけ
				if ($next_visit_times <= 6)
				{
					// 6回以下
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-2_1.png',
						'color'	=> config('const.color.red'),
					];
				}
				else
				{
					// 上記以外
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-2_2.png',
						'color'	=> config('const.color.orange'),
					];
				}
			}
			else
			{
				// 13回以上
				// 消化回数によって出しわけ
				if ($next_visit_times <= 6)
				{
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-3_1.png',
						'color'	=> config('const.color.red'),
					];
				}
				else if ($next_visit_times <= 12)
				{
					// 12回
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-3_2.png',
						'color'	=> config('const.color.orange'),
					];
				}
				else
				{
					// 上記以外
					$ary_return_data = [
						'times'	=> $ary_contract_data['times'],
						'img'	=> 'visit-cycle-3_3.png',
						'color'	=> config('const.color.green'),
					];
				}
			}
		}

		$ary_return_data['next_visit_times'] = $next_visit_times;

		return $ary_return_data;
	}
}