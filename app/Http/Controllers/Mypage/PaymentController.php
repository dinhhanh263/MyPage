<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;
use App\Models\VirtualBank;
use App\Models\Smartpit;

class PaymentController extends Controller
{
	public $title					= 'KIREIMO お支払いについて';
	public $ary_customer			= [];

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;
	}

	/**
	 * 支払いトップ
	 */
	public function index()
	{
		return view('mypage.payment.index',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * 口座支払い
	 */
	public function bank()
	{
		return view('mypage.payment.bank',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * 月額
	 */
	public function monthly()
	{
		return view('mypage.payment.monthly',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * コンビニ支払い
	 */
	public function convenience()
	{
		return view('mypage.payment.convenience',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
			]
			);
	}

	/**
	 * ローン
	 */
	public function loan()
	{
		return view('mypage.payment.loan',
			[
				'title'					=> $this->title,		// タイトル
				'ary_customer'			=> $this->ary_customer,	// お客様情報
			]
			);
	}
}
