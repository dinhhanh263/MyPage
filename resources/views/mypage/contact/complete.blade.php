@extends('common.mypage_base')

@section('content')
    <main class="container main-container">
        <section class="main">
            <div class="main-content">
                <div class="page-title">
                    <h1 class="title">お問い合わせ</h1>
                </div>
                <h2 class="stepBar pt-30">ご質問送信完了</h2>
                <div class="complete-img">
                    <img src="{{  asset('img/icon-send.png') }}">
                </div>
                <div class="information">
                    <p>頂戴したご質問に対するご回答は、後日キレイモカスタマーサポートよりご連絡いたします。</p>
                    <p>お客様がメールの受信ドメイン制限をされている場合は「{{ $contract_type == config('const.contact_flg.normal') ? '@kireimo.jp' : '@kireimo-premium.jp' }}」の受信許可設定をお願いいたします。</p>
                    <p>また、お客様がキャリアメールをご利用の場合には、携帯キャリアドメイン以外からのメールを受信可能にしていただかなければメールが届かない場合がございます。</p>
                    <p>２日営業日経っても折り返しのご連絡がない場合には、おそれ入りますがキレイモコールセンターまでご連絡くださいませ。</p>
                </div>
            </div>
            <p class="btnArea pb-150">
                <button type="button" class="btn btnM" onclick="location.href='{{ route('mypageTop') }}'">マイページトップへ</button>
            </p>
        </section>
    </main>
@endsection
