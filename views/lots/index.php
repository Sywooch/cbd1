<?php

use yii\helpers\Html;
use yii\grid\GridView;
use api\Auctions;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'YouLots ID');
$this->params['breadcrumbs'][] = $this->title;

$delete = Url::to(['/lots/remove-all']);
$this->registerJs(<<<JS
    $('.auctions-list input[type="checkbox"]').on('click', function(){setTimeout(function(){

    var rows = $('#w0').yiiGridView('getSelectedRows');
        if(rows.length === 1){
         $('#auctions-details-btn').attr('href', '/lots/update/'+rows[0]);
         $('#auctions-copy-btn').attr('href', '/lots/copy/'+rows[0]);
            console.log(rows)
        }else{
            $('#auctions-details-btn').attr('href', '#');
             console.log(rows)
        }

}, 100)});

$('#auctions-delete-btn').on('click', function(){
    var rows = $('#w0').yiiGridView('getSelectedRows');
        if(rows.length > 0){
         $.ajax({
            type: 'POST',
            url: "$delete",
            data: {ids:rows },

            success: function(data){
            console.log(rows);
        }
        });
            console.log(rows)
        }else{
            $('#auctions-delete-btn').attr('href', '#');
        }

});
JS

);
?>
<div class="container">
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane auctions fade show active" id="nav-auctions" role="tabpanel" aria-labelledby="nav-auctions-tab">
            <div class="auctions-header">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <h3 class="auctions-title"><?= $this->title; ?></h3>
                    </div>
                    <?php if(Yii::$app->user->can('org')): ?>
                        <div class="col-lg-9">
                            <nav class="navbar navbar-expand-md sticky-top navbar-light">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#auctionsControls" aria-controls="auctionsControls" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="auctionsControls">
                                    <div class="navbar-nav">
                                        <a class="nav-item nav-link auctions-control-edit" id = "auctions-details-btn" href="#">Редагувати</a>
                                        <a class="nav-item nav-link auctions-control-copy" id = "auctions-copy-btn" href="#">Створити копію</a>
                                        <a class="nav-item nav-link auctions-control-trash" id = "auctions-delete-btn" href="#">Видалити</a>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => '{items}{pager}',
                'tableOptions' => [
                    'class' => 'auctions-list table table-responsive',
                ],
                'rowOptions' => function($model) {
                    if(!$model->apiAuction){
                        return ['class' => 'danger'];
                    }
                },
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],

                    //                        'id',
                    [
                        'class' => 'yii\grid\CheckboxColumn'
                    ],
                    [
                        'attribute' => 'auctionID',
                        'value' => 'apiAuction.auctionID',
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function($model){
                            return Html::a($model->name, ['/lots/view/' . $model->id]);
                        }
                    ],
                    [
                        'attribute' => 'organizerName',
                        'visible' => Yii::$app->user->can('admin'),
                    ],
                    //'start_price',
                    'num',
                    //'description',
                    //'step',
                    // 'docs_id',
                    // 'address',
                    //'delivery_time:datetime',
                    // 'delivery_term',
                    // 'requires',
                    //'payment_term',
                    // 'payment_order',
                    // 'member_require',
                    // 'member_docs',
                    // 'requisites_id',
                    // 'notes',
                    // 'dogovor_id',
                    //'date',
                    'auction_date:datetime',
                    //'date:datetime',
                    [
                        'attribute' => 'published',
                        'format' => 'raw',
                        'value' => function($model){
                            return Html::tag(
                                'span',
                                Yii::t('app', $model->lot_lock == '1' ? 'Published' : 'Not published'),
                                ['class' => 'label ' . ($model->lot_lock == '1' ? 'label-success' : 'label-default')]
                            );
                        },
                        'filter' => [
                            '0' => Yii::t('app', 'Not published'),
                            '1' => Yii::t('app', 'Published')
                        ],
                        'visible' => Yii::$app->user->can('org'),
                    ],
                    [
                        'attribute' => 'statusName',
                        'value' => 'apiAuction.statusName',
                        'filter' => Yii::createObject(Auctions::className())->statusNames,
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}{delete}',
                        'buttons' => [
                            'update' => function($url, $model, $key){
                                if(!Yii::$app->user->can('org') || $model->user_id != Yii::$app->user->id || $model->apiAuction) return '';
                                return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $key], ['id' => 'update-btn']);
                            },
                            'view' => function($url, $model, $key){
                                return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['view', 'id' => $key], ['id' => 'view-btn']);
                            },
                            'delete' => function($url, $model, $key){
                                if(!Yii::$app->user->can('org') || $model->user_id != Yii::$app->user->id || $model->apiAuction) return '';
                                return Html::a(
                                    '<i class="glyphicon glyphicon-trash"></i>',
                                    ['delete', 'id' => $key],
                                    [
                                        'id' => 'delete-btn',
                                        'data' => [
                                            'method' => 'post',
                                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        ]
                                    ]
                                );
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>

    </div>
</div>
