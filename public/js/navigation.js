
jQuery(document).ready(function(){
	$('#navi').hide(0);
	$('#navi .sub').hide(0);
	if ($('body').hasClass('sp')) { $('#snsbox').hide(0) }
	//  navi
	$('#menubtn a').on('click', function(e) {
		e.preventDefault();

		var thdiv = $(this).find('div');
		var thp = $(this).find('p');
		var main_cover = $('.main_cover');

		var switch_flg = thdiv.hasClass('active');

		if (switch_flg) {
			$('#navi').stop().fadeOut('fast');
			thdiv.removeClass('active');

			if ($('body').hasClass('sp')) {$('#snsbox').stop().fadeOut('fast')}
		}else{
			$('#navi').stop().fadeIn('fast');
			thdiv.addClass('active');

			if ($('body').hasClass('sp')) {$('#snsbox').stop().fadeIn('fast')}
		};
	});
	if (!$('body').hasClass('sp')) {
		$(window).scroll(function(e) {

			$('#navi').stop().fadeOut('fast');
			$('#menubtn a').find('div').removeClass('active');

		});
	}

});