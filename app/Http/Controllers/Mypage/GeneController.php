<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Common;
use App\Library\CommonApi;

class GeneController extends Controller
{
	public $title			= 'KIREIMO 診断結果';		// 画面タイトル
	public $ary_customer	= [];					// お客様情報格納用
	public $common_api;								// api接続用インスタンス

	public function __construct()
	{
		// ログインチェック
		Common::loginCheck();

		// お客様情報をセッションから取得
		$ary_customer		= session()->get('customer');
		$this->ary_customer	= $ary_customer;

		// api接続用インスタンス生成
		$this->common_api = new CommonApi();
	}

	public function index()
	{
		// 遺伝子情報が存在しない場合はマイページトップへリダイレクト
		if (empty($this->ary_customer['geneInfo']))
		{
			// トップへリダイレクト
			return redirect()->route('mypageTop');
		}

		// 栄養アンケートが未回答の場合は、アンケート回答画面へ
		if (empty($this->ary_customer['nutritionLackInfo']))
		{
			return redirect()->route('mypageGeneQuestion');
		}

		return view('mypage.gene.index',
			[
				'title'					=> $this->title,								// タイトル
				'ary_customer'			=> $this->ary_customer,							// お客様情報
				'ary_gene_data'			=> $this->ary_customer['geneInfo'],				// 遺伝子結果表示情報
				'ary_nutrition_data'	=> $this->ary_customer['nutritionLackInfo'],	// 栄養情報
			]
			);
	}

	/**
	 * 栄養アンケート
	 */
	public function question()
	{
		// 処理中フラグを削除
		session()->forget('question_process_flg');

		// 遺伝子情報が存在しない場合はマイページトップへリダイレクト
		if (empty($this->ary_customer['geneInfo']))
		{
			// トップへリダイレクト
			return redirect()->route('mypageTop');
		}

		// アンケートに回答済みの場合は遺伝子検査にリダイレクトする
		if (!empty($this->ary_customer['nutritionLackInfo']))
		{
			return redirect()->route('mypageGene');
		}

		// 質問情報を取得
		$ary_question = $this->common_api->getApi('', 'nutrition_questionnaires', '1');

		// 質問取得エラーの場合、topへリダイレクトさせる
		if ($ary_question['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			return redirect()->route('mypageTop');
		}

		// エラーで戻ってきた場合、回答を取得する
		$ary_answer = [];
		if (session()->exists('question_answer'))
		{
			$ary_answer = session()->get('question_answer');
		}

		// エラーのセッションを取得
		$ary_error = [];
		if (session()->exists('question_error'))
		{
			$ary_error = session()->get('question_error');

			// セッションを削除する
			session()->forget('question_error');
		}

		// apiエラーを取得
		$str_error = null;
		if (session()->exists('question_api_error'))
		{
			$str_error = session()->get('question_api_error');
			// セッションを削除する
			session()->forget('question_api_error');
		}

		return view('mypage.gene.question',
			[
				'title'			=> $this->title,			// タイトル
				'ary_customer'	=> $this->ary_customer,		// お客様情報
				'ary_question'	=> $ary_question['body'],	// 質問
				'ary_answer'	=> $ary_answer,				// 回答
				'ary_error'		=> $ary_error,				// エラー
				'str_api_error'	=> $str_error,				// apiエラー
			]
			);
	}

	/**
	 * 栄養アンケート更新処理
	 */
	public function questionProcess (Request $request)
	{
		if (session()->exists('question_process_flg') == true)
		{
			// アンケート登録失敗画面へ
			return view('mypage.error.error',
				[
					'title'				=> config('error_const.question.title'),			// タイトル
					'ary_customer'		=> $this->ary_customer,								// お客様情報
					'page_title'		=> config('error_const.question.page_title'),		// ページタイトル
					'error_category'	=> config('error_const.question.error_category'),	// エラーカテゴリ
					'body'				=> config('error_const.question.body'),				// 本文
				]
				);
		}
		else
		{
			// 処理中フラグを立てる
			session()->put('question_process_flg', true);
		}

		// 最新のお客様情報取得
		Common::getCustomer($this->common_api);
		$this->ary_customer = session()->get('customer');

		// 取得したお客様情報にデータが存在する場合はエラーへ
		if (!empty($this->ary_customer['nutritionLackInfo']))
		{
			// アンケート登録失敗画面へ
			return view('mypage.error.error',
				[
					'title'				=> config('error_const.question.title'),			// タイトル
					'ary_customer'		=> $this->ary_customer,								// お客様情報
					'page_title'		=> config('error_const.question.page_title'),		// ページタイトル
					'error_category'	=> config('error_const.question.error_category'),	// エラーカテゴリ
					'body'				=> config('error_const.question.body'),				// 本文
				]
				);
		}

		// リクエスト値を取得
		$ary_requst = $request->all();

		// 対象セッションが存在しない場合はトップへリダイレクト
		if (empty($ary_requst))
		{
			return redirect()->route('mypageTop');
		}

		// お客様情報に回答済みのデータがある場合はマイページトップへリダイレクト
		if (!empty($this->ary_customer['nutritionLackInfo']))
		{
			return redirect()->route('mypageTop');
		}

		// セッションに回答を一旦格納
		session()->put('question_answer', $ary_requst['answer']);

		$ary_validate_result = $this->ansCheck($ary_requst['answer']);

		// バリデーションエラーあり
		if (!empty($ary_validate_result))
		{
			// セッションにエラー内容を入れて元の画面へ
			session()->put('question_error', $ary_validate_result);
			return redirect()->route('mypageGeneQuestion');
		}

		// 格納用パラメータの作成
		$param = [];
		foreach ($ary_requst['answer'] as $key => $val)
		{
			$param['question' . sprintf('%02d', $key)] = $val == 1 ? true : false;
		}

		// api実行
		$ary_result = $this->common_api->postApi($param, 'nutrition_questionnaires_add', $this->ary_customer['id']);

		// 実行エラーの場合、confirmへ
		if ($ary_result['apiStatus'] != config('api.api_status_code.SUCCESS'))
		{
			// エラーメッセージを格納
			session()->put('question_api_error', config('msg.A1011'));
			return redirect()->route('mypageGeneQuestion');
		}

		// 成功した場合は完了ページへ
		return redirect()->route('mypageGeneQuestionComplete');
	}

	public function questionComlete()
	{
		// 処理中フラグを削除
		session()->forget('question_process_flg');

		// 対象セッションが存在しない場合はトップへリダイレクト
		if (!session()->exists('question_answer'))
		{
			return redirect()->route('mypageTop');
		}

		// セッション削除
		session()->forget('question_answer');

		// お客様情報をとりなおす
		Common::getCustomer($this->common_api);

		return view('mypage.gene.complete',
			[
				'title'						=> $this->title,			// タイトル
				'ary_customer'				=> $this->ary_customer,		// お客様情報
			]
			);
	}

	/**
	 * 質問バリデーションチェック
	 * @param array $ary_data
	 */
	private function ansCheck ($ary_data)
	{
		$ary_error = [];

		foreach ($ary_data as $key => $val)
		{
			if ($val != 0 && $val != 1)
			{
				$ary_error[$key] = '不正な値が入力されています。';
			}
		}

		return $ary_error;
	}
}
