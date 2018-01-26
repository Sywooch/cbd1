<?php
use \yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Files;

$file = new Files(['user_id' => Yii::$app->user->id, 'type' => 'cancellationDocument']);

if(isset($document)){

}

?>

<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane auctions fade show active" id="nav-auctions" role="tabpanel" aria-labelledby="nav-auctions-tab">
            <div class="auctions-header">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <h3 class="auctions-title"><?= $this->title; ?></h3>
                    </div>
                </div>
            </div>
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