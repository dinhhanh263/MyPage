@extends('common.mypage_base')

@section('content')
	<main class="container" id="fortune">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<img src="{{  asset('img/horoscope/kireimo-beauty-uranai-title.png') }}" alt="">
				</div>
				<div class="fortune-content">
					<div class="introduction">
						<img src="{{  asset('img/horoscope/pleiades-reina.jpg') }}" alt="">
						<p><span style="font-weight: bold">ぷりあでぃす玲奈</span>
						<br><br>
						占い師/タレント。美容占い、開運ファッションを得意とし、テレビ、ラジオ、雑誌、インターネット番組等で幅広く活躍中。</p>
					</div>
					<div class="zodiac-container">
						@foreach($seiza_imgs as $seiza_img)
							<a href="{{ route('mypageHoroscopeDetail', ['zodiac_id' => $seiza_img[0]]) }}"><img class="zodiac_item" src="{{  asset("img/horoscope/$seiza_img[1]") }}" alt="{{ $seiza_img[2] }}"></a>
						@endforeach
					</div>
				</div>
				<div class="btnArea">
					<button type="button" class="btn btnM" onClick="location.href='{{ route('mypageTop') }}';">トップに戻る</button>
				</div>
			</div>
		</section>
	</main>
@endsection
