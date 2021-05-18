<?php

namespace App\Library;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

class Common
{
	/**
	 * ログインチェック（ログイン画面以外）
	 */
	public static function loginCheck()
	{
		if (empty(Cookie::get(session_name())))
		{
			// sessionを破棄
			session()->flush();

			// 未ログイン
			Redirect::to('/')->send();
		}

		if (!session()->has('customer')) {
			// cookieを破棄
			setcookie(session_name(), '', time() - 30);

			// 未ログイン
			Redirect::to('/')->send();
		}
	}

	/**
	 * お客様情報を取得する
	 */
	public static function getCustomer($common_api)
	{
		// customer情報取得
		$ary_customer = $common_api->getApi(session()->get('customer.id'), 'customer', 1);

		if ($ary_customer['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// データが存在しなかった場合、ログアウト処理
			return self::logout();
		}

		$ary_customer['body']['id'] = session()->get('customer.id');

		// セッションに格納
		session()->put('customer', $ary_customer['body']);

		return true;
	}

	/**
	 * ログアウト共通処理
	 */
	public static function logout()
	{
		// セッション破棄
		session()->flush();
		// クッキー破棄
		Cookie::queue(session_name(), null, time() - 864000);
		// ログイン画面へ
		return redirect()->route('loginTop');
	}

	/**
	 * 脱毛契約プラン判定
	 * @param	array	$ary_data
	 * @return	string
	 */
	public static function contractHairLossJuge($ary_data)
	{
		if ($ary_data['courseMinorPlanFlg'])
		{
			// U-19
			return config('const.hair_loss_plan.under_19');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] < 999)
		{
			// 旧パックプラン(1)
			return config('const.hair_loss_plan.old_pack');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && $ary_data['courseId'] == 2001 && $ary_data['times'] <> 0)
		{
			// 1回無料プラン
			return config('const.hair_loss_plan.free_plan');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && $ary_data['courseId'] >= 999 && $ary_data['times'] <> 0)
		{
			// 新パック（保証期間終了）(3)
			return config('const.hair_loss_plan.new_pack_warranty_end');
		}
		else if($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && !is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] < 999)
		{
			// 新パック（保証期間内）(2)
			return config('const.hair_loss_plan.new_pack');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] > 999 && $ary_data['times'] == 0)
		{
			// 旧通い放題（保証期間終了）(6)
			return config('const.hair_loss_plan.old_sp_warranty_end');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 1 && is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] < 999)
		{
			// 旧通い放題（保証期間内）(5)
			return config('const.hair_loss_plan.old_sp');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 0 && !is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] > 999 && $ary_data['times'] == 0)
		{
			// 新通い放題（保証期間終了）(8)
			return config('const.hair_loss_plan.new_sp_warranty_end');
		}
		else if ($ary_data['courseType'] == 0 && $ary_data['courseZeroFlg'] == 1 && !is_null($ary_data['courseSalesStartDate']) && $ary_data['courseId'] < 999)
		{
			// 新通い放題（保証期間内）(7)
			return config('const.hair_loss_plan.new_sp');
		}
		else if ($ary_data['courseType'] == 1)
		{
			// 月額(4)
			return config('const.hair_loss_plan.monthly');
		}

		return false;
	}

	/**
	 * エステ契約プラン判定
	 * @param	array	$ary_data
	 * @return	string
	 */
	public static function contractEstheticJuge($ary_data)
	{
		if ($ary_data['courseId'] <= 999)
		{
			// エステプラン返金保証期間内
			return config('const.esthetic_plan.pack');
		}
		else
		{
			// エステプラン返金保証期間外
			return config('const.esthetic_plan.pack_warranty_end');
		}
	}

	/**
	 * 整体契約プラン判定
	 * @param	array	$ary_data
	 * @return	string
	 */
	public static function contractManipulativeJuge($ary_data)
	{
		if ($ary_data['courseId'] <= 999)
		{
			// エステプラン返金保証期間内
			return config('const.manipulative_plan.pack');
		}
		else
		{
			// エステプラン返金保証期間外
			return config('const.manipulative_plan.pack_warranty_end');
		}
	}

	/**
	 * 店舗を取得する
	 *
	 * @param	integer	$type 0:全て、1:カウンセリング予約可能、2:脱毛、3:整体、4:エステ
	 */
	public static function getShopApi($common_api, $type = '')
	{
		// セッションを取得
		$ary_session = session()->all();

		if (empty($ary_session['shop_list']) || (strtotime($ary_session['shop_list']['get_date']) <= strtotime('+2 hour', $ary_session['shop_list']['get_date'])))
		{
			// 格納用
			$ary_shop_data = [];

			// セッションにショップ情報が存在しない または 最新ではない場合はapiから取得する
			$type = empty($type) ? 0 : $type;

			$ary_param = [
				'type' => $type,
			];

			$result = $common_api->getApi($ary_param, 'shops');

			if ($result['apiStatus'] != config('api.api_status_code.SUCCESS'))
			{
				// 取得エラー
				return [];
			}

			// shopIdごとの配列にする
			foreach ($result['body'] as $key => $val)
			{
				if ($key == 'get_date')
				{
					continue;
				}

				$ary_shop_data[$val['shopId']] = $val;
			}

			// セッションに保存
			$ary_shop_data['get_date'] = date('Ymd');		// 取得時間
			session()->put('shop_list', $ary_shop_data);	// 取得データ
		}
		else
		{
			$ary_shop_data = session()->get('shop_list');
		}

		return $ary_shop_data;
	}

	/**
	 * return url
	 */
	public static function returnUrlCheck($return_url)
	{
		// urlの形をしているか
		if (!preg_match('/^(https?)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $return_url))
		{
			return null;
		}

		// ドメインが同じであるか
		$domain_return_url	= parse_url($return_url, PHP_URL_HOST);						// return_urlのドメイン
		$domain_mypage		= parse_url(config('env_const.base_url'), PHP_URL_HOST);	// マイページのドメイン

		if (strcmp($domain_return_url, $domain_mypage) !== 0)
		{
			return null;
		}

		// パスを取得する
		return parse_url($return_url, PHP_URL_PATH);
	}
}