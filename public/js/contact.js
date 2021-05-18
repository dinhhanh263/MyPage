
/* 問合せカテゴリ選択 */
function select_one(target,input_h){
    //.no-selectを利用して一つのボタンだけ選択可能にする
    var $target,$input_h;
    $target = $(target),
        $input_h = $(input_h);
    $target.on("click",function(){
        var $this = $(this);
        if($this.hasClass("no-select")){
            $target.addClass("no-select");
            $this.removeClass("no-select");
            $input_h.val($this.val());
            if($(".contract-btn-02").hasClass("no-select")){
              $(".contact-btn-04").removeClass('conceal');
              $(".contact-btn-04").prop('disabled',false);
              $(".contract-btn-01").prop('disabled',true);
              $(".contract-btn-02").prop('disabled',false);
            }else{
              $(".contact-btn-04").addClass('conceal');
              $(".contact-btn-04").prop('disabled',true);
              $(".contract-btn-01").prop('disabled',false);
              $(".contract-btn-02").prop('disabled',true);
              if($(".contact-btn-04").hasClass("no-select")){
              }else{
                $(".contact-btn-04").addClass("no-select");
                $("#contact_type").val("");
              }
            }
        }else{
            $target.addClass("no-select");
            $input_h.val("");
        }
    })
}
select_one(".contact-btn","#contact_type");
select_one(".contract-btn","#contract_type");

/*内容確認動作*/
$.fn.modal = function(options){
    var settings,back_body,$back_body,$btn,$modal_content,$close_btn;
    settings = $.extend({
        'target':'#confirm_modal',
        'close_btn':'#close_button',
        'category':'#contract_type',
        'category_list':'.contract-btn',
        'type':'#contact_type',
        'type_list':'.contact-btn',
        'textarea':'#contact_input',
        'reflection_category':'#category_reflection',
        'reflection_type':'#type_reflection',
        'reflection_area':'#input_reflection'
    },options);
    /* 背景要素を作成 start */
    back_body = document.createElement("div"),
        back_body.id = "back_body",
        back_body.style.position = "fixed",
        back_body.style.top = "0",
        back_body.style.left = "0",
        back_body.style.height = "100vh",
        back_body.style.width = "100vw",
        back_body.style.opacity = "0.7",
        back_body.style.backgroundColor = "#666";
    $back_body = $(back_body),//背景をDOM要素に追加
        /* 背景要素を作成 end */
        $modal_content = $(settings['target']),//modal要素を指定
        $close_btn = $(settings['close_btn']);//closeボタンを設定
    this.on("click",function(){//modalを開く
        /* 投稿内容確認 start */
        var $target_category,category_val,category_name,$category_list,category_list_val,$reflection_category,$target_type,type_val,$type_list,type_list_val,$reflection_type,$target_text,text,$reflection_area,$alert;
        $target_category = $(settings['category']),
            category_val = $target_category.val(),
            $category_list = $(settings['category_list']),
            category_list_val = $category_list.eq([category_val]).text(),
            $reflection_category = $(settings['reflection_category']);
        $target_type = $(settings['type']),
            type_val = $target_type.val(),
            $type_list = $(settings['type_list']),
            type_list_val = $type_list.eq([type_val]).text(),
            $reflection_type = $(settings['reflection_type']);
        $target_text = $(settings['textarea']),
            text_val = $target_text.val(),
            text_val = text_val.replace(/\n/g, "<br>");
        $reflection_area = $(settings['reflection_area']);
        $alert = "";
        category_val==="" ? $alert += "お問合せ内容\n" : $alert += "";//カテゴリ選択の有無
        type_val==="" ? $alert += "お問合せカテゴリ\n" : $alert += "";//カテゴリ選択の有無
        text_val==="" ? $alert += "お問合せ内容\n" : $alert += "";//内容入力の有無の有無
        if($alert!==""){
            $alert = "次の項目は必須項目です。\n" + $alert;
            alert($alert);
            return false;
        }else{
          if(category_val==="1"){category_name="脱毛"};
          if(category_val==="2"){category_name="エステ・整体"};
            $reflection_category.html("カテゴリー： "+category_name);
            $reflection_type.html("内容： "+type_val);
            $reflection_area.html("内容詳細：<br><span>"+text_val+"</span>");
        };
        /* 投稿内容確認 end */
        $target_text.blur(); //入力画面のフォーカスを外す。（スマートフォンでソフトウェアキーボードを消す）
        $("body").append(back_body);//背景を設定
        $back_body.fadeIn('fast');
        setTimeout(function(){
            $modal_content.fadeIn('fast');
        },200)
        return false;
    });
    function close(){//閉じる設定
        $modal_content.fadeOut('fast');
        $back_body.fadeOut('fast');
    }
    $back_body.on('click touchend',close);// 背景クリックで閉じる
    $close_btn.on('click touchend',close);// やめるボタンクリックで閉じる
}
$("#confirm_btn").modal();

$(function() {
    if ($('#contact_input').val().length == 0 || $('#contact_type').val().length == 0) {
        $('#confirm_btn').prop('disabled', true);
        $('#confirm_btn').css('background-color','#c0c0c0');
    }
    $('#contact_input').on('keydown keyup keypress change', function() {
        if ( $(this).val().length > 0 && $('#contact_type').val().length > 0 ) {
            $('#confirm_btn').prop('disabled', false);
            $('#confirm_btn').css('background-color','#e62e8b');
        } else {
            $('#confirm_btn').prop('disabled', true);
            $('#confirm_btn').css('background-color','#c0c0c0');
        }
    });
    $('button.contact-btn').on('click', function() {
         if ( $('#contact_type').val().length > 0 && $('#contact_input').val().length > 0 ) {
             $('#confirm_btn').prop('disabled', false);
             $('#confirm_btn').css('background-color','#e62e8b');
         } else {
             $('#confirm_btn').prop('disabled', true);
             $('#confirm_btn').css('background-color','#c0c0c0');
         }
    });
    $('input.contract-btn').on('click', function() {
      if ( $('#contract_type').val()  == "1" ){
        $('.formail .beauty').addClass("hide");
        $('.formail .epilation').removeClass("hide");
      }
      if ( $('#contract_type').val()  == "2" ){
         $('.formail .beauty').removeClass("hide");
         $('.formail .epilation').addClass("hide");
       }
         if ( $('#contact_type').val().length > 0 && $('#contact_input').val().length > 0 ) {
             $('#confirm_btn').prop('disabled', false);
             $('#confirm_btn').css('background-color','#e62e8b');
         } else {
             $('#confirm_btn').prop('disabled', true);
             $('#confirm_btn').css('background-color','#c0c0c0');
         }
    });
});
