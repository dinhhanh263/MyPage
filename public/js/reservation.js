// 月変更による日一覧の変更
function onChgDayList(date,element) {
    var year = parseInt(date.split("/")[0], 10);
    var nowmonth = parseInt(date.split("/")[1], 10);
    var month = element.value;
    var dayList = $(element).parent().parent().find(".day")[0];
    var nowDay = dayList.options[dayList.selectedIndex].value - 1;
    if(month < nowmonth){
        year++
    };
    let dayLast = new Date(year, month, 0);
    var length = dayLast.getDate();
    for (var i = dayList.length; i >-1; i--) {
        dayList.options[i] = null;
    };
    for (var i = 0; i < length; i++) {
        dayList.options[i] = new Option(dayFile[month][i],dayFile[month][i]);
    };
    if(dayList.options[nowDay] != null){
        dayList.options[nowDay].selected = true;
    }else{
        dayList.lastChild.selected = true;
    }
    onSetCalDate($(element).parent().parent()[0]);
}

// エリア変更による店舗一覧の変更
function onChgShopList() {
    var area = document.reservationActionForm.areaList.options[document.reservationActionForm.areaList.selectedIndex].value;
    var shopList4 = document.reservationActionForm.shopList4;
    var shopList3 = document.reservationActionForm.shopList3;
    var shopList2 = document.reservationActionForm.shopList2;
    var shopList1 = document.reservationActionForm.shopList1;
    var length = shopList1.length;
    for (var i = 0; i < length; i++) {
        shopList4.options[length - i - 1] = null;
        shopList3.options[length - i - 1] = null;
        shopList2.options[length - i - 1] = null;
        shopList1.options[length - i - 1] = null;
    }

    // 初期表示メッセージ
    if (shopFile[area].length > 0) {
        shopList4.options[0] = new Option("選択してください","");
        shopList3.options[0] = new Option("選択してください","");
        shopList2.options[0] = new Option("選択してください","");
        shopList1.options[0] = new Option("選択してください","");
    }

    var cnt = 1;
    for (var i = 0; i < shopFile[area].length; i++) {
        if (shopFile[area][i]) {
            shopList4.options[cnt] = new Option(shopFile[area][i][0],shopFile[area][i][1]);
            shopList3.options[cnt] = new Option(shopFile[area][i][0],shopFile[area][i][1]);
            shopList2.options[cnt] = new Option(shopFile[area][i][0],shopFile[area][i][1]);
            shopList1.options[cnt] = new Option(shopFile[area][i][0],shopFile[area][i][1]);
            cnt++;
        }
    }

    shopList4.selectedIndex = 0;
    shopList3.selectedIndex = 0;
    shopList2.selectedIndex = 0;
    shopList1.selectedIndex = 0;

    if (document.reservationActionForm.areaList.selectedIndex > 0) {
        shopList4.disabled = false;
        shopList3.disabled = false;
        shopList2.disabled = false;
        shopList1.disabled = false;
    } else {
        shopList4.disabled = true;
        shopList3.disabled = true;
        shopList2.disabled = true;
        shopList1.disabled = true;
    }
}

// 日付設定
function onSetCalDate(element) {
    var year = parseInt(baseDate.split("/")[0], 10);
    var month = parseInt(baseDate.split("/")[1], 10);
    var hope_month = parseInt($(element).find(".month")[0].options[$(element).find(".month")[0].selectedIndex].value, 10);
    var hope_day = parseInt($(element).find(".day")[0].options[$(element).find(".day")[0].selectedIndex].value, 10);
    var hope_year = year;

    if (hope_month < month) {
        hope_year++;
    }
    $(element).find(".hasDatepicker")[0].value =  hope_year+"/"+("00"+hope_month).slice(-2)+"/"+("00"+hope_day).slice(-2);
}

// カレンダーから日付の取得
function onChgCalDate(date, element) {
    var month = parseInt(date.split("/")[1], 10);
    var day = parseInt(date.split("/")[2], 10);

    var monthList = $(element).find(".month")[0];
    var dayList = $(element).find(".day")[0];

    // 期間が計算できない状態の顧客情報は操作させない。
    if (monthList.length == 0) {
        monthList.disabled = true;
        dayList.disabled = true;
        document.reservationActionForm.areaList.disabled = true;
        return;
    };

    for (var i = 0; i< monthList.length; i++) {
        if(parseInt(monthList.options[i].value, 10) === month){
            monthList.options[i].selected = true;
            onChgDayList(date, $(element).find(".month")[0]);
            for (var j = 0; j< dayList.length; j++) {
                if (parseInt(dayList.options[j].value, 10) === day) {
                    dayList.options[j].selected = true;
                    onSetCalDate(element);
                }
            }
        }
    }
}

// add20170127 ueda 時間帯検索と複数店舗検索を切り替える
function changeSearchType(type){
    var $shop_search_item,$time_search_item,$selectTimes;
    $shop_search_item = $(".shop_search_item"),
        $time_search_item = $(".time_search_item"),
        $selectTimes = $(".selectTimes");
    if(type == "search_time"){ //時間検索の場合は店舗検索のマスを非表示
        $shop_search_item.css("display","none");
        $time_search_item.css("display","");
        $selectTimes.prop("disabled",true);
    }else{ //店舗検索の場合は時間検索のマスを非表示
        $time_search_item.css("display","none");
        $shop_search_item.css("display","");
        $selectTimes.prop("disabled",false);
    }
}

// add20170127 ueda 時間帯検索で3つ以上選択できないようにする
function overThree(classname){
    var $target,$count,$not,$not_count;
    $target = $("." + classname), //対象のinput
        $count = $target.length, //対象のinput数
        $not = $target.not(":checked"), //対象の未選択input
        $not_count = $target.not(":checked").length, //対象の未選択input数
        // $out_count = $count-$not_count; //選択済input数
        // $not = $target.attr("checked"), //対象の未選択input
        // $not = $target.not(":checked").length, //対象の未選択input
        // $count = $target.length; //input数
        $total = $count -$not_count; // input数-未選択input=選択済input
    if($total > 2){
        $not.prop("disabled",true);
    }else{
        $not.prop("disabled",false);
    }
}

// add20170202 ueda submit押下げ後二重押下げ禁止処理
function after_push(target){
    var $target,cover,cober_back,inner;
    $target = $(target),
        cover = document.createElement('div'), /* 全体の覆いを作成 */
        cover.id = 'cover_box',
        cover_back =  document.createElement('div'), /* 背景を作成 */
        cover_back.id = 'cover_back',
        inner = document.createElement('div'), /* loading文字表示エリアを作成 */
        inner.id = 'cover_inner_box',
        inner.innerHTML = '読み込み中'; /* loading文字を設定*/
    cover.appendChild(inner); /* 覆いの中にloading表示を追加 */
    cover.appendChild(cover_back); /* 覆いの中に背景を追加 */
    $target.on('click',function(){
        $('body').append(cover);
    })
}
after_push('#submit');
