<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\editors\Summernote;

/** @var yii\web\View $this */
/** @var common\models\Project $model */
/** @var yii\widgets\ActiveForm $form */
?>

<class="project-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);  ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php
    echo $form->field($model, 'tech_stack')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]);
    ?>
    <?php
    echo $form->field($model, 'descritption')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]);
    ?>

    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::class, [
    ]) ?>
    <br>
    <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::class, [
    ]) ?>
    <?=
    $form->field($model, 'imageFile')->fileInput()?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
