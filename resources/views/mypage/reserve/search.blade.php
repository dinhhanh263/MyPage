@extends('common.mypage_base')

@section('content')
	<main class="container" id="new_rsv">
		<section class="main">
			<div class="main-content">
				<div class="page-title">
					<h1 class="title">新規ご予約</h1>
				</div>
				<div class="phase-step"><img src="{{ asset('img/phase-step2.png') }}" alt="検索結果"></div>
				<div class="box">
					<h2 class="stepBar pt-30">検索条件</h2>
					<div class="boxInner line-h-1">
						<p class="boxInnerItem"><span class="bold">日時</span>&emsp;&emsp;：{{ $ary_disp_data['target_date'] }}</p>
						<p class="boxInnerItem"><span class="bold">店舗</span>&emsp;&emsp;：{{ implode(',', $ary_disp_data['target_shop']) }}</p>
					</div>
				</div>
				<div class="mainTxtArea pt-30">
					<form method="get" action="{{ route('mypageNewReserve', ['contract_id' => $target_contract_id]) }}">
						<button class="btn btnM toSearchBtn">日付・店舗を検索する</button>
					</form>
				</div>

				<form name="reservationActionForm2" id="reserve_form" method="post" action="">
					@csrf
					<p>時間を確定してください。</p>

					@foreach ($ary_disp_data['reserve_data'] as $key => $val)
						@if ($key != 'target_shop')
							<div class="resultCalendar">
								<p>{{ date('Y年m月d日', strtotime($key)) . '(' . config('const.week.' . date('w', strtotime($key))) . ')' }}</p>
							</div>
						@endif

						<table class="result">
							<thead class="resultHead">
								<tr align="CENTER" valign="MIDDLE">
									<th>&nbsp;</th>
									@foreach ($ary_disp_data['target_shop'] as $shop_id => $shop_name)
										<th>{{ $shop_name }}</th>
										@if ($loop->remaining == 0 && $loop->iteration <= 4)
											@for ($i = $loop->iteration; $i < 4; $i++)
												<th>&nbsp;</th>
											@endfor
										@endif
									@endforeach
								</tr>
							</thead>

							<tbody class="resultBody">
								@foreach ($val as $time_cd => $reserve_info)
									<tr align="CENTER" valign="MIDDLE">
										<th>{{ Config::get('const.hope_time.' .$time_cd) }}</th>
										@foreach ($reserve_info as $shop_id_sub => $value)
											@if ($value == Config::get('const.reservable_ng'))
												<td class="xMark">×</td>
											@else
												<td>
													<span style="cursor: pointer" onclick="pushConfirm(1, '{{ $key }}', {{ $shop_id_sub }}, {{ $time_cd }});">◯</span>
												</td>
											@endif
											@if (count($ary_disp_data['target_shop']) == $loop->iteration)
												@for ($i = count($ary_disp_data['target_shop']); $i < 4; $i++)
													<td class="xMark">-</td>
												@endfor
											@endif
										@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					@endforeach
				</form>
				<div class="btnArea-3">
					<form method="get" action="{{ route('mypageNewReserve', ['contract_id' => $target_contract_id]) }}">
						<button class="btn btnM toSearchBtn">日付・店舗を検索する</button>
					</form>
				</div>
			</div>
		</section>
	</main>
	<div id="notify" class="hide"></div>
	<script src="{{ asset('js/reserve.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/reserve.js')) }}"></script>
	<script src="{{ asset('js/moment.js') }}?date={{ date('YmdHis', filemtime(public_path() . '/js/moment.js')) }}"></script>
@endsection