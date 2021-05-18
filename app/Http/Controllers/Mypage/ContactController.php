<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Library\CommonApi;
use Illuminate\Http\Request;

class ContactController extends Controller
{
	public $title			= 'KIREIMO お問い合わせ';	// ページタイトル
	public $ary_customer	= [];					// お客様情報格納用
	public $common_api;								// api接続用インスタンス

	public function __construct()
	{
		Common::loginCheck();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	/**
	 * お問合わせトップ
	 */
	public function index()
	{
		return view('mypage.contact.index',
			[
				'title'				=> $this->title,
				'ary_customer'		=> $this->ary_customer,
			]
		);
	}

	/**
	 * お問合わせ処理
	 */
	public function process(Request $request)
	{
		// リクエストを取り出す
		$ary_request	= $request->all();
		$email_address	= $this->ary_customer['mail'];

		//リクエストチェック
		if (empty($ary_request) || !$this->requestCheck($ary_request))
		{
			// リクエストが存在しないもしくはバリデーションエラーの場合エラーページへ
			return redirect()->to('mypageTop');
		}

		//メールアドレスを登録している契約者の場合
		if (!empty($email_address))
		{

			// 契約者にお問い合わせ受付完了メール送信
			if ($ary_request['contract_type'] == config('const.contact_flg.normal'))
			{
				// 脱毛の場合
				$result_for_user = $this->sendMailToUserHairLoss($ary_request, $email_address);
			}
			else
			{
				// エステ・整体の場合
				$result_for_user = $this->sendMailToUserPremium($ary_request, $email_address);
			}


			//ユーザーに送信成功の場合
			if ($result_for_user['apiStatus'] == config('api.api_status_code.SUCCESS'))
			{
				// api取得結果をセッションに入れる
				session()->put(['contact_result' => $ary_request['contract_type']]);
				$subject = config('const.subject_contact_mail_for_staff').$ary_request['contact_type'];

				//スタッフにお問い合わせ内容メール送信
				$result_for_staff = $this->sendMailToStaff($ary_request, $subject, $ary_request['contract_type']);

				//スタッフに送信成功の場合
				if ($result_for_staff['apiStatus'] == config('api.api_status_code.SUCCESS'))
				{
					// お問い合わせ完了画面に遷移
					return redirect()->route('mypageContactComplete');
				}
				else
				{
					// エラー画面に遷移
					return view('mypage.error.error',
						[
							'title'				=> $this->title,						// タイトル
							'ary_customer'		=> $this->ary_customer,					// お客様情報
							'page_title'		=> $this->title,						// ページタイトル
							'error_category'	=> config('error_const.contact_error'),	// エラーカテゴリ
							'body'				=> config('msg.A1008'),					// 本文
						]
						);
				}
			}
			// ユーザーに送信失敗の場合
			else
			{
				/// エラー画面に遷移
				return view('mypage.error.error',
					[
						'title'				=> $this->title,						// タイトル
						'ary_customer'		=> $this->ary_customer,					// お客様情報
						'page_title'		=> $this->title,						// ページタイトル
						'error_category'	=> config('error_const.contact_error'),	// エラーカテゴリ
						'body'				=> config('msg.A1008'),					// 本文
					]
					);
			}
		}
		else
		{
			// メールアドレスを登録していない契約者の場合
			$subject = config('const.subject_contact_mail_no_address').$ary_request['contact_type'];
			// スタッフにお問い合わせ内容メール送信
			$result_for_staff = $this->sendMailToStaff($ary_request, $subject, $ary_request['contract_type']);

			// スタッフに送信成功の場合
			if ($result_for_staff['apiStatus'] == config('api.api_status_code.SUCCESS'))
			{
				// api取得結果をセッションに入れる
				session()->put(['contact_result' => $ary_request['contract_type']]);
				// お問い合わせ完了画面に遷移
				return redirect()->route('mypageContactComplete');
			}
			else
			{
				// エラー画面に遷移
				return view('mypage.error.error',
					[
						'title'				=> $this->title,						// タイトル
						'ary_customer'		=> $this->ary_customer,					// お客様情報
						'page_title'		=> $this->title,						// ページタイトル
						'error_category'	=> config('error_const.contact_error'),	// エラーカテゴリ
						'body'				=> config('msg.A1008'),					// 本文
					]
					);
			}
		}
	}

	/**
	 * リクエストチェック
	 */
	public function requestCheck($ary_request)
	{
		// 問い合わせ内容空白チェック
		if (isset($ary_request['contact_input']))
		{
			// 問い合わせカテゴリチェック
			if (in_array($ary_request['contact_type'], config('const.contact_types')))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * 契約者へメール送信(脱毛)
	 */
	public function sendMailToUserHairLoss($ary_request, $email_address)
	{
		// 本文文字列置き換え
		$ary_replace = [
			'{name}'				=> $this->ary_customer['nameKana1'] . ' ' . $this->ary_customer['nameKana2'],	// 名前
			'{mycode}'				=> $this->ary_customer['customerNo'],											// お客様番号
			'{contactCategory}'		=> $ary_request['contact_type'],												// お問い合わせカテゴリ
			'{contactText}'			=> $ary_request['contact_input'],												// お問い合わせ内容
			'{base_url}'			=> config('env_const.base_url'),												// マイページ
			'{kireimo_url}'			=> config('env_const.kireimo_top'),												// kireimo
			'{kireimo_info}'		=> config('env_const.info_address'),											// infoアドレス
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),										// キレイモ 電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),								// キレイモ 電話番号(予約)
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_contact_mail'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		$ary_reserve_data = [
			'toAddress'		=> $email_address,							// 送信先
			'fromAddress'	=> config('env_const.from_address'),		// 送信元アドレス
			'fromName'		=> config('const.from_name'),				// 送信元名称
			'subject'		=> config('const.subject_contact_mail'),	// 件名
			'body'			=> $text,									// 本文
			'mimeType'		=> config('const.mime_type_plane')			// メール形式
		];

		// メール送信実行
		return $this->common_api->postApi($ary_reserve_data, 'single_mail');

	}

	/**
	 * 契約者へメール送信(エステ・整体)
	 */
	public function sendMailToUserPremium($ary_request, $email_address)
	{
		// 本文文字列置き換え
		$ary_replace = [
			'{name}'						=> $this->ary_customer['nameKana1'] . ' ' . $this->ary_customer['nameKana2'],	// 名前
			'{mycode}'						=> $this->ary_customer['customerNo'],											// お客様番号
			'{contactCategory}'				=> $ary_request['contact_type'],												// お問い合わせカテゴリ
			'{contactText}'					=> $ary_request['contact_input'],												// お問い合わせ内容
			'{base_url}'					=> config('env_const.base_url'),												// マイページ
			'{kireimo_premium_url}'			=> config('env_const.kireimo_premium_top'),										// kireimo premium
			'{kireimo_info_premium_mail}'	=> config('env_const.info_address_premium'),									// infoアドレス
			'{kireimo_premium_tel}'			=> config('env_const.kireimo_premium_tel_disp'),								// キレイモ 電話番号
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_contact_mail_premium'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		$ary_reserve_data = [
			'toAddress'		=> $email_address,									// 送信先
			'fromAddress'	=> config('env_const.from_address_premium'),		// 送信元アドレス
			'fromName'		=> config('const.from_name_premium'),				// 送信元名称
			'subject'		=> config('const.subject_contact_mail_premium'),	// 件名
			'body'			=> $text,											// 本文
			'mimeType'		=> config('const.mime_type_plane')					// メール形式
		];

		// メール送信実行
		return $this->common_api->postApi($ary_reserve_data, 'single_mail');

	}

	/**
	 * スタッフにメール送信
	 */
	public function sendMailToStaff($ary_request, $subject, $contract_type)
	{
		// IPアドレス対応
		$ip_address = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			// ロードバランサー経由
			$ip_address = explode(':', $_SERVER["HTTP_X_FORWARDED_FOR"])[0];
		}
		else if (!empty($_SERVER['REMOTE_ADDR']))
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		// 本文文字列置き換え
		$ary_replace = [
			'{name}'			=>  $this->ary_customer['nameKana1'] . ' ' . $this->ary_customer['nameKana2'],			// 名前(カナ)
			'{mycode}'			=> $this->ary_customer['customerNo'],													// お客様番号
			'{contactCategory}'	=> $ary_request['contact_type'],														// お問い合わせカテゴリ
			'{contactText}'		=> $ary_request['contact_input'],														// お問い合わせ内容
			'{IPAddress}'		=> $ip_address,																			// IPアドレス(HTTP_X_FORWARDED_FOR:ロードバランサー経由)
			'{browser}'			=> request()->server('HTTP_USER_AGENT')													// ブラウザ情報
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_contact_mail_for_staff'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		$ary_reserve_data = [
			'toAddress'		=> $contract_type == config('const.contact_flg.normal') ? config('env_const.info_address') : config('env_const.info_address_premium'),	// 送信先
			'fromAddress'	=> $contract_type == config('const.contact_flg.normal') ? config('env_const.from_address') : config('env_const.from_address_premium'),	// 送信元アドレス
			'fromName'		=> $contract_type == config('const.contact_flg.normal') ? config('const.from_name') : config('const.from_name_premium'),				// 送信元名称
			'subject'		=> $subject,																															// 件名
			'body'			=> $text,																																// 本文
			'mimeType'		=> config('const.mime_type_plane'),																										// メール形式
		];
		// メール送信実行
		return $this->common_api->postApi($ary_reserve_data, 'single_mail');
	}

	/**
	 * お問い合わせ完了
	 */
	public function complete()
	{
		if (!session()->exists('contact_result')) {
			return redirect()->to('/');
		}

		$contract_type = session()->get('contact_result');

		// セッションを削除
		session()->forget('contact_result');

		return view('mypage.contact.complete',
			[
				'title'			=> $this->title,		// タイトル
				'ary_customer'	=> $this->ary_customer,	// お客様情報
				'contract_type'	=> $contract_type,		// 問い合わせタイプ
			]
		);
	}

}
