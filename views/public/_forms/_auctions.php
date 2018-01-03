<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="publications-item">
    <div class="row no-gutters">
        <div class="col-md-7">
            <p class="publications-lot subtitle-secondary">Номер лоту: <span class="text-uppercase"> <?= $model->auctionID?></span></p>
            <h4 class="publications-item-title">
                <?= Html::a(Yii::t('app', $model->title), ['/public/view', 'id' => $model->auctionID], ['id' => 'auction-view-btn'])?>
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <p class="publications-org"><?= $model->procuringEntity->name?></p>
                    <div class="publications-org-about">
                        <a href="#" class="popover-trigger link-secondary">Про організатора</a>
                        <div class="webui-popover-content">
                            <div class="publications-org-info">
                                <p class="font-weight-bold mb-0">Контактна особа:</p>
                                <p class="org-name"><?= $model->procuringEntity->name?></p>
                                <p class="font-weight-bold mb-0">E-mail:</p>
                                <p class="org-email"><?= $model->procuringEntity->contactPoint_email?></p>
                                <p class="font-weight-bold mb-0">Телефон:</p>
                                <p class="org-telephone"><?= $model->procuringEntity->contactPoint_telephone?></p>
                                <p class="font-weight-bold mb-0">ЄДРПОУ:</p>
                                <p class="org-edrpou"><?= $model->procuringEntity->identifier_id?></p>
                                <a href="<?=\yii\helpers\Url::to(['/public', 'Auctions' => ['organization' => $model->procuringEntity->name]])?>" class="link-primary">Всі аукціони організатора</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php if($model->typeName == 'dgfFinancialAssets'){
                        $image = 'publications-type-fa';
                    }else if($model->typeName == 'dgfOtherAssets'){
                        $image = 'publications-type-mlb';
                    }else{
                        $image = 'publications-type-mlb';
                    }?>
                    <div class=<?= $image?>>
                        <p><?= $model->typeName?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="publications-status mb-4">
                        <p class="subtitle-secondary mb-2">Статус</p>
                        <p class="text-success font-weight-bold mb-4"><?= $model->statusName?></p>
                    </div>
                    <div class="publications-left">
                        <p class="subtitle-secondary mb-2">Залишилось</p>
                        <p class="publications-left-time font-weight-bold"> <?php
                            $diff = strtotime ($model->auctionPeriod_startDate) - time();
                            //                            die(\app\helpers\Date::strtotime($model->auctionPeriod_startDate));
                            if($diff < 0){
                                echo Yii::t('app', 'Auction is started');
                            }else{
                                echo intval($diff/86400) . ' дн. ' . intval(($diff%86400)/3600) . ' год. ' . intval((($diff%86400)%3600)/60) . ' хв.';
                            }
                            ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="publications-starting-price mb-4">
                        <p class="subtitle-secondary mb-1">Початкова ціна</p>
                        <p class="publications-starting-price-value font-weight-bold">
                            <span class = 'price-calculate'><?= $model->value_amount?></span>
                            <?= Yii::t('app', $model->value_currency)?>.</p>
                    </div>
                    <?= Html::a(Yii::t('app', 'Взяти участь'), ['/bids/create', 'id' => $model->unique_id],
                        ['class' => 'btn btn-primary mt-3'])?>
                    <?= Html::a(Yii::t('app', 'Детальніше'), ['/public/view', 'id' => $model->auctionID],
                        ['class' => 'btn link-secondary'])?>
                </div>
            </div>
        </div>
    </div>
</div>





