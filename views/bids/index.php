<?php

use yii\helpers\Html;
use yii\grid\GridView;
use api\Auctions;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BidsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->user->can('member') ? Yii::t('app', 'Мої заявки на участь') : Yii::t('app', 'Bids');
$this->params['breadcrumbs'][] = $this->title;


if(Yii::$app->user->can('member'))
{
    $buttons = "{view} {update}";
}
else{
    $buttons = "{view}";
}
$delete = \yii\helpers\Url::to(['/bids/remove-all']);

$this->registerJs(<<<JS
    $('.bids-list input[type="checkbox"]').on('click', function(){setTimeout(function(){

    var rows = $('#w0').yiiGridView('getSelectedRows');
        if(rows.length === 1){
         $('#bids-details-btn').attr('href', '/bids/view/'+rows[0]);
            console.log(rows)
        }else{
            $('#bids-details-btn').attr('href', '#');
             console.log(rows)
        }

}, 100)});

$('#bids-delete-btn').on('click', function(){
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
            $('#bids-delete-btn').attr('href', '#');
        }

});
JS

);



?>

<div class="container">
    <div class="bids-header">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <h3 class="bids-title"><?= $this->title; ?></h3>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-md sticky-top navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#auctionsControls" aria-controls="auctionsControls" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="auctionsControls">
                        <div class="navbar-nav">
                            <a class="nav-item nav-link bids-control-view" id = "bids-details-btn" href="#">Деталі заявки</a>
                        </div>
                        <div class="navbar-nav">
                            <a class="nav-item nav-link bids-control-view" id = "bids-delete-btn" href="#">Видалити обрані</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'tableOptions' => [
            'class' => 'bids-list table table-responsive',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn'
            ],

            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'organizationName',
                'value' => 'organization.name',
//                        'visible' => Yii::$app->user->can('org') || Yii::$app->user->can('admin'),
            ],
            [
                'attribute' => 'lotName',
                'value' => function($model){
                    return Html::a($model->apiAuction->title, ['/bids/view/' . $model->unique_id]);
                },
                'format' => 'raw'
            ],
            'date:datetime',
            [
                'attribute' => 'statusName',
                'value' => 'lot.apiAuction.statusName',
                'filter' => Yii::createObject(Auctions::className())->statusNames,
            ],
            [
                'attribute' => 'accepted',
                'format' => 'raw',
                'value' => function($model)
                {
                    return Html::tag(
                        'span',
                        Yii::t('app', $model->accepted == '1' ? 'Прийнята' : 'Не прийнята'),
                        ['class' => $model->accepted == '1' ? 'label label-success' : 'label label-primary']);
                },
                'filter' => [
                    '1' => Yii::t('app', 'Прийнята'),
                    '0' => Yii::t('app', 'Не прийнята'),
                ],
                'visible' => getenv('TRICK') && (Yii::$app->user->can('org') || Yii::$app->user->can('admin')),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'  => $buttons,
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['/bids/view', 'id' => $key], ['id' => 'bid-view']);
                    },
                    'update' => function($url, $model, $key){
                        if(($model->apiAuction && $model->apiAuction->isEnded) || $model->accepted == '0'){
                            return '';
                        }
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['update', 'id' => $key], ['id' => 'bid-update']);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
</div>
</main>