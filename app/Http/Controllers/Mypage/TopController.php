<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Library\CommonApi;
use Illuminate\Http\Request;
use App\Models\Reservation;

use Illuminate\Support\Facades\Cookie;


class TopController extends Controller
{
	public $title			= 'KIREIMO マイページトップ';	// ページタイトル
	public $ary_customer	= [];						// お客様情報格納用
	public $ary_contract	= [];						// 契約情報格納用
	public $ary_reserve		= [];						// 予約情報格納用
	public $common_api;									// api接続用インスタンス

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// 不要セッションの削除
		session()->forget('change_reserve_booking');
		session()->forget('change_target_reserve');
		session()->forget('change_reserve_error_msg');
		session()->forget('change_reserve_request');
		session()->forget('change_reserve_data');

		session()->forget('target_contract_id');
		session()->forget('target_course_type');
		session()->forget('new_reserve_error_msg');
		session()->forget('new_reserve_request');
		session()->forget('reserve_target');
		session()->forget('new_reserve_booking');

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();

		// お客様情報取得
		Common::getCustomer($this->common_api);
		if (session()->exists('customer'))
		{
			$this->ary_customer = session()->get('customer');
		}
		else
		{
			// ログインチェック
			Common::loginCheck();
		}


		// 契約情報を取得
		$this->createContract();
		$this->ary_contract = session()->get('contract');

		// 予約情報を取得
		$this->ary_reserve = session()->get('reserve_data');
	}

	public function index()
	{
		return view('mypage.top.index',
			[
				'title'					=> $this->title,								// タイトル
				'ary_customer'			=> $this->ary_customer,							// お客様情報
				'ary_contract_data'		=> $this->ary_contract,							// 契約情報
				'ary_reserve_data'		=> $this->ary_reserve,							// 予約情報
			]
			);
	}

	public function lateContact(Request $request)
	{
		// リクエストを取得
		$ary_request = $request->all();

		// 対象契約
		$ary_target_contract = $this->ary_reserve[config('const.treatment_type_name_eng.' . $ary_request['target_contract_type'])][$ary_request['target_reservation_id']];

		// 登録時間（すでに時間が登録されていたら同じ時間で登録）
		$entry_time = $ary_target_contract['delayTimeRegDate'] == config('const.initial_time') ? date('Y-m-d H:i:s') : $ary_target_contract['delayTimeRegDate'];

		// reservationテーブルを更新
		$result = Reservation::where(
			[
				'id'			=> $ary_request['target_reservation_id'],
				'customer_id'	=> $this->ary_customer['id']
			])
			->update(
				[
					'delay_time_status'		=> $ary_request['delayTime'],
					'delay_time_reg_date'	=> $entry_time
				]
				);

			if ($result)
			{
				// 更新成功
				session()->put('delay_data', 1);
				return redirect()->to('/latecontract/complete');
			}
			else
			{
				// 更新失敗
				session()->put('error_category', 'delay');
				return redirect()->to('/error');
			}

	}

	public function lateContactComplete()
	{
		// セッションを取得
		if (!session()->exists('delay_data'))
		{
			// セッションが存在しない場合はtopへリダイレクト
			return redirect()->to('/');
		}

		// セッションを削除
		session()->forget('delay_data');

		return view('mypage.top.late_contact_complete',
			[
				'title'					=> $this->title,								// タイトル
				'ary_customer'			=> $this->ary_customer,							// お客様情報
			]
			);
	}

	/**
	 * 契約情報・予約情報をセッションに入れる
	 */
	private function createContract()
	{
		// 契約情報編集後格納先
		$ary_edit_contract = [];

		// 契約情報を取得
		$ary_contract = $this->getContract();

		if (empty($ary_contract))
		{
			// 取得結果が空の場合
			session()->put('contract_abnormal_flg', config('const.contract_abnormal.end'));
			session()->put('contract', []);
			session()->put('reserve_data', []);
			return false;
		}

		// 契約情報を画面表示用に編集
		$ary_contract = $this->checkPlan($ary_contract);

		// contractIdごとに配列に入れる
		foreach ($ary_contract as $val)
		{
			$ary_edit_contract[$val['contractId']] = $val;
		}

		// コンディションチェック
		$ary_edit_contract_condition = $this->checkCondition($ary_edit_contract);

		// 契約終了のデータを除外する
		$ary_edit_contract_condition = $this->deleteEndContract($ary_edit_contract_condition);

		if (empty($ary_edit_contract_condition))
		{
			// 取得結果が空の場合
			session()->put('contract_abnormal_flg', config('const.contract_abnormal.end'));
			session()->put('contract', []);
			session()->put('reserve_data', []);
			return false;
		}

		// 予約情報を取得
		$ary_edit_contract_reserve = $this->getReservation($ary_edit_contract);

		// セッションに予約情報を格納
		session()->put('reserve_data', $ary_edit_contract_reserve);

		// 回数チェック
		//$ary_edit_contract_condition = $this->rTimesCheck($ary_edit_contract_condition);

		// 契約を脱毛・エステ・整体に分ける
		$ary_edit_contract = $this->typeSorting($ary_edit_contract_condition);

		// 契約に対する予約数を算出
		$ary_edit_contract = $this->getReserveCount($ary_edit_contract, $ary_edit_contract_reserve);

		// 契約に異常があるかなどをチェック
		$contract_abnormal_flg = $this->contractCheck($ary_edit_contract);

		session()->put('contract_abnormal_flg', $contract_abnormal_flg);

		session()->put('contract', $ary_edit_contract);
	}

	/**
	 * 契約情報を取得
	 */
	private function getContract()
	{
		// 契約情報を取得
		$ary_contract = $this->common_api->getApi($this->ary_customer['id'] . '/contracts', 'customer', 1);

		if ($ary_contract['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// 契約が存在しない
			return [];
		}

		// 契約終了、返金保証期間終了は一旦除外
		$ary_contract_edit = [];
		foreach ($ary_contract['body'] as $key => $val)
		{
			if ($val['status'] == config('const.contract_status.under_contract'))
			{
				$ary_contract_edit[$key] = $val;
			}
		}

		return $ary_contract_edit;
	}

	/**
	 * 予約情報を取得
	 */
	private function getReservation($ary_contract)
	{
		// 予約情報を取得
		$ary_reserve_info = [];
		$ary_reserve_info = $this->common_api->getApi($this->ary_customer['id'] . '/reservations', 'customer', 1);

		// 予約情報格納用
		$ary_reserve_data = [];

		if ($ary_reserve_info['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// 予約情報がが存在しない
			return [];
		}
		else
		{
			foreach ($ary_reserve_info['body'] as $ary_val)
			{
				if (!empty($ary_contract[$ary_val['contractId']]))
				{
					$treatment_type = null;

					// 配列名の決定
					$treatment_type = config('const.treatment_type_name_eng.' . $ary_contract[$ary_val['contractId']]['courseTreatmentType']);

					$ary_reserve_data[$treatment_type][$ary_val['reservationId']] = $ary_val;
				}
			}
		}

		return $ary_reserve_data;
	}

	/**
	 * コンディションチェック
	 */
	private function checkCondition($ary_contract)
	{
		// コンディションチェック
		foreach ($ary_contract as $key => $ary_contract_val)
		{

			// 契約コンディションチェック
			$ary_condition_result = $this->common_api->getApi('conditionCheck/' . $ary_contract_val['contractId'], 'customer', 1);

			// 取得結果のチェック
			if ($ary_condition_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
			{
				return $ary_contract;
			}

			// つなぎの場合はクーリングオフエラー出さない
			if (!empty($ary_condition_result['body']['failedConditions']))
			{
				$tmp_key_cooling	= null;		// クーリングオフキー格納用
				$tunagi_flg			= false;	// つなぎフラグ初期値

				foreach ($ary_condition_result['body']['failedConditions'] as $key_tmp => $failedConditions)
				{
					// クーリングオフがある場合キーを保存
					if ($failedConditions == 'COOLING_OFF_TIME')
					{
						$tmp_key_cooling = $key_tmp;
					}

					// つなぎの場合はフラグを持たせる
					if ($failedConditions == 'BAD_CONVERSION_CONDITION')
					{
						$tunagi_flg = true;
					}
				}

				// つなぎの場合はクーリングオフのメッセージを除外
				if ($tunagi_flg)
				{
					unset($ary_condition_result['body']['failedConditions'][$tmp_key_cooling]);
				}
			}

			// コンディションを配列に入れる
			$ary_contract[$key]['condition'] = $ary_condition_result['body'];
		}

		return $ary_contract;
	}

	/**
	 * プラン判定/表示内容編集
	 */
	private function checkPlan($ary_contract)
	{
		// データ格納用
		$ary_edit_data = [];

		foreach($ary_contract as $key => $val)
		{
			if ($val['courseTreatmentType'] == config('const.treatment_type.hair_loss'))
			{
				// 脱毛の場合
				$ary_edit_data[$key]				= $val;
				$ary_edit_data[$key]['plan_type']	= Common::contractHairLossJuge($val);

				// 画面表示内容設定
				$ary_edit_data[$key]['disp_data'] = $this->contractDispEditHairLoss($ary_edit_data[$key]);
			}
			else if ($val['courseTreatmentType'] == config('const.treatment_type.esthetic'))
			{
				// エステの場合
				$ary_edit_data[$key]				= $val;
				$ary_edit_data[$key]['plan_type']	= Common::contractEstheticJuge($val);

				// 画面表示内容設定
				$ary_edit_data[$key]['disp_data'] = $this->contractDispEditEsthetic($ary_edit_data[$key]);
			}
			else
			{
				// 整体の場合
				$ary_edit_data[$key]				= $val;
				$ary_edit_data[$key]['plan_type']	= Common::contractManipulativeJuge($val);

				// 画面表示内容設定
				$ary_edit_data[$key]['disp_data'] = $this->contractDispEditManipulative($ary_edit_data[$key]);
			}
		}

		return $ary_edit_data;
	}

	/**
	 * 画面表示情報に編集（脱毛）
	 */
	private function contractDispEditHairLoss($ary_data)
	{
		// 表示内容格納用
		$ary_disp_data = [];

		switch ($ary_data['plan_type'])
		{
			case config('const.hair_loss_plan.old_pack') :
				// 旧パック（1)
				$ary_disp_data['refund_warranty_period_title']	= 'サービス提供期間 : ';																							// サービス提供期間見出し
				$ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																									// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// コース回数
				break;
			case config('const.hair_loss_plan.new_pack') :
				// 新パック（2）
				$ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																												// 返金保証期間見出し
				$ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));					// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																												// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['endDate'] . '+1 day')) . '~' . date('Y/n/j', strtotime($ary_data['extensionEndDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																														// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																											// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																													// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																											// コース回数
				break;
			case config('const.hair_loss_plan.new_pack_warranty_end') :
				// 新パック保証期間外（3）
				$ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																								// 返金保証期間見出し
				$ary_disp_data['refund_warranty_period']		= '終了';																										// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																								// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																									// プラン回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// プラン回数
				break;
			case config('const.hair_loss_plan.monthly') :
				// 月額（4）
				$ary_disp_data['count_progress_title']	= '進捗回数';				// 進捗回数見出し
				$ary_disp_data['count_progress']		= $ary_data['rTimes'];	// 回数
				break;
			case config('const.hair_loss_plan.old_sp') :
				// 旧通い放題（5）
				$ary_disp_data['count_warranty_period_title']	= 'サービス提供期間 : ';																			// サービス提供期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['endDate'] . '+1 day')) . '~';								// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																						// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																			// 進捗回数
				$ary_disp_data['count_course_title']			= '返金保証回数';																					// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																			// コース回数
				$ary_disp_data['attention']						= '※返金保証回数とは、中途解約する際に目処となる返金額を算定する際に必要となる消化単価を計算するための回数です。';	// 注意文言
				break;
			case config('const.hair_loss_plan.old_sp_warranty_end') :
				// 旧通い放題保証期間外（6）
				$ary_disp_data['refund_warranty_period_title']	= 'サービス提供期間 : ';											// サービス提供期間見出し
				$ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~';	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';														// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];											// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';													// プラン回数見出し
				$ary_disp_data['count_course']					= '∞';															// プラン回数
				break;
			case config('const.hair_loss_plan.new_sp') :
				// 新通い放題（7）
				$ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																								// 返金保証期間見出し
				$ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																								// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['endDate'] . '+1 day')) . '~';												// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= '返金保証回数';																									// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// コース回数
				$ary_disp_data['attention']						= '※返金保証回数とは、中途解約する際に目処となる返金額を算定する際に必要となる消化単価を計算するための回数です。';					// 注意文言
				break;
			case config('const.hair_loss_plan.new_sp_warranty_end') :
				// 新通い放題保証期間外（8）
				$ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';												// 返金保証期間見出し
				$ary_disp_data['refund_warranty_period']		= '終了';														// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';												// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~';	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';														// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];											// 回数
				$ary_disp_data['count_course_title']			= 'プラン回数';													// プラン回数見出し
				$ary_disp_data['count_course']					= '∞';															// プラン回数
				break;
			case config('const.hair_loss_plan.under_19') :
				// U-19(9)
				$ary_disp_data['start_tarm']			= date('Y/n', strtotime($ary_data['startYm'])) . ' ~ ' . date('Y/n', strtotime($ary_data['startYm'] . '+1 month'));	// 開始ターム
				$ary_disp_data['end_tarm']				= date('Y/n', strtotime($ary_data['endDate'] . '-1 month')) . ' ~ ' . date('Y/n', strtotime($ary_data['endDate']));	// 終了ターム
				$ary_disp_data['count_progress_title']	= '進捗回数';																											// 進捗回数見出し
				$ary_disp_data['count_progress']		= $ary_data['rTimes'];																								// 回数
			case config('const.hair_loss_plan.free_plan') :
				// 1回無料プラン(10)
				$ary_disp_data['count_warranty_period_title']	= 'サービス提供期間: ';																								// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																									// プラン回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// プラン回数
			default :
				break;
		}

		return $ary_disp_data;
	}

	/**
	 * 画面表示情報に編集（エステ）
	 */
	private function contractDispEditEsthetic($ary_data)
	{
		// 表示内容格納用
		$ary_disp_data = [];

		switch ($ary_data['plan_type'])
		{
			case config('const.esthetic_plan.pack') :
				// パック保証期間内
				// $ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																												// 返金保証期間見出し
				// $ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));					// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																												// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['endDate'] . '+1 day')) . '~' . date('Y/n/j', strtotime($ary_data['extensionEndDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																														// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																											// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																													// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																											// コース回数
				break;
			case config('const.esthetic_plan.pack_warranty_end') :
				// パック保証期間外
				// $ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																								// 返金保証期間見出し
				// $ary_disp_data['refund_warranty_period']		= '終了';																										// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																								// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																									// プラン回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// プラン回数
				break;
		}

		return $ary_disp_data;
	}

	/**
	 * 画面表示情報に編集（整体）
	 */
	private function contractDispEditManipulative($ary_data)
	{
		// 表示内容格納用
		$ary_disp_data = [];

		switch ($ary_data['plan_type'])
		{
			case config('const.manipulative_plan.pack') :
				// パック保証期間内
				// $ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																												// 返金保証期間見出し
				// $ary_disp_data['refund_warranty_period']		= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));					// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																												// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['endDate'] . '+1 day')) . '~' . date('Y/n/j', strtotime($ary_data['extensionEndDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																														// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																											// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																													// コース回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																											// コース回数
				break;
			case config('const.manipulative_plan.pack_warranty_end') :
				// パック保証期間外
				// $ary_disp_data['refund_warranty_period_title']	= '返金保証期間 : ';																								// 返金保証期間見出し
				// $ary_disp_data['refund_warranty_period']		= '終了';																										// 返金保証期間
				$ary_disp_data['count_warranty_period_title']	= '保証延長期間 : ';																								// 回数保証期間見出し
				$ary_disp_data['count_warranty_period']			= date('Y/n/j', strtotime($ary_data['contractDate'])) . '~' . date('Y/n/j', strtotime($ary_data['endDate']));	// 回数保証期間
				$ary_disp_data['count_progress_title']			= '進捗回数';																										// 進捗回数見出し
				$ary_disp_data['count_progress']				= $ary_data['rTimes'];																							// 進捗回数
				$ary_disp_data['count_course_title']			= 'プラン回数';																									// プラン回数見出し
				$ary_disp_data['count_course']					= $ary_data['times'];																							// プラン回数
				break;
		}

		return $ary_disp_data;
	}

	/**
	 * 脱毛・エステ・整体に分ける
	 */
	private function typeSorting($ary_data)
	{
		$ary_edit_data = [];

		foreach ($ary_data as $val)
		{
			$ary_edit_data[config('const.treatment_type_name_eng.' . $val['courseTreatmentType'])][$val['contractId']] = $val;
		}

		return $ary_edit_data;
	}

	/**
	 * 契約情報異常チェック
	 */
	private function contractCheck($ary_data)
	{
		// 初期値は正常値
		$contract_abnormal_flg = config('const.contract_abnormal.normal');

		if (empty($ary_data))
		{
			return  config('const.contract_abnormal.end');
		}

		// フラグ初期化
		$ary_contract_abnormal_flg = [
			'abnormal'		=> false,
			'payment_error'	=> false,
			'confirm'		=> false,
			'preparation'	=> false,
		];

		foreach ($ary_data as $val)
		{
			foreach ($val as $val2)
			{
				if (!empty($val2['condition']['failedConditions']))
				{
					foreach ($val2['condition']['failedConditions'] as $failed_conditions)
					{
						if (in_array($failed_conditions, config('msg.condition_flg_code.abnormal'), true))
						{
							// 契約異常値である
							$ary_contract_abnormal_flg['abnormal'] = true;
						}
						else if (in_array($failed_conditions, config('msg.condition_flg_code.payment_error'), true))
						{
							// 支払異常である
							$ary_contract_abnormal_flg['payment_error'] = true;
						}
						else if (in_array($failed_conditions, config('msg.condition_flg_code.confirm'), true))
						{
							// 確認事項あり
							$ary_contract_abnormal_flg['confirm'] = true;
						}
						else if (in_array($failed_conditions, config('msg.condition_flg_code.preparation'), true))
						{
							// マイページ準備中
							$ary_contract_abnormal_flg['preparation'] = true;
						}
					}
				}
			}
		}

		// 判定
		if ($ary_contract_abnormal_flg['abnormal'])
		{
			// 契約異常が存在する場合、契約異常フラグを立てる
			$contract_abnormal_flg = config('const.contract_abnormal.abnormal');
		}
		else if ($ary_contract_abnormal_flg['payment_error'])
		{
			// 支払異常が存在する場合、支払異常フラグを立てる
			$contract_abnormal_flg = config('const.contract_abnormal.payment_error');
		}
		else if ($ary_contract_abnormal_flg['confirm'])
		{
			// 確認事項が存在する場合、確認事項フラグを立てる
			$contract_abnormal_flg = config('const.contract_abnormal.confirm');
		}
		else if ($ary_contract_abnormal_flg['preparation'])
		{
			// マイページ準備中の場合、マイページ準備中フラグを立てる
			$contract_abnormal_flg = config('const.contract_abnormal.preparation');
		}

		return $contract_abnormal_flg;
	}

	/**
	 * 契約に対する予約数を算出
	 */
	private function getReserveCount($ary_contract, $ary_reserve)
	{
		foreach ($ary_contract as $target_reserve_type => $val_contract)
		{
			foreach ($val_contract as $contract_id => $val_contract2)
			{
				// 個数カウント用
				$rsv_cnt = 0;

				if (!empty($ary_reserve[$target_reserve_type]))
				{
					foreach ($ary_reserve[$target_reserve_type] as $val_reserve)
					{
						if ($contract_id == $val_reserve['contractId'])
						{
							$rsv_cnt++;
						}
					}
				}

				// 契約配列に入れる
				$ary_contract[$target_reserve_type][$contract_id]['reserve_count'] = $rsv_cnt;
			}
		}

		return $ary_contract;
	}

	/**
	 * 契約が終了しているデータは除外する
	 * @access	private
	 * @param	array	$ary_data
	 * @return	array
	 */
	private function deleteEndContract($ary_data)
	{
		foreach ($ary_data as $key => $val)
		{
			if (!empty($val['condition']['failedConditions']))
			{
				foreach ($val['condition']['failedConditions'] as $failed_conditions)
				{
					if ($failed_conditions == 'CONVERSION_TIMES_OVER' || $failed_conditions == 'CONVERSION_PERIOD_OVER')
					{
						unset($ary_data[$key]);
						break;
					}
				}
			}
		}

		return $ary_data;
	}

	/**
	 * rTimesが最大回数-1であるかチェック
	 */
	/*
	 private function rTimesCheck($ary_data)
	 {
	 foreach ($ary_data as $key => $val)
	 {
	 $remaining_times = $val['times'] - $val['rTimes'];

	 // times - rtimes = 1の場合はtrue
	 $ary_data[$key]['remaining_flg'] = $remaining_times == 1 ? true : false;
	 }

	 return $ary_data;
	 }
	 */
}
