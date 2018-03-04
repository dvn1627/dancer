(function($){$(function(){

$('#all_but').click(function(){
    show('all',1);
});

$('#yes_but').click(function(){
    show('yes',1);
});

$('#no_but').click(function(){
    show('no',1);
});

show('all',1);

function show(type, page){
    var col=20;
    $.ajax({
        url:'../ajax/getYearPay2',
        type:'POST',
        data:'type='+type+'&col='+col+'&page='+page,
        success: function(data){
            var res=JSON.parse(data);
            $('#main_table tbody').html(res.list);
            $('#pagg').html(res.pagg);
            $('#next').click(function(){
                show(type, page+1);
                return false;
            });
            $('#prev').click(function(){
                show(type, page-1);
                return false;
            });
        }
    });
}
$('#save_but').click(function(){
    $.ajax({
        url:'../ajax/saveYearPays',
        type:'POST',
        data:$('#pay_form').serialize(),
        success: function(data){
            console.log(data);
            show('all',4,1);
        }
    });
});

$('#search').click(function(){
    var text = $('#search_text').val().trim();
    if (text == '') {
        return false;
    }
    $.ajax({
        url: '../ajax/SearchYearPays',
        type: 'POST',
        data: 'text=' + text,
        success: function(data) {
            console.log(data);
            var res=JSON.parse(data);
            $('#main_table tbody').html(res);
            $('#pagg').html('');
        }
    });
});

})})(jQuery)
