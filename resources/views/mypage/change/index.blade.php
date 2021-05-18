@extends('common.mypage_base')

@section('content')
	<main class="container" id="change_rsv">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">ご予約の変更</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-chg-step1.png') }}" alt="空き状況検索" width="500px"></div>
				<h2 class="stepBar pt-30 pb-20">変更したい日時、店舗の空き状況を検索してください</h2>
				<form name="reservationActionForm" id="" method="post" action="{{ route('mypageReserveChangeSearch') }}">
					@csrf
					<table class="search">
						<tbody>
							@for ($i = 1; $i <= 3; $i++)
								<tr><!-- 日付選択エリア -->
									@if ($i == 1)
										<th><span class="mandatory">必須</span><span class="label date">日付</span></th>
									@else
										<th class="border-none"></th>
									@endif
									<td class="date-area-wrap pb-20">
										<div class="date-area">
											<div class="select selectDate">
												<select id="m{{ $i }}" class="month" name="search_date[{{ $i }}][month]">
													@foreach ($reserve_conditions['reserve']['month'] as $month_val)
														<option value="{{ $month_val }}" {{ !empty($ary_return_data['search_date'][$i]['month']) && $ary_return_data['search_date'][$i]['month'] == $month_val ? 'selected' : '' }}>{{ $month_val }}</option>
													@endforeach
												</select>
												<span class="arrow"></span>
											</div>
											<div class="text-m-area"><span class="text-m">月</span></div>
											<div class="select selectDate">
												<select id="d{{ $i }}" class="day" name="search_date[{{ $i }}][day]">
													@for ($iDay = 1; $iDay <= 31; $iDay++)
														<option value="{{ $iDay }}" {{ empty($ary_return_data['search_date'][$i]['month']) ? ($reserve_conditions['start']['day'] == $iDay ? 'selected' : '') : $ary_return_data['search_date'][$i]['day'] == $iDay ? 'selected' : '' }}>{{ $iDay }}</option>
													@endfor
												</select>
												<span class="arrow"></span>
											</div>
											<div class="text-d-area"><span class="text-d">日</span></div>
										</div>
									</td>
								</tr>
							@endfor

							<!--  テキスト -->
							<tr>
								<th class="border-none"></th>
								<td class="td-second">
									<div class="mainTxtArea">
										<div class="mainTxtInner">
											<p>変更したい日時、店舗の空き状況を検索してください。</p>
										</div>
										<div class="mainTxtInner">
											@if (!empty($ary_error_msg))
												<p class="formError pb-14">
													{{ $ary_error_msg }}
												</p>
											@endif
											<p class="fc-red">
												※{{ $reserve_conditions['start']['year'] }}年{{ $reserve_conditions['start']['month'] }}月{{ $reserve_conditions['start']['day'] }}日〜{{ $reserve_conditions['end']['year'] }}年{{ $reserve_conditions['end']['month'] }}月{{ $reserve_conditions['end']['day'] }}日の日付をご選択いただけます。
											</p>
										</div>
									</div>
								</td>
							</tr>

							<tr><!-- 店舗選択エリア(地域) -->
								<th><span class="mandatory">必須</span><span class="label shop">エリア</span></th>
								<td class="pt-none">
									<div class="select selectZoon">
										<select id="areaList" name="search_shop_area">
											<option value="0">選択してください</option>
											@foreach ($ary_area as $area_key => $area_val)
												<option value="{{ $area_key }}" {{ !empty($ary_return_data['search_shop_area']) && $ary_return_data['search_shop_area'] == $area_key ? 'selected' : '' }}>{{ $area_val }}</option>
											@endforeach
											</select>
										<span class="arrow"></span>
									</div>
								</td>
							</tr>

							<tr><!-- テキスト -->
								<th class="border-none"></th>
								<td class="td-second"></td>
							</tr>
							<tr id="shop_area" style="display:none;"><!-- 店舗選択エリア(店舗名) -->
								<th><span class="mandatory">必須</span><span class="label shop">店舗</span></th>
								<td class="td-fourth">
									@for ($i = 1; $i <= 4; $i++)
										<div class="select selecteVertical" style="display:none;" id="shop_area_select{{ $i }}">
											<select class="shop_list_select" id="shop_select{{ $i }}" name="search_shop[{{ $i }}]" placeholder="店舗名を選択してください" {{ !empty($ary_return_data['search_shop']) && $ary_return_data['search_shop'] == $i ? 'selected' : '' }}>
											</select>
											<span class="arrow"></span>
										</div>
									@endfor
									<p class="formError"></p>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="btnArea-3">
						<button type="submit" class="btn btnM shop_search_item" value=false disabled style="background-color:#c0c0c0">検索する</button>
					</div>
				</form>
			</div>
		</section>
	</main>
	<!-- 来店周期モーダル -->
	@if (!empty($ary_period_data))
		@include('common.period')
	@endif
	<div id="notify" class="hide"></div>
	<input type="hidden" name="start_date" value="{{ $reserve_conditions['start']['month'] . '/' . $reserve_conditions['start']['day'] }}" />
	<input type="hidden" name="end_date" value="{{ $reserve_conditions['end']['month'] . '/' . $reserve_conditions['end']['day'] }}" />
	<script src="{{ asset('js/reserve.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/reserve.js')) }}"></script>
	<script src="{{ asset('js/moment.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/moment.js')) }}"></script>
@endsection