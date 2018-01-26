

<?php

use yii\widgets\Menu;


$unconfirmedLabel = '';
$unconfirmedCount = \app\models\User::find()->where(['confirmed_at' => null])->count();
if($unconfirmedCount){
    $unconfirmedLabel = ' ' . \yii\helpers\Html::tag('span', $unconfirmedCount, ['class' => 'badge badge-warning']);
}

$messages = \app\models\Messages::find()
    ->andWhere(['user_id' => Yii::$app->user->identity->id])
    ->andWhere(['status' => '0'])
    ->count();
$messagesLabel = '';
if($messages>0){
    $messagesLabel = "($messages)";
}
$route = Yii::$app->controller->id;
$bids=$lots=$messages=$users='';
switch($route){
    case 'bids':
        $bids = 'active';
        break;
    case 'lots':
        $lots = 'active';
        break;
    case 'messages':
        $messages = 'active';
        break;
    case 'admin':
        $users = 'active';
        break;
}


?>
<main class="site-content">
    <div class="office">
        <div class="office-header">
            <div class="container">
                <div class="row">
                    <div class="<?= YiI::$app->user->can('org') ? 'col-10' : 'col-12'?>">
                        <nav class="nav nav-tabs" id="officeTab" role="tablist">
                            <?php if(Yii::$app->user->can('admin')): ?>
                                <a class="nav-item nav-link <?=$users?>" id="nav-users-tab" href="/user/admin" role="tab" aria-controls="nav-auctions" aria-selected="true">Користувачі <?= $unconfirmedLabel; ?></a>
                            <?php endif; ?>
                            <a class="nav-item nav-link <?=$lots?>" id="nav-auctions-tab" href="/lots/index" role="tab" aria-controls="nav-auctions" aria-selected="true">Аукціони</a>
                            <a class="nav-item nav-link <?=$bids?>" id="nav-bids-tab" href="/bids/index" role="tab" aria-controls="nav-bids" aria-selected="false">Заявки на участь</a>
                            <a class="nav-item nav-link <?=$messages?>" id="nav-messages-tab" href="/messages/index" role="tab" aria-controls="nav-messages" aria-selected="false">Повідомлення <?=$messagesLabel; ?></a>
                        </nav>
                    </div>
                    <?php if(Yii::$app->user->can('org')):?>
                        <div class="col-2 align-self-center text-right">
                            <a href="/lots/create" id="create-auction-btn" class="btn btn-warning">Додати аукціон</a>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?php
        $items = [];
        if(Yii::$app->user->can('admin')){
            $items[] = ['label' => Yii::t('app', 'Users') . $unconfirmedLabel, 'url' => ['/user/admin'], 'active' => in_array(Yii::$app->controller->id, ['admin'])];
        }
        $items[] = ['label' => Yii::t('app', 'Auctions'),  'url' => ['/lots/index'], 'active' => in_array(Yii::$app->controller->id, ['lots', 'cancellations'])];
        $items[] = ['label' => Yii::t('app', 'Bidding'),  'url' => ['/bids/index'], 'active' => in_array(Yii::$app->controller->id, ['bids'])];
        $items[] = ['label' => Yii::t('app', 'Messages'),  'url' => ['/bids/index'], 'active' => in_array(Yii::$app->controller->id, ['bids'])];

        ?>
