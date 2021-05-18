<div class="main-slider main-slider-1 main-slider-active">
	<div class="swiper-container tab-contents">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<div class="customer-wrap cont-wrap">
					<h2><span>お客様情報</span></h2>
					<div class="customer-inner">
						<div class="customer-icon-btn">
							<div>
								<a href="{{ route('mypageMember') }}">
									@if (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.normal'))
										<!-- 通常 -->
										<img src="{{ asset('img/icon-setting.png') }}" alt="customer icon">
									@elseif (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.abnormal') || session()->get('contract_abnormal_flg') == config('const.contract_abnormal.payment_error') || session()->get('contract_abnormal_flg') == config('const.contract_abnormal.confirm'))
										<!-- 契約異常/支払異常/確認事項あり -->
										<img src="{{ asset('img/icon-setting-lg.png') }}" alt="customer icon">
									@elseif (session()->get('contract_abnormal_flg') == config('const.contract_abnormal.preparation'))
										<!-- 準備中 -->
										<img src="{{ asset('img/icon-setting-lb.png') }}" alt="customer icon">
									@else
										<!-- 契約なし -->
										<img src="{{ asset('img/icon-setting-g.png') }}" alt="customer icon">
									@endif
									<p>確認・変更</p>
								</a>
							</div>
						</div>
						<div class="customer-info">
							<p class="name">{{ $ary_customer['name1'] }} {{ $ary_customer['name2'] }} 様</p>
							<div class="customer-id">
								<p>会員ID&emsp;{{ $ary_customer['customerNo'] }}</p>
							</div>
							<div class="qr-wrap" style="display:none">{{ QrCode::size(100)->generate($ary_customer['id']) }}</div>
						</div>
					</div>
					@if (session()->get('contract_abnormal_flg') > config('const.contract_abnormal.end'))
						<div class="condition-text">
							<p><a href="#contract-wrap">{{ config('msg.condition_msg.' . session()->get('contract_abnormal_flg')) }}</a></p>
						</div>
					@endif
				</div>
				@if (!empty($ary_reserve_data))
					<div class="rsv-wrap cont-wrap">
						<h2><span>ご予約中</span></h2>
						@foreach ($ary_reserve_data as $type_name => $ary_data)
							<div class="rsv-inner">
								<p class="course-label">{{ config('const.treatment_type_name_eng_ja.' . $type_name) }}</p>
								<div class="detail-wrap">
									@foreach ($ary_data as $reserve_id => $val)
										<div>
											<div class="detail-inner">
												<div>
													<div class="rsv-detail">
														<div class="course-inner">
															<p class="course-name">
																{{ $val['courseName'] }}
																@if (!empty($val['delayTimeStatus']))
																	<font color="red">
																		@if ($val['delayTimeStatus'] == config('const.late_under_time'))
																			(10分未満遅刻連絡済み)
																		@else
																			(10分以上遅刻連絡済み)
																		@endif
																	</font>
																@endif
															</p>
															<p>次回ご予約日時: {{ date('Y/m/d', strtotime($val['hopeDate'])) }} {{ config('const.hope_time.' . $val['hopeTimeCd']) }}</p>
															<p>店舗名: {{ $val['shopName'] }}</p>
															<p>住所　: {{ config('const.pref_cd.' . $val['shopPref']) . $val['shopAddress'] }}</p>
														</div>
													</div>
												</div>
											</div>
											<div class="btn-wrap">
												@if ($type_name == config('const.treatment_type_name_eng.0'))
													<!-- 脱毛の場合 -->
													@if (($ary_contract_data[$type_name][$val['contractId']]['courseType'] == config('const.course_type.pack') && strtotime(date('Ymd 23:59:00', strtotime($val['hopeDate'] . '- 1 day'))) > strtotime(date('Ymdhis'))) || ($ary_contract_data[$type_name][$val['contractId']]['courseType'] == config('const.course_type.monthly') && strtotime(date('Ymd 23:59:00', strtotime($val['hopeDate'] . '- 3 day'))) > strtotime(date('Ymdhis'))))
														<!-- パックプランの場合は前日23:59まで -->
														<!-- 月額プランの場合は3日前23:59まで -->
														@if (empty($ary_contract_data[$type_name][$val['contractId']]['condition']['failedConditions']))
															<button type="button" class="p-btn change-icon" onClick="location.href='{{ route('mypageReserveChange', ['contract_id' => $val['contractId'], 'reservation_id' => $reserve_id]) }}'">
																<span>変更</span>
															</button>
														@endif
													@endif

													@if (empty($ary_contract_data[$type_name][$val['contractId']]['condition']['failedConditions']))
														<button type="button" class="w-btn cancel-icon" onClick="location.href='{{ route('mypageCancelChange', ['contract_id' => $val['contractId'], 'reservation_id' => $reserve_id]) }}'">
															<span>キャンセル</span>
														</button>
													@endif

												@elseif ($type_name == config('const.treatment_type_name_eng.1'))
													<!-- エステの場合 -->
													@if (strtotime(date('Ymd 23:59:00', strtotime($val['hopeDate'] . '- 1 day'))) > strtotime(date('Ymdhis')))
														@if (empty($ary_contract_data[$type_name][$val['contractId']]['condition']['failedConditions']))
															<button type="button" class="p-btn change-icon" onClick="location.href='{{ route('mypageReserveChange', ['contract_id' => $val['contractId'], 'reservation_id' => $reserve_id]) }}'">
																<span>変更</span>
															</button>
														@endif
													@endif

													@if (empty($ary_contract_data[$type_name][$val['contractId']]['condition']['failedConditions']))
														<button type="button" class="w-btn cancel-icon" onClick="location.href='{{ route('mypageCancelChange', ['contract_id' => $val['contractId'], 'reservation_id' => $reserve_id]) }}'">
															<span>キャンセル</span>
														</button>
													@endif
												@endif

												@if (strtotime(date('Ymd', strtotime($val['hopeDate']))) == strtotime(date('Ymd')))
													<div class="popup" id="arrival-delay-popup">
														<div class="popup-inner text-center">
															<div class="close-btn" id="arrival-delay-close"><i class="fas fa-times"></i></div>
															<h2 class="arrival-delay-pop-title">遅刻のご連絡</h2>
															<div class="form-area">
																<p class="pop-text">
																	ご予約時間に間に合わない場合は、遅刻時間を選択の上、ご連絡をお願い致します。<br>
																	なお、10分以上遅刻の際は時間内での施術となることをご了承の上、チェックをお願い致します。
																</p>
																<form method="post" action="{{ route('mypageLateContact') }}">
																	@csrf
																	<input name="target_reservation_id" type="hidden" value="{{ $reserve_id }}" />
																	<input name="target_contract_type" type="hidden" value="{{ config('const.treatment_type.' . $type_name) }}" >
																	<input name="registed_delay_time" type="hidden" value="{{ empty($val['delayTimeStatus']) ? 0 : $val['delayTimeStatus'] }}">
																	<div class="select-area display-f">
																		<label for="select-delay-time" class="form-area-item">遅刻時間</label>
																		<div class="cp_ipselect cp_sl01">
																			<select id="select-delay-time" name="delayTime" required>
																				<option value="" hidden>選択してください</option>
																				<option value="1" {{ empty($val['delayTimeStatus']) ? '' : $val['delayTimeStatus'] == config('const.late_under_time') ? 'selected' : '' }}>10分未満</option>
																				<option value="2" {{ empty($val['delayTimeStatus']) ? '' : $val['delayTimeStatus'] == config('const.late_over_time') ? 'selected' : '' }}>10分以上</option>
																			</select>
																		</div>
																	</div>
																	<p class="under10minCheckArea" style="display:{{ $val['delayTimeStatus'] == config('const.late_under_time') ? 'block' : 'none' }}">
																		<input type="checkbox" id="under10minCheckbox" value="" {{ empty($val['delayTimeStatus']) ? '' : $val['delayTimeStatus'] == config('const.late_under_time') ? 'checked' : '' }}>
																		シェービング状態によって、施術ができない部位がでる可能性がございます。予めご了承お願いいたします。
																	</p>
																	<p class="over10minCheckArea" style="display:{{ $val['delayTimeStatus'] == config('const.late_over_time') ? 'block' : 'none' }}">
																		<input type="checkbox" id="over10minCheckbox" value="" {{ empty($val['delayTimeStatus']) ? '' : $val['delayTimeStatus'] == config('const.late_over_time') ? 'checked' : '' }}>
																		時間内の施術となります。施術できない部位がございますが、予めご了承お願いいたします。
																	</p>
																	<div class="modal-btn-area">
																		<div class="modal-btn-left">
																			<button id="arrival-delay-cancel-btn" type="button" class="btn btnWS">キャンセル</button>
																		</div>
																		<div class="modal-btn-right">
																			<button id="arrival-delay-send-btn" type="submit" class="btn btnS" disabled>送信</button>
																		</div>
																	</div>
																</form>
															</div>
														</div>
														<div class="black-background" id="arrival-delay-black-bg"></div>
													</div>
													<button type="button" class="w-btn late-icon btn-open-modal" id="arrival-delay-show-popup"><span>遅刻連絡</span></button>
												@endif
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
				@endif

				@if (!empty($ary_contract_data) && session()->get('contract_abnormal_flg') != config('const.contract_abnormal.end'))
					<div class="contract-wrap cont-wrap" id="contract-wrap">
						<h2><span>ご契約内容</span></h2>
						@foreach ($ary_contract_data as $type_name => $val)
							<div class="contract-inner">
								<p class="course-label">{{ config('const.treatment_type_name_eng_ja.' . $type_name) }}</p>
								<div class="detail-wrap">
									@foreach ($val as $contract_id => $val2)
										<div class="detail-inner">
											<div>
												<div class="contract-detail">
													<div class="course-inner">
														<p class="course-name">{{ $val2['courseName'] }}</p>
														@if (!empty($val2['disp_data']['refund_warranty_period']))
															<p>{{ $val2['disp_data']['refund_warranty_period_title'] }}<span>{{ $val2['disp_data']['refund_warranty_period'] }}</span></p>
														@endif
														@if (!empty($val2['disp_data']['count_warranty_period']))
															<p>{{ $val2['disp_data']['count_warranty_period_title'] }}<span>{{ $val2['disp_data']['count_warranty_period'] }}</span></p>
														@endif
														<div class="count-wrap">
															@if (!is_null($val2['disp_data']['count_progress']))
																<div><span>{{ $val2['disp_data']['count_progress_title'] }}<span class="count">{{ $val2['disp_data']['count_progress'] }}</span>回</span></div>
															@endif
															@if (!empty($val2['disp_data']['count_course']))
																<div><span>{{ $val2['disp_data']['count_course_title'] }}<span class="count">{{ $val2['disp_data']['count_course'] }}</span>回</span></div>
															@endif
														</div>
													</div>
												</div>
												@if ($type_name == config('const.treatment_type_name_eng.0') && empty($val2['condition']['failedConditions']))
													@if ($val2['reserve_count'] < config('const.hair_loss_reserve_max_cnt'))
														<div class="btn-wrap">
															<button type="button" class="p-btn new-search-icon reservation-link" onClick="location.href='{{ route('mypageNewReserve', ['contract_id' => $contract_id]) }}'">
																<span>予約</span>
															</button>
														</div>
													@endif
												@elseif ($type_name == config('const.treatment_type_name_eng.1') && empty($val2['condition']['failedConditions']))
													@if (($val2['rTimes'] + $val2['reserve_count']) >= $val2['times'])
														<!-- 消化数 + 予約数 >= コース回数は表示しない-->
													@elseif ($val2['reserve_count'] < config('const.esthetic_reserve_max_cnt'))
														<!-- 装填数がMAXの場合は予約不可 -->
														<div class="btn-wrap">
															<button type="button" class="p-btn new-search-icon reservation-link" onClick="location.href='{{ route('mypageNewReserve', ['contract_id' => $contract_id]) }}'">
																<span>予約</span>
															</button>
														</div>
													@endif
												@endif
											</div>
										</div>
										@if (!empty($val2['disp_data']['attention']))
											<br />
											<p>{{ $val2['disp_data']['attention'] }}</p>
										@endif
										@if (!empty($val2['condition']['failedConditions']))
											@foreach ($val2['condition']['failedConditions'] as $failedConditions)
												@if (!empty(config('msg.condition_code.' . $failedConditions)))
													<br />
													<font color="red">※{!! nl2br(e(config('msg.condition_code.' . $failedConditions))) !!}</font>
												@endif
											@endforeach
										@endif
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
				@endif
			</div>
		</div>
	</div>
</div>