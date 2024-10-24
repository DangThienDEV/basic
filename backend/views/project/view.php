    <?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Project $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [   // đưa lablel vào yii:t để sử dụng đc nhiều ngôn ngữ thông qua app để images có thể đc nhiều ngôn ngữ
                'label' => Yii::t('app','images'),
                // định dạng giá trị cột raw
                'format' => 'raw',
                // value đc truyền vào thông qua một model
                'value'=> function ($model) {
                    // hasImages được tạo bên file/model để kiểm tra có hình ảnh hay không
                    if(!$model->hasImages()){
                        return null;
                    }
                    // đưa ra 1 cái html cho image để hiển thị nếu ảnh tồn tại
                    $imagesHtml = "";
                    // đưa vào 1 vòng lặp để đưa ra ngoài
                    foreach ($model->images as $image) {
                        // tạo 1 cái Html::img để tạo thẻ cho hình ảnh => thêm hình ảnh vào $imagesHtml và thông qua image và đường dẫn tuyệt đối
                        $imagesHtml .= Html::img($image->file->absoluterUrl(),[
                            //thuộc tính alt
                            "alt" => "Demonstration of the user interface",
                            // căn chỉnh css
                            "height" => 200,
                            "class" => "project-view__image",
                        ]);  
                }
                // sau khi thành công thì sẽ trả về hình ảnh b
                return $imagesHtml;
            }
            ],
            'tech_stack:raw',
            'descritption:raw',
            'start_date',
            'end_date',
        ],
    ]) ?>

</div>
