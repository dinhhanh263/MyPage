// jQuery(function($){

//   var mql = window.matchMedia('screen and (max-width: 767px)');
//   function checkBreakPoint(mql) {
//     if (mql.matches) {
//       // スマホ向け（767px以下のとき）
//       $('.slider').not('.slick-initialized').slick
//       ({
//         //スライドさせる
//         slidesToShow: 1,
//         slidesToScroll: 1,
//         dots: true,
//         minWidth: '320px',
//         maxWidth: '460px',
//         infinite: true,
//         centerMode: true,
//         centerPadding:'0%',
//         variableWidth: true,
//         autoplay:true,
//         autoplaySpeed:2000,
//         waitForAnimate: false,
//       });
//     } else {
//       // PC向け
//       $('.slider').not('.slick-initialized').slick({
//           slidesToShow: 3,
//           slidesToScroll: 1,
//           dots: true,
//           maxWidth: '720px',
//           infinite: true,
//           centerMode: true,
//           centerPadding:'20%',
//           variableWidth: true,
//           autoplay:true,
//           autoplaySpeed:2000,
//           waitForAnimate: false,
//       });
//     }
//   }

//   //ブレイクポイントの瞬間に発火
//   mql.addListener(checkBreakPoint);

//   // 初回チェック
//   checkBreakPoint(mql);
// });
$(function(){
  $('.slider').slick({
    autoplay:true,
    autoplaySpeed:2000,
    dots:true,
    slidesToScroll:1,
    arrows:true,
    infinite: true,
    centerMode: true,
    centerPadding:'20%',

    slidesToShow: 3,
    maxWidth: '720px',
    variableWidth: true,
    waitForAnimate: false,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          centerPadding:'15%',
          // minWidth: '320px',
          maxWidth: '460px',
        }
      }
    ]
  });

});