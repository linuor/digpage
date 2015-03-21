<?php

use yii\helpers\Html;
use backend\assets\ArticleSortAsset;

/* @var $this yii\web\View */
/* @var $sections array */
/* @var $headingId integer */

$this->title = Yii::t('backend/section', 'Sections Sort');
$this->params['breadcrumbs'][] = $this->title;
ArticleSortAsset::register($this);
?>
<div class="section-sort">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend/section', 'Save'), ['index'], ['class' => 'btn btn-success', 'id' => 'btn-save']) ?>
        <?= Html::a(Yii::t('backend/section', 'Cancel'), ['index'], ['class' => 'btn btn-primary', 'id' => 'btn-cancel']) ?>
    </p>
    <ul>
<?php
while ($headingId !== null) {
    echo $this->render('_sort', [
        'sections' => $sections,
        'rootId' => $headingId,
    ]);
    $headingId = $sections[$headingId]['next'];
}
?>
    </ul>
</div>
