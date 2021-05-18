<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use App\Library\Common;
use App\Library\CommonValidation;
use App\Models\Customer;
use App\Library\CommonApi;

class LoginController extends Controller
{
	public $title = 'KIREIMO マイページ ログイン';	// ページタイトル
	public $common_api;							// api接続用インスタンス

	public function __construct(Request $request)
	{
		// パラメータにreturn_urlが設定されている場合
		$return_url = null;
		if ($request->get('return_url'))
		{
			$return_url = Common::returnUrlCheck($request->get('return_url'));
		}

		// ログアウト以外の場合
		if ($request->server->get('REQUEST_URI') != '/logout') {
			// ログインチェック
			if (Cookie::get(session_name()) && $request->session()->has('customer')) {
				// ログイン中
				if (!empty($return_url))
				{
					Redirect::to($return_url)->send();
				}
				else
				{
					Redirect::to('/top')->send();
				}
			}
		}

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	/**
	 * ログイントップ
	 */
	public function index()
	{
		return view('login.index',
			[
				'title'	=> $this->title,
			]
			);
	}

	/**
	 * ログインチェック
	 */
	public function auth(Request $request)
	{
		// リクエストを取得
		$ary_request = $request->all();

		// 入力値チェック
		$ary_error = CommonValidation::loginValidation($ary_request);

		// エラーが存在する場合
		if (!empty($ary_error))
		{
			return view('login.index',
				[
					'title'			=> $this->title,	// タイトル
					'ary_request'	=> $ary_request,	// 入力値
					'ary_error'		=> $ary_error,		// 入力値エラー
				]
				);
		}

		// ログイン情報チェック
		$param = [
			'customerNo'	=> $ary_request['mycode'],
			'password'		=> $ary_request['password']
		];

		$ary_login_result = $this->common_api->postApi($param, 'mypage_auth');

		if ($ary_login_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// データが存在しなかった場合
			$ary_error['password'] = config('msg.A1001');

			return view('login.index',
				[
					'title'			=> $this->title,	// タイトル
					'ary_request'	=> $ary_request,	// 入力値
					'ary_error'		=> $ary_error,		// 入力値エラー
				]
				);
		}
		else if (empty($ary_login_result['body']['authResult']))
		{
			// ユーザーが取得できなかった場合はパスワード違いとする
			$ary_error['password'] = config('msg.V1001');

			return view('login.index',
				[
					'title'			=> $this->title,	// タイトル
					'ary_request'	=> $ary_request,	// 入力値
					'ary_error'		=> $ary_error,		// 入力値エラー
				]
				);
		}

		// データが存在した場合
		// セッションIDの再発行
		session()->regenerate();

		// ユーザーIDをセッションに格納
		session()->put(['customer.id' => $ary_login_result['body']['customerId']]);

		// cookieを設定
		$cookie_value = sha1(uniqid(rand(), true));
		Cookie::queue(session_name(), $cookie_value, 120);

		// return urlが存在した場合
		if (!empty($ary_request['return_url']))
		{
			// return_urlチェック
			$return_path = Common::returnUrlCheck($request->get('return_url'));

			// セッションに入れる
			session()->put('return_path', $return_path);
		}

		// マイページTOPページへリダイレクト
		return redirect()->action('Mypage\TopController@index');
	}
}
