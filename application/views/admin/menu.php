<?php
if ($new) {
    echo '<h2 class="text-danger">Есть новые запросы ролей</h2>';
}
 ?>
<div class="btn-group">
    <a href="<?php echo base_url();?>index.php/cabinet/adminusers" class="btn btn-default">
        Пользователи
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminways" class="btn btn-default">
        Направления
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminstyles" class="btn btn-default">
        Стили
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/admincounts" class="btn btn-default">
        Категории по колличеству
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminages" class="btn btn-default">
        Категории по возрасту
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminligs" class="btn btn-default">
        Лиги
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminagelig" class="btn btn-default">
        возраст - лига
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/admincompetitions" class="btn btn-default">
        Конкурсы
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/adminarchive" class="btn btn-default">
        Архив
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/yearpay" class="btn btn-default">
        Ежегодная оплата
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/statistic" class="btn btn-default">
        Статистика
    </a>
    <a href="<?php echo base_url();?>index.php/cabinet/cities" class="btn btn-default">
        Города
    </a>
</div>
