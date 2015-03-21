<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->getTitle();
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-view">
    <p>
        <?= Html::a(Yii::t('backend/section', 'Update'), ['update', 'id' => $model->getId()], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend/section', 'Delete'), ['delete', 'id' => $model->getId()], [
            'class' => 'btn btn-danger',
            //TODO modify delete
            'data' => [
                'confirm' => Yii::t('backend/section', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php
echo $this->render('_view', [
    'sections' => $model->getSections(),
    'rootId' => $model->getId(),
    'level' => 1,
]);
?>
</div>
