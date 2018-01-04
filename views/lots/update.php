<?php

use yii\helpers\Html;
use app\models\Files;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

$js = "
$('#submissive-btn').on('click', function(){
    $('#submit-auction-btn').click();
});
";
$this->registerJs($js, \yii\web\View::POS_READY);


$this->title = Yii::t('app', 'Update Auction: ') . ' ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');


$files = new Files();


$documentTypes = $files->lotDocumentTypes();

unset($documentTypes['virtualDataRoom']);
if($model->procurementMethodType == 'dgfOtherAssets'){
    unset($documentTypes['x_nda']);
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

            <?php if($model->apiAuction): ?>

                <div class="row well">
                    <div class="col-md-12"><h3><?=Yii::t('app', 'Upload additional documents'); ?></h3></div>
                    <div class = "col-md-12">
                        <?php $form = ActiveForm::begin([
                            'action' => \yii\helpers\Url::to(['/lots/upload-document', 'id' => $model->id] ),
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ],
                        ]); ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($files, 'type')->dropDownList($documentTypes); ?>
                            </div>

                            <div class="col-sm-5">
                                <?= $form->field($files, 'file')->widget(FileInput::className(), [
                                    'options' => [
                                        'id' => 'file-type-input',
                                        'multiple' => false,
                                    ],
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'showPreview' => false,
                                    ]
                                ]); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success btn-block', 'style' => 'margin-top: 25px', 'id' => 'lot-document-upload-btn']); ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="well">

                <?php if($model->apiAuction): ?>

                    <div class="col-sm-12">
                        <?php foreach($model->documents as $document): ?>
                            <div class="well">
                                <?=Html::a($files->lotDocumentTypes()[$document->type] . ' - (' .  $document->name .')',
                                    $document->url, ['id' => $document->id]); ?>
                                <?=Html::a(Yii::t('app', 'Change'), ['reupload', 'document_id' => $document->unique_id, 'id' => $model->id, 'file_id' => $document->file_id], ['class' => 'btn btn-warning']); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if(!$model->apiAuction): ?>
                    <div class="well">
                        <?php if($model->items): ?>
                            <div class="col-sm-12">
                                <h3><?=Yii::t('app', 'Auction items'); ?>:</h3>
                                <?php foreach($model->items as $item): ?>
                                    <p id="<?=$item->id?>" class="lead">
                                        <?=Html::a(
                                            '<i class="glyphicon glyphicon-remove"></i>',
                                            ['/lots/delete-item', 'id' => $item->unique_id],
                                            [
                                                'data' => [
                                                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                    'method' => 'post',
                                                ]
                                            ]); ?>
                                        <?=$item->description; ?> (<?=$item->quantity; ?> <?=Yii::t('app', 'pcs.')?>)
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="lead"><?=Html::tag('h3', Yii::t('app', 'There is no items'))?></p>
                        <?php endif; ?>
                        <?=Html::a(Yii::t('app', 'Add auction item'), ['/lots/add-items', 'id' => $model->id], ['class' => 'btn btn-success lead', 'id' => 'create-item-btn']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>