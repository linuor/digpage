<?php

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $rootId integer */
/* @var $level integer */
/* @var $model common\models\Section */
$model = $sections[$rootId];
$tag = 'h' . ($level>6?6:$level);
$title = "<$tag>" . $model->title . "</$tag>";
?>
<div class="section" id="<?= $rootId ?>">
    <?= $title ?>
    <?= $model->content ?>
<?php
$curModle = $model->getFirstChildSection()->one();
if ($curModle !== null) {
    $cur = $curModle->id;
    while ($cur !== null) {
        echo $this->render('_view', [
            'sections' => $sections,
            'rootId' => $cur,
            'level' => $level + 1,
        ]);
        $cur = $sections[$cur]->next;
    }
}
?>
</div>
