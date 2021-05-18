<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenMng extends Model
{
    // 接続先定義
    //protected $connection = 'mysql_kireimo';

    // テーブル名定義
    protected $table = 'token_mng';

    // 作成日
    const CREATED_AT = 'reg_date';

    // 編集日
    const UPDATED_AT = 'edit_date';

    // データの挿入を許可するカラム
    protected $fillable = [
        'id', 'token', 'no', 'del_flg', 'del_date'
    ];
}
