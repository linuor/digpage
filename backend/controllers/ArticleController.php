<?php

namespace backend\controllers;

use Yii;
use common\models\Article;
use common\models\Section;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Sort;

/**
 * ArticleController implements the CRUD actions for Section model.
 */
class ArticleController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Section models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(new Sort([
            'defaultOrder' => ['updated_at' => SORT_DESC],
        ]));
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Section model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Article();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (Yii::$app->request->post('isPublish', 0) == 1) {
                $model->status = Section::STATUS_PUBLISH;
            }
            $model->create();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->set('KCFINDER', [
                'disabled' => false,
                'uploadURL' => '/upload',
                'uploadDir' => '../../../backend/web/upload',
            ]);
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        return $this->render('update', [
                    'model' => $model,
                    'sections' => $model->getSections(),
        ]);
    }

    public function actionToc($id = null) {
        if (Yii::$app->request->getIsAjax()) {
            $res = Article::getOrderedArticleToc($id);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $res;
        }
        return $this->render('toc');
    }
    
    public function actionReorder($id) {
        if (Yii::$app->request->getIsAjax()){
            $section = Section::findOne(['id' => $id]);
            $section->reorder(Yii::$app->request->post());
        }
    }

    /**
     * Deletes an existing Section model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = new Article();
        if ($model->loadArticle($id)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
