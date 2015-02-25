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
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->markDeleted();
        Yii::$app->getResponse()->setStatusCode(204);
    }
}
