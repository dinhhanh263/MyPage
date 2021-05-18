@extends('common.mypage_base')

@section('content')
	<main class="container" id="gene_enq">
		<section class="main">
			<!-- エラー -->
			@if (!empty($ary_error))
				<div class="enq-err">
					@foreach ($ary_error as $key => $val)
						<p><i class="fas fa-exclamation-triangle"></i>Q{{ $key }} {{ $val }}</p>
					@endforeach
				</div>
			@endif
			@if (!empty($str_api_error))
				<div class="enq-err">
					<p><i class="fas fa-exclamation-triangle"></i>{{ $str_api_error }}</p>
				</div>
			@endif
			<div class="page-title"><h1 class="title">アンケート</h1></div>
			<div class="text-area">
				<p>遺伝子検査の結果は、<span>本アンケートのご回答後にご覧になれます。</span><br>
				アンケートのご回答をお願いいたします。</p>
			</div>
			<form id="questionForm" method="post">
				@csrf
				<div>
					@foreach ($ary_question as $key => $val)
						<dl>
							<dt id="enq_01">質問{{ $val['id'] }}： {{ $val['question'] }}</dt>
							<dd class="enq_content">
								<div>
									<input type="radio" name="answer[{{ $val['id'] }}]" id="radioTypeNormal{{ $val['id'] }}"  class="enq-radio" value="1" {{ empty($ary_answer[$val['id']]) ? '' : ($ary_answer[$val['id']] == 1 ? 'checked' : '') }}>
									<label for="radioTypeNormal{{ $val['id'] }}" class="enq-label">はい</label>
								</div>
								<div>
									<input type="radio" name="answer[{{ $val['id'] }}]" id="radioTypeCurrent{{ $val['id'] }}" class="enq-radio" value="0" {{ empty($ary_answer[$val['id']]) ? 'checked' : ($ary_answer[$val['id']] != 1 ? 'checked' : '') }}>
									<label for="radioTypeCurrent{{ $val['id']}}" class="enq-label">いいえ</label>
								</div>
							</dd>
						</dl>
					@endforeach
				</div>
				<div class="btnArea clearfix">
					<button type="button" class="btn btnM questionButton" name="insert_button" onclick="questionSubmit();">送信</button>
				</div>
			</form>
		</section>
	</main>
@endsection