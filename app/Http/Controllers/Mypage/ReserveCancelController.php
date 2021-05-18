<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;
use App\Library\CommonReserve;
use App\Library\CommonApi;
use PhpParser\Node\Stmt\Else_;

class ReserveCancelController extends Controller
{
	public $title			= 'KIREIMO ご予約のキャンセル';					// ページタイトル
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
	 * キャンセル確認画面
	 */
	public function index($contract_id, $reservation_id)
	{
		// セッションに入っている契約情報の有無
		$target_course_type = null;

		// 対象の契約形態を取得する
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

		// 対象の予約情報を取得
		$ary_target_data = $this->ary_reserve[$target_course_type][$reservation_id];

		// 対象の予約情報をセッションに入れる
		session()->put('cancel_target_reserve', $ary_target_data);

		// 画面表示用に編集
		$ary_disp_data = [];
		$ary_disp_data['target_date']	= date('Y/m/d', strtotime($ary_target_data['hopeDate'])) . '(' . $this->ary_week[date('w', strtotime($ary_target_data['hopeDate']))] .')';
		$ary_disp_data['target_time']	= date('H時i分', strtotime(config('const.hope_time.' . $ary_target_data['hopeTimeCd'])));
		$ary_disp_data['target_shop']	= $ary_target_data['shopName'];

		// ボタン押下時の判定
		$ary_disp_data['message_flg'] = false; // true:モーダル表示対象 false:モーダル非表示

		if ($target_course_type == config('const.treatment_type_name_eng.0'))
		{
			if ($this->ary_contract[$target_course_type][$contract_id]['courseType'] == 0)
			{
				// パック・無制限プランの場合
				// キャンセル日と予約日が同じ場合
				if (strtotime(date('Y-m-d')) == strtotime($ary_target_data['hopeDate']))
				{
					$ary_disp_data['message_flg'] = true;
				}

				$ary_disp_data['course_type'] = 0;
			}
			else
			{
				// 月額
				$ary_disp_data['message'] = CommonReserve::checkDay($ary_target_data['hopeDate']);

				if (!empty($ary_disp_data['message']))
				{
					$ary_disp_data['message_flg'] = true;
				}

				$ary_disp_data['course_type'] = 1;
			}
		}
		else
		{
			// エステ
			if (strtotime(date('Y-m-d')) == strtotime($ary_target_data['hopeDate']))
			{
				$ary_disp_data['message_flg'] = true;
			}

			$ary_disp_data['course_type'] = 0;
		}

		// セッションに対象の契約タイプを入れる
		session()->put('target_course_type', $target_course_type);

		// セッションに契約IDを入れる
		session()->put('target_contract_id', $contract_id);

		return view('mypage.cancel.index',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
				'ary_disp_data'			=> $ary_disp_data,		// 画面表示項目
			]
			);
	}

	/**
	 * キャンセル処理
	 */
	public function process()
	{
		// セッションからキャンセル対象の予約を取り出す
		if (!session()->exists('cancel_target_reserve'))
		{
			// トップへリダイレクト
			return redirect()->to('/top');
		}

		$ary_target_data = session()->get('cancel_target_reserve');

		$ary_result = $this->common_api->putApi($ary_target_data['reservationId'], 'reservation_data', 1);

		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// エラー処理
			// 不要なセッションを削除する
			session()->forget('cancel_target_reserve');

			// エラー画面へ遷移
			return view('mypage.error.error',
				[
					'title'				=> $this->title,									// タイトル
					'ary_customer'		=> $this->ary_customer,								// お客様情報
					'page_title'		=> $this->title,									// ページタイトル
					'error_category'	=> config('error_const.cancel_reserve_api_error'),	// エラーカテゴリ
					'body'				=> config('msg.A1007'),								// 本文
				]
				);
		}

		// 施術対象を取得
		$target_course_type = session()->get('target_course_type');

		// 対象の契約IDを取得
		$target_contract_id = session()->get('target_contract_id');

		// コース名をパラメータに追加
		$ary_target_data['course_name'] = $this->ary_contract[$target_course_type][$target_contract_id]['courseName'];

		// メール送信
		if ($target_course_type == config('const.treatment_type_name_eng.0'))
		{
			// 脱毛
			$this->sendMailCancelReserveHairloss($ary_target_data);
		}
		else
		{
			// エステ
			$this->sendMailCancelReserveEsthetic($ary_target_data);
		}

		return redirect()->to('/cancel/complete');
	}

	/**
	 * キャンセル完了画面
	 */
	public function complete()
	{
		if (!session()->exists('cancel_target_reserve'))
		{
			// 予約情報が存在しない場合はトップにリダイレクト
			return redirect()->to('/top');
		}

		// 予約情報をセッションから取得する
		$ary_reserve_data = session()->get('cancel_target_reserve');

		// 画面表示用
		$ary_disp_data = [
			'hope_date'		=> date('Y/m/d', strtotime($ary_reserve_data['hopeDate'])) . '(' . $this->ary_week[date('w', strtotime($ary_reserve_data['hopeDate']))] . ')',
			'hope_time'		=> date('H時i分', strtotime(config('const.hope_time.' . $ary_reserve_data['hopeTimeCd']))),
			'target_shop'	=> $ary_reserve_data['shopName'],
		];

		// 不要なセッションを削除する
		session()->forget('cancel_target_reserve');
		session()->forget('target_course_type');
		session()->forget('target_contract_id');

		return view('mypage.cancel.complete',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
				'ary_disp_data'	=> $ary_disp_data,		// 画面表示用
			]
			);
	}

	/**
	 * メール送信処理(脱毛)
	 *
	 * @param	array	$ary_param	予約情報
	 */
	private function sendMailCancelReserveHairloss($ary_param)
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
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),																																			// kierimo電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),																																	// キレイモ 予約電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_cancel_treatment'));

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
			'subject'		=> config('const.subject_cancel_treatment'),	// 件名
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
	private function sendMailCancelReserveEsthetic($ary_param)
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
		$text = file_get_contents(config('const.body_cancel_treatment_esthetic'));

		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// メールを送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $this->ary_customer['mail'],							// 送信先
			'fromAddress'	=> config('env_const.info_address_premium'),			// 送信元
			'fromName'		=> config('const.from_name_premium'),					// 送信元名
			'subject'		=> config('const.subject_cancel_treatment_esthetic'),	// 件名
			'body'			=> $text,												// 本文
			'mimeType'		=> config('const.mime_type_plane'),						// 形式
		];

		$this->common_api->postApi($ary_reserve_data, 'single_mail');
	}
}
