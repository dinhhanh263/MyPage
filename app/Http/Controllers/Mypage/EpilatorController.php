<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;

class EpilatorController extends Controller
{
	public $title = 'KIREIMO 脱毛器シェーバー';
	public $ary_customer	= [];

	public function __construct()
	{
		Common::loginCheck();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;
	}

	public function index()
	{
		return view('mypage.epilator.index',
			[
				'title'			=> $this->title,
				'ary_customer'	=> $this->ary_customer,
			]
			);
	}
}
