$(function() {
	// 日を設定
	checkDay();

	// 検索ボタンの活性チェック
	//enabledSearchButton();
})

// 月を変更した場合
$('.month').change(function() {
	checkDay();
	enabledSearchButton();
});

// 日を変更した場合
$('.day').change(function() {
	enabledSearchButton();
});

// エリアを変更した場合
$('#areaList').change(function() {
	enabledSearchButton();
});

// 店舗を変更した場合
$('.shop_list_select').change(function() {
	enabledSearchButton();
});

// 予約確認画面で予約確定ボタン押下時に出すモーダル
$('.js-modal-open').on('click',function(){
		$('.js-modal').fadeIn();
		return false;
});
$('.js-modal-close').on('click',function(){
	$('.js-modal').fadeOut();
	return false;
});

// 新規ご予約
$('.reserveButton').on('click', function(e) {
	// 押下できない様にする
	$(this).prop('disabled', true);
	// submitする
	// 初回予約
	$('#reserveForm').attr('action', '/reserve/process');
	$('#reserveForm').submit();

	setTimeout(function(){
		$(this).prop('disabled', false);
	}, 1000);
});

// 選択可能な日付を出しわけする
function checkDay()
{
	start_md	= $('input[name="start_date"]').val();	// 予定可能開始日
	end_md		= $('input[name="end_date"]').val();	// 予定可能終了日

	ary_start_md	= start_md.split('/');
	ary_end_md		= end_md.split('/');

	now_year	= Number(moment().format('YYYY'));
	now_month	= Number(moment().format('MM'));
	now_day		= Number(moment().format('DD'));

	for (i = 1; i <= 3; i++)
	{
		// 設定されている値を取得
		month		= Number($('#m' + i).val());
		choice_day	= Number($('#d' + i).val());

		// 指定された月の数値が前月以前の場合翌年を指定
		if (now_month > month)
		{
			choice_year = moment().add('year', 1).format('YYYY')
		}
		else
		{
			choice_year = now_year;
		}

		// 設定されている月の最終日を取得
		if (month == ary_end_md[0])
		{
			// 設定されている月が予約可能終了月と同じ場合
			last_day = ary_end_md[1]
		}
		else
		{
			// 設定されている月と違う場合
			last_day = moment(choice_year + '-' + ( '0' + month ).slice( -2 )).endOf('month').format('DD');
		}

		// 日付を一旦削除
		$('#d' + i + ' > option').remove();

		// 日付を設定
		if (month == ary_start_md[0])
		{
			// 設定されている月が予約可能開始月と同じ
			start_day = ary_start_md[1];
		}
		else
		{
			// 設定されている月と違う場合
			start_day = 1;
		}

		// 日付を設定
		for (var iDay = start_day; iDay <= last_day; iDay++) {
			if (String(iDay).length == 1)
			{
				// 1桁だったら0埋め
				var editDay = '0' + iDay;
			}
			else
			{
				// 2桁だったらそのまま
				var editDay = iDay;
			}

			$('#d' + i).append($('<option>').html(editDay).val(editDay));
		}

		// 日付を設定
		$("#d" + i + " option[value='" + choice_day + "']").prop('selected', true);
	}
}

// エリアを変更した場合
$('#areaList').change(function() {
	getShopList($(this).val());
});

// 初期表示時にエリアが設定されていた場合
if ($('#areaList').val() != 0 && (location.pathname.match(/^\/reserve\/\d.+$/) != null || location.pathname.match(/^\/change\/\d.+$/) != null))
{
	// プルダウンを表示する
	getShopList($('#areaList').val());
}

// 店舗を取得
function getShopList(area)
{
	// 店舗を非表示にする
	$('#shop_area').css('display', 'none');

	// 一旦店舗リストを削除
	$('.shop_list_select > option').remove();

	// 一旦店舗プルダウンを非表示にする
	$('[id^=shop_area_select]').css('display', 'none');

	if (area != 0)
	{
		// 設定するurl
		if (location.pathname.match(/^\/reserve\/\d.+$/) != null)
		{
			var get_url = '/reserve';
		}
		else
		{
			var get_url = '/change';
		}

		// 初期表示状態を追加
		$('.shop_list_select').append($('<option>').html('選択してください。').val(0));

		$.ajax({
			headers : {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url : get_url + '/area/shop',
			type : 'POST',
			data : {
				'area' : area,
				'type' : 2,
			}
		})
		// Ajaxリクエストが成功した場合
		.done(function(data) {
			if (data.length == 0)
			{
				alert('サーバーとの通信に失敗しました。');
			}
			else
			{
				if (data['shop_list'].length != 0)
				{
					var shop_list = $(data['shop_list']).get();
					shop_list = shop_list[0];

					var count = 0;

					$.each(shop_list, function(index, element) {
						$('.shop_list_select').append($('<option>').html(element['name']).val(element['shopId']));
						count++;
					});

					// 個数によって表示するプルダウンを制御（最大4つ）
					var loop;
					if (count >= 4)
					{
						loop = 4;
					}
					else
					{
						loop = count;
					}

					// 店舗を表示する
					$('#shop_area').css('display', '');

					// 店舗プルダウンを表示する
					for (i = 1; i <= loop; i++) {
						if ($('#areaList').val() != 0) {
							$('#shop_area_select1').css('display', '');
						}

						if( i == 1) {
							$('select[name="search_shop[1]"]').change(function() {
								var shop_list_select_1 = $('select[name="search_shop[1]"] option:selected').val();
								if (!(shop_list_select_1 === '0')) {
									if(loop != 1){
										$('#shop_area_select2').css('cssText', 'display: block !important;');
									}
								}
							});
						}
						if( i == 2) {
							$('select[name="search_shop[2]"]').change(function() {
								var shop_list_select_2 = $('select[name="search_shop[2]"] option:selected').val();
								if (!(shop_list_select_2 === '0')) {
									if(loop != 2){
										$('#shop_area_select3').css('cssText', 'display: block !important;');
									}
								}
							});
						}
						if( i == 3) {
							$('select[name="search_shop[3]"]').change(function() {
								var shop_list_select_3 = $('select[name="search_shop[3]"] option:selected').val();
								if (!(shop_list_select_3 === '0')) {
									if(loop != 3){
										$('#shop_area_select4').css('cssText', 'display: block !important;');
									}
								}
							});
						}

						// $('#shop_area_select' + i).css('display', '');
					}

					// if ($('#areaList').val() != 0) {
					// 	$('#shop_area_select1').css('display', '');
					// }

					// $('select[name="search_shop[1]"]').change(function() {
					// 	var shop_list_select_1 = $('select[name="search_shop[1]"] option:selected').val();
					// 	if (!(shop_list_select_1 === '0')) {
					// 	  $('#shop_area_select2').css('cssText', 'display: block !important;');
					// 	}
					// });

					// $('select[name="search_shop[2]"]').change(function() {
					// 	var shop_list_select_2 = $('select[name="search_shop[2]"] option:selected').val();
					// 	if (!(shop_list_select_2 === '0')) {
					// 	  $('#shop_area_select3').css('cssText', 'display: block !important;');
					// 	}
					// });

					// $('select[name="search_shop[3]"]').change(function() {
					// 	var shop_list_select_3 = $('select[name="search_shop[3]"] option:selected').val();
					// 	if (!(shop_list_select_3 === '0')) {
					// 	  $('#shop_area_select4').css('cssText', 'display: block !important;');
					// 	}
					// });

					// セッションに選択済みの店舗が存在する場合
					if (data['session_search_shop'] && data['session_search_shop'].length !== 0)
					{
						// 店舗を選択する
						var session_shop_list = $(data['session_search_shop']).get();

						count = 1;

						$.each(session_shop_list[0], function(index, element) {
							if (element != 0)
							{
								$('select[name="search_shop[' + count + ']"]').val(element);
							}
							else
							{
								$('select[name="search_shop[' + count + ']"]').val(0);
							}

							count++;
						});
					}

					// 検索ボタンの活性チェック
					enabledSearchButton();
				}
				else
				{
					alert('サーバーとの通信に失敗しました。');
				}
			}
		})
		// Ajaxリクエストが失敗した場合
		.fail(function(data) {
			alert('サーバーとの通信に失敗しました。');
		});
	}
}

// 予約の◯ボタン押下時
function pushConfirm(mode, target_date, target_shop_id, target_time_cd)
{
	var form = $('#reserve_form');

	if (mode == 1)
	{
		form.attr('action', '/reserve/confirm');
	}
	else
	{
		form.attr('action', '/change/confirm');
	}

	// 対象年月日
	$('<input>').attr({
		'type' : 'hidden',
		'name' : 'target_date',
		'value' : target_date
	}).appendTo(form);

	// 対象タイムコード
	$('<input>').attr({
		'type' : 'hidden',
		'name' : 'target_time_cd',
		'value' : target_time_cd
	}).appendTo(form);

	// 対象店舗
	$('<input>').attr({
		'type' : 'hidden',
		'name' : 'target_shop_id',
		'value' : target_shop_id
	}).appendTo(form);

	form.submit();
}

function enabledSearchButton()
{
	// 判定
	var jage_flg = true;

	// 月は選択されているか
	for (i = 1; i <= 3; i++)
	{
		if ($('#m' + i).length == 0)
		{
			jage_flg = false;
			break;
		}
	}

	// 日は選択されているか
	for (i = 1; i <= 3; i++)
	{
		if ($('#d' + i).length == 0)
		{
			jage_flg = false;
			break;
		}
	}


	// エリアは選択されているか
	if ($('#areaList').val() == 0)
	{
		jage_flg = false;
	}

	// 店舗は選択されているか
	for (i = 1; i <= 4; i++)
	{
		if ($('#shop_area_select' + i).length)
		{
			if ($('select[name="search_shop[' + i + ']"]').val() != 0 && $('select[name="search_shop[' + i + ']"]').val() != null)
			{
				// jage_flg = true;
				break;
			}
			else
			{
				jage_flg = false;
			}
		}
	}

	if (jage_flg == false)
	{
		$('.shop_search_item').prop('disabled', true);
		$('.shop_search_item').css('background-color', '#c0c0c0');
	}
	else
	{
		$('.shop_search_item').prop('disabled', false);
		$('.shop_search_item').css('background-color', '');
	}
}