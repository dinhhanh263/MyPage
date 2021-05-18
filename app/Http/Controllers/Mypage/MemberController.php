<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Models\Bank;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use App\Library\CommonApi;

class MemberController extends Controller
{
	public $title			= 'KIREIMO 会員情報';		// ページタイトル
	public $ary_customer	= [];					// お客様情報
	public $ary_bank		= [						// 口座関連
		'bank_name'			=> '銀行名',
		'bank_branch'		=> '支店名',
		'bank_account_type'	=> '口座種別',
		'bank_account_no'	=> '口座番号',
		'bank_account_name'	=> '口座名義',
	];
	public $common_api;								// api接続用インスタンス

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// お客様情報をセッションから取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	/**
	 * お客様情報トップ
	 */
	public function index()
	{
		// セッション情報を取得(confirm)
		$ary_session_confirm = [];

		// セッション情報を取得(bank)
		$ary_validate_bank_result = [];

		if (session()->has('member_confirm'))
		{
			// confirmから戻ってきた場合
			$ary_session_confirm = session()->get('member_confirm');

			// エラーは削除する
			session()->forget('member_confirm');
		}
		else if (session()->has('ary_validate_bank_result'))
		{
			// 口座登録から戻ってきた場合
			$ary_validate_bank_result = session()->get('ary_validate_bank_result');

			// 不要なセッションは削除する
			session()->forget('ary_validate_bank_result');
		}

		// お客様情報表示用
		$ary_customer_edit = array();

		// 郵便番号を二つに分割する
		$ary_postcode = [];
		$ary_postcode = explode('-', $this->ary_customer['zip']);

		$ary_customer_edit['postcode1'] = empty($ary_postcode[0]) ? '' : $ary_postcode[0];
		$ary_customer_edit['postcode2'] = empty($ary_postcode[1]) ? '' : $ary_postcode[1];

		// 電話番号を三分割する
		$ary_tel = [];
		$ary_tel = explode('-', $this->ary_customer['tel']);

		$ary_customer_edit['tel1'] = empty($ary_tel[0]) ? '' : $ary_tel[0];
		$ary_customer_edit['tel2'] = empty($ary_tel[1]) ? '' : $ary_tel[1];
		$ary_customer_edit['tel3'] = empty($ary_tel[2]) ? '' : $ary_tel[2];

		// 口座情報取得用
		$ary_bank_info = [];
		$ary_bank_info = $this->getBankInfo();
		$ary_bank_info = empty($ary_bank_info) ? [] : $ary_bank_info[0];

		return view('mypage.member.index',
			[
				'title'						=> $this->title,				// タイトル
				'ary_customer'				=> $this->ary_customer,			// お客様情報
				'ary_customer_edit'			=> $ary_customer_edit,			// 編集後お客様情報
				'ary_bank_info'				=> $ary_bank_info,				// 口座情報取得
				'ary_session_confirm'		=> $ary_session_confirm,		// confirmでバリデーションエラーの場合の情報
				'ary_validate_bank_result'	=> $ary_validate_bank_result,	// 口座情報登録セッション
			]
			);
	}

	/**
	 * お客様情報更新確認
	 */
	public function confirm(Request $request)
	{
		if (session()->exists('update_customer_error_msg'))
		{
			// apiエラーで戻ってきた場合
			$ary_request = session()->get('member_confirm.request');

			// エラーをリクエストに入れ込む
			$ary_request['error_msg'] = session()->get('update_customer_error_msg');

			// セッション削除
			session()->forget('update_customer_error_msg');
		}
		else
		{
			// リクエストを取得
			$ary_request = $request->all();

			// 何も入っていない場合はマイページトップへリダイレクト
			if (empty($ary_request))
			{
				return Redirect::to('/top')->send();
			}

			// バリデーションチェック
			$ary_validate_result = $this->validateCheckCustomer($ary_request);

			// セッションにリクエスト値を追加
			session()->put('member_confirm.request', $ary_request);

			if (!empty($ary_validate_result))
			{
				// エラー内容とリクエスト内容をセッションに格納する
				session()->push('member_confirm.validate', $ary_validate_result);

				// 元の画面に戻る
				return Redirect::to('/member')->send();
			}
		}

		return view('mypage.member.confirm',
			[
				'title'				=> $this->title,		// タイトル
				'ary_customer'		=> $this->ary_customer,	// お客様情報
				'ary_request'		=> $ary_request,		// リクエスト値
			]
			);
	}

	/**
	 * お客様情報更新処理
	 */
	public function process()
	{
		// 更新対象をセッションから取り出す
		$ary_session_request = session()->get('member_confirm.request');

		// 更新用データを抜き出す
		$ary_update = [
			'tel'		=> $ary_session_request['tel1'] . '-' . $ary_session_request['tel2'] . '-' . $ary_session_request['tel3'],	// 電話番号
			'mail'		=> $ary_session_request['mail'],																			// メールアドレス
			'zip'		=> $ary_session_request['postcode1'] . '-' . $ary_session_request['postcode2'],								// 郵便番号
			'prefCd'	=> $ary_session_request['address1'],																		// 都道府県コード
			'address'	=> $ary_session_request['address2'],																		// 住所
		];

		$ary_result = $this->common_api->patchApi($this->ary_customer['id'], Cookie::get(session_name()), $ary_update, 'customer');

		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			session()->put('update_customer_error_msg', config('msg.A1002'));

			return Redirect::to('/member/confirm')->send();
		}

		// 成功した場合はcustomrを取得し直し、セッションを入れ直す
		$ary_customer = $this->common_api->getApi($this->ary_customer['id'], 'customer', 1);

		if ($ary_customer['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			session()->put('update_customer_error_msg', config('msg.A1003'));

			return Redirect::to('/member/confirm')->send();
		}

		// idも入れる
		$ary_customer['body']['id'] = $this->ary_customer['id'];

		session()->put('customer', $ary_customer['body']);

		// メールを送信する
		$ary_replace = [];
		$ary_replace = [
			'{name1}'				=> $this->ary_customer['name1'],
			'{name2}'				=> $this->ary_customer['name2'],
			'{no}'					=> $this->ary_customer['customerNo'],
			'{mypage_url}'			=> config('env_const.base_url'),
			'{kireimo_url}'			=> config('env_const.kireimo_top'),
			'{kireimo_info}'		=> config('env_const.info_address'),
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_member_update_mail'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		// apiから送信
		$ary_reserve_data = [];
		$ary_reserve_data = [
			'toAddress'		=> $ary_session_request['mail'],				// 送信先
			'fromAddress'	=> config('env_const.from_address'),			// 送信元
			'fromName'		=> config('const.from_name'),					// 送信元名称
			'subject'		=> config('const.subject_member_update_mail'),	// 件名
			'body'			=> $text,										// 本文
			'mimeType'		=> config('const.mime_type_plane'),				// メール形式
		];

		$ary_mail_result = $this->common_api->postApi($ary_reserve_data, 'single_mail');

		// 完了画面へ
		return Redirect::to('/member/complete')->send();
	}

	/**
	 * お客様情報更新完了
	 */
	public function complete()
	{
		if (!session()->exists('member_confirm.request'))
		{
			// セッションがなかったらマイページトップへ
			return Redirect::to('/')->send();
		}

		// セッションを削除
		session()->forget('member_confirm.request');

		return view('mypage.member.complete',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * 口座情報更新
	 */
	public function bankUpdate(Request $request)
	{
		// リクエスト値を取得
		$ary_request = $request->all();

		// バリデーションチェック
		$ary_validate_result = $this->validateCheckBank($ary_request);

		if (!empty($ary_validate_result))
		{
			session()->put('ary_validate_bank_result', $ary_validate_result);

			return Redirect::to('/member')->send();
		}

		if (array_key_exists('insert_button' , $ary_request))
		{
			// 登録処理
			$result = Bank::create(
				[
					'customer_id'		=> $this->ary_customer['id'],
					'bank_name'			=> $ary_request['bank_name'],
					'bank_branch'		=> $ary_request['bank_branch'],
					'bank_account_type'	=> $ary_request['bank_account_type'],
					'bank_account_no'	=> $ary_request['bank_account_no'],
					'bank_account_name'	=> $ary_request['bank_account_name'],
					'del_flg'			=> 0,
					'ng_flg'			=> 0,
					'status'			=> 0
				]
				);

			$update_flg = false;
		}
		else
		{
			// 更新処理
			$result = Bank::where(
				[
					'customer_id'	=> $this->ary_customer['id'],
					'del_flg'			=> 0,
				]
			)->update(
				[
					'bank_name'			=> $ary_request['bank_name'],
					'bank_branch'		=> $ary_request['bank_branch'],
					'bank_account_type'	=> $ary_request['bank_account_type'],
					'bank_account_no'	=> $ary_request['bank_account_no'],
					'bank_account_name'	=> $ary_request['bank_account_name'],
					'del_flg'			=> 0,
					'ng_flg'			=> 0,
					'status'			=> 0
				]
				);

			$update_flg = true;
		}

		if ($result)
		{
			// 成功した場合
			$ary_validate_result['success'] = $update_flg ? config('msg.S1002') : config('msg.S1001');

			session()->put('ary_validate_bank_result', $ary_validate_result);

			return Redirect::to('/member')->send();
		}
		else
		{
			$ary_validate_result['error'] = config('msg.A1004');

			session()->put('ary_validate_bank_result', $ary_validate_result);

			// 失敗した場合
			return Redirect::to('/member')->send();
		}
	}

	/**
	 * パスワード変更
	 */
	public function changePassword()
	{
		$ary_error_msg = [];

		if (session()->exists('password_change_msg'))
		{
			// セッションを取得
			$ary_error_msg = session()->get('password_change_msg');

			// セッションを削除
			session()->forget('password_change_msg');
		}

		return view('mypage.member.changePassword.index',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
				'ary_error_msg'	=> $ary_error_msg,		// エラーメッセージ
			]
			);
	}

	/**
	 * パスワード変更(処理)
	 */
	public function changePasswordProcess(Request $request)
	{
		// リクエストを取得
		$ary_request = $request->all();

		// バリデーションチェック
		$ary_validate_msg = $this->validateCheckPassword($ary_request);

		if (!empty($ary_validate_msg))
		{
			// セッションにメッセージを入れる
			session()->put('password_change_msg', $ary_validate_msg);

			// 変更画面へリダイレクト
			return redirect()->route('mypageChangePassword');
		}

		// DB更新(Customer)
		$result_customer_update = $this->updateCustomer($ary_request); // 戻り値：true or false

		// DB更新完了した場合
		if ($result_customer_update)
		{
			// メール送信
			$this->sendMailChangePass($this->ary_customer['mail']);

			// 更新完了フラグをセッションに入れる
			session()->put('password_change_complete', 1);

			// パスワード変更完了画面へ
			return redirect()->route('mypageChangePasswordComplete');
		}
		else
		{
			// パスワード変更失敗画面へ
			return view('mypage.error.error',
				[
					'title'				=> $this->title,									// タイトル
					'ary_customer'		=> $this->ary_customer,								// お客様情報
					'page_title'		=> config('error_const.change_pass_title'),			// ページタイトル
					'error_category'	=> config('error_const.change_pass_error_title'),	// エラーカテゴリ
					'body'				=> config('error_const.change_pass_error_body'),	// 本文
				]
				);
		}
	}

	/**
	 * パスワード変更完了
	 */
	public function changePasswordComplete()
	{
		// 完了フラグがあるか
		if (!session()->exists('password_change_complete'))
		{
			// 存在しない場合はマイページトップへリダイレクト
			return redirect()->route('mypageTop');
		}

		// セッション削除
		session()->forget('password_change_complete');

		return view('mypage.member.changePassword.complete',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * お客様情報入力値バリデーションチェック
	 */
	private function validateCheckCustomer($ary_request)
	{
		// バリデーション結果格納用
		$ary_validate_result = [];

		// 電話番号チェック
		if (empty($ary_request['tel1']) || empty($ary_request['tel2']) || empty($ary_request['tel3']))
		{
			// 未入力の場合
			$ary_validate_result['error']['tel'] = sprintf(config('msg.V1002'), '電話番号');
		}
		else
		{
			if (!preg_match('/^0\d{2,3}$/', $ary_request['tel1']))
			{
				$ary_validate_result['error']['tel'] = config('msg.V1003');
			}
			else if (!preg_match('/^\d{1,4}$/', $ary_request['tel2']))
			{
				$ary_validate_result['error']['tel'] = config('msg.V1003');
			}
			else if (!preg_match('/^\d{4}$/', $ary_request['tel3']))
			{
				$ary_validate_result['error']['tel'] = config('msg.V1003');
			}
		}

		// メールアドレスチェック
		if (empty($ary_request['mail']))
		{
			// 未入力の場合
			$ary_validate_result['error']['mail'] = sprintf(config('msg.V1002'), 'メールアドレス');
		}
		else
		{
			if (!preg_match('/^[a-zA-Z0-9-_\.+]+@[a-zA-Z0-9-_\.]+$/', $ary_request['mail']))
			{
				$ary_validate_result['error']['mail'] = config('msg.V1004');
			}
		}

		// 郵便番号チェック
		if (empty($ary_request['postcode1']) || empty($ary_request['postcode2']))
		{
			// 未入力の場合
			$ary_validate_result['error']['postcode'] = sprintf(config('msg.V1002'), '郵便番号');
		}
		else
		{
			if (!preg_match('/^\d{7}$/', $ary_request['postcode1'] . $ary_request['postcode2']))
			{
				$ary_validate_result['error']['postcode'] = config('msg.V1005');
			}
		}

		// 都道府県チェック
		if (empty($ary_request['address1']))
		{
			// 未入力の場合
			$ary_validate_result['error']['address1'] = sprintf(config('msg.V1002'), '都道府県');
		}

		// 都道府県に不正値
		if ($ary_request['address1'] < 0 || $ary_request['address1'] > 47)
		{
			// 不正値
			$ary_validate_result['error']['address1'] = sprintf(config('msg.V1024'), '都道府県');
		}

		// 住所チェック
		if (empty($ary_request['address2']))
		{
			// 未入力の場合
			$ary_validate_result['error']['address2'] = sprintf(config('msg.V1002'), '市区町村以降');
		}

		return $ary_validate_result;
	}

	/**
	 * 口座情報入力値バリデーションチェック
	 */
	private function validateCheckBank($ary_request)
	{
		// チェック結果格納用
		$ary_validate_result = [];

		// 空チェック
		foreach ($ary_request as $request_key => $request_val)
		{
			// 除外
			if ($request_key == 'insert_button' || $request_key == 'update_button')
			{
				continue;
			}

			if (empty($request_val))
			{
				// 一番最初の空の項目のみ取得
				$ary_validate_result['error'] = sprintf(config('msg.V1002'), $this->ary_bank[$request_key]);
				break;
			}
		}

		// 銀行名桁数
		if (empty($ary_validate_result) && mb_strlen($ary_request['bank_name']) > 64)
		{
			$ary_validate_result['error'] = sprintf(config('msg.V1025'), $this->ary_bank['bank_name'], 64);
		}

		// 支店名桁数
		if (empty($ary_validate_result) && mb_strlen($ary_request['bank_branch']) > 64)
		{
			$ary_validate_result['error'] = sprintf(config('msg.V1025'), $this->ary_bank['bank_branch'], 64);
		}

		// 口座種別
		if (empty($ary_validate_result) && !preg_match('/^[1-2]$/', $ary_request['bank_account_type']))
		{
			$ary_validate_result['error'] = config('msg.V1008');
		}

		// 口座番号
		if (empty($ary_validate_result) && !preg_match('/^[0-9]{7}$/', $ary_request['bank_account_no']))
		{
			$ary_validate_result['error'] = config('msg.V1009');
		}

		// 口座名義
		if (empty($ary_validate_result) && !preg_match('/^[ァ-ヶー　]+$/u', $ary_request['bank_account_name']))
		{
			$ary_validate_result['error'] = config('msg.V1010');
		}
		else if (empty($ary_validate_result) && mb_strlen($ary_request['bank_account_name']) > 64)
		{
			$ary_validate_result['error'] = sprintf(config('msg.V1025'), $this->ary_bank['bank_account_name'], 64);
		}

		return $ary_validate_result;
	}

	/**
	 * 口座情報取得
	 */
	private function getBankInfo()
	{
		return Bank::where(
			[
				'customer_id'	=> $this->ary_customer['id'],
				'del_flg'		=> 0
			]
			)
			->get()
			->toArray();
	}

	/**
	 * 新パスワードバリデーションチェック
	 */
	private function validateCheckPassword($ary_request)
	{
		// メッセージ格納
		$ary_message = [];

		// 現在のパスワード空チェック
		if (empty($ary_request['mypass']))
		{
			$ary_message['mypass'] = sprintf(config('msg.V1002'), 'パスワード');
		}

		// 現在のパスワードが正しいか
		if (empty($ary_message['mypass']))
		{
			$param = [
				'customerNo'	=> $this->ary_customer['customerNo'],
				'password'		=> $ary_request['mypass']
			];

			$ary_login_result = $this->common_api->postApi($param, 'mypage_auth');

			// 500 エラーが戻ってきた場合
			if ($ary_login_result['apiStatus'] == config('api.api_status_code.INTERNAL_SERVER_ERROR') )
			{
				$ary_message['mypass'] = config('msg.A1010');
			}
			else if($ary_login_result['apiStatus'] != config('api.api_status_code.SUCCESS') || empty($ary_login_result['body']['authResult']))
			{
				$ary_message['mypass'] = config('msg.V1011');
			}
		}

		// 新しいパスワードが入力されているか
		if (empty($ary_request['newpass']))
		{
			$ary_message['newpass'] = sprintf(config('msg.V1002'), '新しいパスワード');
		}

		// パスワード形式チェック
		if (empty($ary_message['newpass']) && !preg_match('/^[a-zA-Z0-9]+$/', $ary_request['newpass']))
		{
			$ary_message['newpass'] = config('msg.V1013');
		}

		// 新しいパスワード桁数チェック
		if (empty($ary_message['newpass']) && !(mb_strlen($ary_request['newpass']) >= config('const.password_count_min')))
		{
			$ary_message['newpass'] = config('msg.V1012');
		}

		// 新しいパスワード（確認用）未入力チェック
		if (empty($ary_request['newpass_confirmation']))
		{
			$ary_message['newpass_confirmation'] = sprintf(config('msg.V1002'), '新しいパスワード(確認用)');
		}

		// 新しいパスワード一致チェック
		if (empty($ary_message['newpass_confirmation']) && strcmp($ary_request['newpass'], $ary_request['newpass_confirmation']) != 0)
		{
			$ary_message['newpass_confirmation'] = config('msg.V1014');
		}

		return $ary_message;
	}

	/**
	 * DB更新
	 */
	private function updateCustomer($ary_request)
	{
		try{
			// Customer更新
			$result_update = Customer::where([
				'no'		=> $this->ary_customer['customerNo'],
				'del_flg'	=> 0
			])
			->first()
			->update([
				'password'	    => $ary_request['newpass'],
				'edit_date'     => Carbon::now()
			]);

			$result_update = true;
		}
		catch(\Exception $e)
		{
			$result_update = false;
		}

		return $result_update;
	}

	/**
	 * パスワード変更完了メール送信
	 */
	public function sendMailChangePass($email_address)
	{
		$name_kana = $this->ary_customer['nameKana1'] .' '. $this->ary_customer['nameKana2'];	//例)カイハツ ハナコ

		// 本文文字列置き換え
		$ary_replace = [
			'{name}'				=> $name_kana,										// 名前
			'{kireimo_url}'			=> config('env_const.kireimo_top'),					// kireimo
			'{kireimo_info}'		=> config('env_const.info_address'),				// infoアドレス
			'{mypage_url}'			=> config('env_const.base_url'),					// infoアドレス
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),			// キレイモ 電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),	// キレイモ 予約電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_change_password_mail'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		$ary_reserve_data = [
			'toAddress'		=> $email_address,									// 送信先
			'fromAddress'	=> config('env_const.from_address'),				// 送信元アドレス
			'fromName'		=> config('const.from_name'),						// 送信元名称
			'subject'		=> config('const.subject_change_password_mail'),	// 件名
			'body'			=> $text,											// 本文
			'mimeType'		=> config('const.mime_type_plane'),					// メール形式
		];

		// メール送信実行
		return $this->common_api->postApi($ary_reserve_data, 'single_mail');
	}
}
