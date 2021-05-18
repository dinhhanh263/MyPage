@extends('common.mypage_base')

@section('content')
	<main class=" main-container">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">{{ $page_title }}</h1>
				</div>
				<div class="stepBar stepBarEnd">
					<p>{{ $error_category }}</p>
				</div>
				<div class="mainTxtArea">
					<p>{!! nl2br(e($body)) !!}</p>
				</div>
				<hr>
				<div class="btnArea-3">
					<button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button>
				</div>
			</div>
		</section>
	</main>
@endsection
