<?php

use yii\bootstrap\Nav;

$messages_count = \app\models\Messages::find()->where(['user_id' => Yii::$app->user->id, 'status' => '0'])->count();
$messages_counter = $messages_count > 0 ? ('</span><span class="notification">' . $messages_count . '</span>') : '';



echo Nav::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Про нас'),
            'url' => ['/site/about'],
            
        ],
        [
            'label' => Yii::t('app', 'Учасникам'),
            'items' => [
                ['label' => Yii::t('app', 'Фонд гарантування вкладів фізичних осіб'), 'url' => 'http://etm.biddingtime.com.ua/public'],
                ['label' => Yii::t('app', 'Фонд державного майна України'), 'url' => 'http://etm.biddingtime.com.ua'],
            ],
        ],
        [
            'label' => Yii::t('app', 'Замовникам'),
            'url' => ['/site/zamovnykam'],
        ],
        [
            'label' => Yii::t('app', 'Контакти'),
            'url' => ['/site/contacts'],
            
        ],

    ],
    'options' => [
        'class' => ['nav navbar-nav navbar-right']
    ],
    'encodeLabels' =>'false',
]);
