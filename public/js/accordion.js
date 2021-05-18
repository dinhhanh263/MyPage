$(function(){
    $('.accordion').hide();
    $('.trigger').on("click", function() {
        $(this).toggleClass('open');
        $('.accordion').slideToggle();
    });

    // 不要な処理
    //$('.accordion02').hide();
    $('.trigger02').on("click", function() {
        $(this).toggleClass('open');
        $('.accordion02').slideToggle();
    });

});
