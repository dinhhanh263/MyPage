$(function () {
    select_day_make();
    $('select[name="birthday_y"]').change(function(){
        select_day_make();
    });
    $('select[name="birthday_m"]').change(function(){
        select_day_make();
    });

    function select_day_make(){
        var selected_day = $('select[name="birthday_d"]').val();
        $('select[name="birthday_d"]').empty();
        var element = '<option value="">-<\/option>';
        var year = parseInt($('select[name="birthday_y"]').val());
        var month = parseInt($('select[name="birthday_m"]').val());
        if(year == 0 || month == 0){
            // 年または月が未選択
        } else {
            var end_date = new Date(year, month, 0);
            var end_day = end_date.getDate();
            for(i=1; i<=end_day; i++){
                if(i == selected_day){
                    element += '<option value="' + ('0'+i).slice(-2) + '" selected>' + i + '<\/option>';
                } else {
                    element += '<option value="' + ('0'+i).slice(-2) + '">' + i + '<\/option>';
                }
            }
        }
        $('select[name="birthday_d"]').append(element);
    }
});
