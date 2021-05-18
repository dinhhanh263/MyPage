//pagetop
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

$(function(){
	// Swiper初期化
	var main_slider1 = new Swiper('.main-slider--1 .swiper-container', {
		pagination: {
			el: '.main-slider-1 .swiper-pagination',
		},
		navigation: {
			nextEl: '.main-slider-1 .swiper-button-next',
			prevEl: '.main-slider-1 .swiper-button-prev',
		},
		observer: true,
		observeParents: true,
	})
	var main_slider2 = new Swiper('.main-slider-2 .swiper-container', {
		pagination: {
			el: '.main-slider-2 .swiper-pagination',
		},
		navigation: {
			nextEl: '.main-slider-2 .swiper-button-next',
			prevEl: '.main-slider-2 .swiper-button-prev',
		},
		observer: true,
		observeParents: true,
	})
	// タブ切り替え
	var tab_item = document.querySelectorAll('.tab-item');
	for (let i = 0; i < 2; i++) {
		tab_item[i].addEventListener('click', (e) => {
			document.getElementsByClassName('tab-item-active')[0].classList.remove('tab-item-active');
			tab_item[i].classList.add('tab-item-active');
			document.getElementsByClassName('main-slider-active')[0].classList.remove('main-slider-active');
			var main_slider = document.querySelectorAll('.main-slider');
			main_slider[i].classList.add('main-slider-active');
		});
	}

	// 遅刻連絡
	$('#select-delay-time').change(function() {
		if ($(this).val() == 1)
		{
			// 10分以上のチェックボックスを外す
			$('#over10minCheckbox').prop('checked', false);

			// 10分未満を選択された場合
			$('.under10minCheckArea').css('display', 'block');	// 表示
			$('.over10minCheckArea').css('display', 'none');	// 非表示
			delayCheck(1);
		}
		else
		{
			// 10分未満のチェックボックスを外す
			$('#under10minCheckbox').prop('checked', false);

			// 10分以上を選択された場合
			$('.over10minCheckArea').css('display', 'block');	// 表示
			$('.under10minCheckArea').css('display', 'none');	// 非表示

			delayCheck(2);
		}
	});

	// 遅刻連絡ボタン押下時
	$('#arrival-delay-show-popup').click(function() {
		var delay_time_status = $('input[name="registed_delay_time"]').val();
		if (delay_time_status == 0)
		{
			// 値が設定されていない場合は初期化

			// プルダウンを初期値にする
			$('#select-delay-time').val('');

			// チェックボックスを空にする
			$('#over10minCheckbox').prop('checked', false);
			$('#under10minCheckbox').prop('checked', false);

			// チェックボックスを非表示にする
			$('.over10minCheckArea').css('display', 'none');
			$('.under10minCheckArea').css('display', 'none');

			// 送信ボタンにdisabledにする
			$('#arrival-delay-send-btn').prop('disabled', true);
		}
		else
		{
			// プルダウンを選択
			$('#select-delay-time').val(delay_time_status);

			if (delay_time_status == 1)
			{
				// チェックボックスのチェック
				$('#under10minCheckbox').prop('checked', true);
				$('#over10minCheckbox').prop('checked', false);

				// チェックボックスを表示処理
				$('.under10minCheckArea').css('display', 'block');
				$('.over10minCheckArea').css('display', 'none');
			}
			else
			{
				// チェックボックスのチェック
				$('#under10minCheckbox').prop('checked', false);
				$('#over10minCheckbox').prop('checked', true);

				// チェックボックスを表示処理
				$('.under10minCheckArea').css('display', 'none');
				$('.over10minCheckArea').css('display', 'block');
			}

			$('#arrival-delay-send-btn').prop('disabled', false);
		}
	});

	// 遅刻連絡チェックボックス(10分未満)
	$('#under10minCheckbox').change(function() {
		delayCheck(1);
	});

	// 遅刻連絡チェックボックス(10分以上)
	$('#over10minCheckbox').change(function() {
		delayCheck(2);
	});
});

function questionSubmit()
{
	// 押下できない様にする
	$('.questionButton').prop('disabled', true);
	// submitする
	$('#questionForm').attr('action', '/gene/question/process');
	$('#questionForm').submit();

}


function delayCheck(type)
{
	if (type == 1)
	{
		// 10分未満の場合

		// チェック状態を確認
		if($('#under10minCheckbox').prop('checked'))
		{
			// 送信ボタンにdisabledを解除
			$('#arrival-delay-send-btn').prop('disabled', false);
		}
		else
		{
			// 送信ボタンのdisabledを追加
			$('#arrival-delay-send-btn').prop('disabled', true);
		}
	}
	else
	{
		// 10分以上の場合

		// チェック状態を確認
		if($('#over10minCheckbox').prop('checked'))
		{
			// 送信ボタンにdisabledを解除
			$('#arrival-delay-send-btn').prop('disabled', false);
		}
		else
		{
			// 送信ボタンのdisabledを追加
			$('#arrival-delay-send-btn').prop('disabled', true);
		}
	}
}