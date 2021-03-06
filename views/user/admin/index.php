<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

use yii\widgets\ActiveForm;


$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->controller->layout = '@app/views/layouts/user';


$js = "
    $(document).on('click', '.decline-btn', function(e){
        e.preventDefault();
        var userId = $(this).attr('data-user-id');
        $('#decline-form').attr('action', '" . \yii\helpers\Url::to(['decline']) . "?id=' + userId);
        $('#decline-modal').modal('show');
    });
";

$this->registerJs($js, \yii\web\View::POS_READY);

\yii\bootstrap\Modal::begin(['id' => 'decline-modal']);

$form = ActiveForm::begin([
    'id' => 'decline-form',
]);

$messages = new \app\models\EmailTasks();

echo $form->field($messages, 'message')->textarea()->label('Вкажіть допущені помилки');

echo Html::submitButton(Yii::t('app','Send ID'),['class' => 'btn btn-primary','id' => 'decline-modal-id']);

ActiveForm::end();

\yii\bootstrap\Modal::end();

$dataProvider->sort->defaultOrder = ['confirmed_at' => SORT_ASC, 'created_at' => SORT_DESC];

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
            <?= GridView::widget([
                'dataProvider' 	=> $dataProvider,
                'filterModel'  	=> $searchModel,
                'layout'  		=> "{items}\n{pager}",
                'columns' => [
                    'username',
                    'email:email',
                    'fio',
                    'organization.name',
                    [
                        'attribute' => 'registration_ip',
                        'value' => function ($model) {
                            return $model->registration_ip == null
                                ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                                : $model->registration_ip;
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            if (extension_loaded('intl')) {
                                return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                            } else {
                                return date('Y-m-d G:i:s', $model->created_at);
                            }
                        },
                        'filter' => DatePicker::widget([
                            'model'      => $searchModel,
                            'attribute'  => 'created_at',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]),
                    ],
                    [
                        'header' => Yii::t('user', 'Confirmation'),
                        'value' => function ($model) {
                            if ($model->isConfirmed) {
                                return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
                            } else {
                                return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                                        'class' => 'btn btn-xs btn-success btn-block',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                                    ])
                                    .
                                    Html::a(Yii::t('app', 'Повідомити про помилку'), false, [
                                        'class' => 'btn btn-xs btn-warning decline-btn',
                                        'data-user-id' => $model->id
                                    ]);
                            }
                        },
                        'format' => 'raw',
                        'visible' => Yii::$app->getModule('user')->enableConfirmation,
                    ],

                    [
                        'header' => Yii::t('user', 'Block status'),
                        'value' => function ($model) {
                            if ($model->isBlocked) {
                                return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-success btn-block',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                ]);
                            } else {
                                return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-danger btn-block',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                ]);
                            }
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {login} {update} {delete}',
                        'buttons' => [
                            'view' => function($url, $model, $key){
                                return Html::a('<i class="fa fa-eye"></i>', ['info', 'id' => $key]);
                            },
                            'delete' => function($url, $model, $key){
                                return Html::a('<i class="fa fa-remove"></i>', ['delete', 'id' => $key], [
                                    'data' => [
                                        'method' => 'post',
                                        'confirm' => Yii::t('app', 'Ви впевнені?')
                                    ]
                                ]);
                            },
                            'login' => function($url, $model, $key){
                                return Html::a(
                                    '<i class="fa fa-arrow-right"></i>',
                                    ['login-user', 'id' => $model->id],
                                    ['data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to login into this account?'),
                                        'method' => 'post',
                                    ]]
                                );
                            }
                        ],
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>