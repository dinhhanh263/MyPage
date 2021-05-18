<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//***************************************************************************
// ログイン
//***************************************************************************
Route::get('/',							'Login\LoginController@index')->name('loginTop');					// ログイントップ
Route::post('/',						'Login\LoginController@auth')->name('loginAuth');					// ログインチェック

Route::get('/logout',					'Login\LogoutController@index')->name('logoutTop');					// ログアウトトップ
Route::post('/logout',					'Login\LogoutController@complete')->name('logoutCompletePost');		// ログアウト完了

// パスワードリセット
Route::prefix('/reset')->group(function () {
	Route::get('/remind',			'Login\ResetController@index')->name('resetMailTop');				// パスワード再設定、お客様コード確認トップ
	Route::post('/remind',			'Login\ResetController@mailProcess')->name('resetMailProcess');		// パスワード再設定、お客様コード確認(処理)
	Route::get('/remind/complete',	'Login\ResetController@mailComplete')->name('resetMailComplete');	// パスワード再設定、お客様コード確認(完了)
	Route::get('/',					'Login\ResetController@reset')->name('resetPassword');				// パスワード再設定
	Route::post('/',				'Login\ResetController@resetProcess')->name('resetProcess');		// パスワード再設定(処理)
	Route::get('/complete',			'Login\ResetController@resetComplete')->name('resetComplete');		// パスワード再設定完了
});

// LINE連携
Route::prefix('/line/cooperate')->group(function () {
	Route::get('/auth',				'Login\LineCooperateController@auth')->name('cooperateLineAuth');			// LINE連携認証
	Route::get('/callback',			'Login\LineCooperateController@callback')->name('cooperateLineCallback');	// LINE連携コールバック
	Route::get('/',					'Login\LineCooperateController@index')->name('cooperateLine');				// LINE連携
	Route::post('/process',			'Login\LineCooperateController@process')->name('cooperateLineProcess');		// LINE連携処理
	Route::get('/complete',			'Login\LineCooperateController@complete')->name('cooperateLineComplete');	// LINE連携完了
	Route::get('/error',			'Login\LineCooperateController@error')->name('cooperateLineError');			// LINE連携エラー
});

//***************************************************************************
// マイページメニュー
//***************************************************************************
Route::get('/top',						'Mypage\TopController@index')->name('mypageTop');								// マイページトップ
Route::get('/friends',					'Mypage\FriendsController@index')->name('mypageFriends');						// 友達紹介
Route::post('/latecontact',				'Mypage\TopController@lateContact')->name('mypageLateContact');					// 遅刻連絡
Route::get('/latecontract/complete',	'Mypage\TopController@lateContactComplete')->name('mypageLateContactComplete');	// 遅刻連絡完了
Route::get('/guide',					'Mypage\GuideController@index')->name('mypageGuide');							// 注意事項
Route::get('/epilator',					'Mypage\EpilatorController@index')->name('mypageEpilator');							// 注意事項
Route::get('/error',					'Mypage\ErrorController@index')->name('mypageError');							// エラー画面

// お客様情報
Route::prefix('/member')->group(function () {
	Route::get('/',						'Mypage\MemberController@index')->name('mypageMember');										// お客様情報
	Route::post('/confirm',				'Mypage\MemberController@confirm')->name('mypageMemberConfirmPost');						// お客様情報更新(確認)
	Route::get('/confirm',				'Mypage\MemberController@confirm')->name('mypageMemberConfirmGet');							// お客様情報更新(確認)
	Route::get('/process',				'Mypage\MemberController@process')->name('mypageMemberProcess');							// お客様情報更新(処理)
	Route::get('/complete',				'Mypage\MemberController@complete')->name('mypageMemberComplete');							// お客様情報更新(完了)
	Route::post('/bank',				'Mypage\MemberController@bankUpdate')->name('mypageMemberBank');							// お客様口座情報更新
	Route::get('/password',				'Mypage\MemberController@changePassword')->name('mypageChangePassword');					// お客様パスワード変更
	Route::post('/password',			'Mypage\MemberController@changePasswordProcess')->name('mypageChangePasswordProcess');		// お客様パスワード変更(処理)
	Route::get('/password/complete',	'Mypage\MemberController@changePasswordComplete')->name('mypageChangePasswordComplete');	// お客様パスワード変更完了
});

// 占い
Route::prefix('/horoscope')->group(function () {
	Route::get('/',				'Mypage\HoroscopeController@index')->name('mypageHoroscope');										// 星座占い一覧
	Route::get('/{zodiac_id}',	'Mypage\HoroscopeController@show')->name('mypageHoroscopeDetail')->where('zodiac_id', '[0-9]+');	// 各星座占い
});

// お問い合わせ関連
Route::prefix('/contact')->group(function () {
	Route::get('/',					'Mypage\ContactController@index')->name('mypageContact');											// トップ
	Route::post('/process',			'Mypage\ContactController@process')->name('mypageContactProcess');									// お問い合わせ(処理)
	Route::get('/complete',			'Mypage\ContactController@complete')->name('mypageContactComplete');								// お問い合わせ(完了)
});

// 新規予約関連
Route::prefix('/reserve')->group(function () {
	Route::get('/full',					'Mypage\NewReserveController@full')->name('mypageFull');					// ブッキング
	Route::redirect('/', 				'../top');																	// パラメータなしで遷移してきた場合、topへリダイレクトする
	Route::get('/complete',				'Mypage\NewReserveController@complete')->name('mypageNewReserveComplete');	// 新規予約完了画面
	Route::get('/period',				'Mypage\NewReserveController@period')->name('mypagePeriod');				// 来店周期
	Route::get('/{contract_id}',		'Mypage\NewReserveController@index')->name('mypageNewReserve');				// 新規予約条件設定
	Route::post('/search',				'Mypage\NewReserveController@search')->name('mypageNewReserveSearch');		// 新規予約検索結果
	Route::post('/confirm',				'Mypage\NewReserveController@confirm')->name('mypageNewReserveConfirm');	// 新規予約確認画面
	Route::post('/process',				'Mypage\NewReserveController@process')->name('mypageNewReserveProcess');	// 新規予約処理
	Route::post('/area/shop',			'Mypage\NewReserveController@areaShop')->name('mypageNewReserveAreaShop');	// エリア別ショップリストを取得
});

// 予約変更関連
Route::prefix('/change')->group(function () {
	Route::get('/full',								'Mypage\ReserveChangeController@full')->name('mypageChangeFull');					// ブッキング
	Route::get('/complete',							'Mypage\ReserveChangeController@complete')->name('mypageReserveChangeComplete');	// 予約変更完了画面
	Route::get('/{contract_id}/{reservation_id}',	'Mypage\ReserveChangeController@index')->name('mypageReserveChange');				// 予約変更条件設定
	Route::post('/search',							'Mypage\ReserveChangeController@search')->name('mypageReserveChangeSearch');		// 予約変更検索結果
	Route::post('/confirm',							'Mypage\ReserveChangeController@confirm')->name('mypageReserveChangeConfirm');		// 予約変更確認画面
	Route::post('/process',							'Mypage\ReserveChangeController@process')->name('mypageReserveChangeProcess');		// 予約変更処理
	Route::post('/area/shop',						'Mypage\ReserveChangeController@areaShop')->name('mypageReserveChangeAreaShop');	// エリア別ショップリストを取得
});

// 予約キャンセル関連
Route::prefix('/cancel')->group(function () {
	Route::get('/complete',							'Mypage\ReserveCancelController@complete')->name('mypageReserveCancelComplete');	// 予約キャンセル完了画面
	Route::get('/{contract_id}/{reservation_id}',	'Mypage\ReserveCancelController@index')->name('mypageCancelChange');				// 予約キャンセル確認画面
	Route::post('/process',							'Mypage\ReserveCancelController@process')->name('mypageReserveCancelProcess');		// 予約キャンセル処理
});

// お支払い方法
Route::prefix('/payment')->group(function () {
	Route::get('/',					'Mypage\PaymentController@index')->name('mypagePayment');					// 支払い方法トップ
	Route::get('/bank',				'Mypage\PaymentController@bank')->name('mypagePaymentBank');				// 銀行口座
	Route::get('/convenience',		'Mypage\PaymentController@convenience')->name('mypagePaymentConvenience');	// コンビニ支払い
	Route::get('/loan',				'Mypage\PaymentController@loan')->name('mypagePaymentLoan');				// ローン
	Route::get('/monthly',			'Mypage\PaymentController@monthly')->name('mypagePaymentMonthly');			// 月額プランに関して
});

// 遺伝子関連
Route::prefix('/gene')->group(function () {
	Route::get('/',						'Mypage\GeneController@index')->name('mypageGene');								// 遺伝子検査結果
	Route::get('/question',				'Mypage\GeneController@question')->name('mypageGeneQuestion');					// 栄養アンケート
	Route::post('/question/process',	'Mypage\GeneController@questionProcess')->name('mypageGeneQuestionProcess');	// 栄養アンケート登録処理
	Route::get('/question/complete',	'Mypage\GeneController@questionComlete')->name('mypageGeneQuestionComplete');	// 栄養アンケート完了
});