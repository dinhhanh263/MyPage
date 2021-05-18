function popupTellText() {
    var popup = document.getElementById('js-popup');
    if(!popup) return;

    var blackBg = document.getElementById('js-black-bg');

    var blackBg = document.getElementById('js-black-bg');
    var closeBtn = document.getElementById('js-close-btn');
    var showBtn = document.getElementById('js-show-popup');

    closePopUp(blackBg);
    closePopUp(closeBtn);
    closePopUp(showBtn);
    function closePopUp(elem) {
      if(!elem) return;
      elem.addEventListener('click', function() {
        popup.classList.toggle('is-show');
      });

    }
}
popupTellText();

function popupArrivalDelay() {
    var popup = document.getElementById('arrival-delay-popup');
    if(!popup) return;

    var blackBg = document.getElementById('arrival-delay-black-bg');
    var closePopup = document.getElementById('arrival-delay-close');
    var showBtn = document.getElementById('arrival-delay-show-popup');
    var cancelBtn = document.getElementById('arrival-delay-cancel-btn');

    closePopUp(blackBg);
    closePopUp(closePopup);
    closePopUp(showBtn);
    closePopUp(cancelBtn);
    function closePopUp(elem) {
      if(!elem) return;
      elem.addEventListener('click', function() {
        popup.classList.toggle('is-show');
      });
    }
}
popupArrivalDelay();

function popupMap() {
    var popup = document.getElementById('map-popup');
    if(!popup) return;

    var blackBg = document.getElementById('map-black-bg');

    var blackBg = document.getElementById('map-black-bg');
    var closeBtn = document.getElementById('map-close-btn');
    var showBtn = document.getElementById('map-show-popup');

    closePopUp(blackBg);
    closePopUp(closeBtn);
    closePopUp(showBtn);
    function closePopUp(elem) {
        if(!elem) return;
        elem.addEventListener('click', function() {
        popup.classList.toggle('is-show');
        });
    }
}
popupMap();

window.onload = function() {
    var popup = document.getElementById('contact-popup');
    if(!popup) return;
    popup.classList.add('is-show');

    var blackBg = document.getElementById('contact-black-bg');
    var closeBtn = document.getElementById('contact-close-btn');

    closePopUp(blackBg);
    closePopUp(closeBtn);

    function closePopUp(elem) {
        if(!elem) return;
        elem.addEventListener('click', function() {
        popup.classList.remove('is-show');
        })
    }
}

//複数モーダル
$('.news-modal-open').on('click', function(){
    var target = $(this).data('target');
    var modal = document.getElementById(target);
    scrollPosition = $(window).scrollTop();

    $('body').addClass('news-fixed');
    $(modal).fadeIn();
    return false;
});
// モーダルウィンドウを閉じる
$('.news-modal-close').on('click', function(){
    $('body').removeClass('news-fixed');
    window.scrollTo( 0 , scrollPosition );
    $('.news-modal').fadeOut();
    return false;
});
