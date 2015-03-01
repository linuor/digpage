<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $sections array */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">
    <p>
        <?= Html::a(Yii::t('backend/section', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend/section', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            //TODO modify delete
            'data' => [
                'confirm' => Yii::t('backend/section', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?=
$this->render('_view', [
    'sections' => $sections,
    'rootId' => $model->id,
    'level' => 1,
]);
?>
</div>
