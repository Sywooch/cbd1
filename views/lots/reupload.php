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
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="document-reupload">
                <div class="row">
                    <div class="col-sm-12">
                        <?=Html::tag('h3', Yii::t('app', 'Reupload lot document'), ['class' => ''])?>
                    </div>
                    <div class="col-sm-12">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <div class="col-sm-8">
                            <?=$form->field($model, 'file')->widget(\kartik\file\FileInput::className(), [
                                'options' => ['multiple' => false],
                                'pluginOptions' => ['showUpload' => false, 'showPreview' => false],
                            ]); ?>
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
        </div>
    </div>
</div>