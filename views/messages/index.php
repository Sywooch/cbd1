<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Messages ID');
$this->params['breadcrumbs'][] = $this->title;

$delete = Url::to(['/messages/remove-all']);
$marked = Url::to(['/messages/marked']);

$this->registerJs(<<<JS
    $('#messages-details-btn').on('click', function(){
    var rows = $('#w1').yiiGridView('getSelectedRows');
        if(rows.length > 0){
         $.ajax({
            type: 'POST',
            url: "$marked",
            data: {ids:rows },

            success: function(data){
            console.log(rows);
        }
        });
            console.log(rows)
        }else{
            $('#messages-delete-btn').attr('href', '#');
        }

});

$('#messages-delete-btn').on('click', function(){
    var rows = $('#w1').yiiGridView('getSelectedRows');
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
            $('#messages-delete-btn').attr('href', '#');
        }

});
JS
);


?>
<div class="container">
    <div class="messages-header">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <h3 class="messages-title">Мої повідомлення</h3>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-md sticky-top navbar-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#messagesControls" aria-controls="messagesControls" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="messagesControls">
                        <div class="navbar-nav">
                            <a class="nav-item nav-link messages-control-view" id = 'messages-details-btn' href="#">Відмітити як прочитане</a>
                            <a class="nav-item nav-link messages-control-trash" id = 'messages-delete-btn' href="#">Видалити</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model) {
            return ['class' => $model['status'] == '1' ? '' : 'message-new'];
        },
        'tableOptions' => [
            'class' => 'messages-list table table-responsive',
        ],
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
//                        'checkboxOptions' => function($model, $key, $index, $widget) {
//                            return ['value' => $model['id']];
//                        },
            ],
            [
                'attribute' => 'notes',
                'header' => Yii::t('app', 'Notes ID'),
                'value' => function($model){
                    return Html::a($model['notes'], ['/messages/view/' . $model['id']]);
                },
                'format' => 'raw'
            ],

            [
                'attribute' => 'date',
                'header' => Yii::t('app', 'Dates ID'),
                'format' => 'datetime',

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
</div>
</main>
