<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;
use App\Library\CommonApi;
use App\Library\CommonReserve;
use PhpParser\Node\Stmt\Else_;

class NewReserveController extends Controller
{
	public $title			= 'KIREIMO 新規ご予約';	// ページタイトル
	public $ary_customer	= [];					// お客様情報格納用
	public $ary_contract	= [];					// 契約情報格納用
	public $ary_shop		= [];					// 店舗情報格納用
	public $ary_reserve		= [];					// 予約情報格納用
	public $common_api;								// api接続用インスタンス

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
	public function index($id, Request $request)
	{
		// 存在する契約であるかチェックを行いつつ、どの施術タイプであるかチェック
		$target_course_type = null;

		foreach (config('const.treatment_type_name_eng') as $course_type)
		{
			if (!empty($this->ary_contract[$course_type]) && array_key_exists($id, $this->ary_contract[$course_type]))
			{
				$target_course_type = $course_type;
			}
		}

		// $target_course_typeがnull または 整体の場合、トップにリダイレクトする
		if ($target_course_type == null || $target_course_type == config('const.treatment_type_name_eng.2'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// コンディションエラーが存在するかチェック
		if (!empty($this->ary_contract[$target_course_type][$id]['condition']['failedConditions']))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// ステータスが契約中であるか
		if ($this->ary_contract[$target_course_type][$id]['status'] != config('const.contract_status.under_contract'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 脱毛の場合、すでに予約済みの場合は除外する
		if ($target_course_type == config('const.treatment_type_name_eng.0'))
		{
			// 予約数がMAXの場合は予約不可
			if ($this->ary_contract[$target_course_type][$id]['reserve_count'] >= config('const.hair_loss_reserve_max_cnt'))
			{
				// トップへリダイレクト
				return redirect()->to('/top');
			}
		}

		// エステの場合
		if ($target_course_type == config('const.treatment_type_name_eng.1'))
		{
			// 予約数がMAXの場合は予約不可
			if ($this->ary_contract[$target_course_type][$id]['reserve_count'] >= config('const.esthetic_reserve_max_cnt'))
			{
				// トップへリダイレクト
				return redirect()->to('/top');
			}
		}

		// コースタイプがパックまたは無制限 かつ 返金期間内 かつ 予約数 + rTimes = timesの場合は予約不可
		$tmp_r_times = $this->ary_contract[$target_course_type][$id]['reserve_count'] + $this->ary_contract[$target_course_type][$id]['rTimes'];

		if ($this->ary_contract[$target_course_type][$id]['courseType'] == config('const.course_type.pack') &&
			($this->ary_contract[$target_course_type][$id]['times'] != 0) &&
			($this->ary_contract[$target_course_type][$id]['times'] <= $tmp_r_times))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 予約可能日付取得
		$ary_reserve_date = $this->common_api->getApi('reservableRange/' . $id, 'customer', 1);

		if ($ary_reserve_date['apiStatus'] != config('api.api_status_code.SUCCESS') || empty($ary_reserve_date['body']['minDate']) || empty($ary_reserve_date['body']['maxDate']))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// 店舗情報を取得
		$ary_area = CommonReserve::getArea($this->common_api, config('const.shop_search_type.treatment'), $target_course_type);

		if (!$ary_area)
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// セッションに対象の契約IDを入れる
		session()->put('target_contract_id', $id);

		// セッションに対象の契約タイプを入れる
		session()->put('target_course_type', $target_course_type);

		// 検索結果画面から戻ってきた場合のデータ格納先
		$ary_return_data = [];

		// エラーから帰ってきた場合
		$period_disp_flg = true;	// 回数表示画像を表示するか

		if (session()->exists('new_reserve_request'))
		{
			$ary_return_data = session()->get('new_reserve_request');	// セッション登録
			$period_disp_flg = false;									// 回数表示画像を表示しない
		}
		else if ($target_course_type != config('const.treatment_type_name_eng.0'))
		{
			// 脱毛以外は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$id]['courseType'] == config('const.course_type.monthly'))
		{
			// 月額の場合は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$id]['courseId'] == 999)
		{
			// 特別保障の場合は非表示
			$period_disp_flg = false;
		}
		else if ($this->ary_contract[$target_course_type][$id]['courseId'] == 2001)
		{
			// 無料一回プランは非表示
			$period_disp_flg = false;
		}
		else
		{
			// トップから遷移してきてセッションが残っている場合は削除
			session()->forget('new_reserve_request');
			session()->forget('reserve_target');
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

		// 来店周期モーダル
		$ary_period_data = [];

		if ($period_disp_flg)
		{
			$ary_period_data = CommonReserve::periodDisp($this->ary_contract[$target_course_type][$id]);
		}

		// エラーメッセージが存在するか
		$ary_error_msg = null;

		if (session()->exists('new_reserve_error_msg'))
		{
			// 変数にエラーメッセージを格納
			$ary_error_msg = session()->get('new_reserve_error_msg');

			// セッション削除
			session()->forget('new_reserve_error_msg');
		}

		return view('mypage.reserve.index',
			[
				'title'					=> $this->title,									// タイトル
				'ary_customer'			=> $this->ary_customer,								// お客様情報
				'reserve_conditions'	=> $reserve_conditions,								// 条件
				'ary_return_data'		=> $ary_return_data,								// 検索結果画面から戻ってきた場合のデータ
				'ary_period_data'		=> $ary_period_data,								// 周期関連
				'user_agent'			=> $request->header('User-Agent'),					// ユーザーエージェント
				'ary_area'				=> $ary_area,										// エリア情報
				'ary_contract'			=> $this->ary_contract[$target_course_type][$id],	// 対象契約内容
				'ary_error_msg'			=> $ary_error_msg,									// エラーメッセージ
			]
			);
	}

	/**
	 *	検索結果表示
	 */
	public function search(Request $request)
	{
		// 対象契約ID
		$target_contract_id = session()->get('target_contract_id');

		// 対象契約タイプ
		$target_course_type = session()->get('target_course_type');

		// リクエストを取得
		$ary_request = $request->all();

		// セッション切れの場合、エラー画面へ遷移
		if (empty($target_contract_id) || empty($target_course_type) || (empty($ary_request['search_date']) && !session()->exists('new_reserve_request')))
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
		if (session()->exists('reserve_target') && !empty(session()->get('new_reserve_request')))
		{
			$ary_edit_request = CommonReserve::validateCheck(session()->get('new_reserve_request'));

			// エラーメッセージが存在する場合は前の画面に戻る
			if (!empty($ary_edit_request['error_msg']))
			{
				// エラー内容をセッションに入れる
				session()->put('new_reserve_error_msg', $ary_edit_request['error_msg']);
				return redirect()->to('/reserve/' . $target_contract_id);
			}
		}
		else
		{
			// 条件チェック
			$ary_edit_request = CommonReserve::validateCheck($ary_request);

			// セッションに値を入れる
			session()->put('new_reserve_request', $ary_request);

			// エラーメッセージが存在する場合は前の画面に戻る
			if (!empty($ary_edit_request['error_msg']))
			{
				// エラー内容をセッションに入れる
				session()->put('new_reserve_error_msg', $ary_edit_request['error_msg']);
				return redirect()->to('/reserve/' . $target_contract_id);
			}
		}

		// 画面表示用にデータ成形
		$ary_disp_data = CommonReserve::searchDataModling($ary_edit_request, $this->ary_shop);

		// 対象店舗が存在しなかった場合
		if (empty($ary_disp_data['target_shop']))
		{
			// エラー内容をセッションに入れる
			session()->put('new_reserve_error_msg', config('msg.A1009'));
			return redirect()->to('/reserve/' . $target_contract_id);
		}

		// apiから空き情報を取得
		$ary_disp_data['reserve_data'] = CommonReserve::getReservableCheck($this->common_api, $ary_edit_request, $this->ary_contract[$target_course_type][$target_contract_id]);

		// エラーメッセージが存在した場合
		if (!empty($ary_disp_data['reserve_data']['error_msg']))
		{
			// エラー内容をセッションに入れる
			session()->put('new_reserve_error_msg', $ary_disp_data['reserve_data']['error_msg']);
			return redirect()->to('/reserve/' . $target_contract_id);
		}

		return view('mypage.reserve.search',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
				'ary_disp_data'			=> $ary_disp_data,		// 画面表示データ
				'target_contract_id'	=> $target_contract_id,	// 対象契約ID
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
		// リクエストを取得
		$ary_request = $request->all();

		// 対象の契約ID
		$target_contract_id = session()->get('target_contract_id');

		// 対象契約タイプ
		$target_course_type = session()->get('target_course_type');

		// セッション切れの場合、エラー画面へ遷移
		if (empty($target_contract_id) || empty($target_course_type))
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

		// セッションに取得値を入れる
		$ary_param = [];
		$ary_param = [
			'target_date'		=> $ary_request['target_date'],
			'target_time_cd'	=> $ary_request['target_time_cd'],
			'target_shop_id'	=> $ary_request['target_shop_id'],
		];

		if (session()->exists('reserve_target'))
		{
			// セッションが存在する場合は削除
			session()->forget('reserve_target');
		}

		session()->put('reserve_target', $ary_param);

		// 画面表示ようにデータを成形
		$ary_disp_data = [];
		$ary_disp_data = [
			'target_date'	=> date('Y年m月d日', strtotime($ary_request['target_date'])) . '(' . config('const.week.' . date('w', strtotime($ary_request['target_date']))) . ')',
			'target_time'	=> config('const.hope_time.' . $ary_request['target_time_cd']),
			'target_shop'	=> $this->ary_shop[$ary_request['target_shop_id']]['name'],
		];

		// 対象の契約
		$ary_target_contract = $this->ary_contract[$target_course_type][$target_contract_id];

		$ary_disp_data['change_flg'] = 0;	// 予約変更不可フラグ（0 : 不可 / 1 : 可能）
		$ary_disp_data['cancel_flg'] = 0;	// 予約キャンセル不可フラグ（0 : 不可 / 1 : 可能）

		// 日付判定（月額のみ）
		if ($ary_target_contract['courseType'] == config('const.course_type.monthly'))
		{
			$ary_disp_data['confirm_msg']	= CommonReserve::checkDay($ary_request['target_date']);
			if (!empty($ary_disp_data['confirm_msg']))
			{
				$ary_disp_data['change_flg']	= 1;
			}
		}

		// 予約日と予約した日が同じ日であるか
		if (strtotime(date('Ymd')) == strtotime($ary_request['target_date']))
		{
			$ary_disp_data['change_flg']	= 1;
			$ary_disp_data['cancel_flg']	= 1;
		}

		return view('mypage.reserve.confirm',
			[
				'title'					=> $this->title,			// タイトル
				'ary_customer'			=> $this->ary_customer,		// お客様情報
				'ary_disp_data'			=> $ary_disp_data,			// 画面表示用
				'ary_contract'			=> $ary_target_contract,	// 対象契約情報
			]
			);
	}

	/**
	 * 予約登録処理
	 */
	public function process()
	{
		// 予約対象データを取得
		$ary_target_reserve = session()->get('reserve_target');

		// 対象契約タイプ
		$target_course_type = session()->get('target_course_type');

		// 対象の契約ID
		$target_contract_id = session()->get('target_contract_id');

		// セッション切れの場合、エラー画面へ遷移
		if (empty($target_contract_id) || empty($target_course_type))
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

		// 契約情報
		$ary_target_contract = $this->ary_contract[$target_course_type][$target_contract_id];

		// apiに送るデータの作成
		$ary_param = [];

		$ary_param = [
			'customerId'		=> $this->ary_customer['id'],
			'reservationType'	=> config('const.reservetion_type.treatment'),
			'contractId'		=> $target_contract_id,
			'shopId'			=> $ary_target_reserve['target_shop_id'],
			'hopeDate'			=> $ary_target_reserve['target_date'],
			'hopeTimeCd'		=> $ary_target_reserve['target_time_cd'],
		];

		// apiへ登録実施
		$ary_result = $this->common_api->postApi($ary_param, 'treatment_reserve');

		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			if ($ary_result['errorReasonCd'] == 'E9012')
			{
				// 予約が他の人とブッキング
				session()->put('new_reserve_booking', '1');
				return redirect()->to('/reserve/full');
			}
			else
			{
				// 不要なセッションを削除する
				session()->forget('reserve_target');
				session()->forget('new_reserve_request');
				session()->forget('target_contract_id');
				session()->forget('target_course_type');

				// エラー画面へ遷移
				return view('mypage.error.error',
					[
						'title'				=> $this->title,								// タイトル
						'ary_customer'		=> $this->ary_customer,							// お客様情報
						'page_title'		=> $this->title,								// ページタイトル
						'error_category'	=> config('error_const.new_reserve_api_error'),	// エラーカテゴリ
						'body'				=> config('msg.A1005'),							// 本文
					]
					);
			}
		}

		// 配列にコース名を追加
		$ary_param['course_name'] = $ary_target_contract['courseName'];

		// メール送信処理
		if ($target_course_type == config('const.treatment_type_name_eng.0'))
		{
			// 脱毛の場合
			$this->sendMailNewReserveHairLoss($ary_param);
		}
		else
		{
			// エステの場合
			$this->sendMailNewReserveEsthetic($ary_param);
		}


		return redirect()->to('/reserve/complete');
	}

	/**
	 * ブッキング
	 */
	public function full()
	{
		// セッションが存在するかチェック
		if (!session()->exists('new_reserve_booking'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		// セッション削除
		session()->forget('new_reserve_booking');

		return view('mypage.reserve.full',
			[
				'title'				=> $this->title,								// タイトル
				'ary_customer'		=> $this->ary_customer,							// お客様情報
			]
			);
	}

	/**
	 * 完了画面
	 */
	public function complete()
	{
		if (!session()->exists('reserve_target'))
		{
			// 予約情報が存在しない場合はトップにリダイレクト
			return redirect()->to('/top');
		}

		// 予約情報をセッションから取得する
		$ary_reserve_data = session()->get('reserve_target');

		// 画面表示用
		$ary_disp_data = [
			'hope_date'		=> date('Y/m/d', strtotime($ary_reserve_data['target_date'])) . '(' . config('const.week.' . date('w', strtotime($ary_reserve_data['target_date']))) . ')',
			'hope_time'		=> date('H時i分', strtotime(config('const.hope_time.' . $ary_reserve_data['target_time_cd']))),
			'target_shop'	=> $this->ary_shop[$ary_reserve_data['target_shop_id']]['name'],
		];

		// 不要なセッションを削除する
		session()->forget('reserve_target');
		session()->forget('new_reserve_request');
		session()->forget('target_contract_id');
		session()->forget('target_course_type');

		return view('mypage.reserve.complete',
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
		if (session()->exists('new_reserve_request'))
		{
			$ary_shop_list['session_search_shop'] = session()->get('new_reserve_request.search_shop');

			// リクエストデータを削除
			session()->forget('new_reserve_request');
		}

		return $ary_shop_list;
	}

	/**
	 * メール送信処理(脱毛用）
	 *
	 * @param	array	$ary_param	予約情報
	 */
	private function sendMailNewReserveHairLoss($ary_param)
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
			'{target_shop_address}'	=>config('const.pref_cd.' . $this->ary_shop[$ary_param['shopId']]['pref']) . $this->ary_shop[$ary_param['shopId']]['address'],														// 店舗住所
			'{target_shop_url}'		=> config('env_const.kireimo_top') . 'salon/' . config('const.salon_area.' . $this->ary_shop[$ary_param['shopId']]['area']) . '/' . $this->ary_shop[$ary_param['shopId']]['url'] ,	// 店舗URL
			'{mypage_base_url}'		=> config('env_const.base_url'),																																					// マイページURL
			'{kireimo_url}'			=> config('env_const.kireimo_top'),																																					// キレイモトップ
			'{kireimo_info}'		=> config('env_const.info_address'),																																				// infoメールアドレス
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),																																			// キレイモ 電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),																																	// キレイモ 予約電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_new_treatment'));

		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// メールを送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $this->ary_customer['mail'],				// 送信先
			'fromAddress'	=> config('env_const.from_address'),		// 送信元
			'fromName'		=> config('const.from_name'),				// 送信元名
			'subject'		=> config('const.subject_new_treatment'),	// 件名
			'body'			=> $text,									// 本文
			'mimeType'		=> config('const.mime_type_plane'),			// 形式
		];

		$this->common_api->postApi($ary_reserve_data, 'single_mail');
	}

	/**
	 * メール送信処理(エステ用）
	 *
	 * @param	array	$ary_param	予約情報
	 */
	private function sendMailNewReserveEsthetic($ary_param)
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
			'{target_shop_address}'			=> config('const.pref_cd.' . $this->ary_shop[$ary_param['shopId']]['pref']) . $this->ary_shop[$ary_param['shopId']]['address'],														// 店舗住所
			'{target_shop_url}'				=> config('env_const.kireimo_top') . 'salon/' . config('const.salon_area.' . $this->ary_shop[$ary_param['shopId']]['area']) . '/' . $this->ary_shop[$ary_param['shopId']]['url'] ,	// 店舗URL
			'{mypage_base_url}'				=> config('env_const.base_url'),																																					// マイページURL
			'{kireimo_premium_url}'			=> config('env_const.kireimo_premium_top'),																																			// キレイモトップ
			'{kireimo_info_premium_mail}'	=> config('env_const.info_address_premium'),																																		// infoメールアドレス
			'{kireimo_premium_tel}'			=> config('env_const.kireimo_premium_tel_disp'),																																	// キレイモ 電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_new_treatment_esthetic'));

		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// メールを送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $this->ary_customer['mail'],						// 送信先
			'fromAddress'	=> config('env_const.from_address_premium'),		// 送信元
			'fromName'		=> config('const.from_name_premium'),				// 送信元名
			'subject'		=> config('const.subject_new_treatment_esthetic'),	// 件名
			'body'			=> $text,											// 本文
			'mimeType'		=> config('const.mime_type_plane'),					// 形式
		];

		$this->common_api->postApi($ary_reserve_data, 'single_mail');
	}
}
