<?php

use yii\bootstrap\Nav;

$messages_count = \app\models\Messages::find()->where(['user_id' => Yii::$app->user->id, 'status' => '0'])->count();
$messages_counter = $messages_count > 0 ? ('</span><span class="notification">' . $messages_count . '</span>') : '';



echo Nav::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'About Us'),
            'url' => ['/site/about'],
        ],
        [
            'label' => Yii::t('app', 'Учасникам'),
            'items' => [
                ['label' => Yii::t('app', 'Право вимоги'), 'url' => ['/participant/pravo-vymogy']],
                ['label' => Yii::t('app', 'Майно'), 'url' => ['/participant/maino']],
                ['label' => Yii::t('app', 'Регламент'), 'url' => ['/participant/reglament']],
            ],
        ],
        [
            'label' => Yii::t('app', 'Замовникам'),
            'url' => ['/site/zamovnykam'],
        ],
        [
            'label' => Yii::t('app', 'Prozorro Sale'),
            'url' => 'https://www.prozorro.sale',
            'linkOptions' => ['target' => '_blank'],
        ],
        [
            'label' => Yii::t('app', 'Contacts'),
            'url' => ['/site/contacts'],
        ],

    ],
    'options' => [
        'class' => ['nav navbar-nav navbar-right']
    ],
    'encodeLabels' =>'false',
]);
