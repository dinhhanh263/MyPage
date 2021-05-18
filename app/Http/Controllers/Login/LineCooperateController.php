<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Library\CommonApi;
use App\Library\CommonValidation;

class LineCooperateController extends Controller
{

	public $common_api;	// api接続用インスタンス

	public function __construct()
	{
		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	public function auth()
	{
		// LINE設定値
		$line_state = bin2hex(openssl_random_pseudo_bytes(16));

		// セッションに入れる
		session()->put(['line_state' => $line_state]);

		// url生成
		$url = config('line_const.base_url')
		. sprintf(config('line_const.uri'),
			config('line_const.respons_type_code'),
			config('line_const.channel_id'),
			config('line_const.redirect_url'),
			$line_state,
			config('line_const.scope_profile'),
			config('line_const.bot_prompt_normal'));

		return redirect()->to($url);
	}

	public function callback(Request $request)
	{
		// リクエスト値を取得
		$ary_request = $request->all();

		// セッション値を取得
		$ary_session = session()->all();

		// コード取得チェック
		if (empty($ary_request['code']))
		{
			\Log::channel('mypagesystemerrorlog')->info(session()->getId() . ' : 認証コールバックcode取得エラー(mypage/lineCallback)');
			// エラー画面へ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// state値チェック
		if (empty($ary_request['state']) || empty($ary_session['line_state']) || $ary_request['state'] != $ary_session['line_state'])
		{
			\Log::channel('mypagesystemerrorlog')->info(session()->getId() . ' : 認証コールバックstate取得エラー(mypage/lineCallback)');
			// エラー画面へ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// LINEアクセストークンを取得
		$ary_line_access_token = $this->common_api->postApiLineAccessToken($ary_request['code']);

		if ($ary_line_access_token == false)
		{
			\Log::channel('mypagesystemerrorlog')->info(session()->getId() . ' : アクセストークン取得エラー(mypage/lineCallback)');
			// エラー画面へ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// ユーザーID取得
		$ary_user_data = $this->common_api->getApiLineUserId($ary_line_access_token['access_token']);

		if ($ary_user_data == false)
		{
			\Log::channel('mypagesystemerrorlog')->info(session()->getId() . ' : ユーザープロフィール取得エラー(mypage/lineCallback)');
			// エラー画面へ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// セッションに格納
		session()->put('line_user_data', $ary_user_data);

		// ログイン画面へ
		return Redirect::to('/line/cooperate')->send();
	}

	public function index()
	{
		// lineユーザーIDが取得できているか
		if (!session()->exists('line_user_data'))
		{
			return Redirect::to('/line/cooperate/error')->send();
		}

		// バリデーションエラーが存在する場合
		$ary_validate_error = [];
		if (session()->exists('ary_error'))
		{
			$ary_validate_error = session()->get('ary_error');

			// セッションを削除
			session()->forget('ary_error');
		}

		// セッションを取り出す（エラーから戻ってきた場合）
		$ary_user_session = [];
		if (session()->exists('user_info'))
		{
			$ary_user_session = session()->get('user_info');

			// セッションを削除
			session()->forget('user_info');
		}

		$index_flg = 1;

		return view('login.line_cooperate.index',
			[
				'title'			=> 'LINE連携',
				'ary_session'	=> $ary_user_session,
				'ary_error'		=> $ary_validate_error,
				'index_flg'		=> $index_flg,
			]
			);
	}

	public function process(Request $request)
	{
		// リクエスト値を取得
		$ary_request = $request->all();

		// セッション値を取得
		$ary_session = session()->all();

		if (empty($ary_session))
		{
			return Redirect::to('/line/cooperate/error')->send();
		}

		// 入力値チェック
		$ary_error = CommonValidation::loginValidation($ary_request);

		// エラーが存在する場合
		if (!empty($ary_error))
		{
			// エラーをセッションに格納
			session()->put('ary_error', $ary_error);

			// データが存在しなかった場合
			return Redirect::to('/line/cooperate')->send();
		}

		// マイページ認証
		// ログイン情報チェック
		$param = [
			'customerNo'	=> $ary_request['mycode'],
			'password'		=> $ary_request['password']
		];

		$ary_login_result = $this->common_api->postApi($param, 'mypage_auth');

		if ($ary_login_result['apiStatus'] != config('api.api_status_code.SUCCESS') || empty($ary_login_result['body']['authResult']))
		{
			// エラーページへ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// クッキーの設定
		\Cookie::queue('line_cokkie', $ary_login_result['body']['customerId']);

		$cokkie = \Cookie::get('line_cokkie');

		// データが存在した場合lineユーザーIDを登録する
		$param_line['lineMid'] = $ary_session['line_user_data']['userId'];

		$ary_result = $this->common_api->patchApi($ary_login_result['body']['customerId'], $cokkie, $param_line, 'customer');

		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// エラーページへ
			return Redirect::to('/line/cooperate/error')->send();
		}

		// 成功したら完了ページへ
		return Redirect::to('/line/cooperate/complete')->send();
	}

	public function complete()
	{
		// セッションを全削除
		session()->flush();

		return view('login.line_cooperate.complete',
			[
				'title'	=> 'LINE連携完了',
			]
			);
	}

	public function error()
	{
		// セッションを全削除
		session()->flush();

		return view('login.line_cooperate.error',
			[
				'title'	=> 'LINE連携エラー',
			]
			);
	}
}
