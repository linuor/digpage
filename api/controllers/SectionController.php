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
        $model = Section::find()->with('nextSection')->with('prevSection')
                ->where(['id'=>$id])->one();
        if ($model->markDeleted() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }
}
