<?php
use \yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Files;

$file = new Files(['user_id' => Yii::$app->user->id, 'type' => 'cancellationDocument']);

if(isset($document)){

}

?>

<div class="container">

        <div class="panel-heading"><span class="glyphicon glyphicon-inbox"></span><strong> <?= Html::encode($this->title) ?></strong></div>
        <div class="panel-body">
            <div class="upload-document">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?=$form->field($file, 'file')->fileInput(); ?>

                <?=$form->field($model, 'description')->textInput(); ?>

                <div class="form-group">
                    <?=Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success', 'id' => 'upload-document']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>