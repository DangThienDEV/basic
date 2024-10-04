<?php

namespace app\controllers;

use Yii;
use app\models\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Html;

class PostController extends Controller
{
    public function actionIndex()
    {
        $posts = Post::find()->all(); // Lấy tất cả bài viết
        return $this->render('index', ['posts' => $posts]);
    }

    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time(); // Đặt thời gian tạo
            $model->updated_at = time(); // Đặt thời gian cập nhật
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time(); // Cập nhật thời gian
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
