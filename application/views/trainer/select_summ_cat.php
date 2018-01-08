<?php $this->load->view('header');?>
<?php echo '<h1>'.$this->CabinetModel->getCompName($comp_id).'</h1>'; ?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">В этих категориях зарегистрировались:</h4>
      </div>
      <div class="modal-body" id="result">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="row">
    <form id="comp_form">
        <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_id;?>">
    </form>
    <div class="col-md-2">
        <form id="dancers_form">
            <table class="table">
                <tbody>
                    <?php if(isset($dancers))echo $dancers; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Танцор</th>
                        <th>Дата рожд.</th>
                    </tr>
                </thead>
            </table>
        </form>
        <button id="add_but" class="btn btn-success">
            Зарегистрировать
        </button>
    </div>
    <div class="col-md-4">
        <table class="table table-condensed">
            <tbody>
                <?php if(isset($list)) echo $list; ?>
            </tbody>
            <thead>
                <tr>
                    <th>Категория</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table">
            <tbody id="comp_list">
            </tbody>
            <thead>
                <tr>
                    <th>Танцор</th>
                    <th>Категория</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script src="<?php echo base_url(); ?>js/trainer/select_sum.js"></script>
<?php $this->load->view('footer'); ?>
