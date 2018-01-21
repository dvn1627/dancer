<?php $this->load->view('header');?>

<!-- Modal delete-->
<div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Удалить город</h4>
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

<!-- edit modal -->
<div id="editmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Редактирование</h4>
            </div>
            <div class="modal-body">
                <form id="formEditmodal">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="text" class="form-control" id="edit_name" name="style">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="savemodal">СОХРАНИТЬ</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<h1 class="h4 text-success">Стили</h1>
<?php $this->load->view('admin/menu');?>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-11">
        <select class="form-control" id="region_select">
            <option value="0">Выберите область</option>
            <?php
            foreach ($regions as $region) {
                echo '<option value=' . $region['id'] . '>' . $region['region'] . '</option>';
            }
             ?>
        </select>
        <div id="cities_block">
            <table id="cities_table" class="table table-striped">
                <tbody></tbody>
                <thead>
                    <tr>
                        <td>Город</td>
                        <td>действия</td>
                    </tr>
                </thead>
            </table>
            <form id="add_form">
                <div class="input-group">
                    <input type="hidden" id="region_id" name="region_id">
                    <input type="text" placeholder="название..." id="new" class="form-control" name="city">
                    <div class="input-group-btn">
                        <input type="submit" value="добавить" class="btn btn-success" id="new_but">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo base_url(); ?>/js/admin/cities.js"></script>
