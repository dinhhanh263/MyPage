<div class="careful-nutrient"><!-- あなたの気をつけるべき栄養素-->
	<h2 class="stepBar pt-30">あなたの気をつけるべき栄養素</h2>
	<div class="careful-nutrient-wrap">
		@if ($ary_gene_data['intakeRestrictionId'] == config('gene_const.intake_restrictions.carbohydrate'))
		<!-- 糖質 -->
			<div class="careful-nutrient-carbohydrate">
				<h3>糖質</h3>
				<p>三大栄養素のひとつで生きていく上でなくてはならない栄養素のひとつですが摂り過ぎは肥満の原因に！<br>炭水化物=糖質+食物繊維</p>

				<h4>★糖質量の多い食材</h4>
				<h5><span>■</span>麺類</h5>
				<img src="{{ asset('img/gene/carbohydrate-img-1.jpg') }}">
				<p class="careful-nutrient-text">麺類には、糖質が多く含まれています。<br>
				特に、ラーメン、パスタなどは糖質量が多く、脂質も練り込まれているため、要注意！<br>
				麺類をお召し上がりになりたい際は糖質、脂質の少ない蕎麦がおすすめです。
				</p>

				<h5><span>■</span>米類</h5>
				<img src="{{ asset('img/gene/carbohydrate-img-2.jpg') }}">
				<p class="careful-nutrient-text">お米は、糖質量の多い食べ物ではありますが、腹持ちもよく、脂質などの保有量が少ないためお勧めの食材となります！<br>
				また、玄米は食物繊維やビタミン、ミネラルも多く含んでいるため特におすすめの食材です。咀嚼を意識し、量に気をつければダイエットの強い味方になってくれます。<br>
				一緒に食べるおかずなどで、糖質や脂質をとりすぎないように注意しましょう！
				</p>

				<h5><span>■</span>パン類</h5>
				<img src="{{ asset('img/gene/carbohydrate-img-3.jpg') }}">
				<p class="careful-nutrient-text last">パンにも糖質は多く含まれております。<br>
				パンは、１食あたりの糖質量はお米やパスタに比べると少ないですが、腹持ちが悪いため、お腹が空きやすいです。<br>
				また、菓子パンなどは、糖質、脂質共に保有量もかなり多いため、ダイエットをする上では、特に要注意です！<br>
				パンを食べる際は、食物繊維が多く含まれている全粒粉を使ったパンやライ麦パンなどをよく噛んで食べるのがお勧めです。
				</p>

				<h4>★意外と糖質が多い食材</h4>
				<p>野菜：さつまいも、かぼちゃ、とうもろこし、じゃがいも、ごぼう、レンコン、たまねぎ、にんじん<br>
					その他：豆類、春雨、乳製品、調味料類
				</p>
				<p class="red-comment">糖質をマスターしたら脂質の量も見てみましょう！<br>
					糖質＋脂質はダイエットの大敵！<br>
					ラーメン、菓子パンなどは糖質、脂質ともに多いので特に要注意！
				</p>
			</div>
		@elseif ($ary_gene_data['intakeRestrictionId'] == config('gene_const.intake_restrictions.lipid'))
			<!--脂質 -->
			<div class="careful-nutrient-lipid">
				<h3>脂質</h3>
				<p>脂質とは、三大栄養素の１つで生きていく上でなくてはならない栄養素の１つですが取り過ぎは肥満の原因に！</p>

				<h4>★脂質は大きく分けて２種類</h4>
				<h5><span>■</span>不飽和脂肪酸</h5>
				<div class="lipid-img-content">
					<div class="lipid-img-1">
						<p class="lipid-img-ttl">不飽和脂肪酸（良い脂）</p>
						<img src="{{ asset('img/gene/lipid-img-1.jpg') }}">
						<p class="lipid-img-txt">常温で液体<br>植物性や魚の油に多い</p>
					</div>
					<div>
						<p class="lipid-ttl">不飽和脂肪酸を含む食品</p>
						<p>オリーブオイル・ゴマ油・アボカド・大豆類・ナッツ類・アマニ油・えごま油・青魚など</p>
						<p class="red-comment">良い油だからといって摂取のしすぎはNGです。油は小さじ１杯、アボカドは１日半分、ナッツは20gまでがおすすめです。</p>
					</div>

				</div>

				<h5><span>■</span>飽和脂肪酸</h5>
				<div class="lipid-img-content">
					<div class="lipid-img-1">
						<p class="lipid-img-ttl">飽和脂肪酸（悪い脂）</p>
						<img src="{{ asset('img/gene/lipid-img-2.jpg') }}">
						<p class="lipid-img-txt">常温で個体のものが多い<br>動物性の油に多い</p>
					</div>
					<div>
						<p class="lipid-ttl">飽和脂肪酸を含む食品</p>
						<p>バター・生クリーム・脂身の多いお肉・鶏の皮・ベーコン・ソーセージ・ポテトチップス・チョコレート・クッキー・ビスケット・ドーナツなど</p>
						<p class="red-comment">血液の流れを悪くする作用があるので、ダイエット中は食べ過ぎに注意！とくにトランス脂肪酸は控えましょう。</p>
					</div>
				</div>

				<div class="text-area">
					<p>特にトランス脂肪酸に気をつけましょう</p>
					<p class="s-red">マーガリン・ショートニング（植物性の油を人工的に固めたもの）・植物性ホイップクリーム・ホットケーキなどのミックス粉・パイ生地などは特にトランス脂肪酸が入っております。<br>
						これらの食品は、悪玉コレステロールを多く含んでいるため、肥満の原因となります。<br>
						また、食べ過ぎると動脈硬化などの生活習慣病を引き起こす可能性もあります。<br>
						悪い脂質は、健康維持の観点でも良くない成分なので、極力控えましょう。
					</p>
				</div>

			</div>
		@else
			<!-- タンパク質 -->
			<div class="careful-nutrient-protein">
				<h3>たんぱく質</h3>
				<p>たんぱく質とは、三大栄養素の１つで筋肉や臓器、肌、髪、爪などを作り、栄養素の運搬を行い、体を動かすエネルギーになります。</p>

				<h4>★たんぱく質を多く含む食材</h4>
				<h5><span>■</span>肉</h5>
				<p>お肉はたんぱく質を多く含みます。ただお肉の油は脂質ですので下記がおすすめ順となります。</p>
				<div class="protein-img-1">
					<div>
						<img src="{{ asset('img/gene/protein-img-1_1.jpg') }}">
						<p><span>鶏肉</span><br>ムネ（皮なし）<br>モモ（皮なし）<br>ササミ</p>
					</div>
					<div class="arrow"></div>
					<div>
						<img src="{{ asset('img/gene/protein-img-1_2.jpg') }}">
						<p><span>豚肉</span><br>ロース<br>肩（赤身）<br>モモ</p>
					</div>
					<div class="arrow"></div>
					<div>
						<img src="{{ asset('img/gene/protein-img-1_3.jpg') }}">
						<p><span>牛肉</span><br>肩（赤身）<br>モモ<br>ヒレ</p>
					</div>
				</div>

				<h5><span>■</span>魚・貝</h5>
				<div class="protein-img-2">
					<img src="{{ asset('img/gene/protein-img-2_1.jpg') }}" alt="鮭">
					<img src="{{ asset('img/gene/protein-img-2_2.jpg') }}" alt="鯵">
					<img src="{{ asset('img/gene/protein-img-2_3.jpg') }}" alt="鯖">
					<img src="{{ asset('img/gene/protein-img-2_4.jpg') }}" alt="海老">
					<img src="{{ asset('img/gene/protein-img-2_5.jpg') }}" alt="浅蜊">
				</div>

				<h5><span>■</span>手軽にたんぱく質を摂れる食材</h5>
				<div class="protein-img-3">
					<img src="{{ asset('img/gene/protein-img-3_1.jpg') }}" alt="納豆">
					<img src="{{ asset('img/gene/protein-img-3_2.jpg') }}" alt="蕎麦">
					<img src="{{ asset('img/gene/protein-img-3_3.jpg') }}" alt="ブロッコリー">
					<img src="{{ asset('img/gene/protein-img-3_4.jpg') }}" alt="アボカド">
					<img src="{{ asset('img/gene/protein-img-3_5.jpg') }}" alt="バナナ">
					<img src="{{ asset('img/gene/protein-img-3_6.jpg') }}" alt="とうもろこし">
				</div>
			</div>
		@endif
	</div>
</div>