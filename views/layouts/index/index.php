<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\IndexAsset;
use \yii\widgets\ActiveForm;
use yii\helpers\Url;

IndexAsset::register($this);

if(Yii::$app->user->isGuest){
    $model = Yii::createObject(\app\models\LoginForm::className());
}
else{
    $messagesCount = \app\models\Messages::find()->where(['user_id' => Yii::$app->user->id, 'status' => 0])->andWhere(['not like', 'notes', 'При реєстрації були допущені наступні помилки'])->count();
    $messagesLabel = '';
    if($messagesCount){
        $messagesLabel = Html::tag('span', $messagesCount, ['class' => 'label label-danger']);
    }
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="keywords" content="биддинг тайм, торги, биржа, торговая площадка, аукцион, ставка, ставки, лот, лоты">
    <meta name="description" content="биддинг тайм, торги, биржа, торговая площадка, аукцион, ставка, ставки, лот, лоты">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="icon" href="/favicon.jpg"><link rel="shortcut icon" href="/favicon.jpg">
    <!-- <meta name="MobileOptimized" content="991"/>
    <meta name="HandheldFriendly" content="true"/> -->
    <?php $this->head() ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body>
<?php $this->beginBody() ?>
<header class="site-header">
    
<section class="access" style="height:30px"></section>
    
    <section class="banner">
        <div class="container">
            <div class="container-in">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <img src="/images/strateg-logo.png" alt="Логотип Стратег">
                        <h1 class="banner__title helvetica-bc">
                            Bidding Time<br>
                            <small class="helvetica">Стань першим серед рівних</small>
                        </h1>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-5 col-lg-offset-1 text-right">
                        <a class="prozorro-link" href="https://prozorro.sale" target="_blank">
                            <img class="hidden-xs hidden-sm" src="/images/prozorro-logo.png" alt="Логотип Прозорро">
                        </a>
                        <p class="banner__subtitle helvetica-l text-uppercase text-left hidden-xs hidden-sm">
                            Акредитований<br>Майданчик
                        </p>
                        <form action="<?= Url::to(['/public']); ?>">
                            <div style="margin-top: 5px;" class="input-group">
                                <input type="text" class="form-control form-control-custom" name="Auctions[auctionID]" placeholder="Пошук...">
                                <span class="input-group-btn">
                                                <button class="btn btn-default btn-dark" type="submit" id="submit-search">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                            </div>
                        </form>
                        <!-- /input-group -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark menu">
            <div class="container-in">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                <div class="collapse navbar-collapse" id="main-menu">
                    <?= $this->render('header') ?>
                </div>
                <!-- /.navbar-collapse -->
            </div>
        </nav>
    </div>
</header>

<main class="container">
    <div class="container-in main-content">
        <?= $this->render('@app/views/_alert'); ?>
        <?= $content ?>
    </div>
</main>
</section>

<?= $this->render('footer') ?>

<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
