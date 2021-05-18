<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;
use App\Library\CommonApi;
use App\Library\CommonReserve;

class ReserveChangeController extends Controller
{

	public $title			= 'KIREIMO ご予約の変更';						// ページタイトル
	public $ary_customer	= [];										// お客様情報格納用
	public $ary_week		= ['日', '月', '火', '水', '木', '金', '土'];	// 曜日情報
	public $ary_contract	= [];										// 契約情報格納用
	public $ary_shop		= [];										// 店舗情報格納用
	public $ary_reserve		= [];										// 予約情報格納用
	public $common_api;													// api接続用インスタンス

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;

		// 契約情報取得
		$this->ary_contract	= session()->get('contract');

		// 予約情報を取得
		$this->ary_reserve = session()->get('reserve_data');

		// 店舗情報取得
		$this->ary_shop	= Common::getShopApi($this->common_api);
	}

	/**
	 *	条件設定画面
	 */
	public function index($contract_id, $reservation_id, Request $request)
	{
		// 存在する予約であるかチェックを行いつつ、どの施術タイプであるかチェック
		$target_course_type = null;

		foreach (config('const.treatment_type_name_eng') as $course_type)
		{
			if (!empty($this->ary_reserve[$course_type][$reservation_id]) && $this->ary_reserve[$course_type][$reservation_id]['contractId'] == $contract_id)
			{
				$target_course_type = $course_type;
				break;
			}
		}

		// 整体だったらトップへリダイレクト
		if ($target_course_type == config('const.treatment_type_name_eng.2'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// コンディションエラーが存在するかチェック
		if (!empty($this->ary_contract[$target_course_type][$contract_id]['condition']['failedConditions']))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// コースタイプがパックまたは無制限 かつ 返金期間内 かつ 予約数 + rTimes = timesの場合は予約不可
		$tmp_r_times = $this->ary_contract[$target_course_type][$contract_id]['reserve_count'] + $this->ary_contract[$target_course_type][$contract_id]['rTimes'];

		if ($this->ary_contract[$target_course_type][$contract_id]['courseType'] == config('const.course_type.pack') &&
			(strtotime($this->ary_reserve[$target_course_type][$reservation_id]['hopeDate']) == strtotime(date('Y-m-d'))) &&
			($this->ary_contract[$target_course_type][$contract_id]['times'] != 0) &&
			($this->ary_contract[$target_course_type][$contract_id]['times'] <= $tmp_r_times))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 予約変更対象データを格納
		$ary_target_data					= $this->ary_reserve[$target_course_type][$reservation_id];
		$ary_target_data['treatmentType']	= $target_course_type;
		$ary_target_data['contractId']		= $contract_id;
		$ary_target_data['reservationId']	= $reservation_id;

		session()->put('change_target_reserve', $ary_target_data);

		// 検索結果画面から戻ってきた場合のデータ格納先
		$ary_return_data = [];

		// エラーまたは検索結果画面から帰ってきた場合
		$period_disp_flg = true;	// 回数表示画像を表示するか

		if (session()->exists('change_reserve_request'))
		{
			$ary_return_data = session()->get('change_reserve_request');	// セッションを登録
			$period_disp_flg = false;										// 回数表示画像を表示しない
		}
		else if ($target_course_type != config('const.treatment_type_name_eng.0'))
		{
			// 脱毛以外は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$contract_id]['courseType'] == config('const.course_type.monthly'))
		{
			// 月額の場合は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$contract_id]['courseId'] == 999)
		{
			// 特別保障の場合は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$contract_id]['courseId'] == 2001)
		{
			// 無料一回プランは非表示
			$period_disp_flg = false;
		}
		else
		{
			// トップから遷移してきてセッションが残っている場合は削除
			session()->forget('target_course_type');
			session()->forget('target_contract_id');
			session()->forget('change_reserve_request');
			session()->forget('change_reserve_data');
		}

		// 予約可能日付取得
		$ary_reserve_date = $this->common_api->getApi('reservableRange/' . $contract_id, 'customer', 1);

		if ($ary_reserve_date['apiStatus'] != config('api.api_status_code.SUCCESS') || empty($ary_reserve_date['body']['minDate']) || empty($ary_reserve_date['body']['maxDate']))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 店舗エリア情報を取得
		$ary_area = CommonReserve::getArea($this->common_api, config('const.shop_search_type.treatment'), $target_course_type);

		if (!$ary_area)
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 予約可能開始日
		$reserve_conditions = [];

		$reserve_conditions['start']['year']	= date('Y', strtotime($ary_reserve_date['body']['minDate']));
		$reserve_conditions['start']['month']	= date('m', strtotime($ary_reserve_date['body']['minDate']));
		$reserve_conditions['start']['day']		= date('d', strtotime($ary_reserve_date['body']['minDate']));

		// 年始除外設定
		switch($reserve_conditions['start']['month'] . '-' . $reserve_conditions['start']['day'])
		{
			case '01-01':
				$reserve_conditions['start']['day']		= '04';
				break;
			case '01-02':
				$reserve_conditions['start']['day']		= '04';
				break;
			case '01-03':
				$reserve_conditions['start']['day']		= '04';
				break;
			default:
				break;
		}

		$reserve_conditions['end']['year']		= date('Y', strtotime($ary_reserve_date['body']['maxDate']));
		$reserve_conditions['end']['month']		= date('m', strtotime($ary_reserve_date['body']['maxDate']));
		$reserve_conditions['end']['day']		= date('d', strtotime($ary_reserve_date['body']['maxDate']));

		// 予約可能格納月を入れる
		$reserve_conditions['reserve']['month']	= [];

		if ($reserve_conditions['start']['month'] > $reserve_conditions['end']['month'])
		{
			// 予約可能開始月の数値より予約可能終了月の数値が低い場合
			// 予約可能開始月~12月までを配列に入れる
			for ($iStart = $reserve_conditions['start']['month']; $iStart <= 12; $iStart++)
			{
				$reserve_conditions['reserve']['month'][] = strlen($iStart) == 1 ? '0' . $iStart : $iStart;
			}

			// 1月〜予約終了月までを配列に入れる
			for ($iStart = 1; $iStart <= $reserve_conditions['end']['month']; $iStart++)
			{
				$reserve_conditions['reserve']['month'][] = strlen($iStart) == 1 ? '0' . $iStart : $iStart;
			}
		}
		else
		{
			// 予約開始月より予約可能終了月が大きい場合
			for ($iStart = $reserve_conditions['start']['month']; $iStart <= $reserve_conditions['end']['month']; $iStart++)
			{
				$reserve_conditions['reserve']['month'][] = strlen($iStart) == 1 ? '0' . $iStart : $iStart;
			}
		}

		// エラーメッセージが存在するか
		$ary_error_msg = null;

		if (session()->exists('change_reserve_error_msg'))
		{
			// 回数表示画像を非表示
			$period_disp_flg = false;

			// 変数にエラーメッセージを格納
			$ary_error_msg = session()->get('change_reserve_error_msg');

			// セッション削除
			session()->forget('change_reserve_error_msg');
		}

		// 来店周期モーダル
		$ary_period_data = [];
		if ($period_disp_flg)
		{
			$ary_period_data = CommonReserve::periodDisp($this->ary_contract[$target_course_type][$contract_id]);
		}

		// 契約情報
		$ary_contract_edit = $this->ary_contract[$target_course_type][$contract_id];

		// セッションに対象の契約タイプを入れる
		session()->put('target_course_type', $target_course_type);

		// セッションに契約IDを入れる
		session()->put('target_contract_id', $contract_id);

		return view('mypage.change.index',
			[
				'title'					=> $this->title,					// タイトル
				'ary_customer'			=> $this->ary_customer,				// お客様情報
				'reserve_conditions'	=> $reserve_conditions,				// 条件
				'ary_return_data'		=> $ary_return_data,				// 検索結果画面から戻ってきた場合のデータ
				'ary_period_data'		=> $ary_period_data,				// 周期関連
				'user_agent'			=> $request->header('User-Agent'),	// ユーザーエージェント
				'ary_area'				=> $ary_area,						// エリア
				'ary_error_msg'			=> $ary_error_msg,					// エラーメッセージ
				'ary_contract'			=> $ary_contract_edit,				// 契約情報
			]
		);
	}

	/**
	 *	検索結果表示
	 */
	public function search(Request $request)
	{
		// 対象契約データ
		$ary_change_data = session()->get('change_target_reserve');

		// リクエストを取得
		$ary_request = $request->all();

		// セッション切れの場合、エラー画面へ遷移
		if (!session()->exists('target_contract_id') || !session()->exists('target_course_type') || (empty($ary_request['search_date']) && !session()->exists('change_reserve_request')))
		{
			return view('mypage.error.error',
			[
				'title'				=> $this->title,								// タイトル
				'ary_customer'		=> $this->ary_customer,							// お客様情報
				'page_title'		=> $this->title,								// ページタイトル
				'error_category'	=> config('error_const.session.error_category'),	// エラーカテゴリ
				'body'				=> config('error_const.session.body'),							// 本文
			]
			);
		}

		// 確認画面から戻ってきた場合
		if (empty($ary_request['search_date']) && !empty(session()->get('change_reserve_data')))
		{
			$ary_edit_request = CommonReserve::validateCheck(session()->get('change_reserve_request'));

			// エラーメッセージが存在する場合は前の画面に戻る
			if (!empty($ary_edit_request['error_msg']))
			{
				// エラー内容をセッションに入れる
				session()->put('change_reserve_error_msg', $ary_edit_request['error_msg']);
				return redirect()->to('/change/' . $ary_change_data['contractId'] . '/' . $ary_change_data['reservationId']);
			}
		}
		else
		{
			// 条件チェック
			$ary_edit_request = CommonReserve::validateCheck($ary_request);

			// セッションに値を入れる
			session()->put('change_reserve_request', $ary_request);

			// エラーメッセージが存在する場合は前の画面に戻る
			if (!empty($ary_edit_request['error_msg']))
			{
				session()->put('change_reserve_error_msg', $ary_edit_request['error_msg']);
				return redirect()->to('/change/' . $ary_change_data['contractId'] . '/' . $ary_change_data['reservationId']);
			}
		}

		// 画面表示用にデータ成形
		$ary_disp_data = CommonReserve::searchDataModling($ary_edit_request, $this->ary_shop);

		// 対象店舗が存在しなかった場合
		if (empty($ary_disp_data['target_shop']))
		{
			// エラー内容をセッションに入れる
			session()->put('change_reserve_error_msg', config('msg.A1009'));
			return redirect()->to('/change/' . $ary_change_data['contractId'] . '/' . $ary_change_data['reservationId']);
		}

		// apiから空き情報を取得
		$ary_disp_data['reserve_data'] = CommonReserve::getReservableCheck($this->common_api, $ary_edit_request, $ary_change_data, $ary_change_data['reservationId']);

		// エラーメッセージが存在した場合
		if (!empty($ary_disp_data['reserve_data']['error_msg']))
		{
			// エラー内容をセッションに入れる
			session()->put('change_reserve_error_msg', $ary_disp_data['reserve_data']['error_msg']);
			return redirect()->to('/change/' . $ary_change_data['contractId'] . '/' . $ary_change_data['reservationId']);
		}

		return view('mypage.change.search',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
				'ary_disp_data'			=> $ary_disp_data,		// 画面表示データ
				'ary_week'				=> $this->ary_week,		// 曜日配列
				'ary_change_data'		=> $ary_change_data,	// 対象データ
			]
			);
	}

	/**
	 * 確認画面
	 *
	 * @param Request $request
	 */
	public function confirm(Request $request)
	{
		// セッション切れの場合、エラー画面へ遷移
		if (!session()->exists('target_contract_id') || !session()->exists('target_course_type'))
		{
			return view('mypage.error.error',
			[
				'title'				=> $this->title,								// タイトル
				'ary_customer'		=> $this->ary_customer,							// お客様情報
				'page_title'		=> $this->title,								// ページタイトル
				'error_category'	=> config('error_const.session.error_category'),	// エラーカテゴリ
				'body'				=> config('error_const.session.body'),							// 本文
			]
			);
		}

		// リクエストを取得
		$ary_request = $request->all();

		// 変更前の予約データ
		$ary_before_reserve = session()->get('change_target_reserve');

		// セッションに取得値を入れる
		$ary_param = [];
		$ary_param = [
			'target_date'		=> $ary_request['target_date'],
			'target_time_cd'	=> $ary_request['target_time_cd'],
			'target_shop_id'	=> $ary_request['target_shop_id'],
		];

		if (session()->exists('change_reserve_data'))
		{
			// セッションが存在する場合は削除
			session()->forget('change_reserve_data');
		}

		session()->put('change_reserve_data', $ary_param);

		// 変更後のデータを画面表示用にデータを成形
		$ary_disp_data = [];
		$ary_disp_data = [
			'target_date'	=> date('Y年m月d日', strtotime($ary_request['target_date'])) . '(' . $this->ary_week[date('w', strtotime($ary_request['target_date']))] . ')',
			'target_time'	=> config('const.hope_time.' . $ary_request['target_time_cd']),
			'target_shop'	=> $this->ary_shop[$ary_request['target_shop_id']]['name'],
		];

		// 対象の契約
		$ary_disp_data['change_flg'] = 0;	// 予約変更不可フラグ（0 : 不可 / 1 : 可能）
		$ary_disp_data['cancel_flg'] = 0;	// 予約キャンセル不可フラグ（0 : 不可 / 1 : 可能）

		if ($ary_before_reserve['treatmentType'] == 'hair_loss')
		{
			// 日付判定（月額のみ）
			if ($this->ary_contract[$ary_before_reserve['treatmentType']][$ary_before_reserve['contractId']]['courseType'] == config('const.course_type.monthly'))
			{
				$ary_disp_data['confirm_msg']	= CommonReserve::checkDay($ary_request['target_date']);
				if (!empty($ary_disp_data['confirm_msg']))
				{
					$ary_disp_data['change_flg']	= 1;
				}
			}
		}

		// 予約日と予約した日が同じ日であるか
		if (strtotime(date('Ymd')) == strtotime($ary_request['target_date']))
		{
			$ary_disp_data['change_flg']	= 1;
			$ary_disp_data['cancel_flg']	= 1;
		}

		// 変更前のデータを画面表示用に編集
		$ary_disp_data['before_data']['target_date']	= date('Y年m月d日', strtotime($ary_before_reserve['hopeDate'])) . '(' . $this->ary_week[date('w', strtotime($ary_before_reserve['hopeDate']))] . ')';
		$ary_disp_data['before_data']['target_time']	= config('const.hope_time.' . $ary_before_reserve['hopeTimeCd']);
		$ary_disp_data['before_data']['target_shop']	= $this->ary_shop[$ary_before_reserve['shopId']]['name'];

		// 対象の契約を取り出す
		$ary_contract = $this->ary_contract[$ary_before_reserve['treatmentType']][$ary_before_reserve['contractId']];

		return view('mypage.change.confirm',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
				'ary_disp_data'			=> $ary_disp_data,		// 画面表示用
				'ary_contract'			=> $ary_contract,		// 対象契約情報
			]
			);
	}

	/**
	 * 予約変更処理
	 */
	public function process()
	{
		// 予約変更対象データを取得
		$ary_target_reserve = session()->get('change_reserve_data');

		// セッション切れの場合、エラー画面へ遷移
		if (!session()->exists('target_contract_id') || !session()->exists('target_course_type'))
		{
			return view('mypage.error.error',
			[
				'title'				=> $this->title,								// タイトル
				'ary_customer'		=> $this->ary_customer,							// お客様情報
				'page_title'		=> $this->title,								// ページタイトル
				'error_category'	=> config('error_const.session.error_category'),	// エラーカテゴリ
				'body'				=> config('error_const.session.body'),							// 本文
			]
			);
		}

		// 予約

		// apiに送るデータの作成
		$ary_param = [];

		$ary_param = [
			'reservation_id'	=> session()->get('change_target_reserve.reservationId'),
			'shopId'			=> $ary_target_reserve['target_shop_id'],
			'hopeDate'			=> $ary_target_reserve['target_date'],
			'hopeTimeCd'		=> (int)$ary_target_reserve['target_time_cd'],
		];

		// apiへ更新実施
		$ary_result = $this->common_api->putApi($ary_param, 'reservation_data');

		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			if ($ary_result['errorReasonCd'] == 'E9012')
			{
				// 予約が他の人とブッキング
				session()->put('change_reserve_booking', '1');
				return redirect()->to('/change/full');
			}
			else
			{
				// 不要なセッションを削除する
				session()->forget('target_course_type');
				session()->forget('target_contract_id');
				session()->forget('change_target_reserve');
				session()->forget('change_reserve_request');
				session()->forget('change_reserve_data');

				// エラー画面へ遷移
				return view('mypage.error.error',
					[
						'title'				=> $this->title,									// タイトル
						'ary_customer'		=> $this->ary_customer,								// お客様情報
						'page_title'		=> $this->title,									// ページタイトル
						'error_category'	=> config('error_const.change_reserve_api_error'),	// エラーカテゴリ
						'body'				=> config('msg.A1005'),								// 本文
					]
					);
			}
		}

		// 施術対象を取得
		$target_course_type = session()->get('target_course_type');

		// 対象の契約IDを取得
		$target_contract_id = session()->get('target_contract_id');

		// コース名をパラメータに追加
		$ary_param['course_name'] = $this->ary_contract[$target_course_type][$target_contract_id]['courseName'];

		// メール送信処理
		if ($target_course_type == config('const.treatment_type_name_eng.0'))
		{
			// 脱毛
			$this->sendMailChangeReserveHairLoss($ary_param);
		}
		else
		{
			// エステ
			$this->sendMailChangeReserveEsthetic($ary_param);
		}

		 return redirect()->to('/change/complete');
	}

	/**
	 * ブッキング
	 */
	public function full()
	{
		// セッションが存在するかチェック
		if (!session()->exists('change_reserve_booking'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// セッション削除
		session()->forget('change_reserve_booking');

		return view('mypage.change.full',
			[
				'title'				=> $this->title,		// タイトル
				'ary_customer'		=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * 完了画面
	 */
	public function complete()
	{
		if (!session()->exists('change_target_reserve'))
		{
			// 予約情報が存在しない場合はトップにリダイレクト
			return redirect()->to('/top');
		}

		// 予約情報をセッションから取得する
		$ary_reserve_data = session()->get('change_reserve_data');

		// 画面表示用
		$ary_disp_data = [
			'hope_date'		=> date('Y/m/d', strtotime($ary_reserve_data['target_date'])) . '(' . $this->ary_week[date('w', strtotime($ary_reserve_data['target_date']))] . ')',
			'hope_time'		=> date('H時i分', strtotime(config('const.hope_time.' . $ary_reserve_data['target_time_cd']))),
			'target_shop'	=> $this->ary_shop[$ary_reserve_data['target_shop_id']]['name'],
		];

		// 不要なセッションを削除する
		session()->forget('change_target_reserve');
		session()->forget('change_reserve_request');
		session()->forget('change_reserve_data');

		return view('mypage.change.complete',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
				'ary_disp_data'	=> $ary_disp_data,		// 画面表示用
			]
			);
	}

	/**
	 * ajaxで店舗情報取得用
	 *
	 * @param Request $request
	 * @return array
	 */
	public function areaShop(Request $request)
	{
		// 取得したリクエストを配列へ
		$ary_request = $request->all();

		// セッションから取得した内容を配列に設定
		$ary_shop = session()->get('ary_search_shop_data');

		// エリア別にする
		$ary_shop_list = [];

		foreach ($ary_shop as $key => $val)
		{
			// keyがget_dateの場合は次へ
			if ($key == 'get_date')
			{
				continue;
			}

			// 指定されたエリアのみ取得
			if ($val['area'] == $ary_request['area'])
			{
				$ary_shop_list['shop_list'][$key] = $val;
			}
		}

		// セッションに選択済みの店舗が存在するか
		if (session()->exists('change_reserve_request'))
		{
			$ary_shop_list['session_search_shop'] = session()->get('change_reserve_request.search_shop');

			// リクエストデータを削除
			session()->forget('change_reserve_request');
		}

		return $ary_shop_list;
	}

	/**
	 * メール送信処理(脱毛)
	 *
	 * @param	array	$ary_param	予約情報
	 */
	private function sendMailChangeReserveHairLoss($ary_param)
	{
		// 本文文字列置き換え
		$ary_replace = [];
		$ary_replace = [
			'{name1}'				=> $this->ary_customer['name1'],																																					// 苗字
			'{name2}'				=> $this->ary_customer['name2'],																																					// 名前
			'{course_name}'			=> $ary_param['course_name'],																																						// コース名
			'{target_date}'			=> date('Y/m/d', strtotime($ary_param['hopeDate'])),																																// 予約日
			'{target_time}'			=> date('H時i分', strtotime(config('const.hope_time.' . $ary_param['hopeTimeCd']))),																									// 予約時間
			'{target_shop}'			=> $this->ary_shop[$ary_param['shopId']]['name'],																																	// 店舗名
			'{target_shop_address}'	=> $this->ary_shop[$ary_param['shopId']]['prefName'] . $this->ary_shop[$ary_param['shopId']]['address'],																			// 店舗住所
			'{target_shop_url}'		=> config('env_const.kireimo_top') . 'salon/' . config('const.salon_area.' . $this->ary_shop[$ary_param['shopId']]['area']) . '/' . $this->ary_shop[$ary_param['shopId']]['url'] ,	// 店舗URL
			'{mypage_base_url}'		=> config('env_const.base_url'),																																					// マイページURL
			'{kireimo_url}'			=> config('env_const.kireimo_top'),																																					// キレイモトップ
			'{kireimo_info}'		=> config('env_const.info_address'),																																				// infoメールアドレス
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),																																			// キレイモ電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),																																	// キレイモ 予約電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_change_treatment'));

		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// メールを送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $this->ary_customer['mail'],					// 送信先
			'fromAddress'	=> config('env_const.from_address'),			// 送信元
			'fromName'		=> config('const.from_name'),					// 送信元名
			'subject'		=> config('const.subject_change_treatment'),	// 件名
			'body'			=> $text,										// 本文
			'mimeType'		=> config('const.mime_type_plane'),				// 形式
		];

		$this->common_api->postApi($ary_reserve_data, 'single_mail');
	}

	/**
	 * メール送信処理(エステ)
	 *
	 * @param	array	$ary_param	予約情報
	 */
	private function sendMailChangeReserveEsthetic($ary_param)
	{
		// 本文文字列置き換え
		$ary_replace = [];
		$ary_replace = [
			'{name1}'						=> $this->ary_customer['name1'],																																					// 苗字
			'{name2}'						=> $this->ary_customer['name2'],																																					// 名前
			'{course_name}'					=> $ary_param['course_name'],																																						// コース名
			'{target_date}'					=> date('Y/m/d', strtotime($ary_param['hopeDate'])),																																// 予約日
			'{target_time}'					=> date('H時i分', strtotime(config('const.hope_time.' . $ary_param['hopeTimeCd']))),																									// 予約時間
			'{target_shop}'					=> $this->ary_shop[$ary_param['shopId']]['name'],																																	// 店舗名
			'{target_shop_address}'			=> $this->ary_shop[$ary_param['shopId']]['prefName'] . $this->ary_shop[$ary_param['shopId']]['address'],																			// 店舗住所
			'{target_shop_url}'				=> config('env_const.kireimo_top') . 'salon/' . config('const.salon_area.' . $this->ary_shop[$ary_param['shopId']]['area']) . '/' . $this->ary_shop[$ary_param['shopId']]['url'] ,	// 店舗URL
			'{mypage_base_url}'				=> config('env_const.base_url'),																																					// マイページURL
			'{kireimo_premium_url}'			=> config('env_const.kireimo_premium_top'),																																			// キレイモトップ
			'{kireimo_info_premium_mail}'	=> config('env_const.info_address_premium'),																																		// infoメールアドレス
			'{kireimo_premium_tel}'			=> config('env_const.kireimo_premium_tel_disp'),																																	// キレイモ 電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_change_treatment_esthetic'));

		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// メールを送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $this->ary_customer['mail'],							// 送信先
			'fromAddress'	=> config('env_const.from_address_premium'),			// 送信元
			'fromName'		=> config('const.from_name_premium'),					// 送信元名
			'subject'		=> config('const.subject_change_treatment_esthetic'),	// 件名
			'body'			=> $text,												// 本文
			'mimeType'		=> config('const.mime_type_plane'),						// 形式
		];

		$this->common_api->postApi($ary_reserve_data, 'single_mail');
	}
}
