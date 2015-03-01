<?php

use backend\assets\ArticleUpdateAsset;
use common\models\Section;
use yii\web\View;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $sections array */

$items = [];
$allTags = [
    'toc_mode' => Section::getAllTocMode(),
    'comment_mode' => Section::getAllCommentMode(),
];
foreach ($allTags as $field => $fieldTags)
{
    $tmpTags =[];
    foreach ($fieldTags as $k=>$s) {
        $tmpTags[] = [
            'value' => strval($k),
            'label' => $s,
        ];
    }
    $fieldTitle = $model->attributeLabels()[$field];
    
    $items[$field] = [
        'label' => $fieldTitle,
        'title' => $fieldTitle,
        'groups' => [
            [
                'label' => $fieldTitle,
                'tags' => $tmpTags,
            ],
        ],
    ];
}

$js = 'window.digpage=window.digpage||{};window.digpage.update_dropdown_items = ' . Json::encode($items) . ';';
$this->registerJs($js, View::POS_END);

ArticleUpdateAsset::register($this);

$this->title = Yii::t('backend/section', 'Update {modelClass}: ', [
    'modelClass' => 'Section',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend/section', 'Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend/section', 'Update');
?>
<div class="section-update">
<?=
$this->render('_update', [
    'sections' => $sections,
    'rootId' => $model->id,
    'level' => 1,
]);
?>
</div>
