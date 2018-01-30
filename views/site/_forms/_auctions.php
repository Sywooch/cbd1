<?php

use yii\helpers\Html;

/**
 * @var $model \api\Auctions
 * */
?>

<ul>
    <li><time class="helvetica"><?= Yii::$app->formatter->asDatetime($model->auctionPeriod_startDate); ?></time></li>
    <li class="publications__text">
        <?= $model->title; ?>
        <p><?= $model->description; ?></p>
    </li>

</ul>
<?= Html::a(Yii::t('app', 'More'), ['/public/view', 'id' => $model->auctionID])?>
