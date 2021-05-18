<?php

namespace App\Library;

use Illuminate\Support\Facades\Log;
use Exception;

class CommonApi {

	private $curl;

	private $curl_template = [
		CURLOPT_URL				=> '',		// url
		CURLOPT_CUSTOMREQUEST	=> '',		// POST,GET,PATCH,PUTを指定
		CURLOPT_HTTPHEADER		=> '',		// ヘッダーを指定
		CURLOPT_SSL_VERIFYPEER	=> true,	// サーバ証明書の検証
		CURLOPT_RETURNTRANSFER	=> true,	// 返却値を文字列で返却する
		CURLOPT_HEADER			=> true,	// ヘッダの内容
		CURLOPT_CONNECTTIMEOUT	=> 300,		// タイムアウト時間
		//CURLOPT_FORBID_REUSE	=> false,	// 再利用設定
	];

	/**
	 * インスタンス破棄時にcurlをClose
	 */
	public function __destruct()
	{
		if (isset($this->curl)) {
			curl_close($this->curl);
			unset($this->curl);
		}
	}

	/**
	 * post
	 */
	public function postApi ($param, $uri, $customer_id = '')
	{
		// ヘッダーの設定
		$header = [
			'api-token: ' . config('api.api_token'),
			'Content-Type: application/json',
			'Expect:',
			//'Connection: close',
		];

		// 顧客IDがセットされている場合
		$url = null;

		if (!empty($customer_id))
		{
			$url = sprintf(config('api.api_url') . config('api.api_sub.' . $uri), $customer_id);
		}
		else
		{
			$url = config('api.api_url') . config('api.api_sub.' . $uri);
		}

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= $url;
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'POST';
		$curl_template[CURLOPT_POSTFIELDS]		= json_encode($param);
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	/**
	 * get
	 */
	public function getApi ($param, $uri, $search_type_flg = 0)
	{
		// 取得apiのurl生成
		$url = config('api.api_url') . config('api.api_sub.' . $uri);

		if ($search_type_flg == 0)
		{
			// パラメーターの生成
			$ary_param = null;

			foreach ($param as $key => $val)
			{
				$ary_param[] = $key . '=' . $val;
			}

			// 取得apiのurl生成
			$url = $url . '?' . implode('&', $ary_param);
		}
		else if ($search_type_flg == 1)
		{
			$url = $url . '/' . $param;
		}

		// ヘッダーの設定
		$header = [
			'Accept: application/json' ,
			'api-token: ' . config('api.api_token') ,
			'Content-Type: application/json',
			'Expect:',
			//'Connection: close',
		];

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= $url;
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'GET';
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	/**
	 * put
	 * @param	array	$param
	 * @param	string	$uri
	 * @param	int		$mode_flg 0:指定なし 1:キャンセル 2:パスワード変更
	 */
	public function putApi ($param, $uri, $mode_flg = 0)
	{
		// 取得apiのurl生成
		$url = config('api.api_url') . config('api.api_sub.' . $uri);

		if ($mode_flg == 1)
		{
			// キャンセルの場合
			$url = $url . '/' . $param . '/cancel';
		}
		else
		{
			if ($mode_flg == 2)
			{
				// パスワード変更の場合
				$url = $url . '/' . $param['customer_id'] . '/password';

				// 不要な配列削除
				unset($param['customer_id']);
			}
			else
			{
				$url = $url . '/' . $param['reservation_id'];

				unset($param['reservation_id']);
			}

			// パラメーターの生成
			$ary_param = [];

			foreach ($param as $key => $val)
			{
				$ary_param[] = $key . '=' . $val;
			}

			// 取得apiのurl生成
			$url = $url . '?' . implode('&', $ary_param);
		}

		// ヘッダーの設定
		$header = [
			'Accept: application/json' ,
			'api-token: ' . config('api.api_token') ,
			'Content-Type: application/json',
			'Expect:',
			//'Connection: close',
		];

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= $url;
		$curl_template[CURLOPT_PUT]				= true;
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'PUT';
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	/**
	 * patch
	 */
	public function patchApi ($id, $cokkie, $param, $uri)
	{
		// 取得apiのurl生成
		$url = config('api.api_url') . config('api.api_sub.' . $uri) . '/' . $id;

		// ヘッダーの設定
		$header = [
			'api-token: ' . config('api.api_token') ,
			'Content-Type: application/json',
			'Cookie: ARRAffinity=' . $cokkie,
			'Expect:',
			//'Connection: close',
		];

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= $url;
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'PATCH';
		$curl_template[CURLOPT_POSTFIELDS]		= json_encode($param);
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	public function postApiLineAccessToken ($code)
	{
		// ヘッダーの設定
		$header = [
			'Content-Type: application/x-www-form-urlencoded',
			'Expect:',
			//'Connection: close',
		];

		// パラメータの設定
		$param = [
			'grant_type'	=> 'authorization_code',
			'code'			=> $code,
			'redirect_uri'	=> config('line_const.redirect_url'),
			'client_id'		=> config('line_const.channel_id'),
			'client_secret'	=> config('line_const.secret_key'),
		];

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= config('line_const.access_token_url');
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'POST';
		$curl_template[CURLOPT_POSTFIELDS]		= http_build_query($param);
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	public function getApiLineUserId($access_token)
	{
		// ヘッダーの設定
		$header = [
			'Authorization: Bearer ' . $access_token,
			'Expect:',
			//'Connection: close',
		];

		// カール設定内容
		$curl_template = $this->curl_template;

		$curl_template[CURLOPT_URL]				= config('line_const.profile_url');
		$curl_template[CURLOPT_CUSTOMREQUEST]	= 'GET';
		$curl_template[CURLOPT_HTTPHEADER]		= $header;

		return $this->exec($curl_template);
	}

	private function exec($curl_template)
	{
		$result = [];

		try {
			// 計測用
			$startTime = microtime(true);
			$initialMemory = memory_get_usage();

			if (!isset($this->curl)) {
				$this->curl = curl_init();
			}

			curl_setopt_array($this->curl, $curl_template);

			$response		= curl_exec($this->curl);							// 実行
			$httpcode		= curl_getinfo($this->curl, CURLINFO_HTTP_CODE);	// ステータスコード
			$header_size	= curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);	// 受信したヘッダのサイズ
			$body			= substr($response, $header_size);					// レスポンスを受信したヘッダのサイズにする
			$result			= json_decode($body, true);							// 取得結果をデコードする

			// オプションをリセットする
			curl_reset($this->curl);

			// ログ出力
			$param = !empty($curl_template[CURLOPT_POSTFIELDS]) ? $curl_template[CURLOPT_POSTFIELDS] : '';

			if (strpos($curl_template[CURLOPT_URL], 'shops'))
			{
				// ショップ検索の場合は長いのでショップ情報のみ除外
				$temp_result = $result;
				unset($temp_result['body']);
				Log::channel('apilog')->info('Request Url : ' . $curl_template[CURLOPT_URL] . PHP_EOL . 'Request : ' . print_r($param, true) . PHP_EOL . 'Result : ' . print_r($temp_result, true));
			}
			else
			{
				// ショップ以外
				Log::channel('apilog')->info('Request Url : ' . $curl_template[CURLOPT_URL] . PHP_EOL . 'Request : ' . print_r($param, true) . PHP_EOL . 'Result : ' . print_r($result, true));
			}

			// 計測用
			$runningTime =  microtime(true) - $startTime;
			$usedMemory = (memory_get_peak_usage() - $initialMemory) / (1024 * 1024);

			// 計測結果をログに出す
			Log::channel('apilog')->info('running time : ' . $runningTime . '[s]');
			Log::channel('apilog')->info('used memory : ' . $usedMemory . '[MB]');

			if ($httpcode != 200)
			{
				return false;
			}

			return $result;
		}
		catch (Exception $e)
		{
			// ログ出力
			Log::channel('mypagesystemerrorlog')->info($e . PHP_EOL . print_r(session()->all(), true));
			return false;
		}
	}
}