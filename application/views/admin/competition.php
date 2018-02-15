<?php $this->load->view('header');?>
<?php if ($status == "DONE"):?>
    <!-- Modal -->
<div id="recoverModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Отменить результаты конкурса</h4>
      </div>
      <div class="modal-body">
        <h3><?php echo $this->CabinetModel->getCompName($comp_id); ?></h3>
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" href="../recoverCompetition/<?php echo $comp_id; ?>">ДА</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">НЕТ</button>
      </div>
    </div>

  </div>
</div>
<?php endif?>
<div id="rewardmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Для награждения</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-striped" id="reward_table">
                    <tbody>

                    </tbody>
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Участников</th>
                            <th>Медаль 1 место</th>
                            <th>Медаль 2 место</th>
                            <th>Медаль 3 место</th>
                            <th>Кубок 1 место</th>
                            <th>Кубок 2 место</th>
                            <th>Кубок 3 место</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
<?php include_once 'menu.php';?>
<?php echo '<h1>'.$this->CabinetModel->getCompName($comp_id).'</h1>'; ?>
<input type="hidden" id="comp_id" value="<?php echo $comp_id; ?>">
<button class="btn btn-info" id="reward_but" data-toggle="modal" data-target="#rewardmodal">Для награждения</button>
<?php
if ($status == "CLOSE") {
    echo '<a class="btn btn-info" href="../numbers/' . $comp_id . '">Номера участников</a>';
} else {
    echo '<button class="btn btn-warning" id="close_but">Остановить регистрацию</button>';
}
 ?>
<a class="btn btn-info" href="../comppays/<?php echo $comp_id; ?>">Оплата за конкурс</a>
<a class="btn btn-warning <?php if($status!='CLOSE') echo ' disabled'; ?>" href="../uploadResults/<?php echo $comp_id; ?>" target="_blank">Распределить места</a>
<a class="btn btn-default" href="../admincompcontacts/<?php echo $comp_id; ?>">Контакты</a>
<a class="btn btn-success" href="../adddancers/<?php echo $comp_id; ?>">Добавить танцоров</a>
<button class="btn btn-danger" id="done_but">Завершить конкурс</button>
<?php
/// кнопка Откатить конкурс
if ($status == "DONE") {
    echo '<a class="btn btn-default" data-toggle="modal" data-target="#recoverModal" href="#">Отменить результаты</a>';
}
 ?>
<h3>Скачать в формате CSV:</h3>
<?php
 foreach($files as $file){
     echo '<a href="'.base_url().$file['file'].'" class="btn btn-link">'.$file['name'].'</a><br>';
 }
?>
<p id="mess"></p>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped" id="main_table">
            <caption>Участники соревнования</caption>
            <tbody id="comp_list">
                <?php echo $comp_list; ?>
            </tbody>
            <thead>
                <tr>
                    <th>№ уч</th>
                    <th>Имя</th>
                    <th>Год р.</th>
                    <th>Л.танцора</th>
                    <th>Очки</th>
                    <th>Стиль</th>
                    <th>Кат.возраст</th>
                    <th>Кат.кол.</th>
                    <th>Лига конк.</th>
                    <th>Регистр.</th>
                    <th>Оплата</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script src="<?php echo base_url(); ?>/js/admin/competition.js"></script>
<?php $this->load->view('footer'); ?>
