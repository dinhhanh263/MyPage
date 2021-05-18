<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
	// 接続先定義
	//protected $connection = 'mysql_kireimo';

	// テーブル名定義
	protected $table = 'bank';

	// プライマリーキー
	protected $primaryKey = 'id';

	// 作成日
	const CREATED_AT = 'reg_date';

	// 編集日
	const UPDATED_AT = 'edit_date';

	// 更新可能カラム
	protected $fillable = [
		'customer_id',
		'bank_name',
		'bank_branch',
		'bank_account_type',
		'bank_account_no',
		'bank_account_name',
		'del_flg',
		'ng_flg',
		'status',
	];
}