(function($){$(function(){
console.log('start cities');
$('#cities_block').hide();

$('#region_select').change(function(){
    var region_id = $('#region_select').val();
    $('#region_id').val(region_id);
    if(region_id == 0){
        $('#cities_block').hide();
    }
    else{
        showCities();
        $('#cities_block').show();
    }
});

$('#savemodal').click(function(){
    var id = $('#edit_id').val();
    var name = $('#edit_name').val().trim();
    if (name.length == 0) {
        return true;
    }
    $.ajax({
        url:'../ajax/saveCity',
        type:'POST',
        data: 'id=' + id + '&name=' + name,
        success: function(data){
            console.log(data);
            showCities();
        }
    });
});

$('#new_but').click(function(){
    var data=$('#add_form').serializeArray();
    data[data.length]={name:'table', value:'cities'};
    $.ajax({
        url:'../ajax/insert',
        type:'POST',
        data:data,
        success: function(data){
            $('#new').val('');
            showCities();
        }
    });
    return false;
});

function addClick(){
    $('.edit').each(function(){//кнопка редактировать
    	$(this).click(function(){
            var id = $(this).attr('id').substr(1);
            console.log('id=', id);
            var name = $(this).parent().parent().find('td:nth-child(2)').text();
            console.log('name=', name);
            $('#edit_id').val(id);
            $('#edit_name').val(name);
    	});
    });

    $('.del').each(function(){//кнопка удалить
    	$(this).click(function(){
                var id=$(this).attr('id').substr(1);
                var name= $(this).parent().parent().find('td:nth-child(2)').text();
                $('#delete_id').val(id);
                $('#delete_name').text(name);
            });
    });
}

function showCities(){
    console.log('region=',$('#region_id').val());
    $.ajax({
	url:'../ajax/showCities',
	type:'POST',
        data:'region='+$('#region_id').val(),
	success: function(data){
            $('#cities_table tbody').html(data);
            addClick();
        }
    });
}


$('#delete_confirm_but').click(function(){
	$.ajax({
		url: '../ajax/deleteCity',
		type:'POST',
		data:'id='+ $('#delete_id').val(),
		success: function(data){
			console.log(data);
			var text = '';
			if (data == '0') {
				var name = $('#delete_name').text();
				text = '<p class="alert alert-success alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Пользователь ' + name + ' удалён</p>';
			} else {
				text = '<p class="alert alert-danger alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>ОШИБКА:</strong> ' + data + '</p>';
			}
			$('#success').remove();
			$('main').prepend(text);
		}
	});
});

})})(jQuery)
