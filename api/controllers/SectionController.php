<?php

namespace api\controllers;
use common\models\Section;
use Yii;

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
        $model->markDeleted();
        Yii::$app->getResponse()->setStatusCode(204);
    }
}
