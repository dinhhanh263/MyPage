<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Throwable  $exception
	 * @return void
	 *
	 * @throws \Exception
	 */
	public function report(Throwable $exception)
	{
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Throwable  $exception
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @throws \Throwable
	 */
	public function render($request, Throwable $exception)
	{
		switch ($_SERVER['SERVER_NAME'] ?? '') {
			// product
			case 'mypage.kireimo.jp':
				if (session()->exists('customer'))
				{
					// セッションが存在する場合
					return redirect()->to('/top');
					break;
				}
				else
				{
					// セッションが存在しない場合
					return redirect()->to('/');
					break;
				}
				// stg
			case 'mypage.kireimo-stage.jp':
				if (session()->exists('customer'))
				{
					// セッションが存在する場合
					return redirect()->to('/top');
					break;
				}
				else
				{
					// セッションが存在しない場合
					return redirect()->to('/');
					break;
				}
				// dev
			case 'mypage.kireimo-dev.jp':
				if (session()->exists('customer'))
				{
					// セッションが存在する場合
					return redirect()->to('/top');
					break;
				}
				else
				{
					// セッションが存在しない場合
					return redirect()->to('/');
					break;
				}
			default:
				return parent::render($request, $exception);
				break;
		}
	}
}
