<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;

class ErrorController extends Controller
{
	public $ary_customer	= [];

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// お客様情報をセッションから取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;
	}

	public function index()
	{
		// セッションをチェックする
		if (!session()->exists('error_category'))
		{
			// セッションが存在しない場合
			return redirect()->to('/');
		}

		// セッション値を取得
		$error_category = session()->get('error_category');

		// セッション削除
		session()->forget('error_category');

		// エラー内容を描画
		return view('mypage.error.error',
			[
				'title'				=> config('error_const.' . $error_category . '.title'),				// タイトル
				'ary_customer'		=> $this->ary_customer,												// お客様情報
				'page_title'		=> config('error_const.' . $error_category . '.page_title'),		// ページタイトル
				'error_category'	=> config('error_const.' . $error_category . '.error_category'),	// エラーカテゴリ
				'body'				=> config('error_const.' . $error_category . '.body'),				// 本文
			]
			);
	}
}
