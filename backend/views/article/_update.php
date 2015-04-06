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
<div class="section">
    <div id="section<?= $rootId?>" contenteditable="true"
         data-id="<?=$model->id?>"
         data-ver="<?=$model->ver?>"
         data-status="<?=$model->status?>"
         data-comment_mode="<?=$model->comment_mode?>"
         data-toc_mode="<?=$model->toc_mode?>"
         >
        <?= $title ?>
        <?= $model->content ?>
    </div>
    <?php
    $curModle = $model->getFirstChildSection()->one();
    if ($curModle !== null) {
        $cur = $curModle->id;
        while ($cur !== null) {
            echo $this->render('_update', [
                'sections' => $sections,
                'rootId' => $cur,
                'level' => $level + 1,
            ]);
            $cur = $sections[$cur]->next;
        }
    }
    ?>
</div>
