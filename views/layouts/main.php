<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\widgets\Menu;


AppAsset::register($this);
$messages_counter = \app\models\Messages::find()->where(['user_id' => Yii::$app->user->id])->count();
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
<?php
$items = [];

if(Yii::$app->user->can('admin') ) {
    $items[] = ['label' => Yii::t('app', 'Users'), 'url' => '/user/admin'];
    $items[] = ['label' => Yii::t('app', 'Categories ID'), 'url' => '/category'];
    $items[] = ['label' => Yii::t('app', 'EventLog ID'), 'url' => '/eventlog'];
}
if (!Yii::$app->user->isGuest){
    $items[] = ['label' => Yii::t('app','Profile'),'url' => '/settings/profile', 'options' => ['class' => 'dropdown-item']];
    $items[] = ['label' => Yii::t('app', 'Change password'), 'url' => '/settings/account',  'options' => ['class' => 'dropdown-item']];
    $items[] =  ['label' => Yii::t('user', 'Logout')."\n\n\n(".Yii::$app->user->identity->username.")",  'url' => '/site/logout',  'options' => ['class' => 'dropdown-item']];
}
else{

}

?>
<section class="site-header banner bg-dark d-flex align-items-center">
<div class="container">
    <div class="row no-gutters">
        <!-- logos -->
        <div class="col-6 col-sm-4 col-lg-5 align-self-center">
            <a class="link-contacts" href="tel:+380442194747">Тел +38 044 219 47 47</a>
        </div>
        <!-- contact info -->
        <div class="col-12 col-sm-6 col-lg-7 align-self-center text-right d-none d-sm-inline-block">
            <span class="contact-info text-left d-none d-lg-inline-block pr-4">
                <a class="btn-link-social mr-3 align-self-center" href="https://twitter.com/KMEwelcome" target="_blank">
                    <img src="/img/twitter.png" alt="twitter">
                </a>
                <a class="btn-link-social align-self-center" href="https://www.linkedin.com/company/kyivmercantileexchange/" target="_blank">
                    <img src="/img/linkedin.png" alt="linkedin">
                </a>
            </span>
            <span class="contact-info-reg text-right d-lg-inline-block">
                <?php if(Yii::$app->user->isGuest):?>
                    <a href="/registration/register" class="btn btn-link btn-overlap">Реєстрація</a>
                    <a href="/user/login" class="btn btn-link btn-overlap">Вхід до кабінету</a>

                <?php else:?>
                    <a href="/lots" id="cabinet" class="btn btn-primary btn-signin">Кабінет</a>
                    <a href="/user/logout" data-method="post" class="btn btn-primary btn-signin">Вихід</a>
                <?php endif;?>
            </span>
        </div>

        <!-- start hidden -->
        <div class="col-6 col-sm-2 d-inline-block d-lg-none align-self-center text-right">
            <button type="button" id="miniRegCall" class="btn btn-primary d-sm-none">
                <img src="/images/icon-user.png">
            </button>
            <button type="button" id="miniListCall" class="btn btn-primary ml-3">
                <img src="/images/menu-icon.png">
            </button>
        </div>
        <!-- end hidden -->
    </div>
</div>

<nav id="menuMiniReg" class="menu-mini-reg d-sm-none">
    <h4 class="menu-mini-title font-weight-bold text-center">Вхід та реєстрація</h4>
    <?php if(Yii::$app->user->isGuest):?>
        <a href="/user/login" class="btn btn-primary btn-block btn-lg">Вхід</a>
        <a href="/registration/register" class="btn btn-warning btn-block btn-lg">Реєстрація</a>
    <?php else:?>
        <a href="/lots" id="cabinet" class="btn btn-primary btn-signin">Кабінет</a>
        <a href="/user/logout" data-method="post" class="btn btn-primary btn-signin">Вихід</a>
    <?php endif;?>

    <button type="button" class="menu-mini-close">
        <img src="/images/icon-close.png" alt="">
    </button>
</nav>

<nav id="menuMiniList" class="menu-mini-list d-lg-none">
    <ul class="text-center">
        <li><a href="http://kme.net.ua/pro-birzhu/" class="link-primary">Про нас</a></li>
        <li><a href="/public" class="link-primary">Публікації</a></li>
        <li><a href="http://kme.net.ua/contacty/" class="link-primary">Контакти</a></li>
    </ul>

    <div class="contact-info-mini text-center">
        <p><a href="tel:+380442194747">044-219-47-47</a></p>
    </div>

    <button type="button" class="menu-mini-close">
        <img src="/images/icon-close.png" alt="">
    </button>
</nav>
</section>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-primary d-none d-lg-flex">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/img/logo-kme.png" alt="KME Logo">
        </a>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#bottom-menu" aria-controls="bottom-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="bottom-menu">
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="http://kme.net.ua/pro-birzhu/">Про нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public">Публікації</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://kme.net.ua/contacty/">Контакти</a>
                </li>
            </ul>
            <span style="display:none" class="contact-info-reg text-right">
            <?php if(Yii::$app->user->isGuest):?>
                <a href="/registration/register" class="btn btn-link btn-overlap">Реєстрація</a>
                <a href="/user/login" class="btn btn-link btn-overlap">Вхід до кабінету</a>

            <?php else:?>
                <a href="/lots" id="cabinet" class="btn btn-primary btn-signin">Кабінет</a>
            <?php endif;?>
        </span>
        </div>
    </div>
</nav>
<main class="site-content">
    <div class="container">
        <?= $this->render('@app/views/_alert'); ?>
    </div>
    <?= $content ?>
</main>
<footer class="site-footer align-items-center bg-dark-footer">
<div class="container">
    <div class="row">
        <div class="banner-footer col-12 col-lg-6 align-self-center">
            <a href="/"><img class="logo" src="/img/logo-kme-white.png" alt="logo"></a>

            <button type="button" id="miniListCallFooter" class="btn btn-primary d-lg-none float-right mt-2">
                <img src="/images/menu-icon.png">
            </button>

            <nav class="menu-mini-list-footer">
                <ul class="text-center">
                    <li><a href="http://kme.net.ua/pro-birzhu/" class="link-primary">Про нас</a></li>
                    <li><a href="/public" class="link-primary">Публікації</a></li>
                    <li><a href="http://kme.net.ua/contacty/" class="link-primary">Контакти</a></li>
                </ul>
                <button type="button" class="menu-mini-close-footer">
                    <img src="/images/icon-close.png" alt="">
                </button>
            </nav>

        </div>

        <div class="col-12 col-lg-6 align-self-center text-right d-none d-lg-block">
            <span class="contact-info text-left d-lg-inline-block">
                <a class="link-contacts link-contacts-footer" href="tel:+380442194747">+38 044 219 47 47</a><br>
                <a class="link-contacts link-contacts-footer" href="mailto:welcome@kme.net.ua">welcome@kme.net.ua</a>
            </span>

        </div>
    </div>

    <div style="padding: 30px 0 0; border-top: 1px solid #dcdde0; border-bottom: 1px solid #dcdde0" class="contact-info-mini text-center mt-3 d-lg-none">
        <p><a class="link-contacts" href="tel:+380442194747">+38 044 219 47 47</a></p>
        <p><a class="link-contacts" href="mailto:welcome@kme.net.ua">welcome@kme.net.ua</a></p>
        <ul class="navbar-nav flex-row social justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="https://www.linkedin.com/company/kyivmercantileexchange/" target="_blank">
                    <img src="/img/linkedin.png" alt="linkedin">
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://twitter.com/KMEwelcome" target="_blank">
                    <img src="/img/twitter.png" alt="twitter">
                </a>
            </li>
        </ul>
    </div>

    <div class="copyright mt-2">
        <div class="row">
            <div class="col d-none d-lg-block">
                <p>&copy; 2017, <a href="https://kme.net.ua" target="_blank" class="link-copyright">KYIV MERCANTILE EXCHANGE, </a>ALL RIGHTS RESERVED</p>
            </div>
        </div>
    </div>
</footer><?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
