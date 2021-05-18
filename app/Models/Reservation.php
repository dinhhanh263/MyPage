<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
	// 接続先定義
	//protected $connection = 'mysql_kireimo';

	// テーブル名定義
	protected $table = 'reservation';

	// プライマリーキー
	protected $primaryKey = 'id';

	// 作成日
	const CREATED_AT = 'reg_date';

	// 編集日
	const UPDATED_AT = 'edit_date';

	// 更新可能カラム
	protected $fillable = [
		'delay_time_status',
		'delay_time_reg_date',
	];
}