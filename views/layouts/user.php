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
<!--                <a href="/"><img class="logo" src="/images/logo.png" alt=""></a>-->
                <img class="logo-prozorro" src="/images/logo-prozorro.png" alt="">
                <p class="d-none d-lg-inline-block accredit">Акредитований<br>майданчик</p>
            </div>
            <div class="col-12 col-sm-6 align-self-center text-right d-none d-sm-inline-block">
          <span class="contact-info text-left">
                <a href="tel:+380443337098">044-333-70-98</a><br>
                <a href="tel:+380508883298">050-888-32-98</a>
              </span>
                <span class="contact-info text-left">
                <a href="tel:+380988883298">098-888-32-98</a><br>
                <a class="link-primary" href="mailto:mail@mail.ua">mail@mail.ua</a>
              </span>
                <?php if(Yii::$app->user->isGuest): ?>
                    <a href="/user/login" class="btn btn-primary btn-signin">Вхід</a>
                    <a href="/registration/register" class="btn btn-warning btn-signup">Реєстрація</a>
                    <?php else: ?>
                    <a href="/user/security/logout" class="btn btn-primary btn-signin" data-method="post">Вихід</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#bottom-menu" aria-controls="bottom-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="bottom-menu">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="#">Про нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Учасникам</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Ліквідаторам</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public">Публікації</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/category/novini">Новини</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Контакти</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto social">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/images/vk.png" alt="">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/images/fb.png" alt="">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/images/g+.png" alt="">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?= $this->render('@app/views/_alert'); ?>
<?= $this->render('userMenu'); ?>
<?= $content ?>
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 align-self-center">
<!--                <a href="#"><img class="logo" src="/images/logo.png" alt=""></a>-->
                <img class="logo-prozorro" src="/images/logo-prozorro.png" alt="">
                <p class="d-none d-lg-inline-block accredit">Акредитований<br>майданчик</p>
            </div>
            <div class="col-12 col-md-6 align-self-center text-right">
          <span class="contact-info text-left">
                <a href="tel:+380443337098">044-333-70-98</a><br>
                <a href="tel:+380508883298">050-888-32-98</a>
              </span>
                <span class="contact-info text-left">
                <a href="tel:+380988883298">098-888-32-98</a><br>
                <a class="link-primary" href="mailto:mail@mail.ua">mail@mail.ua</a>
              </span>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="container">
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#top-menu" aria-controls="top-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="top-menu">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Про нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Учасникам</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ліквідаторам</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Публікації</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Новини</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Контакти</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto social">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="/images/vk-2.png" alt="">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="/images/fb-2.png" alt="">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="/images/g.png" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="copyright mt-2">
            <div class="row">
                <div class="col">
                    <p>&copy; 2017, <a class="link-copyright" href="/">Електронний торговий майданчик</a></p>
                </div>
                <div class="col text-right">Сайт зроблено — <a href="#" class="link-copyright">«React Logic»</a></div>
            </div>
        </div>
    </div>
</footer><?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
