<?php $this->view('header'); ?>
<form action="<?php echo base_url(); ?>index.php/auth/addcluber" method="post" id="reg_form">
	<div class="row">
		<div class="col-md-4">
			
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Область</label>
				<select  required name="region" class="form-control" id="region">
					<option value="0">выберите область...</option>
					<?php 
					echo $regions;
					 ?>
				</select>
			</div>
			<div class="form-group" id="cities">
				<label>Область</label>
				<select name="city" class="form-control" id="city">
					
				</select>
			</div>
			<div class="form-group" id="cities">
				<label>
					Название клуба
					<input required  type="text" class="form-control" name="name" id="name">
				</label>
			</div>
			<input type="submit" class="btn btn-default" name="add" id="add" value="Зарегистрироваться">
		</div>
		<div class="col-md-4">
			
		</div>
	</div>
</form>
<script src="<?php echo base_url(); ?>/js/register_cluber.js"></script>
<?php $this->view('footer'); ?>