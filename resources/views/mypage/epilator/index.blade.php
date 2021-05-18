@extends('common.mypage_base')
@section('content')

<!--Q&A open-closed script/smooth scrool-->
<script>
  $(function(){
    $('.js-menu__item__link').each(function(){
        $(this).on('click',function(){
            $("+.a",this).slideToggle();
            if($(this).hasClass("close")){
              $(this).removeClass("close");
            }else{
              $(this).addClass("close");
            }
            return false;
        });
    });
  });
  
  $(function(){
  $('a[href^="#"]').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
});
</script>

<section id="mainImg">
<h1><span>キレイモ</span>
オリジナル<br class="sp_only">脱毛器<br class="sp_only">&<br class="sp_only">シェーバー</h1>
<p class="lead">いま売り出すのには理由がある♡<br>
  使い心地の良さと確かな効果に<br>
  こだわりつづけて開発しました。<br>
  決して妥協せず何度も試行錯誤を重ねて、<br>
  ようやくあなたに自信を持ってお届けできる<br>
  「キレイモ品質」の製品ができました！</p>
<div class="productName">高性能脱毛器&フェイシャルシェーバー</div>
</section>

<section class="epi-nav">
<nav>
  <ul>
    <li><a href="#epc" data-scroll>オリジナル脱毛器<br><small>KIREIMO EPI PHOTO CRYSTAL</small></a></li>
    <li><a href="#sfs" data-scroll>フェイシャルシェーバー<br><small>KIREIMO SMOOTH FACIAL SHAVE</small></a></li>
  </ul>
</nav>
</section>

<section id="epc" class="product-content">
  <div class="head">
    <div class="head-lead"><p>日本最大級(※)のハイパワーなのに痛くない。<br>
      スキンケアにもなる、家庭用脱毛器</p>
      <strong>オリジナル脱⽑器 </strong>
      <h2>KIREIMO<br>
        EPI PHOTO CRYSTAL</h2>
    </div>
    <div class="head-img"></div>
  </div>
  <p class="small right">※メーカー調べ　2021年2月時点　サファイアクリスタルによるW冷却機能を採用した家庭用「光美容器」として</p>

<div class="container">
  <div class="cont">
    <h3><span>最⾼峰のハイパワー&<br class="sp_only">日本初のW冷却で痛みが少ない！</span></h3>
    <div class="flex">
    <img src="{{ asset('img/epilator/epc-exp.jpg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/epc-exp.jpg')) }}" alt="">
    <div class="exp">
      <p><strong>「痛くない」 「気軽」 「キレイになれる」</strong><br>
        すべてのキレイを叶えるキレイモだから実現できた家庭用脱毛器。<br>
        ハイパワーなのに心地よくつかえる
        ヒミツは、日本初のW冷却機能を搭載しているから。照射口には熱に強い「サファイアクリスタル」をつかい、痛みが少なく照射後の冷却も必要なし。<br>
        さらに美肌を促す波長だから、脱毛とキレイがWで叶う。</p>
      <div class="check">
        <h4><span>さらにここがキレイモ品質</span></h4>
        <ul>
          <li>家庭用脱毛器最高峰のハイパワー：4.5J/cm2</li>
          <li>照射回数は日本最大級の100万発以上。<br>
            全身一回の照射が約330発として 3,000回以上全身ケアできる! <br>
            ご家族みんなでも半永久的にお使いいただけます</li>
          <li>冷却ジェル不要で、気になる時にいつでも使える</li>
          <li>3色から好きな色を選べる！</li>
        </ul>
      </div>
    </div>
   </div>
  </div>

  <div class="border">
    <div>
    <h3>冷却ジェルを使用しない  キレイモの脱毛</h3>
    <h4><span>冷たく不快な思いをしないだけではなく、<br class="sp_only">スピーディーに全身のお手入れが完了します。</span></h4>
    <div class="flex">
      <img src="{{ asset('img/epilator/sfs-cut.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/sfs-cut.png')) }}" alt="">
      <div class="txt">
      <p>光脱毛を利用した一般的な脱毛サロンでは、<br>
        光の熱からお肌を守るために冷却ジェルを使用します。<br>
        それが「脱毛サロン＝寒い」といわれる原因でした。</p>
        
      <p>冷却ジェルを使用しないキレイモでは、<br>
        お客様が冷たくて不快な思いをしないだけでなく、<br>
        ジェルを塗る時間も、拭き取る時間も必要ないため、<br>
        その分施術に十分な時間をかけることが可能です。</p>
        
      <p><strong>そんな「キレイモ品質」をおうちでも。</strong></p>
      </div>
    </div>
    </div>
  </div>

  <div class="inq">
    <div class="flex">
    <img src="{{ asset('img/epilator/epc-product.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/epc-product.png')) }}" alt="">
    <p>家庭用脱毛器は<br class="sp_only"><span>キレイモサロンと</span><span>キレイモ公式オンラインストアにて</span><span>販売しております。</span><br>
    キレイモにて脱毛コースを<br class="sp_only">ご契約のお客様には特別に<span>会員価格をご提供いたしております。</span><br>
    お通いのキレイモサロンで<br class="sp_only">お求めいただきますよう<span>お願い申し上げます。</span><br>
キレイモ公式オンラインストアは<span>一般価格にて販売しております。</span>
</p>
    </div>
  </div>
</div>
</section>

<section id="sfs" class="product-content">
  <div class="head">
    <div class="head-lead"><p>お肌をなでるような剃り心地。<br>
      見た目もかわいいコンパクトシェーバー</p>
      <strong>フェイシャルシェーバー</strong>
      <h2>KIREIMO<br>
        SMOOTH FACIAL SHAVE</h2>
    </div>
    <div class="head-img"></div>
  </div>

<div class="container">
  <div class="cont">
    <h3><span>キレイモが開発した、お肌をなでるような<br class="sp_only">剃り味のコンパクトシェーバー！</span></h3>
    <div class="flex">
    <img src="{{ asset('img/epilator/sfs-exp.jpg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/sfs-exp.jpg')) }}" alt="">
    <div class="exp">
      <p>お顔の気になるところをさっとお⼿⼊れできるフェイシャルシェーバー。<br>
        コスメポーチにもぴったりの⼤きさと約30gの軽さで持ち運びも楽々。<br>
        デリケートな顔も撫でるように剃れてダメージレス。</p>
      <div class="check">
        <h4><span>さらにここがキレイモ品質</span></h4>
        <ul>
          <li>3⾊から好きな色を選べる！</li>
          <li>刃がお肌に直接触れない安全設計</li>
          <li>サージカルステンレスを使用し、金属アレルギーの方にも優しい<br>
            <small>※全ての方にアレルギー反応が出ないとは限りません</small></li>
          <li>シェーバー部分は簡単に分解・⽔洗い可能なので、いつでも清潔</li>
        </ul>
      </div>
    </div>
   </div>
  </div>

  <div class="border">
    <div>
    <h3>小さくて持ち運びもラクラク！<br class="sp_only">手軽に使えるフェイシャルシェーバー</h3>
    <div class="flex">
      <div class="lip">
      <h4>リップと同じくらいコンパクト！</h4>
      <img src="{{ asset('img/epilator/lip.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/lip.png')) }}" alt="">
      <p><strong>軽量＆可愛いサイズで持ち運びに最適！</strong><br>
          外出先で「はっ！」とした経験はありませんか？<br>
          これさえあれば、顔はもちろん指や手首など<br class="mid">
          気づいたムダ毛にすぐ対処できる！<br>
          約30g 約12cm(キャップ込み)</p>
      </div>
      <div class="table">
        <h4>シェーバー比較表</h4>
        <table>
          <tr>
            <th></th>
            <td class="kireimo">kireimo<br>フェイシャル<br>シェーバー</td>
            <td>カミソリ型電動<br>フェイシャル<br>シェーバー<br>(A社製)</td>
            <td>I字カミソリ</td>
          </tr>
          <tr>
            <th></th>
            <td class="kireimo"><img src="{{ asset('img/epilator/table-img1.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/table-img1.png')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/table-img2.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/table-img2.png')) }}" alt=""><br><small>※イメージ</small></td>
            <td><img src="{{ asset('img/epilator/table-img3.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/table-img3.png')) }}" alt=""><br><small>※イメージ</small></td>
          </tr>
          <tr>
            <th>デザイン性</th>
            <td class="kireimo"><img src="{{ asset('img/epilator/circle.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/circle.svg')) }}" alt=""><small>※</small></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
          </tr>
          <tr>
            <th>肌への優しさ</th>
            <td class="kireimo"><img src="{{ asset('img/epilator/circle.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/circle.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/tri.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/tri.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
          </tr>
          <tr>
            <th>お手軽さ</th>
            <td class="kireimo"><img src="{{ asset('img/epilator/circle.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/circle.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/tri.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/tri.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
          </tr>
          <tr>
            <th>充電式</th>
            <td class="kireimo"><img src="{{ asset('img/epilator/circle.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/circle.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
            <td><img src="{{ asset('img/epilator/batsu.svg') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/batsu.svg')) }}" alt=""></td>
          </tr>
          <tr>
            <th>刃のもちの<br class="sp_only">良さ</th>
            <td class="kireimo">1-2年</td>
            <td>1ヶ月程度</td>
            <td>1週間程度</td>
          </tr>
          <tr>
            <th>年間の費用</th>
            <td class="kireimo"><strong>4,378</strong>円</td>
            <td><small>本体約4,400円+<br>
              替刃約1,870円<br>
              ×12ヶ月</small><br>
              =<strong>26,840</strong>円</td>
            <td><small>一本約220円×<br>
              一月に4本<br>
              ×12ヶ月</small><br>
              =<strong>10,560</strong>円
            </td>
          </tr>
        </table>
        <p class="chu"><small><span>※</span>ダイヤモンドのようなカッティング、三色展開</small></p>
      </div>
    </div>
  </div>
  </div>

  <div class="inq">
    <div class="flex">
      <img src="{{ asset('img/epilator/sfs-product.png') }}?rev={{ date('YmdHis', filemtime(public_path() . '/img/epilator/sfs-product.png')) }}" alt="">
    <p>フェイシャルシェーバーは<br class="sp_only"><span>キレイモサロンと</span><span>キレイモ公式オンラインストアにて</span><span>販売しております。</span></p>
  </div>
  </div>
</div>

</section>

<section id="faq">
  <div class="container">
<h2>Q&A - 脱毛器編</h2>
<div class="box">
  <div class="q"><p>フラッシュが熱く感じたり、赤みや痛みが出ることはありますか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>使い始めはレベル１でご使用ください。慣れてきたら徐々にレベルを上げてください。<br>
    フラッシュは多少の熱さを感じますが、お肌には問題ありません。<br><br>
   赤みや痛みを感じた場合は、ただちに使用を中止して清潔な冷たいタオルで３０分以上お肌を冷却してください。<br>
    治らない場合は、それ以上の使用はせずに医師にご相談ください。</p>
  </div>
</div>
<div class="box">
  <div class="q"><p>ケアの周期や使用頻度はどれくらいですか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>最初の2ヶ月は2週間に1度、3ヶ月目以降は4〜8週間に一度の頻度がおすすめです。<br>
    ※効果の感じ方は、使用する部位や個人によって異なります。</p></div>
</div>
<div class="box">
  <div class="q"><p>1日の使用頻度はどれくらいですか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>オートモードは1部位5分を上限としてください。<br>
    マニュアルモードは同じ部位に1日3回以上、フラッシュを照射しないでください。<br>
    熱による刺激が強く、肌トラブルの原因となります。</p></div>
</div>
<div class="box">
  <div class="q"><p>日焼けをしているのですが脱毛できますか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>原則として、日焼けをしてから２週間以上期間をあけてからの使用を推奨しております。<br>
    日焼け後のお肌に照射しますと、お肌に負担が掛かる可能性があり、肌トラブルの原因となります。<br>
    また施術後は肌がデリケートな状態になっているため、使用後2週間は日焼けをしないよう対策をお願いしています。<br>
    セルフタンニングも同様です。</p></div>
</div>
<div class="box">
  <div class="q"><p>故障かな？と思ったら</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>ご迷惑をお掛けして申し訳ございません。<br>
故障かな？と思われた場合は、<a href="https://store.kireimo.jp/about" target="_blank">こちらのページ</a>の『Q：故障かなと思ったら』をご確認ください。<br>
代表的な現象に関する対処方法をFAQとしてご用意しております。</p></div>
</div>


<h2>Q&A - フェイシャルシェーバー編</h2>
<div class="box">
  <div class="q"><p>どれくらい持ちますか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>USB充電タイプで、60分充電して120分使用できます。</p></div>
</div>
<div class="box">
  <div class="q"><p>清掃の仕方を教えてください</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>シェーバーの毛のつまりは専用のブラシをご使用いただき、毛を取り除いてください。<br>
    また、シェーバー部分は水洗い可能なので、いつでも清潔にご使用できます。</p></div>
</div>
<div class="box">
  <div class="q"><p>金属アレルギーでも使用可能でしょうか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>金属アレルギーの方にも優しいサージカルステンレスにを使用しております。<br>
    万が一、赤みや痛みを感じた場合は、ただちに使用を中止してください。<br>
    治らない場合は、それ以上の使用はせずに医師にご相談ください。</p></div>
</div>
<div class="box">
  <div class="q"><p>お風呂でも使用できますか？</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>本品は防水仕様ではございません。お風呂での使用はお控えください。<br>
    シェーバーのヘッドは本体から外している状態のときのみ、水洗い可能です。<br>
    ※本体全体は水洗いできません。</p></div>
</div>
<div class="box">
  <div class="q"><p>故障かな？と思ったら</p></div>
  <a class="open menu__item__link js-menu__item__link"></a>
  <div class="a"><p>ご迷惑をお掛けして申し訳ございません。<br>
故障かな？と思われた場合は、<a href="https://store.kireimo.jp/about" target="_blank">こちらのページ</a>の『Q：故障かなと思ったら』をご確認ください。<br>
代表的な現象に関する対処方法をFAQとしてご用意しております。</p></div>
</div>
</div>
</section>
@endsection