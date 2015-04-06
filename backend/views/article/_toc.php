<?php
/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
while (!is_null($rootId)) {
    ?>
    <li id="section<?= $sections[$rootId]['id'] ?>" data-id="<?= $sections[$rootId]['id'] ?>" data-ver="<?= $sections[$rootId]['ver'] ?>">
        <a class= "title" href="#"><?= $sections[$rootId]['title'] ?></a>
        <ul class="section" data-parent="<?= $sections[$rootId]['id'] ?>"></ul>
    </li>
    <?php
    $rootId = $sections[$rootId]['next'];
}
?>