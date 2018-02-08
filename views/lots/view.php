<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Lots */

$this->title = $model->name;
if(Yii::$app->user->can('member'))
{
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auction ID'), 'url' => ['/auctions/index']];
}
else
{
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lots ID'), 'url' => ['index']];
}

$this->params['breadcrumbs'][] = $this->title;
if($model->nds==1)
{
    $price_nds = Html::tag('span', $model->start_price, ['class' => 'start_price_nds']) .
        ' ' . Yii::t('app', 'UAH') . ' ' ." ".Yii::t('app','zNDS ID');
    $step_nds = Html::tag('span', $model->step, ['class' => 'money_step'])
        . ' ' . Yii::t('app', 'UAH') . ' ' . Yii::t('app','zNDS ID');

    $guarentee_nds = Html::tag('span', $model->apiAuction ? $model->apiAuction->guarantee_amount : '', ['class' => 'guarentee_price_nds']) .
        ' ' . Yii::t('app', 'UAH') . ' ' ." ".Yii::t('app','zNDS ID');
}
else
{
    $price_nds = Html::tag('span', $model->start_price, ['class' => 'start_price_nds']) .
        ' ' . Yii::t('app', 'UAH') . ' ' ." ".Yii::t('app','bNDS ID');
    $step_nds = Html::tag('span', $model->step, ['class' => 'money_step'])
        . ' ' . Yii::t('app', 'UAH') . ' ' . Yii::t('app','bNDS ID');
    $guarentee_nds = Html::tag('span', $model->apiAuction ? $model->apiAuction->guarantee_amount : '', ['class' => 'guarentee_price_nds']) .
        ' ' . Yii::t('app', 'UAH') . ' ' ." ".Yii::t('app','bNDS ID');
}
?>

<main class="site-content">
    <section class="auction-detail">
        <div class="container">
            <div class="auction-detail-header mt-4 mb-5">
                <a class="text-info d-inline-block mb-2" href="/lots/index">&larr; Назад до аукціонів</a>
                <div class="row">

                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-md sticky-top navbar-light align-self-center">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#auctionsControls" aria-controls="auctionsControls" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="auctionsControls">
                                <div class="navbar-nav">
                                    <?php
                                    if(Yii::$app->user->can('org') && $model->user_id == Yii::$app->user->id)
                                    {
                                        if($model->lot_lock == '0') {
                                            echo Html::a('Редагувати', Url::to(['/lots/update', 'id' => $model->id]),
                                                ['class' => 'nav-item nav-link auctions-control-edit']);
                                            echo Html::a('Опублікувати', ['/lots/publish', 'id' => $model->id],
                                                [
                                                    'class' => 'nav-item nav-link auctions-control-publish',
                                                    'id' => 'publish-btn',
                                                ]);
                                        }
                                        elseif($model->apiAuction && ($model->apiAuction->status == 'active.tendering')){
                                            echo Html::a(Yii::t('app', 'Edit'), ['edit', 'id' => $model->id], ['class' => 'nav-item nav-link auctions-control-publish']);
                                        }
                                        if($model->apiAuction && !$model->apiAuction->isEnded && $model->apiAuction->status != 'active.auction'){
                                            echo Html::a(Yii::t('app', 'Upload documents'), ['update', 'id' => $model->id], ['class' => 'nav-item nav-link auctions-control-publish']);
                                        }

                                        echo Html::a('Створити копію', Url::to(['/lots/copy', 'id' => $model->id]),
                                            ['class' => 'nav-item nav-link auctions-control-copy']);
                                        echo Html::a('Видалити', Url::to(['/lots/delete', 'id' => $model->id]), [
                                            'class' => 'nav-item nav-link auctions-control-trash',
                                            'data-method' => 'post',
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?')]);
                                    }
                                    elseif(!Yii::$app->user->can('org') && $model->apiAuction && (strtotime($model->apiAuction->tenderPeriod_endDate) > time())){
                                        echo Html::a(Yii::t('app', 'CreateBid ID'), ['/bids/create', 'id' => $model->apiAuction->unique_id], ['class' => 'nav-item nav-link auctions-control-publish', 'id' => 'create-bid-btn']);
                                    } ?>
                                </div>
                            </div>
                        </nav>
                    </div>

                    <div class="col-lg-5 align-self-center">
                        <h3>Аукціон <span class="auction-detail-number"><?= $model->apiAuction
                                    ? Html::tag('span', $model->apiAuction->auctionID, ['id' => 'auction-id'])
                                    : Yii::t('app', 'Not published')?></span></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col">
                            <p>Статус аукціону</p>
                        </div>
                        <div class="col">
                            <p><span class="text-danger font-weight-bold"><?= Html::tag('span', $model->apiAuction
                                        ? $model->apiAuction->statusName : Yii::t('app', 'Not published'),
                                        ['class' => 'lead'])?></span>

                                <?php if($model->apiAuction && ($model->apiAuction->auctionUrl)): ?>
                                    <span class="lead">
                                        |
                                        <?= Html::a(Yii::t('app', 'Перейти до торгів'), $model->apiAuction->auctionUrl, ['target' => '_blank']); ?>
                                </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Ідентифікатор аукціону</p>
                        </div>
                        <div class="col">
                            <p><?= $model->apiAuction
                                    ?
                                    Html::tag('span', Html::a($model->apiAuction->auctionID, getenv('API_URL') .  '/api/' . getenv('API_VERSION') . '/auctions/' . $model->apiAuction->id, ['target' => '_blank']), ['id' => 'auction-id'])
                                    . ' | ' .
                                    Html::tag('span', Html::a(Yii::t('app', 'Перейти до публікації'), ['/public/view', 'id' => $model->apiAuction->auctionID], ['target' => '_blank']), ['class' => 'public_view'])
                                    :
                                    Yii::t('app', 'Not published'); ?></p>
                        </div>
                    </div>
                    <?php if($model->apiAuction && $model->apiAuction->isEnded): ?>
                        <div class="row">
                            <div class="col"><p>Протол торгів</p></div>
                            <div class="col">
                                <p>
                                    <?= $model->apiAuction ? Html::a('HTML', "https://www.prozorro.sale/auction/{$model->apiAuction->auctionID}/print/protocol/html", ['target' => '_blank']) . ' | ' .  Html::a('PDF', "https://www.prozorro.sale/auction/{$model->apiAuction->auctionID}/print/protocol/pdf", ['target' => '_blank']) : ''; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col">
                            <p>Назва аукціону</p>
                        </div>
                        <div class="col">
                            <p id="lots-name"><?= $model->name?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Номер лоту в ФГВ</p>
                        </div>
                        <div class="col">
                            <p><?= $model->num ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Відомості про майно, що виставляється на торги, його склад, характеристики, опис</p>
                        </div>
                        <div class="col">
                            <p><?=$model->description?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Початок прийому заявок на участь</p>
                        </div>
                        <div class="col">
                            <p><?=$model->apiAuction ? Yii::$app->formatter->asDatetime($model->apiAuction->tenderPeriod_startDate)
                                    : $model->bidding_date?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Дата початку аукціону</p>
                        </div>
                        <div class="col">
                            <p><?=$model->auction_date?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Початкова ціна, грн.</p>
                        </div>
                        <div class="col">
                            <p><?= $model->start_price?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Початкова ціна, грн. (з ПДВ)</p>
                        </div>
                        <div class="col">
                            <p><?= $price_nds?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Мін. крок торгів, грн.</p>
                        </div>
                        <div class="col">
                            <p><?=$model->step?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Порядок ознайомлення з майном</p>
                        </div>
                        <div class="col">
                            <p><?=$model->address?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Мін. крок торгів, грн.</p>
                        </div>
                        <div class="col">
                            <p>—</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Терміни поставки товарів</p>
                        </div>
                        <div class="col">
                            <p><?=$model->delivery_time?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p>Додаткова інформація</p>
                        </div>
                        <div class="col">
                            <p><?=$model->notes?></p>
                        </div>
                    </div>
                </div>
            </div> <!-- end .row -->
            <?php if($model->apiAuction): ?>
                <hr>
                <?php if($model->apiAuction && $model->apiAuction->awards && !Yii::$app->user->isGuest && !in_array($model->apiAuction->status, ['cancelled', 'unsuccessful'])){ ?>
                    <div class="well">
                        <h3><?=Yii::t('app', 'List of bids'); ?>:</h3>
                        <ul class="list-group">
                            <?php foreach($model->apiAuction->bids as $bid){ ?>
                                <?php if(($bid->status == 'draft') && !Yii::$app->user->can('admin')) continue; ?>
                                <?php if($bid->award): ?>
                                    <?php
                                    switch($bid->award->status){
                                        case 'pending.verification':
                                            $awardsCount = \api\Awards::find()->where(['auction_id'=> $bid->award->auction_id])->andWhere(['<', 'unique_id', $bid->award->unique_id])->count();
                                            if($awardsCount < 2){
                                                $class = 'success';
                                                $statusName = 'Очікується завантаження протоколу';
                                            }
                                            else{
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
                                            $awardsCount = \api\Awards::find()->where(['auction_id'=> $bid->award->auction_id])->andWhere(['<', 'unique_id', $bid->award->unique_id])->count();
                                            if($awardsCount < 2){
                                                $statusName = 'Second';
                                            }
                                            else{
                                                $statusName = 'Учасник, що не бере участі';
                                            }
                                            break;
                                        case 'cancelled':
                                            $class = 'default';
                                            $statusName = 'Скасовано учасником';
                                            break;
                                        default:
                                            $class = 'default';
                                            $status = ' ';
                                    }
                                    ?>
                                    <?=Html::tag('li', Yii::t('app', $statusName) . ' ' . Html::a($bid->organization->name ?: $bid->organization->contactPoint_name, ['/bids/view', 'id' => $bid->unique_id], ['class' => 'btn btn-' . $class]), ['class' => 'list-group-item']); ?>
                                <?php endif; ?>
                            <?php }; ?>
                        </ul>
                    </div>
                <?php }; ?>

                <?php if((strtotime($model->apiAuction->enquiryPeriod_endDate)) > time() && !$model->apiAuction->isEnded && (Yii::$app->user->id != $model->user_id)): ?>
                    <hr>
                    <?= Html::a(Yii::t('app', 'Задати питання щодо аукціону'), ['/questions/create', 'id' => $model->apiAuction->unique_id], ['class' => 'btn btn-primary']); ?>
                <?php endif; ?>
                <hr>
                <h4 class="font-weight-bold mt-4 mb-3">Список активів</h4>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th width: 12%">Короткий опис активу</th>
                        <th width: 12%">Кількість, од. виміру.</th>
                        <th width: 12%">Розташування об'екту</th>
                        <th width: 12%">Опис класифікації</th>
                        <?php if((strtotime($model->apiAuction->enquiryPeriod_endDate)) > time() && !$model->apiAuction->isEnded): ?>
                            <th>Посилання на створення питання</th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model->apiAuction->items as $n => $modelItem): ?>
                        <tr>
                            <td>
                                <p class="lot-description mb-0" id = "items[<?=$n?>].description"><?= $modelItem->description?></p>
                                <small class="lot-subdescription">Код <?=Html::tag('span', $modelItem->classification->scheme, ['id' => 'items['.$n.'].classification.scheme'])?>: <span id = "items[<?=$n?>].classification.id"><?= $modelItem->classification_id?></span></small>
                            </td>
                            <td><span id = "items[<?=$n?>].quantity"><?=$modelItem->quantity?></span> <span id = "items[<?=$n?>].unit_name"><?=$modelItem->unit_name?></span>
                                <?= Html::tag('span', $modelItem->unit_code, ['id' => "items[$n].unit_code"]);?>
                            </td>
                            <td><?php
                                if($modelItem->address_countryName && $modelItem->address_locality
                                    && $modelItem->address_streetAddress){
                                    echo $modelItem->address_countryName . ', ' . $modelItem->address_locality
                                        . ', ' . $modelItem->address_streetAddress;
                                }else{
                                    echo Yii::t('app', 'Не вказано');
                                }?></td>
                            <td>
                                <?=Html::tag('span',
                                    isset(explode(': ', $modelItem->description)[1])
                                        ?
                                        explode(': ', $modelItem->description)[1]
                                        :
                                        $modelItem->description,
                                    ['id' => "items[$n].classification_description"]);?>
                            </td>
                            <?php if((strtotime($model->apiAuction->enquiryPeriod_endDate)) > time() && !$model->apiAuction->isEnded && (Yii::$app->user->id != $model->user_id)): ?>
                                <td>
                                    <?=Html::a(Yii::t('app', 'Задати питання'),
                                        [
                                            '/questions/create', 'id' => $model->apiAuction->unique_id,
                                            'item_id' => $modelItem->id,
                                        ],
                                        [
                                            'id' => explode(':', $modelItem->description)[0] . 'item',
                                            'class' => 'btn btn-success btn-sm question-item'
                                        ])?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <h4 class="font-weight-bold mt-4 mb-3">Документи аукціону</h4>
                <div class="row">
                    <?php if($model->apiAuction->documents or ($model->apiAuction->cancellation && $model->apiAuction->cancellation->documents)):?>
                        <div class="row" id='auction-documents'>
                            <table>
                                <?php
                                ?>
                                <?php foreach ($model->apiAuction->documents as $k => $file): ?>
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
                                <?php if($model->apiAuction->cancellation && $model->apiAuction->cancellation->documents): ?>
                                    <?php foreach ($model->apiAuction->cancellation->documents as $k => $file): ?>
                                        <tr>
                                            <td>
                                                <?= $file->documentTypeName; ?>
                                            </td>
                                            <td>
                                                <?= Html::a($file->name, $file->url, [
                                                    'name' => "$k.title." . explode('.', $file->name)[0],
                                                ]); ?>
                                                <?= Html::tag('a', $file->description, ['name' =>  "$k.description." . explode('.', $file->name)[0], 'class' => 'is_debug']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if($model->documents):?>
                <div class="row" id='auction-documents'>
                    <h3><?=Yii::t('app', 'Auction documents'); ?></h3>
                    <table>
                        <?php foreach ($model->documents as $k => $file): ?>
                            <tr>
                                <td>
                                    <?= $file->documentTypeName; ?>
                                </td>
                                <td>
                                    <?=Html::a($file->name, $file->url, ['id' => $file->documentTypeName == 'no type' ? "document-id" : '', 'name' => $file->type]); ?>
                                    <?=Html::tag('span', $file->type, ['class' => 'documentType is_debug']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif;?>
            <?php if(Yii::$app->user->can('org') && $model->user_id == Yii::$app->user->id) {
                if ($model->apiAuction && !$model->apiAuction->isEnded && !$model->apiAuction->cancellation) {
                    echo Html::a(
                        Yii::t('app', 'Cancel'),
                        ['/cancellations/create', 'id' => $model->apiAuction->unique_id],
                        [
                            'class' => 'nav-item nav-link auctions-control-publish',
                            'id' => 'cancel-auction-btn',
                        ]);
                } elseif ($model->apiAuction && !$model->apiAuction->isEnded && $model->apiAuction->cancellation && $model->apiAuction->cancellation->status == 'pending') {
                    echo '<div class="well">';
                    echo Html::tag('h3', Yii::t('app', 'Cancellation'));
                    if ($model->apiAuction->cancellation->documents) {
                        echo Html::a(Yii::t('app', 'Confirm cancellation'), ['/cancellations/confirm', 'id' => $model->apiAuction->unique_id], ['class' => 'btn btn-primary', 'id' => 'confirm-cancellation-btn']);
                    }
                    echo Html::a(Yii::t('app', 'Add cancellation documents'), ['/cancellations/upload-document', 'id' => $model->apiAuction->unique_id], ['class' => 'btn btn-default', 'id' => 'add-cancellation-document']);
                    echo Html::tag('h4', Yii::t('app', 'Cancellation documents'));
                    foreach ($model->apiAuction->cancellation->documents as $document) {
                        echo Html::tag('p', Html::a($document->title, $document->url) . ' - ' . $document->description
                            . Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/cancellations/reupload', 'id' => $model->apiAuction->unique_id, 'document_id' => $document->unique_id], ['class' => 'btn btn-primary btn-xs']));
                    }
                    echo '</div>';
                }
            }
            ?>
        </div> <!-- end .container -->

    </section>

</main>
