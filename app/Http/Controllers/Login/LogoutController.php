<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Library\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
	public $title			= 'KIREIMO ログアウト';
	public $ary_customer	= [];

	public function __construct()
	{
		Common::loginCheck();

		// お客様情報取得
		$this->ary_customer = session()->get('customer');
	}

	/**
	 * ログアウトトップ
	 */
	public function index()
	{
		return view('login.logout.index',
			[
				'title'			=> $this->title,
				'ary_customer'	=> $this->ary_customer,
			]
		);
	}

	/**
	 * ログアウト(処理)
	 */
	public function complete()
	{
		return Common::logout();
	}
}
