<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;

class GuideController extends Controller
{
	public $title = 'KIREIMO 施術に関する注意事項';
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
		return view('mypage.guide.index',
			[
				'title'			=> $this->title,
				'ary_customer'	=> $this->ary_customer,
			]
			);
	}
}
