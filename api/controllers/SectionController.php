<?php

namespace api\controllers;
use common\models\Section;

class SectionController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Section';
    
    public function actions() {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }
    
    public function actionDelete($id) {
        return Section::findOne($id)->markDeleted();
    }
}
