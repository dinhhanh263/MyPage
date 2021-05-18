<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Library\Common;
use Illuminate\Http\Request;
use App\Library\Encrypt;

class FriendsController extends Controller
{
	public $title			= 'KIREIMO キレイモおともだち紹介';
	public $ary_customer	= array();

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// お客様情報取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;
	}

	public function index()
	{
		// 友達紹介関連
		$ary_invite = $this->createInviteUrl($this->ary_customer['customerNo']);

		return view('mypage.friends.index',
			[
				'title'				=> $this->title,		// タイトル
				'ary_customer'		=> $this->ary_customer,	// お客様情報
				'ary_invite'		=> $ary_invite,			// 友達紹介
			]
			);
	}

	/**
	 * 友達紹介関連
	 */
	private function createInviteUrl($no)
	{
		// 友達紹介関連配列格納用
		$ary_invite = array();

		$encrypt = new Encrypt();

		// お客様noをエンコード
		$invite_code = $encrypt->clipter_encode($no);

		// urlの生成
		$ary_invite['url'] = urlencode(config('env_const.kireimo_top') . 'invite/?code=' . $invite_code);

		// タイトル
		$ary_invite['subject'] = urlencode("【全身脱毛サロン キレイモのおともだち紹介】\n");

		// 本文
		$ary_invite['body'] = urlencode("わたしの紹介でこのURLから予約すると割引になるよ♪\n") . $ary_invite['url'];

		// タイトル+本文
		$ary_invite['text'] = $ary_invite['subject'] . '\n' . $ary_invite['body'];

		return $ary_invite;
	}
}
