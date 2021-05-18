@extends('common.mypage_base')

@section('content')
	<main class="container" id="gene">
		<section class="main">
			<div class="page-title"><h1 class="title">診断結果</h1></div>
			<div class="gene-type">
				<div class="gene-type-inner">
					<img src="{{ asset(config('gene_const.gene_master.' . $ary_gene_data['geneTypeId'] . '.img')) }}" alt="{{ config('gene_const.gene_master.' . $ary_gene_data['geneTypeId'] . '.name') }}">
					<h2><span>あなたは<br class="pc"><span class="sp"><strong>{{ config('gene_const.gene_master.' . $ary_gene_data['geneTypeId'] . '.name') }}</strong><br class="pc">タイプです。</span></span></h2>
				</div>
				<div class="bubble-eval">
					<div class="bubble">
						<p>{{ config('gene_const.gene_master.' . $ary_gene_data['geneTypeId'] . '.description') }}</p>
						<div class="bubble-link"><a href="{{ config('env_const.kireimo_premium_top') }}gene/" target="_blank">遺伝子タイプについて</a></div>
					</div>
					<div class="gene-info">
						<h3>★肥満リスク</h3>
						<p>{{ config('gene_const.obesity_risk_id.' . $ary_gene_data['obesityRiskId']) }}</p>
						<h3>★遺伝子タイプ分類</h3>
						<p>{{ config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.group') }}</p>
						<h3>★脂肪がつきやすい箇所</h3>
						<p>{{ config('gene_const.obesity_pattern.' . $ary_gene_data['obesityPatternId']) }}</p>
					</div>
				</div>
			</div>
			<div class="risk-level"><!-- あなたの肥満リスクレベル -->
				<h2 class="stepBar pt-30">あなたの肥満リスクレベル</h2>
				<div class="risk-wrap">
					<img src="{{ asset(config('gene_const.obesity_risk_img.' . $ary_gene_data['obesityRiskLevel'])) }}">
					<div class="risk-label"><p>普通</p><p>非常に高い</p></div>
				</div>
				<p class="risk-text">あなたのカラダは、<span>肥満リスクレベル{{ $ary_gene_data['obesityRiskLevel'] }}/12</span> です。</p>
			</div>

			<div class="gene-eval"><!-- あなたの遺伝子別評価 -->
				<h2 class="stepBar pt-30">あなたの遺伝子別評価</h2>
				<div class="mainTxtArea">
					<p>{{ config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.msg') }}</p>
				</div>
				<div class="gene-eval-cont">
					<div class="cont-inner sugar">
						<p><img src="{{ asset('img/gene/icon-sugar.png') }}" alt=""><br>糖質代謝に関する<br>遺伝子</p>
						<div class="arrow"></div>
						<div class="recommend-img"><img src="{{ asset(config('gene_const.suger_risk_img.' . $ary_gene_data['sugarRiskId'])) }}"></div>
					</div>
					<div class="cont-inner lipid">
						<p><img src="{{ asset('img/gene/icon-lipid.png') }}" alt=""><br>脂質代謝に関する<br>遺伝子</p>
						<div class="arrow"></div>
						<div class="recommend-img"><img src="{{ asset(config('gene_const.fat_risk_img.' . $ary_gene_data['fatRiskId'])) }}"></div>
					</div>
					<div class="cont-inner muscle">
						<p><img src="{{ asset('img/gene/icon-protein.png') }}" alt=""><br>筋肉のつきやすさ<br>に関する遺伝子</p>
						<div class="arrow"></div>
						<div class="recommend-img"><img src="{{ asset(config('gene_const.protein_risk_img.' . $ary_gene_data['proteinRiskId'])) }}"></div>
					</div>
				</div>
			</div>

			<div class="intake-status"><!-- あなたの栄養素の摂取状況 -->
				<h2 class="stepBar pt-30">あなたの栄養素の摂取状況</h2>
				<div class="intake-status-wrap">
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.vitamin_b1.' . ($ary_nutrition_data['vitaminB1Lack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">ビタミンB1</p>
					</div>
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.vitamin_b2.' . ($ary_nutrition_data['vitaminB2Lack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">ビタミンB2</p>
					</div>
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.vitamin_b6.' . ($ary_nutrition_data['vitaminB6Lack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">ビタミンB6</p>
					</div>
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.niacin.' . ($ary_nutrition_data['niacinLack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">ナイアシン</p>
					</div>
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.pantothenic_acid.' . ($ary_nutrition_data['pantothenicAcidLack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">パントテン酸</p>
					</div>
					<div class="content-inner">
						<img src="{{ asset(config('gene_const.nutrients_img.l_arnitine.' . ($ary_nutrition_data['lCarnitineLack'] == true ? 0 : 1))) }}">
						<p class="nutrient-name">L-カルニチン</p>
					</div>
				</div>
				<div class="intake-status-text">
					<p>エネルギーが燃えるまでの過程に、６つの栄養素が必要と言われています。</p>
					<ul>
						<li>糖質を代謝するために必要なビタミンＢ１</li>
						<li>脂質を代謝するために必要なビタミンＢ２</li>
						<li>たんぱく質から筋肉や骨を作るために必要なビタミンＢ６</li>
						<li>代謝した脂肪を運ぶパントテン酸とナイアシン</li>
						<li>脂肪を燃やすために必要なＬ－カルニチン</li>
					</ul>
					<p>各栄養素の相互作用で、エネルギーは燃やされていきます。<br>各栄養素をバランス良く摂取することが、美しいカラダ作りの秘訣です。</p>
				</div>
			</div>

			<!-- 気をつけるべき栄養素 -->
			@include('mypage.gene.caution_nutrients')

			<div class="gene-content3 recommend-ote"><!-- あなたのおすすめの食べる順番 -->
				<h2 class="stepBar pt-30">あなたのおすすめの食べる順番</h2>
				<div class="recommend-wrap">
					<div class="recommend-inner">
						<img src="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_img_1')) }}" alt="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_alt_1')) }}">
						<div class="arrow"></div>
						<img src="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_img_2')) }}" alt="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_alt_2')) }}">
						<div class="arrow"></div>
						<img src="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_img_3')) }}" alt="{{ asset(config('gene_const.gene_evaluation.' . $ary_gene_data['obesityRiskEvaluationId'] . '.eat_order_alt_3')) }}">
					</div>
				</div>
			</div>

			<div class="gene-content2 recommend-exercise"><!-- あなたのおすすめの運動 -->
				<h2 class="stepBar pt-30">あなたのおすすめの運動</h2>
				<div class="recommend-wrap">
					<div class="recommend-inner">
						<h3>{{ config('gene_const.recommended_motion.' . $ary_gene_data['recommendedExercise'] . '.main_motion') }}</h3>
						<img src="{{ asset(config('gene_const.recommended_motion.' . $ary_gene_data['recommendedExercise'] . '.main_motion_img')) }}">
					</div>
					<div class="recommend-inner">
						<h3>{{ config('gene_const.recommended_motion.' . $ary_gene_data['recommendedExercise'] . '.sub_motion') }}</h3>
						<img src="{{ asset(config('gene_const.recommended_motion.' . $ary_gene_data['recommendedExercise'] . '.sub_motion_img')) }}">
					</div>
				</div>
				<p class="recommend-text">{{ config('gene_const.recommended_motion.' . $ary_gene_data['recommendedExercise'] . '.msg') }}</p>
			</div>

			<div class="gene-content2 recommend-suppl"><!-- あなたのおすすめのサプリ -->
				<h2 class="stepBar pt-30">あなたのおすすめのサプリ</h2>
				<div class="recommend-wrap">
					<div class="recommend-inner">
						<span><img src="{{ asset(config('gene_const.recommended_supplement.' . $ary_gene_data['mainSupplementId'] . '.img')) }}"></span>
						<div>
							<h3>{{ config('gene_const.recommended_supplement.' . $ary_gene_data['mainSupplementId'] . '.name') }}</h3>
							<p class="recommend-text">{!! nl2br(config('gene_const.recommended_supplement.' . $ary_gene_data['mainSupplementId'] . '.msg')) !!}
							</p>
						</div>
					</div>
				</div>
			</div>

			<div class="gene-content2 recommend-prg"><!-- あなたにオススメのエステプログラム -->
				<h2 class="stepBar pt-30">あなたにオススメのエステプログラム</h2>
				<div class="recommend-wrap">
					<div class="recommend-inner">
						<h3>{{ config('gene_const.treatment_equipments.' . $ary_gene_data['mainTreatmentEquipmentId'] . '.name') }}</h3>
						<img src="{{ asset(config('gene_const.treatment_equipments.' . $ary_gene_data['mainTreatmentEquipmentId'] . '.img')) }}">
						<p class="recommend-text">{{ config('gene_const.treatment_equipments.' . $ary_gene_data['mainTreatmentEquipmentId'] . '.description') }}</p>
					</div>
					<div class="recommend-inner">
						<h3>{{ config('gene_const.treatment_equipments.' . $ary_gene_data['subTreatmentEquipmentId'] . '.name') }}</h3>
						<img src="{{ asset(config('gene_const.treatment_equipments.' . $ary_gene_data['subTreatmentEquipmentId'] . '.img')) }}">
						<p class="recommend-text">{{ config('gene_const.treatment_equipments.' . $ary_gene_data['subTreatmentEquipmentId'] . '.description') }}</p>
					</div>
				</div>
			</div>
		</section>
	</main>
@endsection