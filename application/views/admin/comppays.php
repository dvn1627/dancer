<?php $this->load->view('header');?>
<?php include_once 'menu.php';?>
<h1 class="h1 text-success">оплата за конкурс</h1>
<?php echo $this->CabinetModel->getCompLink($comp_id); ?>
<p id="mess"></p>
<div class="row">
    <div class="col-md-4">
        <table class="table table-striped" id="main_table">
            <caption>Участники соревнования</caption>
            <tbody id="comp_list">
                <?php echo $comp_list; ?>
            </tbody>
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Категория</th>
                    <th>Оплата</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-8">
        <form id="pay_form">
            <input type="hidden" value="<?php echo $comp_id; ?>" id="comp_id">
            <table class="table table-striped" id="main_table">
                <caption>Оплата</caption>
                <tbody>
                    <?php echo $pay_list; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Категория по количеству</th>
                        <th>Лига</th>
                        <th>Опл. IUDE</th>
                        <th>Опл. др.орг.</th>
                        <th>Опл. нет орг.</th>
                    </tr>
                </thead>
            </table>
            <input class="btn btn-success" type="submit" value="СОХРАНИТЬ" id="save_but">
        </form>
    </div>
</div>
<script src="<?php echo base_url(); ?>/js/admin/pays.js"></script>
<?php $this->load->view('footer'); ?>