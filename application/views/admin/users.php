<?php $this->load->view('header');?>

<!-- Modal delete-->
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Удалить пользователя</h4>
      </div>
      <div class="modal-body">
        <h4 id="delete_name"></h4>
		<input type="hidden" value="" id="delete_id">
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" id="delete_confirm_but">ДА</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">НЕТ</button>
      </div>
    </div>

  </div>
</div>
<!-- end delete modal -->

<!-- Modal edit trainer info-->
<div id="trainerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Изменить клуб</h4>
      </div>
      <div class="modal-body">
        <h4 id="trainer_name"></h4>
        <select id="region_id"></select>
        <select id="city_id"></select>
        <select id="club_id"></select>
		<input type="hidden" id="trainer_id">
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-success" data-dismiss="modal" id="save_trainer">СОХРАНИТЬ</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">НЕТ</button>
      </div>
    </div>

  </div>
</div>
<!-- end modal edit trainer info-->

<h1 class="h4 text-success">Администрирование пользователей</h1>
	<?php $this->load->view('admin/menu');?>
	<div class="row">
		<div class="col-md-2">
			<form id="filter_form">
				<h4>Фильтр</h4>
				<div class="form-group">
					<label for="filter_admin">Админ</label>
						<select name="filter_admin" id="filter_admin" class="form-control">
							<option value="-1">не важно</option>
							<option value="0">нет</option>
							<option value="1">запрошен</option>
							<option value="2">активен</option>
							<option value="3">блокирован</option>
						</select>
				</div>
				<div class="form-group">
					<label for="filter_organizer">Организатор</label>
						<select name="filter_organizer" id="filter_organizer"  class="form-control">
							<option value="-1">не важно</option>
							<option value="0">нет</option>
							<option value="1">запрошен</option>
							<option value="2">активен</option>
							<option value="3">блокирован</option>
						</select>
				</div>
				<div class="form-group">
					<label for="filter_cluber">Руководитель клуба</label>
						<select name="filter_cluber" id="filter_cluber"  class="form-control">
							<option value="-1">не важно</option>
							<option value="0">нет</option>
							<option value="1">запрошен</option>
							<option value="2">активен</option>
							<option value="3">блокирован</option>
						</select>
				</div>
				<div class="form-group">
					<label for="filter_trainer">Тренер</label>
						<select name="filter_trainer" id="filter_trainer"  class="form-control">
							<option value="-1">не важно</option>
							<option value="0">нет</option>
							<option value="1">запрошен</option>
							<option value="2">активен</option>
							<option value="3">блокирован</option>
						</select>
				</div>
				<div class="form-group">
					<label for="filter_dancer">Танцор</label>
						<select name="filter_dancer" id="filter_dancer"  class="form-control">
							<option value="-1">не важно</option>
							<option value="0">нет</option>
							<option value="1">запрошен</option>
							<option value="2">активен</option>
							<option value="3">блокирован</option>
						</select>
				</div>
				<div class="form-group">
					<label for="filter_text">Строка поиска</label>
					<input type="text" class="form-control" name="filter_text" id="filter_text">
				</div>
				<input type="submit" value="НАЙТИ" class="btn btn-info" id='filter_but'>
			</form>
		</div>
		<div class="col-md-4">
			<table class="table table-striped" id="user_table">
			  <tbody>
				<?php
				foreach ($users as $user) {
					echo '<tr>';
					echo '<td class="hidden">'.$user['id'].'</td>';
					echo '<td class="pointer">'.$user['last_name'].' '.$user['first_name'].' '.$user['father_name'].'</td>';
					echo '</tr>';
				}
			echo $this->pagination->create_links();
			 ?>
			 </tbody>
			</table>
		</div>
		<div class="col-md-3" id="user_info">

		</div>
		<div class="col-md-3">
			<form id="user_form">
				<input type="submit" id="edit_but" class='btn btn-warning' value="модерация">
				<br><br>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#trainerModal" id="trainer_but">Изменить клуб</button>
                <br><br>
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" id="delete_but">УДАЛИТЬ</button>
				<div id="edit_block">
					<input type="hidden" id="user_id" name="id" class="form-control">
					<input type="text" id="last_name" name="last_name" placeholder="фамилия..." class="form-control">
					<input type="text" id="first_name" name="first_name" placeholder="имя..." class="form-control">
					<input type="text" id="father_name" name="father_name" placeholder="отчество..." class="form-control">
					<input type="text" id="phone" name="phone" placeholder="телефон..." class="form-control">
					<input type="text" id="password" name="password" placeholder="пароль..." class="form-control">
					<input type="text" id="email" name="email" placeholder="e-mail.." class="form-control">

					<h4>Администратор</h4>
					<div class="radio">
						<label>
							<input type="radio" name="admin" value="0">нет
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="admin" value="1">запрошено
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="admin" value="2">активен
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="admin" value="3">блокирован
						</label>
					</div>

					<h4>Организатор</h4>
					<div class="radio">
						<label>
							<input type="radio" name="organizer" value="0">нет
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="organizer" value="1">запрошено
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="organizer" value="2">активен
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="organizer" value="3">блокирован
						</label>
					</div>

					<h4>Руководитель клуба</h4>
					<div class="radio">
						<label>
							<input type="radio" name="cluber" value="0">нет
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="cluber" value="1">запрошено
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="cluber" value="2">активен
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="cluber" value="3">блокирован
						</label>
					</div>

					<h4>Тренер</h4>
					<div class="radio">
						<label>
							<input type="radio" name="trainer" value="0">нет
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="trainer" value="1">запрошено
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="trainer" value="2">активен
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="trainer" value="3">блокирован
						</label>
					</div>

					<h4>Танцор</h4>
					<div class="radio">
						<label>
							<input type="radio" name="dancer" value="0">нет
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="dancer" value="1">запрошено
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="dancer" value="2">активен
						</label>
					</div>
					<div class="radio">
						<label>
							<input type="radio" name="dancer" value="3">блокирован
						</label>
					</div>

					<input type="submit" id="save_but" class='btn btn-success'  value="сохранить">
				</div>
			</form>

		</div>

	</div>
<?php

$this->load->view('footer'); ?>
<script>
<?php
if ($page == 0) {
	echo "var baseUrl = '../'";
} else {
	echo "var baseUrl = '../../'";
}
 ?>
</script>
<script src="/js/admin/admin_users.js"></script>
