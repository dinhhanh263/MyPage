@extends('common.mypage_base')

@section('content')
	<main class="container" id="gene_enq_comp">
		<section class="main">
			<div class="page-title"><h1 class="title">アンケート送信完了</h1></div>
			<div class="complete-img">
				<img src="{{ asset('img/icon-complete.png') }}">
			</div>
			<div class="text-area">
				<p>アンケートの回答</p>
				<p class="s-red">ありがとうございました。</p>
			</div>
			<div class="btnArea ">
				<div class="btn-left"><button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button></div>
				<div class="btn-right"><button type="button" class="btn btnM" onclick="location.href='{{ route('mypageGene') }}'">遺伝子検査結果ページへ</button></div>
			</div>
		</section>
	</main>
@endsection