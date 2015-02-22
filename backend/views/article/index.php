<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Section;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/section', 'Sections');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend/section', 'Create {modelClass}', [
    'modelClass' => 'Section',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'title',
                'value' => function ($model, $key, $index, $column) {
                    return Html::a($model->getTitleText(), ['article/view', 'id' => $key]);
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model, $key, $index, $column) {
                    return $model->getStatusText();
                },
                'filter' => Section::getAllStatus(),
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'toc_mode',
                'value' => function ($model, $key, $index, $column) {
                    return $model->getTocModeText();
                },
                'filter' => Section::getAllTocMode(),
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'comment_mode',
                'value' => function ($model, $key, $index, $column) {
                    return $model->getCommentModeText();
                },
                'filter' => Section::getAllCommentMode(),
            ],
            'updated_at:datetime',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'created_by',
                'value' => function ($model, $key, $index, $column) {
                    if ($model->getCreatedBy() === null) {
                        return '';
                    }
                    return $model->getCreatedBy()->one()->username;
                },
                'filter' => false,
            ],        
            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
]); ?>

</div>
