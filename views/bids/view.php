<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Files;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model api\Bids */

$this->title = $model->apiAuction ? $model->apiAuction->title : $model->lot->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$file = new \app\models\Files([
    'user_id' => Yii::$app->user->id,
    'bid_id' => $model->unique_id,
]);

$documentTypes = $file->bidDocumentTypes();

$procurementMethodType = $model->apiAuction ? $model->apiAuction->procurementMethodType : $model->lot->procurementMethodType;

$licenseRequired = $model->apiAuction ? $model->apiAuction->licenseRequired : $model->lot->procurementMethodType == 'dgfFinancialAssets';

if($licenseRequired && !$model->financialLicense && ($model->user_id == Yii::$app->user->id)){
    if(Yii::$app->user->identity->profile->org_type == 'entity'){
        Yii::$app->session->setFlash('danger', Yii::t('app', 'Необхідно завантажити підписане повідомлення про те, що ви не є боржником та/або поручителем за даним кредитним договором'));
        $documentTypes['financialLicense'] = Yii::t('app', 'Підписане повідомлення про те, що ви не є боржником та/або поручителем за даним кредитним договором');
    }
    else{
        Yii::$app->session->setFlash('danger', Yii::t('app', 'You must upload the financial license'));
    }
}
else{
    unset($documentTypes['financialLicense']);
}

if($model->accepted == '0') {
    $js =<<< JS
    $('#decline-modal-btn').on('click', function(){
        $('#decline-bid-modal').modal('show');
    });
JS;


    $this->registerJs($js, \yii\web\View::POS_READY);

    \yii\bootstrap\Modal::begin(['id' => 'decline-bid-modal']);

    $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to([
            '/bids/decline',
            'id' => $model->unique_id
        ])
    ]);

    $messages = new \app\models\Messages();

    echo $form->field($messages, 'notes')->textArea(['rows' => 6]);

    echo Html::submitButton(Yii::t('app', 'Send ID'), ['class' => 'btn btn-primary', 'id' => 'decline-btn']);

    ActiveForm::end();

    \yii\bootstrap\Modal::end();
}


?>


<main class="site-content">
    <div class="bid-detail">
        <div class="container">
            <div class="bid-detail-header mt-4 mb-5">
                <a class="link-primary auction-detail-back-arrow d-inline-block mb-2" href="/bids/index">Назад до заявок</a>
                <div class="row align-items-center">
                    <div class="col-lg-5 align-self-center">
                        <h3>Cтавка #<?=$model->unique_id?></h3>
                    </div>
                    <div class="col-lg-7 text-right">
                        <span class="text-white bg-danger p-2"><?=$model->apiAuction->statusName?></span>
                    </div>
                </div>
            </div>

            <?php if(Yii::$app->user->can('admin')): ?>
                <?php if($model->accepted == '0' && $model->status == 'draft'): ?>
                    <?=Html::a(Yii::t('app', 'Прийняти'), ['accept', 'id' => $model->unique_id], ['class' => 'btn btn-success']); ?>
                    <?=Html::a(Yii::t('app', 'Повідомити про помилку'), false, ['class' => 'btn btn-warning', 'id' => 'decline-modal-btn']); ?>
                <?php endif; ?>
            <?php endif;?>

            <!--      ORGANIZATOR      -->

            <?php if($model->contract) {

                $js = "
                    $('#contract-signed-btn').on('click', function(e){
                        e.preventDefault();
                        $('#contract').removeClass('hidden');
                    });
                ";

                $this->registerJs($js, \yii\web\View::POS_READY);

                echo '<div id="contract" class="hidden">';

                echo Html::tag('h3', 'Підтвердження підписання контракту');

                $form = ActiveForm::begin([
                    'action' => \yii\helpers\Url::to([
                        '/bids/confirm-contract',
                        'id' => $model->unique_id]),
                    'method' => 'GET',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]);

                $messages = new \app\models\Messages();

                echo $form->field($model->contract, 'dateSigned')->widget(DateTimePicker::className(), [
                    'name' => 'date',
                    'options' => ['placeholder' => Yii::t('app', 'Select date of contract signing'), 'id' => 'contract-signed-input'],
                    'pluginOptions' => ['autoclose' => true]
                ])->hint($model->contract->hint);

                echo $form->field($model->contract, 'contractNumber')->textInput();

                echo Html::submitButton(Yii::t('app', 'Send ID'), ['class' => 'btn btn-primary', 'id' => 'contract-signed-submit']);

                ActiveForm::end();

                echo '</div>';
            } ?>

            <?php if(Yii::$app->user->can('org') && $model->apiAuction && $model->apiAuction->lot && $model->apiAuction->lot->user_id == Yii::$app->user->id): ?>
                <!-- NOT PUBLISHED -->
                <?php if(!$model->id): ?>
                    <?php if(($model->apiAuction->procurementMethodType == 'dgfFinancialAssets') && !$model->financialLicense): ?>
                        <p class="lead"><?=Yii::t('app', 'Waiting for uploading financial license'); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <!-- END IS NOT PUBLISHED -->

                <?php if($model->award): ?>
                    <!-- VERIFICATION -->
                    <?php if($model->award->status == 'pending.verification'): ?>
                        <?php if($model->orgAuctionProtocol && !$model->apiAuction->isEnded): ?>
                            <?=Html::a(Yii::t('app', 'Confirm protocol'),
                                ['confirm-protocol', 'id' => $model->unique_id],
                                [
                                    'class' => 'btn btn-success',
                                    'data' => [
                                        // 'confirm' => Yii::t('app', 'Are you sure?'),
                                        'method' => 'post',
                                    ],
                                    'id' => 'confirm-protocol-btn',
                                ]); ?>
                        <?php else: ?>
                            <?=Yii::t('app', 'Auction is ended. You can {upload} the auction protocol', ['upload' => Html::a(Yii::t('app', 'upload'), ['upload-protocol', 'id' => $model->unique_id], ['class' => 'btn btn-primary', 'id' => 'upload-protocol-btn'])]); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <!-- END VERIFICATION -->
                    <!-- PAYMENT -->
                    <?php if($model->award->status == 'pending.payment'): ?><?=Html::a(Yii::t('app', 'Confirm payment'),
                        ['confirm-award', 'id' => $model->unique_id],
                        [
                            'class' => 'btn btn-success',
                            'id' => 'confirm-payment-btn',
                            'data' => [
//                                'confirm' => Yii::t('app', 'Are you sure?'),
                                'method' => 'post',
                            ]
                        ]); ?>
                    <?php endif; ?>
                    <!-- END PAYMENT -->
                    <!-- UNSUCCESSFUL -->
                    <?php if($model->award->status == 'unsuccessful'): ?>
                        <h3><?=Yii::t('app', 'Disqualified'); ?></h3>
                    <?php endif; ?>
                    <!-- END UNSUCCESSFUL -->
                    <!-- ACTIVE -->
                    <?php if($model->award->status == 'active'): ?>
                        <?php if($model->contract): ?>
                            <?php if($model->contract->status == 'pending'): ?>
                                <?=Html::a(Yii::t('app', 'Upload contract documents'), ['upload-contract', 'id' => $model->unique_id], ['class' => 'btn btn-success', 'id' => 'upload-contract-link']); ?>
                                <?php if($model->contractDocuments): ?>
                                    <?=Html::a(Yii::t('app', 'Confirm contract'), ['confirm-contract', 'id' => $model->unique_id], ['class' => 'btn btn-success', 'id' => 'contract-signed-btn']); ?>
                                <?php endif; ?>
                            <?php elseif($model->contract->status == 'active'): ?>
                                <h3><?=Yii::t('app', 'Contract confirmed'); ?></h3>
                            <?php else:?>
                                <h3><?=Yii::t('app', 'Contract cancelled'); ?></h3>
                            <?php endif;?>
                        <?php else:?>
                            <?=Html::a(Yii::t('app', 'Upload contract documents'), ['upload-contract', 'id' => $model->unique_id], ['class' => 'btn btn-success', 'id' => 'upload-contract-link']); ?>
                            <h3><?=Yii::t('app', 'Qualification is confirmed. Waiting for contract signing'); ?></h3>
                        <?php endif;?>
                    <?php endif; ?>
                    <!-- END ACTIVE -->
                    <!-- WAITING -->
                    <?php if($model->award->status == 'pending.waiting'): ?>
                        <h3><?=Yii::t('app', 'Bidder are second'); ?></h3>
                    <?php endif; ?>
                    <!-- END WAITING -->
                    <!-- CANCELLED -->
                    <?php if($model->award->status == 'cancelled'): ?>
                        <h3> <?=Yii::t('app', 'Bid is cancelled by bidder'); ?></h3>
                    <?php endif; ?>
                    <!-- END CANCELLED -->
                <?php endif; ?>

                <?php if(
                    $model->award
                    && in_array($model->apiAuction->status, ['active.qualification', 'active.awarded'])
                    && !in_array($model->award->status, ['unsuccessful', 'cancelled', 'pending.waiting'])
                    && (!$model->contract or $model->contract->status != 'active')
                ): ?>
                    <?=Html::a(Yii::t('app', 'Disqualify'), ['decline-award', 'id' => $model->unique_id], [
                        'class' => 'btn btn-warning',
                        'id' => 'disqualify-link',
                    ]); ?>
                <?php endif; ?>
            <?php endif; ?>
            <!-- END ORGANIZATOR -->

            <!--      MEMBER      -->
            <?php if(Yii::$app->user->can('member') && $model->user_id == Yii::$app->user->id): ?>
                <?php if(!$model->award): ?>
                    <!-- doesn`t have award -->
                    <?php if(!$model->apiAuction->isEnded && (strtotime($model->apiAuction->tenderPeriod_endDate) > time())): ?>
                        <?php if($model->status == 'draft'): ?>
                            <?= Html::a(Yii::t('app', 'Activate'),
                                getenv('TRICK') == '1' ? ($model->accepted == '1' ? ['activate', 'id' => $model->unique_id] : false) : ['activate', 'id' => $model->unique_id],
                                [
                                    'class' => 'btn btn-success',
                                    'id' => 'bid-activate-btn',
                                    'disabled' => getenv('TRICK') == '1' ? ($model->accepted == '0' ? 'disabled' : false) : false,
                                    'data' => [
                                        'method' => 'POST',
                                    ]
                                ]);?>
                        <?php endif; ?>
                        <?=Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->unique_id], ['class' => 'btn btn-success', 'id' => 'bid-update-btn']); ?>
                    <?php endif; ?>
                    <?php if((strtotime($model->apiAuction->tenderPeriod_endDate) > time()) && ($model->apiAuction->procurementMethodType != 'dgfInsider')): ?>
                        <?= Html::a(Yii::t('app', 'Delete ID'),
                            ['delete', 'id' => $model->unique_id],
                            [
                                'class' => 'btn btn-danger',
                                'id' => 'bid-delete-btn',
                                'data' => [
                                    'method' => 'post',
                                    // 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                ]
                            ]);?>
                    <?php endif; ?>
                    <!-- end doesn`t have award -->

                <?php else: ?> <!-- IS AWARD -->
                    <?php if($model->award->status == 'pending.verification'): ?>
                        <?php if(!$model->memberAuctionProtocol): ?>
                            <h3><?=Yii::t('app', 'Your bid is awarded. You can {upload} the auction protocol',
                                    [
                                        'upload' => Html::a(Yii::t('app', 'upload'),
                                            [
                                                'upload-protocol', 'id' => $model->unique_id,
                                            ], ['id' => 'upload-protocol-btn']),
                                    ]); ?>
                            </h3>
                        <?php endif; ?>

                    <?php elseif($model->award->status == 'pending.waiting'): ?>
                        <h3><?=Html::a(Yii::t('app', 'Cancel my bid'), ['cancel', 'id' => $model->unique_id], [
                                'class' => 'btn btn-danger',
                                'id' => 'cancel-bid-btn',
//                                'data' => ['method' => 'post', 'confirm' => Yii::t('app', 'Are you sure you want to cancel your bid?')],
                            ]); ?></h3>
                    <?php elseif($model->award->status == 'pending.payment'): ?>
                        <h3><?=Yii::t('app', 'Waiting for payment confirm'); ?></h3>
                    <?php elseif($model->award->status == 'unsuccessful'): ?>
                        <h3><?=Yii::t('app', 'Your bid has been disqualified.'); ?></h3>
                    <?php elseif($model->award->status == 'active'): ?>
                        <?php if($model->contract): ?>
                            <?php if($model->contract->status == 'active'): ?>
                                <h3><?=Yii::t('app', 'Contract is presented and confirmed'); ?></h3>
                            <?php else: ?>
                                <h3><?=Yii::t('app', 'Waiting for contract confirmation'); ?></h3>
                            <?php endif; ?>
                        <?php else:?>
                            <h3><?=Yii::t('app', 'Qualification is confirmed. Waiting for contract signing'); ?></h3>
                        <?php endif; ?>
                    <?php elseif($model->award->status == 'cancelled'): ?>
                        <h3><?=Yii::t('app', 'Your bid has been cancelled'); ?></h3>
                    <?php endif; ?>
                <?php endif; ?> <!-- END IS AWARD -->
            <?php endif; ?>


            <?php if(($model->user_id == Yii::$app->user->id) && (strtotime($model->apiAuction->tenderPeriod_endDate) > time())): ?>

                <div class="well">
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h3><?=Yii::t('app', 'Upload bid documents'); ?></h3>
                        </div>

                        <?php $form = ActiveForm::begin([
                            'options' => [
                                'enctype' => 'multipart/form-data',
                            ],
                            'action' => Url::to(['upload-document', 'id' => $model->unique_id]),
                        ]); ?>
                        <div class="row">

                            <div class="col-md-4">
                                <?=$form->field($file, 'type')->dropDownList($documentTypes); ?>
                            </div>
                            <div class="col-md-6">
                                <?=$form->field($file, 'file')->widget(\kartik\file\FileInput::className(), [
                                    'options' => [],
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'showPreview' => false,
                                    ]
                                ]); ?>
                            </div>
                            <div class="col-md-2">
                                <?= Html::submitButton(Yii::t('app', 'Upload'), ['id' => 'document-upload-btn', 'class' => 'btn btn-primary btn-block', 'style' => 'margin-top: 25px']); ?>
                            </div>
                        </div>
                        <?php ActiveForm::end();?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                            <p>Статус аукціону</p>
                        </div>
                        <div class="col">
                            <p>
                                <span class="text-danger font-weight-bold"><?=$model->apiAuction->statusName?></span>
                                <span class="is_debug" id="auction-procurementMethodType"><?= $procurementMethodType; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Дата проведення аукціону</p>
                        </div>
                        <div class="col">
                            <p><?=Yii::$app->formatter->asDatetime($model->apiAuction->auctionPeriod_startDate)?></p>
                        </div>
                    </div>
                    <?php if($model->participationUrl && ($model->user_id == Yii::$app->user->id)): ?>
                        <div class="row">
                            <div class="col">
                                <p>Посилання для участі</p>
                            </div>
                            <div class="col">
                                <p><?= Html::a($model->participationUrl, $model->participationUrl, ['target' => '_blank']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col">
                            <p>Назва лоту</p>
                        </div>
                        <div class="col">
                            <p><?=$model->apiAuction->title?></p>
                        </div>
                    </div>
                    <?php if($model->award && (Yii::$app->user->id != $model->user_id)): ?>
                        <div class="row">
                            <div class="col">
                                <p>Назва організації</p>
                            </div>
                            <div class="col">
                                <p><?=$model->organization->name?> (Контактна особа - <?= $model->organization->contactPoint_name?>, <?= $model->organization->contactPoint_telephone?>)</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col">
                            <p>Дата публікування заявки</p>
                        </div>
                        <div class="col">
                            <p><?=Yii::$app->formatter->asDatetime($model->date)?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Дата створення запису</p>
                        </div>
                        <div class="col">
                            <p><?=Yii::$app->formatter->asDatetime($model->created_at)?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Валюта</p>
                        </div>
                        <div class="col">
                            <p><?=$model->value_currency?></p>
                        </div>
                    </div>
                    <?php if($model->award || ($model->user_id == Yii::$app->user->id)): ?>
                        <div class="row">
                            <div class="col">
                                <p>Розмір ставки</p>
                            </div>
                            <div class="col">
                                <p id="bid-value_amount"><?=$model->value_amount?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <h3 class="mb-3 mt-3">Прикладені документи</h3>
                    <?php foreach($model->documents as $document): ?>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <?=Html::a(Files::documentType($document->type) . ' - (' .  $document->name .')',
                                        $document->url, ['id' => 'document-id', 'target' => '_blank', 'class' => 'link-primary']) .''// Html::a(Yii::t('app', 'Change'), ['/bids/reupload-document', 'id' => $model->unique_id, 'document_id' => $document->unique_id], ['class' => 'btn btn-sm btn-primary']); ?>
                                    <?php if($document->file_id && (Yii::$app->user->can('org') || Yii::$app->user->can('admin') || (Yii::$app->user->id == $model->user_id))): ?>
                                        <?=Html::a(' (запасная ссылка)' . Files::documentType($document->type) . ' - (' .  $document->name .')', ['/files/download', 'id' => $document->file_id], ['id' => 'document-id']); ?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if($model->award): ?>
                        <?php foreach($model->award->documents as $document): ?>
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <?=Html::a(Files::documentType($document->type) . ' - (' .  $document->name .')',
                                            $document->url, ['id' => 'document-id']); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if($model->memberAuctionProtocol): ?>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <?=Html::a(Files::documentType($model->memberAuctionProtocol->type)
                                        . ' - (' .  $model->memberAuctionProtocol->name .') '
                                        . Html::tag('span', 'Завантажено переможцем торгів', ['class' => 'lead']),
                                        $model->memberAuctionProtocol->url,
                                        ['id' => 'document-id']); ?>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>




