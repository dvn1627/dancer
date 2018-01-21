<?php $this->load->view('header');?>
<div class="row">
    <?php echo $this->CabinetModel->getCompLink($comp_id); ?>
    <form method="POST" action="/index.php/cabinet/compreglist">
    <input type="hidden" name="comp_id" value="<?php echo $comp_id;?>">
    <div class="col-md-2">
        <button class="btn btn-success">
            Добавить
        </button>
    </div>
    <div class="col-md-3">
        
            <table>
                <tbody>
                    <?php echo $dancers; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Имя</th>
                    </tr>
                </thead>
            </table>
    </div>
    <div class="col-md-7">
        <table class="table table-condensed">
            <tbody>
                <?php echo $comp_list;?>
            </tbody>
            <thead>
                <tr>
                    <th>Танцор</th>
                    <th>Регистрация</th>
                </tr>
            </thead>
        </table>
    </div>
</form>
</div>
<?php $this->load->view('footer'); ?>