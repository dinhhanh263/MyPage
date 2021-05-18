<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Library\CommonApi;
use App\Models\Customer;
use App\Models\TokenMng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class ResetController extends Controller
{
	public $title = 'KIREIMO パスワード再設定';	// ページタイトル
	public $common_api;							// api接続用インスタンス

	public function __construct(Request $request)
	{
		// ログインチェック
		if (Cookie::get(session_name()) && $request->session()->has('customer')) {
			// ログイン中
			Redirect::to('/top')->send();
		}

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	/**
	 * ①パスワード再設定、お客様コード確認トップ
	 */
	public function index()
	{
		$ary_error_message = [];

		if (session()->exists('reset_mail_message'))
		{
			// エラーメッセージを取り出す
			$ary_error_message = session()->get('reset_mail_message');

			// セッションを削除する
			session()->forget('reset_mail_message');
		}

		// 生年月日用
		$birth_year_start	= date('Y', strtotime('-100 year'));
		$birth_year_end		= date('Y', strtotime('-16 year'));
		$lastday = date( 't' , strtotime($birth_year_end . '/01/01'));

		return view('login.reset.index',
			[
				'title'					=> $this->title,
				'birth_year_start'		=> $birth_year_start,
				'birth_year_end'		=> $birth_year_end,
				'lastday'				=> $lastday,
				'ary_error_message'		=> $ary_error_message,
			]
		);
	}

	/**
	 * ②パスワード再設定、お客様コード確認
	 * メール送信
	 */
	public function mailProcess(Request $request)
	{
		// リクエストを取得
		$ary_request = $request->all();

		// バリデーションチェック
		$validate = $this->validateCheckMail($ary_request);

		if (!empty($validate))
		{
			// セッションにメッセージを入れる
			session()->put('reset_mail_message', $validate);

			// 入力画面へリダイレクトする
			return Redirect::to('/reset/remind')->send();
		}

		$birthday_str		= "${ary_request['birthday_y']}-${ary_request['birthday_m']}-${ary_request['birthday_d']}";	//Y-m-d
		$birthday			= new Carbon($birthday_str);																//date型に変換
		$ary_customer_info	= $this->getCustomerInfo($ary_request, $birthday);

		// クエリ結果が1レコードの場合のみメール送信
		if (count($ary_customer_info) == 1)
		{
			$this->sendResetMail($ary_customer_info[0]);
			return redirect()->route('resetMailComplete');
		}
		else
		{
			// メールは送らないが完了ページへ
			return redirect()->route('resetMailComplete');
		}
	}

	/**
	 * ②パスワード再設定、お客様コード確認
	 * メール送信完了
	 */
	public function mailComplete()
	{
		return view('login.reset.mailComplete',
			[
				'title'	=> $this->title,
			]
		);
	}

	/**
	 * ③パスワード再設定
	 */
	public function reset(Request $request)
	{
		$token	= $request->input('key');
		$mycode	= '';

		// セッション取得
		$error_msg = [];
		if (session()->exists('password_error'))
		{
			$error_msg = session()->get('password_error');
		}

		// パラメータがある場合
		if (isset($token))
		{
			$token = base64_decode($token);
			$tokenInfo = TokenMng::where([
				'token'		=> $token,
				'del_flg'	=> 0
			])
			->get()
			->toArray();

			// DBに該当するトークンがある場合
			if (count($tokenInfo) > 0)
			{
				$mycode = $tokenInfo[0]['no'];
			}
			else
			{
				// ログイントップへ
				return redirect()->route('loginTop');
			}
		}
		else
		{
			// ログイントップへ
			return redirect()->route('loginTop');
		}

		return view('login.reset.reset',
			[
				'title'		=> $this->title,
				'mycode'	=> $mycode,
				'error_msg'	=> $error_msg,
			]
		);
	}

	/**
	 * ④パスワード再設定(処理)
	 */
	public function resetProcess(Request $request)
	{
		// リクエストを取得
		$ary_request = $request->all();

		// バリデーションチェック
		$ary_error_message = $this->validateCheckPassword($ary_request);

		if (!empty($ary_error_message))
		{
			// セッションにエラーメッセージを入れる
			session()->put('password_error', $ary_error_message);

			// 元画面へ遷移
			return redirect()->route('resetPassword', ['key' => $ary_request['key']]);
		}

		// DB更新(CustomerとTokenMng)
		$bool_result = $this->updateCustomerTokenMng($ary_request); // 戻り値：true or false

		// DB更新完了した場合
		if ($bool_result['customer'] && $bool_result['tokenmng'])
		{
			// 再設定完了フラグ
			session()->put('password_complete_flg', 1);

			// パスワード再設定完了画面へ
			return redirect()->route('resetComplete');
		}
		else
		{
			// 元画面へ遷移
			session()->put('password_error.system', config('msg.D1002'));
			return redirect()->route('resetPassword', ['key' => $ary_request['key']]);
		}
	}

	/**
	 * ⑤パスワード再設定完了
	 */
	public function resetComplete()
	{
		// セッション判定
		if (!session()->exists('password_complete_flg'))
		{
			// ログイン画面へリダイレクト
			return redirect()->route('loginTop');
		}

		// セッションを削除
		session()->forget('password_complete_flg');

		return view('login.reset.complete', [
			'title'	=> $this->title
		]);
	}

	/**
	 * DB更新
	 */
	private function updateCustomerTokenMng($ary_request)
	{
		try
		{
			// customer更新
			$result_customer = Customer::where([
				'no'		=> $ary_request['mycode'],
				'del_flg'	=> 0
			])
			->first()
			->update([
				'password'	=> $ary_request['newpass'],
				'edit_date'	=> Carbon::now()
			]);

			// TokenMng更新
			$result_tokenmng = TokenMng::where([
				'no'		=> $ary_request['mycode'],
				'del_flg'	=> 0
			])
			->first()
			->update([
				'del_flg'	=> 1,
				'edit_date'	=> Carbon::now(),
				'del_date'	=> Carbon::now()
			]);

			return [
				'customer'	=> $result_customer,
				'tokenmng'	=> $result_tokenmng,
			];
		}
		catch (\Exception $e)
		{
			return [
				'customer'	=> false,
				'tokenmng'	=> false,
			];
		}
	}

	/**
	 * 新パスワードバリデーションチェック
	 */
	private function validateCheckPassword($ary_request)
	{
		$ary_message = [];

		// パスワードが入力されているか
		if (empty($ary_request['newpass']))
		{
			$ary_message['newpass'] = sprintf(config('msg.V1002'), '新しいパスワード');
		}

		// パスワードが正しい形式であるか
		if (empty($ary_message['newpass']) && !preg_match('/^[a-zA-Z0-9]+$/', $ary_request['newpass']))
		{
			$ary_message['newpass'] = config('msg.V1013');
		}

		// パスワードが8文字以上であるか
		if (empty($ary_message['newpass']) && !(mb_strlen($ary_request['newpass']) >= config('const.password_count_min')))
		{
			$ary_message['newpass'] = config('msg.V1012');
		}

		// 確認用のパスワードが入力されているか
		if (empty($ary_request['newpass_confirmation']))
		{
			$ary_message['newpass_confirmation'] = sprintf(config('msg.V1002'), '新しいパスワード(確認用)');
		}

		// パスワードが一致しているか
		if (empty($ary_message['newpass_confirmation']) && strcmp($ary_request['newpass'], $ary_request['newpass_confirmation']) != 0)
		{
			$ary_message['newpass_confirmation'] = config('msg.V1014');
		}

		return $ary_message;
	}

	/**
	 * お客様情報入力値バリデーションチェック
	 */
	private function validateCheckMail($ary_request)
	{
		$ary_message = [];

		// メールアドレスが入力されているか
		if (empty($ary_request['email']))
		{
			$ary_message['email'] = sprintf(config('msg.V1002'), 'メールアドレス');
		}

		// メールアドレスの形式が正しいか
		if (empty($ary_message['email']) && !preg_match('/^[a-zA-Z0-9-_\.+]+@[a-zA-Z0-9-_\.]+$/', $ary_request['email']))
		{
			$ary_message['email'] = config('msg.V1019');
		}

		return $ary_message;
	}

	/**
	 * お客様情報取得
	 */
	private function getCustomerInfo($ary_request, $birthday)
	{
		return Customer::where([
			'birthday'	=> $birthday,
			'mail'		=> $ary_request['email'],
			'del_flg'	=> 0
			])
			->get()
			->toArray();
	}

	/**
	 * パスワード再設定メール送信
	 */
	private function sendResetMail($ary_customer_info)
	{
		$mycode	= $ary_customer_info['no'];
		$url	= $this->urlGenerator($mycode);

		// 本文文字列置き換え
		$ary_replace = [
			'{mycode}'				=> $mycode,											// お客様番号
			'{url}'					=> $url,											// URL
			'{kireimo_url}'			=> config('env_const.kireimo_top'),					// kireimo
			'{kireimo_info}'		=> config('env_const.info_address'),				// infoアドレス
			'{mypage_url}'			=> config('env_const.base_url'),					// マイページurl
			'{kireimo_tel}'			=> config('env_const.kireimo_tel_disp'),			// kireimo電話番号
			'{kireimo_reserve_tel}'	=> config('env_const.kireimo_reserve_tel_disp'),	// kireimo電話番号(予約)
		];

		// 本文埋め込み
		$text = file_get_contents(config('const.body_reset_password_mail'));
		foreach ($ary_replace as $param_key => $param)
		{
			$text = str_replace($param_key, $param, $text);
		}

		$ary_reserve_data = [
			'toAddress'		=> $ary_customer_info['mail'],					// 送信先
			'fromAddress'	=> config('env_const.from_address'),			// 送信元アドレス
			'fromName'		=> config('const.from_name'),					// 送信元名称
			'subject'		=> config('const.subject_reset_password_mail'),	// 件名
			'body'			=> $text,										// 本文
			'mimeType'		=> config('const.mime_type_plane'),				// メール形式
		];

		// メール送信実行
		return $this->common_api->postApi($ary_reserve_data, 'single_mail');

	}

	/**
	 * パスワード再設定URL生成
	 */
	private function urlGenerator($mycode)
	{
		$token = "";
		// DBで同じトークンが存在しないかチェック
		do{
			$token .= $this->tokenGenerator();
			$tokenInfo = TokenMng::where([
				'token'		=> $token,
				'del_flg'	=> 0
			])
			->get()
			->toArray();
		} while (count($tokenInfo) > 0);

		// tokenのinsert
		TokenMng::create([
			'id'	=> 0,
			'token'		=> $token,
			'no'		=> $mycode,
			'del_flg'	=> 0,
			'reg_date'	=> Carbon::now()
		]);
		// tokenをさらにBase64化する。
		$token = base64_encode($token);

		return config('env_const.base_url').'reset?key='.$token;	// URL
	}

	/**
	 * トークン生成
	 */
	private function tokenGenerator()
	{
		// パスワードに使っても良い文字集合
		$password_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$password_chars_count	= strlen($password_chars);
		$length					= 16;
		$data					= random_bytes($length);  //ランダムバイト生成

		$token = '';
		for ($n = 0; $n < $length; $n ++)
		{
			$token .= substr($password_chars, ord(substr($data, $n, 1)) % $password_chars_count, 1);
		}

		return $token;
	}
}
