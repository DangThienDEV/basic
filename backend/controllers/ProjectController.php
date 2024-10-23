<?php

namespace backend\controllers;

use common\models\Project;
use backend\models\ProjectSearch;
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
    public function actionCreate() // Phương thức tạo mới một dự án.
    {
        $model = new Project(); // Khởi tạo một đối tượng Project mới.
    
        if ($this->request->isPost) { // Kiểm tra xem yêu cầu có phải là POST không.
            if ($model->load($this->request->post())) { // Tải dữ liệu từ POST vào mô hình.
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile'); // Lấy tệp hình ảnh từ yêu cầu.
                if ($model->save()) { // Lưu mô hình vào cơ sở dữ liệu.
                    $model->saveImage(); // Lưu hình ảnh liên quan đến dự án.
                    Yii::$app->getSession()->setFlash('success', 'Thành Công'); // Thiết lập thông báo thành công.
                    return $this->redirect(['view', 'id' => $model->id]); // Chuyển hướng đến trang xem dự án vừa tạo.
                }
            }
        } else {
            $model->loadDefaultValues(); // Tải các giá trị mặc định vào mô hình nếu không phải là yêu cầu POST.
        }
    
        return $this->render('create', [ // Hiển thị trang tạo mới với mô hình đã tải.
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success','Thành Công');
            return $this->redirect(['view', 'id' => $model->id]);
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
