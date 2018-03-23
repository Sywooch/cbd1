<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model api\Auctions */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(<<<JS
$('.question-item').on('click', function() {
  var url = $(this).data('url');
  $('#question-form').attr('action', url);
  $('#questions-questionof').val('item');
});
$('.question-tender').on('click', function() {
   $('#questions-questionof').val('tender');
});
JS
);

?>

<main class="site-content">
    <section class="lot">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <p class="subtitle-primary">Номер лоту: <span class="text-uppercase"><?= $model->auctionID ?></span></p>
                    <h3 class="lot-title">
                        <span id="auction-title"><?= $model->title ?></span>
                        <?php if($model->access_token && Yii::$app->user->can('org')): ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/lots/edit', 'id' => $model->baseAuction_id], ['id' => 'lot-edit-btn']); ?>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/lots/update', 'id' => $model->baseAuction_id], ['id' => 'lot-update-btn']); ?>
                        <?php endif; ?>
                    </h3>
                    <?php if($model->typeName == 'dgfFinancialAssets') {
                        $image = 'publications-type-fa';
                    } elseif($model->typeName == 'dgfOtherAssets') {
                        $image = 'publications-type-mlb';
                    } else {
                        $image = 'publications-type-mlb';
                    } ?>
                    <div class=<?= $image ?>>
                        <p><?= $model->typeName ?></p>
                        <span id='auction-procurementMethodType' class='is_debug'><?= $model->procurementMethodType; ?></span>
                    </div>
                    <nav class="nav nav-tabs" id="myTab" role="tablist">
                        <a class="nav-item nav-link link-secondary active" id="nav-auction-tab" data-toggle="tab1" href="#nav-auction" role="tab" aria-controls="nav-auction" aria-expanded="true">Аукціон</a>
                        <a class="nav-item nav-link link-secondary" id="tab-selector-2" data-toggle="tab1" href="#nav-faq" role="tab" aria-controls="nav-faq">Питання та вiдповiдi <span class="faq-counter">(<?= count($model->questions) ?>)</span></a>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-auction" role="tabpanel" aria-labelledby="nav-auction-tab">
                            <h4 class="font-weight-bold mt-4 mb-3">Органiзатор</h4>
                            <div class="row">

                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Найменування органiзатора</p>
                                </div>
                                <div class="col-md-7">
                                    <a href="#" id="auction-procuringEntity_name" class="link-secondary mb-1 popover-trigger"><?= $model->baseAuction->ownerName ?: ($model->procuringEntity ? $model->procuringEntity->name : ''); ?></a>
                                    <div class="webui-popover-content">
                                        <div class="publications-org-info">
                                            <p class="font-weight-bold mb-0">Контактна особа:</p>
                                            <p class="org-name"><span><?= $model->procuringEntity->name ?></span></p>
                                            <p class="font-weight-bold mb-0">E-mail:</p>
                                            <p class="org-email"><?= $model->procuringEntity->contactPoint_email ?></p>
                                            <p class="font-weight-bold mb-0">Телефон:</p>
                                            <p class="org-telephone"><?= $model->procuringEntity->contactPoint_telephone ?></p>
                                            <p class="font-weight-bold mb-0">ЄДРПОУ:</p>
                                            <p class="org-edrpou"><?= $model->procuringEntity->identifier_id ?></p>
                                            <a href="<?= \yii\helpers\Url::to(['/public', 'AuctionsSearch' => ['org_name' => $model->procuringEntity->name]]) ?>" class="link-primary">Всі аукціони замовника</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Код в ЄДРПОУ / ІПН</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><?= $model->procuringEntity->identifier_id ?></p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Юридична адреса</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><?= $model->procuringEntity->address_postalCode . ', '
                                        . $model->procuringEntity->address_countryName . ', '
                                        . $model->procuringEntity->address_locality . ', '
                                        . $model->procuringEntity->address_streetAddress ?></p>
                                </div>
                                <div class="w-100"></div>
                            </div>
                            <h4 class="font-weight-bold mt-4 mb-3">Інформация про лот</h4>
                            <div class="row">
                                <div class="col-12">
                                    <p id="auction-description"><?= $model->description ?></p>
                                </div>
                                <?php if($model->isEnded): ?>
                                    <div class="col-md-5">
                                        <p class="mb-1">Протокол торгів</p>
                                    </div>
                                    <div class="col-md-7">
                                        <?= Html::a('HTML', "https://www.prozorro.sale/auction/{$model->auctionID}/print/protocol/html", ['target' => '_blank']) . ' | ' . Html::a('PDF', "https://www.prozorro.sale/auction/{$model->auctionID}/print/protocol/pdf", ['target' => '_blank']); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Ідентифікатор аукціону</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><span id="auction-auctionID"><?= $model->auctionID ?></span></p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Номер лоту ФГВ</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1" id="auction-dgfID"><?= $model->dgfID ?></p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Рішення ФГВ</p>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-1" id="auction-dgfDecisionID"><?= $model->dgfDecisionID; ?></div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Дата рішення ФГВ</p>
                                </div>
                                <div class="col-md-7">
                                    <div class="mb-1" id="auction-dgfDecisionDate"><?= date('d.m.Y', strtotime($model->dgfDecisionDate)); ?></div>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Лоти виставляються</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><?= $model->tenderAttemptsString; ?></p>
                                    <?= Html::tag('span', $model->tenderAttempts, ['id' => 'auction-tenderAttempts', 'class' => 'is_debug']); ?>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Посилання на аукціон</p>
                                </div>
                                <div class="col-md-7">
                                    <?php if(false == ($bid = \api\Bids::findOne(['user_id' => Yii::$app->user->id, 'lot_id' => $model->lot->id]))): ?>
                                        <div class="col-md-12 text-left"><?= $model->auctionUrl ? Html::a($model->auctionUrl, $model->auctionUrl, ['target' => '_blank', 'id' => 'auction-url']) : 'Очікується'; ?>
                                        </div>
                                    <?php else: ?><?= $bid->participationUrl ? Html::a
                                    ($bid->participationUrl, $bid->participationUrl,
                                        ['target' => '_blank', 'id' => 'auction-url']) : 'Очікується'; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Мінімальний крок аукціону</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><span id="auction-minimalStep_amount"><?= $model->minimalStep_amount ?></span> <?= Yii::t('app', $model->guarantee_currency) ?>.</p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Гарантійний внесок</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><span id="auction-guarantee_amount"><?= $model->guarantee_amount ?></span> <?= Yii::t('app', $model->guarantee_currency) ?>.</p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Подача пропозицій</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1"><span id="auction-tenderPeriod_startDate"><?= Yii::$app->formatter->asDatetime($model->tenderPeriod_startDate)
                                            . '</span> - <span id = "auction-tenderPeriod_endDate">' . Yii::$app->formatter->asDatetime($model->tenderPeriod_endDate) ?></span></p>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Дата провдення аукціону</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1">
                                        <span id="auction-auctionPeriod_startDate">
                                            <?= Yii::$app->formatter->asDatetime($model->auctionPeriod_startDate ?: $model->enquiryPeriod_startDate); ?>
                                        </span>
                                        -
                                        <span id="auction-auctionPeriod_endDate">
                                        <?= Yii::$app->formatter->asDatetime($model->auctionPeriod_endDate ?: $model->enquiryPeriod_endDate); ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <p class="subtitle-secondary mb-1">Критерії оцінювання</p>
                                </div>
                                <div class="col-md-7">
                                    <p class="mb-1" id="auction-eligibilityCriteria"><?= $model->eligibilityCriteria; ?></p>
                                </div>

                                <div class="w-100"></div>
                            </div>
                            <h4 class="font-weight-bold mt-4 mb-3">Список активів</h4>
                            <table class="table table-responsive">
                                <thead>
                                <tr>
                                    <th width="20%">Короткий опис активу</th>
                                    <th width="20%">Кількість, од. виміру.</th>
                                    <th width="20%">Розташування об'екту</th>
                                    <th width="20%">Опис класифікації</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($model->items as $n => $modelItem): ?>
                                    <tr>
                                        <td>
                                            <?php if((strtotime($model->enquiryPeriod_endDate)) > time() && !$model->isEnded): ?>
                                                <?= Html::button(Yii::t('app', 'Задати питання'),
                                                    [
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#exampleModal',
                                                        'id' => explode(':', $modelItem->description)[0] . 'item',
                                                        'class' => 'btn btn-success btn-sm question-item',
                                                        'data-url' => Url::to(['/questions/create', 'id' => $model->unique_id,
                                                            'item_id' => $modelItem->id]),
                                                    ]) ?>
                                            <?php endif; ?>
                                            <p class="lot-description mb-0" id="items[<?= $n ?>].description"><?= $modelItem->description ?></p>
                                            <small class="lot-subdescription">Код <?= Html::tag('span', $modelItem->classification->scheme, ['id' => 'items[' . $n . '].classification.scheme']) ?>: <span id="items[<?= $n ?>].classification.id"><?= $modelItem->classification_id ?></span></small>
                                        </td>
                                        <td><span id="items[<?= $n ?>].quantity"><?= $modelItem->quantity ?></span> <span id="items[<?= $n ?>].unit_name"><?= $modelItem->unit_name ?></span>
                                            <?= Html::tag('span', $modelItem->unit_code, ['id' => "items[$n].unit_code"]); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if($modelItem->address_countryName && $modelItem->address_locality
                                                && $modelItem->address_streetAddress) {
                                                echo $modelItem->address_countryName . ', ' . $modelItem->address_locality
                                                    . ', ' . $modelItem->address_streetAddress;
                                            } else {
                                                echo Yii::t('app', 'Не вказано');
                                            } ?>
                                        </td>
                                        <td>
                                            <?= $modelItem->classification->description; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <h4 class="font-weight-bold mt-4 mb-3">Документи аукціону</h4>
                            <?php if($model->documents or ($model->cancellation && $model->cancellation->documents)): ?>
                                <div id='auction-documents'>
                                    <table>
                                        <?php
                                        ?>
                                        <?php foreach($model->documents as $k => $file): ?>
                                            <tr>
                                                <td>
                                                    <?= $file->documentTypeName; ?>
                                                </td>
                                                <td>
                                                    <?= Html::a($file->name, $file->url, ['name' => "$k.title." . explode('.', $file->name)[0]]); ?>
                                                    <?= Html::tag('span', $file->type, ['class' => 'documentType is_debug', 'name' => "$k.documentType"]); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if($model->cancellation && $model->cancellation->documents): ?>
                                            <?php foreach($model->cancellation->documents as $k => $file): ?>
                                                <tr>
                                                    <td>
                                                        <?= $file->documentTypeName; ?>
                                                    </td>
                                                    <td>
                                                        <?= Html::a($file->name, $file->url, [
                                                            'name' => "$k.title." . explode('.', $file->name)[0],
                                                        ]); ?>
                                                        <?= Html::tag('a', $file->description, ['name' => "$k.description." . explode('.', $file->name)[0], 'class' => 'is_debug']); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            <?php endif; ?>

                            <?php if($model->awards): ?>
                                <h2><?= Yii::t('app', 'Bids list'); ?></h2>
                                <?php
                                $awardNumber = 0;
                                $bidNumber = 0;
                                ?>
                                <?php foreach($model->bids as $n => $modelBid): ?>
                                    <?php if($modelBid->award): ?>
                                        <?php if($modelBid->award->status == 'unsuccessful') {
                                            //$bidNumber--;
                                            //$awardNumber--;
                                        };
                                        $n++; ?>
                                        <h3>
                                            <?= Html::a(Yii::t('app', "Учасник  № $n"),
                                                ['/bids/view', 'id' => $modelBid->unique_id], [
                                                    'id' => "bids[{$bidNumber}].link",
                                                    'name' => $modelBid->isWinner ? 'winner' : 'loser',
                                                ]); ?>
                                            <?php

                                            $awardsCount = \api\Awards::find()
                                                ->where(['auction_id' => $modelBid->award->auction_id])
                                                ->andWhere(['<', 'unique_id', $modelBid->award->unique_id])
                                                ->count();
                                            switch($modelBid->award->status) {
                                                case 'pending.verification':
                                                    if($awardsCount < 2) {
                                                        $class = 'success';
                                                        $statusName = 'Очікується завантаження протоколу';
                                                    } else {
                                                        $class = 'default';
                                                        $statusName = 'Учасник, що не бере участі';
                                                    }
                                                    break;
                                                case 'pending.payment':
                                                    $class = 'warning';
                                                    $statusName = 'Waiting for payment';
                                                    break;
                                                case 'unsuccessful':
                                                    $class = 'danger';
                                                    $statusName = 'Disqualified';
                                                    break;
                                                case 'active':
                                                    $class = 'success';
                                                    $statusName = 'Winner';
                                                    break;
                                                case 'pending.waiting':
                                                    $class = 'default';
                                                    if($awardsCount < 2) {
                                                        $statusName = 'Second';
                                                    } else {
                                                        $statusName = 'Учасник, що не бере участі';
                                                    }
                                                    break;
                                                case 'cancelled':
                                                    $class = 'default';
                                                    $statusName = 'Скасовано учасником';
                                                    break;
                                                default:
                                                    $class = 'default';
                                                    $statusName = ' ';
                                            }
                                            ?>
                                            <?= Html::tag('li',
                                                Yii::t('app', $statusName)
                                                . ' ' . Html::a($modelBid->organization->name ?:
                                                    $modelBid->organization->contactPoint_name,
                                                    ['/bids/view', 'id' => $modelBid->unique_id],
                                                    ['class' => 'btn btn-' . $class]), ['class' => 'list-group-item']); ?>
                                            <?= Html::tag('span', $modelBid->award->status, ['id' => "awards[{$awardNumber}].status", 'class' => 'is_debug']); ?>
                                        </h3>
                                        <?php
                                        $documents = '';
                                        foreach($modelBid->documents as $document) {
                                            $documents .= "<br />" . Html::a($document->title, $document->url, ['name' => $document->documentType]);
                                        }
                                        ?>
                                        <?= DetailView::widget([
                                        'model' => $modelBid,
                                        'attributes' => [
                                            [
                                                'attribute' => 'award.description',
                                                'header' => 'Причина дискваліфікації',
                                                'visible' => $modelBid->award->status == 'unsuccessful',
                                            ],
                                            'date:datetime',
                                            'value_amount',
                                            [
                                                'attribute' => 'value_currency',
                                                'value' => Yii::t('app', $modelBid->value_currency),
                                            ],
                                            [
                                                'attribute' => 'documents',
                                                'format' => 'raw',
                                                'value' => $documents,
                                            ],
                                            [
                                                'attribute' => 'value_valueAddedTaxIncluded',
                                                'format' => 'raw',
                                                'value' => Html::checkbox('value_valueAddedTaxIncluded', $model->value_valueAddedTaxIncluded, ['disabled' => 'disabled']),
                                            ],
                                        ],
                                    ]) ?>
                                        <?php
                                        $awardNumber++;
                                        $bidNumber++;
                                        ?>
                                        <?php $prolongations = $modelBid->award->prolongations; ?>
                                        <?php if(count($prolongations)): ?>
                                            <p class="lead">
                                                <?= Yii::t('app', 'Період підписання контракту було продовжено'); ?>
                                            </p>
                                        <p>
                                                <?= Yii::t('app', 'Наразі кінцева дата підписання договору'); ?> - <?= Yii::$app->formatter->asDatetime($modelBid->contract->signingPeriod_endDate); ?>
                                            </p>

                                            <table class="table table-striped table-responsive">
                                                <tr>
                                                    <th><?= Yii::t('app', 'Номер рішення'); ?></th>
                                                    <th><?= Yii::t('app', 'Причина'); ?></th>
                                                    <th><?= Yii::t('app', 'Дата рішення ФГВФО'); ?></th>
                                                    <th><?= Yii::t('app', 'Опис'); ?></th>
                                                    <th><?= Yii::t('app', 'Документ рішення ФГВФО'); ?></th>
                                                </tr>
                                                <?php foreach($prolongations as $prolongation):
                                                    /* @var \api\Prolongations $prolongation */
                                                    ?>
                                                    <tr>
                                                        <td><?= $prolongation->decisionID; ?></td>
                                                        <td><?= $prolongation->getReason(); ?></td>
                                                        <td><?= Yii::$app->formatter->asDate($prolongation->dateCreated); ?></td>
                                                        <td><?= $prolongation->description; ?></td>
                                                        <td>
                                                            <?php foreach($prolongation->documents as $document): ?>
                                                                <p>
                                                                    <?= Html::a($document->name, $document->url); ?>
                                                                </p>
                                                            <?php endforeach; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php $bidNumber--;
                                        $awardNumber--;
                                        $n--; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="tab-pane fade show active" id="nav-faq" role="tabpanel" aria-labelledby="nav-faq-tab">
                            <?php if(count($model->questions) > 0): ?>
                                <?php foreach($model->questions as $n => $question): $n++; ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <?php $item_id = explode(':', $question->title)[0]; ?>
                                            <article class="faq-item">
                                                <header class="faq-item-header">
                                                    <h3 class="faq-item-title" id="questions[<?= $n; ?>].title"><?= $question->title ?></h3>
                                                    <time class="faq-item-time font-weight-bold">
                                                        <?= Yii::$app->formatter->asDatetime($question->created_at); ?>
                                                    </time>
                                                </header>
                                                <div class="faq-item-message" id="questions[<?= $n; ?>].description">
                                                    <?= $question->description; ?>
                                                </div>
                                                <?php if($question->answer): ?>
                                                    <p><span class='answer-date <?= $n; ?>' id="questions[<?= $n; ?>].answer-date"><?= Yii::t('app', 'Date answered'); ?>: <?= Yii::$app->formatter->asDatetime($question->updated_at); ?></span></p>
                                                    <p><span class="lead question-answer <?= $n; ?>" id="questions[<?= $n; ?>].answer"><?= $question->answer; ?></span></p>
                                                <?php elseif($model->lot && ($model->lot->user_id == Yii::$app->user->id) && !$question->answer): ?>
                                                    <?= Html::a(Yii::t('app', 'Answer the question'), ['/questions/answer', 'id' => $question->unique_id], ['class' => 'btn btn-primary', 'id' => "question[{$item_id}].answer"]); ?>
                                                <?php endif; ?>
                                            </article>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="well"><h3><?= Yii::t('app', 'No questions'); ?></h3></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="lot-info">
                        <div class="publications-starting-price mb-4">
                            <p class="subtitle-secondary mb-1">Початкова ціна</p>
                            <p class="publications-starting-price-value font-weight-bold">
                                <?= Html::tag('span', zeropad($model->value_amount), ['id' => 'auction_value_amount', 'class' => 'price-calculate']) . " " . ' ' . Yii::t('app', $model->value_currency) . ' ' . Html::tag('span', $model->value_currency, ['id' => 'auction-value_currency', 'class' => 'is_debug']) . " ";
                                if($model->value_valueAddedTaxIncluded == 1) {
                                    echo Yii::t('app', 'With Pdf');
                                } else {
                                    echo Yii::t('app', 'Without Pdf');
                                } ?>
                            </p>
                        </div>

                        <input type="checkbox" class="is_debug" checked="<?= $model->value_valueAddedTaxIncluded == 1 ? 'checked' : ''; ?>" id="auction-valueAddedTaxIncluded" disabled="disabled" readonly="readonly">
                        <div class="publications-status mb-4">
                            <p class="subtitle-secondary mb-2">Статус</p>
                            <p class="text-success font-weight-bold mb-4"><?= $model->statusName ?></p>
                            <div class="col-md-12"><?= Html::tag('span', $model->status, ['id' => 'auction-status', 'class' => 'is_debug']); ?></div>
                            <?php if($model->cancellation) { ?>
                                <span><?= Yii::t("app", "Cancellation reason"); ?></span>
                                <?= Html::tag('span', $model->cancellation->status, ['class' => 'is_debug', 'id' => 'cancellation-status']); ?>
                                <p class="lead"><?= Html::tag('span', $model->cancellation->reason, ['id' => 'cancellation-reason']); ?></p>
                            <?php }; ?>
                        </div>
                        <?php if(!$model->isEnded): ?>
                            <div class="publications-left">
                                <p class="subtitle-secondary mb-2">Залишилось</p>
                                <p class="publications-left-time font-weight-bold"><?php
                                    $diff = strtotime($model->auctionPeriod_startDate) - time();
                                    if($diff < 0) {
                                        echo Yii::t('app', 'Auction is started');
                                    } else {
                                        echo intval($diff / 86400) . ' дн. ' . intval(($diff % 86400) / 3600) . ' год. ' . intval((($diff % 86400) % 3600) / 60) . ' хв.';
                                    }
                                    ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if(!Yii::$app->user->can('org') && !$model->isEnded && (strtotime($model->tenderPeriod_endDate) > time())): ?>
                            <?= Html::a(Yii::t('app', 'Взяти участь'), ['/bids/create', 'id' => $model->unique_id],
                                ['class' => 'btn btn-primary btn-block mt-4 mb-3', 'id' => 'bid-create-btn']) ?>
                        <?php endif; ?>

                        <?php if((strtotime($model->enquiryPeriod_endDate)) > time() && !$model->isEnded): ?>
                            <?= Html::a(Yii::t('app', 'Create question'), ['/questions/create', 'id' => $model->unique_id],
                                [
                                    'class' => 'btn-block link-secondary text-center question-tender',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#exampleModal',
                                    'id' => 'create-question-btn',
                                ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Задайте своє питання тут</h3>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin([
                        'action' => ['/questions/create', 'id' => $model->unique_id],
                        'id' => 'question-form',
                    ]);
                    $questionModel = new \api\Questions(['questionOf' => 'tender']);
                    $questionModel->setScenario('create');
                    ?>
                    <?= $form->field($questionModel, 'title', ['inputOptions' => ['class' => 'form-control', 'id' => 'question-title']])->textInput(['maxlength' => true]) ?>
                    <?= $form->field($questionModel, 'description', ['inputOptions' => ['class' => 'form-control', 'id' => 'question-description']])->textarea(['rows' => 6]) ?>
                    <?= $form->field($questionModel, 'questionOf')->hiddenInput()->label(false) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити вікно</button>
                    <?= Html::submitButton(Yii::t('app', 'Відправити'), ['class' => 'btn btn-primary', 'id' => 'submit-question-btn']) ?>
                </div>
                <?php ActiveForm::end() ?>

            </div>
        </div>
    </div>
</main>