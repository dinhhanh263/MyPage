<?php

namespace App\Library;

class CommonValidation
{
	// ログインチェック
	public static function loginValidation($ary_request)
	{
		// エラ〜コード返却用
		$ary_error = array();

		// お客様コード
		if (!empty($ary_request['mycode']))
		{
			if (!preg_match('/^[A-Z0-9]+$/', $ary_request['mycode']))
			{
				// 半角英数字以外の場合
				$ary_error['mycode'] = config('msg.V1022');
			}
		}
		else
		{
			// 未入力の場合
			$ary_error['mycode'] = sprintf(config('msg.V1002'), 'お客様コード');
		}

		// パスワード
		if (!empty($ary_request['password']))
		{
			if (!preg_match('/^[a-zA-Z0-9]+$/', $ary_request['password']))
			{
				// 半角英数字以外の場合
				$ary_error['password'] = config('msg.V1023');
			}
		}
		else
		{
			// 未入力の場合
			$ary_error['password'] = sprintf(config('msg.V1002'), 'パスワード');
		}

		return $ary_error;
	}
}