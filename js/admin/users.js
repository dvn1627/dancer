(function($){$(function(){

var regions;
$('#dancer_but').hide();

$.ajax({
	url: baseUrl + 'ajax/getRegions',
	type:'POST',
	success: function(data){
		regions = JSON.parse(data);
	}
});

function regionsList(regionId = 0) {
	var html = '<option value="0">Выберите регион</option>';
	for (var i = 0; i < regions.length; i++) {
		if (regions[i].id == regionId) {
			html += '<option value="' + regions[i].id + '" selected="selected">' + regions[i].region + '</option>';
		} else {
			html += '<option value="' + regions[i].id + '">' + regions[i].region + '</option>';
		}
	}
	$('#region_id').html(html);
	$('#region_id').change(function(){
		var id = $('#region_id').val();
		if (id > 0) {
			citiesList(id);
		}
	});
}

function citiesList(regionId, cityId = 0) {
	$.ajax({
		url: baseUrl + 'ajax/getCities',
		type:'POST',
		data: 'region=' + regionId,
		success: function(data){
			var cities = JSON.parse(data);
			var html = '<option value="0">Выберите город</option>';
			for (var i = 0; i < cities.length; i++) {
				if (cities[i].id == cityId) {
					html += '<option value="' + cities[i].id + '" selected="selected">' + cities[i].city + '</option>';
				} else {
					html += '<option value="' + cities[i].id + '">' + cities[i].city + '</option>';
				}
			}
			$('#city_id').html(html);
			$('#city_id').change(function(){
				var id = $('#city_id').val();
				if (id > 0) {
					clubList(id);
				}
			});
		}
	});
}

function clubList(cityId, clubId = 0) {
	$.ajax({
		url: baseUrl + 'ajax/getClubes',
		type:'POST',
		data: 'city=' + cityId,
		success: function(data){
			var clubes = JSON.parse(data);
			var html = '<option value="0">Выберите клуб</option>';
			for (var i = 0; i < clubes.length; i++) {
				if (clubes[i].id == clubId) {
					html += '<option value="' + clubes[i].id + '" selected="selected">' + clubes[i].title + '</option>';
				} else {
					html += '<option value="' + clubes[i].id + '">' + clubes[i].title + '</option>';
				}
			}
			$('#club_id').html(html);
		}
	});
}

function getWays() {
	$.ajax({
		url: baseUrl + 'ajax/getWays',
		type:'POST',
		success: function(data){
			var ways = JSON.parse(data);
			var html = '<option value="0">Выберите направление</option>';
			for (var i = 0; i < ways.length; i++) {
				html += '<option value="' + ways[i].id + '">' + ways[i].way + '</option>';
			}
			$('#dancer_way_id').html(html);
			$('#dancer_lig_id').hide();
			$('#dancer_lig_id').html('');
			$('#dancer_points').hide();
			$('#dancer_points').val(0);
		}
	});
}

function getExperience(wayId) {
	$.ajax({
		url: baseUrl + 'ajax/getExperience',
		type:'POST',
		data: 'way_id=' + wayId + '&dancer_id=' + $('#dancer_id').val(),
		success: function(data) {
			var exp = JSON.parse(data);
			var html = '';
			for (var i = 0; i < exp.ligs.length; i++) {
				if (exp.ligs[i].id == exp.selected) {
					html += '<option value="' + exp.ligs[i].id + '" selected>' + exp.ligs[i].name + '</option>';
				} else {
					html += '<option value="' + exp.ligs[i].id + '">' + exp.ligs[i].name + '</option>';
				}
			}
			$('#dancer_lig_id').html(html);
			$('#dancer_points').val(exp.points);
			$('#dancer_lig_id').show();
			$('#dancer_points').show();
		}
	});
}

$('#edit_block').hide();
$('#edit_but').hide();
$('#delete_but').hide();
$('#trainer_but').hide();

$('#edit_but').click(function(){
	$('#edit_block').show();
	$('#edit_but').hide();
	return false;
});

$('#save_but').click(function(){
	var ver = verify([
        ['last_name','name'],
        ['first_name','name'],
        ['password','pass'],
        ['email','email'],
	]);
	if (ver) {
		var form=$('#user_form').serialize();
		$.ajax({
			url: baseUrl + 'ajax/saveUser',
			type:'POST',
			data:form,
			success: function(data){

			}
		});
	}
	return false;
});

$('#save_trainer').click(function(){
	var clubId = $('#club_id').val();
	if (clubId > 0) {
		$.ajax({
			url: baseUrl + 'ajax/saveTrainerInfo',
			type:'POST',
			data:'trainer_id=' + $('#trainer_id').val() + '&club_id=' + clubId + '&user_id=' + $('#user_id').val(),
			success: function(data){
				if (data == '0') {
					var name = $('#trainer_name').text();
					text = '<p class="alert alert-success alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Тренер ' + name + ' изменён</p>';
				} else {
					text = '<p class="alert alert-danger alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>ОШИБКА:</strong> ' + data + '</p>';
				}
				$('#success').remove();
				$('main').prepend(text);
			}
		});
	} else {
		return false;
	}
	return true;
});

$('#filter_but').click(function(){
	var form=$('#filter_form').serialize();
	$.ajax({
		url: baseUrl + 'ajax/filterUser',
		type:'POST',
		data:form,
		success: function(data){
			$('.pagination').hide();
			var users=JSON.parse(data);
			var table='';
			for (var i = 0; i < users.length; i++) {
				table+='<tr>';
				table+='<td class="hidden">'+users[i]['id']+'</td>';
				table+='<td class="pointer">'+users[i]['last_name']+' '+users[i]['first_name']+' '+users[i]['father_name']+'</td>';
				table+='</tr>';
			}
			$('#user_table tbody').html(table);
			user_click();
		}
	});

	return false;
});

function user_click(){
    $('td.pointer').each(function(){
        $(this).click(function(){
                var id=$(this).parent().find('td.hidden').text();
				$('#delete_id').val(id);
                $('#edit_block').hide();
				$('#dancer_but').hide();
                user_info(id);
        });
    });
}

user_click();

function user_info(id){
	$.ajax({
		url: baseUrl + 'ajax/getUserInfo',
		type:'POST',
		data:'id='+id,
		success: function(data){
			$('#edit_but').show();
			$('#delete_but').show();

			var user=JSON.parse(data);
			$('#user_id').val(user.id);
			$('#last_name').val(user.last_name);
			$('#first_name').val(user.first_name);
			$('#father_name').val(user.father_name);
			$('#delete_name').html(user.last_name + ' ' +user.first_name + ' ' + user.father_name);
			$('#phone').val(user.phone);
			$('#email').val(user.email);
			$('#password').val(user.password);
			$('#edit_block input[type=radio]').each(function(){
				$(this).prop('checked', false);
			});
			$('#edit_block input[name=admin]').each(function(){
				if (user.admin==$(this).val()) $(this).prop('checked', true);;
			});
			$('#edit_block input[name=organizer]').each(function(){
				if (user.organizer==$(this).val()) $(this).prop('checked', true);;
			});
			$('#edit_block input[name=cluber]').each(function(){
				if (user.cluber==$(this).val()) $(this).prop('checked', true);;
			});
			$('#edit_block input[name=trainer]').each(function(){
				if (user.trainer==$(this).val()) $(this).prop('checked', true);;
			});
			$('#edit_block input[name=dancer]').each(function(){
				if (user.dancer==$(this).val()) $(this).prop('checked', true);;
			});
			var info='';
			info+='<h4>'+user.last_name+' '+user.first_name+' '+user.father_name+'</h4>';
			info+='<p><b>Телефон:</b> '+user.phone+'</p>';
			info+='<p><b>E-mail:</b> '+user.email+'</p>';
			info+='<p><b>Пароль:</b> '+user.password+'</p>';
			info+='<ul>';
			if (user.admin>0){
				info+='<li><b>Администратор</b>';
				if (user.admin==1) info+=' (запроc)';
				if (user.admin==2) info+=' (активен)';
				if (user.admin==3) info+=' (блокирован)';
				info+='</li>';
				}
			if (user.organizer>0){
				info+='<li><b>Организатор</b>';
				if (user.organizer==1) info+=' (запроc)';
				if (user.organizer==2) info+=' (активен)';
				if (user.organizer==3) info+=' (блокирован)';
				info+='</li>';
				}
			if (user.cluber>0){
				info+='<li><b>Руководитель клуба</b>';
				if (user.cluber==1) info+=' (запроc)';
				if (user.cluber==2) info+=' (активен)';
				if (user.cluber==3) info+=' (блокирован)';
				info+='</li>';
				}
			if (user.trainer>0){
				info+='<li><b>Тренер</b>';
				if (user.trainer==1) info+=' (запроc)';
				if (user.trainer==2) info+=' (активен)';
				if (user.trainer==3) info+=' (блокирован)';
				info+='</li>';
				}
			if (user.dancer>0){
				info+='<li><b>Танцор</b>';
				if (user.dancer==1) info+=' (запроc)';
				if (user.dancer==2) info+=' (активен)';
				if (user.dancer==3) info+=' (блокирован)';
				info+='</li>';
				}
			info+='</ul>';
			$('#user_info').html(info);
			if (user.trainer == 2) {
				$('#trainer_but').show();
				regionsList(user.trainer_info.region_id);
				$('#trainer_id').val(user.trainer_info.id);
				$('#trainer_name').html(user.last_name + ' ' + user.first_name);
				citiesList(user.trainer_info.region_id, user.trainer_info.city_id);
				clubList(user.trainer_info.city_id, user.trainer_info.club_id);
			}
			if (user.dancer_info > 0) {
				$('#dancer_but').show();
				$('#dancer_id').val(user.dancer_info);
				$('#dancer_name').html(user.last_name + ' ' + user.first_name);
				$('#dancer_lig_id').hide();
				$('#dancer_lig_id').html('');
				$('#dancer_points').hide();
				$('#dancer_points').val(0);
				getWays();
			}
		}
	})
}

$('#delete_confirm_but').click(function(){
	$.ajax({
		url: baseUrl + 'ajax/deleteUser',
		type:'POST',
		data:'id='+ $('#delete_id').val(),
		success: function(data){
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

$('#dancer_way_id').change(function() {
	var wayId = $('#dancer_way_id').val();
	if (wayId > 0) {
		getExperience(wayId);
	}
});

$('#save_dancer').click(function(){
	var dancerId = $('#dancer_id').val();
	var points = $('#dancer_points').val();
	var wayId = $('#dancer_way_id').val();
	var ligId = $('#dancer_lig_id').val();
	if (ligId > 0) {
		$.ajax({
			url: baseUrl + 'ajax/saveDancerExp',
			type:'POST',
			data:'dancer_id=' + dancerId + '&points=' + points + '&way_id=' + wayId + '&lig_id=' + ligId,
			success: function(data){
				console.log(data);
				if (data == '0') {
					var name = $('#dancer_name').text();
					text = '<p class="alert alert-success alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Опыт танцора ' + name + ' изменён</p>';
				} else {
					text = '<p class="alert alert-danger alert-dismissable" id="success"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>ОШИБКА:</strong> ' + data + '</p>';
				}
				$('#success').remove();
				$('main').prepend(text);
			}
		});
	} else {
		return false;
	}
	return true;
});

})})(jQuery)
