<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon.jpg" />
    <!-- <meta name="MobileOptimized" content="991"/>
    <meta name="HandheldFriendly" content="true"/> -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<section class="banner d-flex align-items-center">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-6 col-sm-4 col-lg-6 align-self-center">
                <a href="/"><img class="logo" src="/images/strateg-logo.png" height="60" alt=""></a>
                <img class="logo-prozorro" src="/images/prozorro-logo.png" alt="">
                <p class="d-none d-lg-inline-block accredit">Акредитований<br>майданчик</p>
            </div>
            <div class="col-12 col-sm-6 align-self-center text-right d-none d-sm-inline-block">
                <span class="contact-info text-right">
                    <a class="text-light" href="tel:+380443511081">044-351-10-81</a><br>
                    <a class="text-light" href="mailto:office@biddingtime.com.ua">office@biddingtime.com.ua</a>
                </span>
                <?php if(Yii::$app->user->isGuest): ?>
                    <a href="/user/login" class="btn btn-primary btn-signin">Вхід</a>
                    <a href="/registration/register" class="btn btn-warning btn-signup">Реєстрація</a>
                    <?php else: ?>
                    <a href="/user/security/logout" class="btn btn-outline-light btn-lg btn-signin" data-method="post">Вихід</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?= $this->render('@app/views/_alert'); ?>
<?= $this->render('userMenu'); ?>
<?= $content ?>
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 align-self-center">
               <a href="#"><img class="logo" src="/images/strateg-logo.png" height="60" alt=""></a>
                <img class="logo-prozorro" src="/images/prozorro-logo.png" alt="">
                <p class="d-none d-lg-inline-block accredit">Акредитований<br>майданчик</p>
            </div>
            <div class="col-12 col-md-6 align-self-center text-right">
                <span class="contact-info text-right">
                    <a class= "text-light" href="tel:+380443511081">044-351-10-81</a><br>
                    <a class="text-light" href="mailto:office@biddingtime.com.ua">office@biddingtime.com.ua</a>
                </span>
            </div>
        </div>
        <div class="copyright mt-2">
            <div class="row">
                <div class="col">
                    <p>&copy; 2017, <a class="link-copyright" href="/">Електронний торговий майданчик</a></p>
                </div>
                <div class="col text-right">Сайт розроблено — <a href="#" class="link-copyright">«React Logic»</a></div>
            </div>
        </div>
    </div>
</footer><?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
