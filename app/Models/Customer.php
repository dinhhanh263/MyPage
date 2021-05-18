<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	// 接続先定義
	//protected $connection = 'mysql_kireimo';

	// テーブル名定義
	protected $table = 'customer';

    // 編集日
    const UPDATED_AT = 'edit_date';

    // データの挿入を許可するカラム
    protected $fillable = [
        'password', 'del_flg'
    ];
}
