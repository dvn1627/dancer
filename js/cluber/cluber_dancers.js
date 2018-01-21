(function($){$(function(){

addClick();

$('#savemodal').click(function(){
    var data=$('#formmodal').serializeArray();
    $.ajax({
        url:'../../ajax/saveDancer',
        type:'POST',
        data:data,
        success: function(data){
            show();
        }
    });
});

$('#new_but').click(function(){
    var data=$('#add_form').serializeArray();
    data[data.length]={name:'table',value:'ligs'};
    $.ajax({
        url:'../../ajax/insert',
        type:'POST',
        data:data,
        success: function(data){
            $('#new_name').val('');
            $('#new_points').val('');
            $('#new_days').val('');
            $('#new_days').val('');
            $('#new_number').val('');
            show();
        }
    });
    return false;
});

function addClick(){

    $('.info').each(function(){//кнопка информация
	$(this).click(function(){
            var id=$(this).attr('id').substr(1);
            $.ajax({
		url:'../../ajax/dancerInfo',
		type:'POST',
		data:'id='+id,
		success: function(data){
                var modal=JSON.parse(data);
                    $('#i_first_name').html(modal.first_name);
                    $('#i_last_name').html(modal.last_name);
                    $('#i_father_name').html(modal.father_name);
                    $('#i_password').html(modal.password);
                    $('#i_email').html(modal.email);
                    $('#i_phone').html(modal.phone);
                    $('#i_bell').html(modal.bell);
                    $('#i_birthdate').html(modal.birthdate);
                    $('#i_year').html(modal.year);
                    $('#exp').html(modal.exp);
		}
            });
	});
    });
    $('.edit').each(function(){//кнопка редактировать
	$(this).click(function(){
            var id=$(this).attr('id').substr(1);
            $.ajax({
		url:'../../ajax/dancerInfo',
		type:'POST',
		data:'id='+id,
		success: function(data){
                var modal=JSON.parse(data);
                    $('#e_status option').each(function(){
                        if ($(this).val()==modal.dancer){
                            $(this).attr('selected','selected');
                        }else{
                            $(this).removeAttr('selected');
                        }
                    });
                    $('#e_first_name').val(modal.first_name);
                    $('#e_last_name').val(modal.last_name);
                    $('#e_father_name').val(modal.father_name);
                    $('#e_password').val(modal.password);
                    $('#e_email').val(modal.email);
                    $('#e_phone').val(modal.phone);
                    $('#e_bell').val(modal.bell);
                    $('#e_birthdate').val(modal.birthdate);
                    $('#e_id').val(modal.id);
                    $('#e_user_id').val(modal.user_id);
                    //$('#e_status option').removeAttr('selected');

                    $('#e_bell option').each(function(){
                        if ($(this).val()==modal.bell_id){
                            $(this).attr('selected','selected');
                        }else{
                            $(this).removeAttr('selected');
                        }
                    });
		}
            });
	});
    });
    $('.deactivate').each(function(){//кнопка деактивировать
	$(this).click(function(){
            var id=$(this).attr('id').substr(1);
            $.ajax({
		url:'../../ajax/deactivateDancer',
		type:'POST',
		data:'id='+id,
		success: function(data){
                    show();
		}
            });
	});
    });
    $('.activate').each(function(){//кнопка активировать
	$(this).click(function(){
            var id=$(this).attr('id').substr(1);
            $.ajax({
		url:'../../ajax/activateDancer',
		type:'POST',
		data:'id='+id,
		success: function(data){
                    show();
		}
            });
	});
    });

    $('button.del_but').each(function() {
        $(this).click(function() {
            var name = $(this).parent().parent().find('td:nth-child(2)').text();
            $('#delete_id').val($(this).next().val());
            $('#delete_name').text(name);
        });
    });
}

show();

function show(){
    $.ajax({
	url:'../../ajax/showTrainerDancers2',
	type:'POST',
        data: 'id='+$('#trainer_id').val(),
        success: function(data){
            $('#dancers_table tbody').html(data);
            addClick();
        }
    });
}

$('#delete_confirm_but').click(function(){
	$.ajax({
		url: '../../ajax/deleteUser',
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
            show();
			$('#success').remove();
			$('main').prepend(text);
		}
	});
});

})})(jQuery)
