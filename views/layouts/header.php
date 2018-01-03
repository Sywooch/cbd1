<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use yii\helpers\Html;

$messages_count = \app\models\Messages::find()->where(['user_id' => Yii::$app->user->id, 'status' => '0'])->count();
$messages_counter = $messages_count > 0 ? ('</span><span class="notification">' . $messages_count . '</span>') : '';



echo Menu::widget([
    'items' => [
        [
            'label' => Yii::t('app','Home'),
            'url' => ['/site/index'],
            'template' => '<a href="/">{label}</a></li>',
        ],
        [
            'label' => Yii::t('app','Instructions'),
            'url' => ['/services/index'],
            'options'=>['class'=>'dropdown'],
            'template' => '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}  <span class="caret"></span></a>',
            'items' => [
                ['label' => Yii::t('app', 'Manager'),'url' => ['/site/manager']],
                // ['label' => Yii::t('app', 'Rules'),'url' => ['/downloads-files/rules']],
                ['label' => Yii::t('app','Examples'),'url' => ['/downloads-files/examples']],
                ['label' => Yii::t('app','Reglament'),'url' => ['/downloads-files/reglament']],
            ],
            'submenuTemplate' => "<ul class='dropdown-menu'>{items}</ul>",
            //'visible' => !Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app','Publishing ID'),
            'url' => ['/public'],
            'template' => '<a href="{url}" >Публікації</a>',
        ],
        [
            'label' => Yii::t('app','SignIn') . '/' . Yii::t('app', 'SignUp'),
            'url' => ['/user/login'],
            'options'=>['class'=>'dropdown'],
            'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label} <span class="caret"></span></a>',
            'items' => [
                ['label' => Yii::t('app', 'SignIn'),'url' => ['/user/login']],
                ['label' => Yii::t('app', 'SignUp'),'url' => ['/registration/register']],
            ],
            'submenuTemplate' => "<ul class='dropdown-menu'>{items}</ul>",
            'visible' => Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app','Cabinet'),
            'url' => ['/lots'],
            'template' => '<a id = "cabinet" href="{url}">{label}</a>',
            'visible' => !Yii::$app->user->isGuest,
        ],
        [
            'label'=> '(044)337-23-64, (068)257-38-98',
            'options'=>['class'=>'contacts'],
            'template' => '<a href="tel:+380443372364" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{label}</a>',
            // 'submenuTemplate' => "<ul class='dropdown-menu'>{items}</ul>",
            'visible' => Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app','Messages'),
            'template' => '<a href="{url}" title="{label}"><span class="glyphicon glyphicon-inbox" aria-hidden="true">' . $messages_counter . '</a>',
            'url' => ['/messages/index'],
            'visible' => !Yii::$app->user->isGuest,
        ],
        [
            'label' => Yii::t('app','Profile'),
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="hidden-sm hidden-xs"></span><span class="glyphicon glyphicon-user " aria-hidden="true"></span><span class="caret"></span></a>',
            'items' => [
                ['label' => Yii::t('app','Profile'),'url' => ['/settings/profile']],
                ['label' => Yii::t('app', 'Change password'), 'url' => ['/settings/account']],
                ['label' => Yii::t('user', 'Logout') . ' (' . @Yii::$app->user->identity->username . ')',  'url' => ['/site/logout']],
            ],
            'submenuTemplate' => "<ul class='dropdown-menu'>{items}</ul>",
            'visible' => !Yii::$app->user->isGuest,
        ]
    ],
    'options' => [
        'class' => ['nav navbar-nav navbar-right']
    ],
    'encodeLabels' =>'false',
]);
?>