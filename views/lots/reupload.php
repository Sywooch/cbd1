<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 23.01.17
 * Time: 19:04
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Files */
/* @var $lot app\models\Lots */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Reupload lot document');

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
            <div class="col-sm-8">
                <?=$form->field($model, 'file')->fileInput(['class' => 'form-control']); ?>
            </div>
            <div class="col-sm-4">
                <?=Html::submitButton(Yii::t('app', 'Reupload'), ['class' => 'btn btn-primary btn-block', 'style' => 'margin-top: 25px']); ?>
            </div>
            <div class="col-sm-4">
                <div class="hidden">
                    <?=$form->field($model, 'type')->dropDownList($model->lotDocumentTypes()); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>