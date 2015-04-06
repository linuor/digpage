<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Section;
use backend\assets\ArticleIndexAsset;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend/section', 'Sections');
$this->params['breadcrumbs'][] = $this->title;
ArticleIndexAsset::register($this);
?>
<div class="section-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend/section', 'Create {modelClass}', [
    'modelClass' => 'Section',
]), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('backend/section', 'Sort {modelClass}', [
            'modelClass' => 'Section',
        ]),['toc'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid){
            return [
                'data' => ['sectionver' => $model->ver,],
            ];
        },
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'title',
                'value' => function ($model, $key, $index, $column) {
                    return Html::a($model->title, ['article/view', 'id' => $key]);
                },
                'format' => 'raw',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'value' => function ($model, $key, $index, $column) {
                    return Html::dropDownList(
                            'status' . $key,
                            $model->status,
                            $model->getAllStatus(),
                            [
                                'class' => 'stauts-dropdown',
                                'data' => [
                                    'sectionfield' => 'status',
                                ],
                            ]
                            );
                },
                'format' => 'raw',
                'filter' => Section::getAllStatus(),
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'toc_mode',
                'value' => function ($model, $key, $index, $column) {
                    return Html::dropDownList(
                            'toc_mode' . $key,
                            $model->toc_mode,
                            $model->getAllTocMode(),
                            [
                                'class' => 'toc_mode-dropdown',
                                'data' => [
                                    'sectionfield' => 'toc_mode',
                                ],
                            ]
                            );
                },
                'format' => 'raw',
                'filter' => Section::getAllTocMode(),
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'comment_mode',
                'value' => function ($model, $key, $index, $column) {
                    return Html::dropDownList(
                            'comment_mode' . $key,
                            $model->comment_mode,
                            $model->getAllCommentMode(),
                            [
                                'class' => 'comment_mode-dropdown',
                                'data' => [
                                    'sectionfield' => 'comment_mode',
                                ],
                            ]
                            );
                },
                'format' => 'raw',
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
                'header' => Yii::t('backend/section', 'Operation'),
                'buttons' => [
                    'delete' => function($url, $model, $key){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                'javascript:void(0);', [
                                    'title' => Yii::t('backend/section', 'Delete'),
                                    'class' => 'lnk-del-section'
                                ]);
                    },
                ],
            ],
        ],
]); ?>

</div>
