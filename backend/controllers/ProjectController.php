<?php

namespace backend\controllers;

use common\models\Project;
use backend\models\ProjectSearch;
use common\models\ProjectImage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\UploadedFile;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'delete-project-image' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Project models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
             $model->loadUploadedImagefiles();  
                if( $model->save()){
                    $model->saveImage();
                    Yii::$app->getSession()->setFlash('success','Thành Công');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
              
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->loadUploadedImagefiles();  
            if ($model->save()) { // Lưu mô hình vào cơ sở dữ liệu.
                $model->saveImage(); // Lưu hình ảnh liên quan đến dự án.
                Yii::$app->getSession()->setFlash('success', 'Thành Công'); // Thiết lập thông báo thành công.
                return $this->redirect(['view', 'id' => $model->id]); // Chuyển hướng đến trang xem dự án vừa tạo.
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    // Hàm xóa ảnh
    public function actionDeleteProjectImage(){
        // lấy 1 hình ảnh theo id
        // $image = ProjectImage::findOne($this->request->post('id'));       // kiểm tra nếu không có ảnh
        // lấy hình ảnh theo key
        $image = ProjectImage::findOne($this->request->post('key'));       // kiểm tra nếu không có ảnh
 
        if(!$image){
            throw new NotFoundHttpException('img not found');
        }
        // hàm xóa ảnh 
        // if($image->file->delete()){
            // truyền đúng hình ảnh cần xóa
        //     $path = Yii::$app->params['uploads']['projects'] .'/'. $image->file->name;// lưu ý cần phải đúng đường link
        //     unlink($path);// xóa file vật lý trong project
        // }
        if($image->file->delete()){
            return json_encode(null);
        }
        return json_encode(['error' => true]);

    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
