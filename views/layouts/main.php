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
    <link rel="icon" type="image/png" href="/images/favicon.png" />
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
<section class="banner d-flex align-items-center">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-6 col-sm-4 col-lg-6 align-self-center">
                <!--                <a href="/"><img class="logo" src="/images/logo.png" alt=""></a>-->
                <img class="d-none d-lg-inline-block logo-prozorro" src="/images/logo-prozorro.png" alt="">
                <p class="d-none d-lg-inline-block accredit">Акредитований<br>майданчик</p>
            </div>
            <div class="col-12 col-sm-6 align-self-center text-right d-none d-sm-inline-block">
        <span class="contact-info text-left d-none d-lg-inline-block">
              <a href="tel:+380000000000">0000000000</a><br>
              <a href="tel:+380000000000">0000000000</a>
            </span>
                <span class="contact-info text-left d-none d-lg-inline-block">
              <a href="tel:+380000000000">000000000</a><br>
              <a class="link-primary" href="mailto:mail@mail.ua">mail@mail.ua</a>
            </span>
                <?php if(Yii::$app->user->isGuest){?>
                    <a href="/user/login" class="btn btn-primary btn-signin">Вхід</a>
                    <a href="/registration/register" class="btn btn-warning btn-signup">Реєстрація</a>
                <?php }else{?>
                    <!-- <a href="/user/profile/show" class="btn btn-primary btn-signin">Вхід</a> -->
                    <?= Menu::widget([
                        'items' => [
                            [
                                'label' => Yii::t('app','Cabinet'),
                                'url' => ['/lots'],
                                'template' => '<a id="cabinet" class="btn btn-primary btn-lg" href="{url}">{label}</a>',
                                'visible' => !Yii::$app->user->isGuest,
                                'options' => [
                                    'class' => 'nav-item',
                                ]
                            ],
                            [
                                'label' => Yii::t('app','Messages'),
                                'template' => '<a class="btn btn-primary btn-lg ml-2 mr-2" href="{url}" title="{label}"><span class="badge-message"><img src="/images/icon-chat.png">' . $messages_counter . '</span></a>',
                                'url' => ['/messages/index'],
                                'visible' => !Yii::$app->user->isGuest,
                                'options' => [
                                    'class' => 'nav-item',
                                ]
                            ],
                            [
                                'label' => Yii::t('app','Logout'),
                                'template' => '<a data-method="post" id="cabinet" class="btn btn-primary btn-lg" href="{url}">{label}</a>',
                                'url' => ['/user/logout'],
                                'visible' => !Yii::$app->user->isGuest,
                                'options' => [
                                    'class' => 'nav-item',
                                ]
                            ],
//                            [
//                                'label' => Yii::t('app','Profile'),
//                                //'template' => '<a class="drpdwn-link" href="#"><span class="glyphicon glyphicon-briefcase hidden-md hidden-lg" aria-hidden="true"></span><span class="hidden-sm hidden-xs">{label}</span></a>',
//                                'template' => '<a class="btn btn-primary btn-lg badge-profile nav-link dropdown-toggle" href="#" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><img src="/images/icon-profile.png"></a>',
//                                'items' => $items,
//                                'submenuTemplate' => "<ul class='dropdown-menu dropdown-menu-right' >{items}</ul>",
//                                'visible' => !Yii::$app->user->isGuest,
//                                'options' => [
//                                    'class' => 'nav-item dropdown',
//                                ]
//                            ],

                        ],
                        'options' => [
                            'class' => 'nav d-inline-flex',
                        ],
                        'encodeLabels' =>'false',
                    ]);?>
                    <!-- <a href="/registration/register" class="btn btn-warning btn-signup">Реєстрація</a> -->
                <?php }?>
            </div>
            <div class="col-6 col-sm-2 d-inline-block d-lg-none align-self-center text-right">
                <button type="button" id="miniRegCall" class="btn btn-primary d-sm-none">
                    <img src="/images/icon-user.png">
                </button>
                <button type="button" id="miniListCall" class="btn btn-primary ml-3">
                    <img src="/images/menu-icon.png">
                </button>
            </div>
        </div>
    </div>
    <nav id="menuMiniReg" class="menu-mini-reg d-sm-none">
        <?php if(Yii::$app->user->isGuest): ?>
            <h4 class="menu-mini-title font-weight-bold text-center">Вхід та реєстрація</h4>
            <a href="/user/login" class="btn btn-primary btn-block btn-lg">Вхід</a>
            <a href="/registration/register" class="btn btn-warning btn-block btn-lg">Реєстрація</a>
        <?php else: ?>
            <a href="/bids" class="btn btn-primary btn-block btn-lg">Кабінет</a>
            <a href="/messages" class="btn btn-primary btn-block btn-lg">Повідомлення</a>
        <?php endif; ?>
        <button type="button" class="menu-mini-close">
            <img src="/images/icon-close.png" alt="">
        </button>
    </nav>
    <nav id="menuMiniList" class="menu-mini-list d-lg-none">
        <ul class="text-center">
            <li><a href="/" class="link-primary">Про нас</a></li>
            <li><a href="#" class="link-primary">Учасникам</a></li>
            <li><a href="#" class="link-primary">Замовникам</a></li>
            <li><a href="/public" class="link-primary">Публікації</a></li>
            <li><a href="/category/novini" class="link-primary">Новини</a></li>
            <li><a href="#" class="link-primary">Контакти</a></li>
        </ul>
        <div class="contact-info-mini text-center">
            <p><a href="tel:+380443337098">044-333-70-98</a></p>
            <p><a href="tel:+380508883298">050-888-32-98</a></p>
            <p><a href="tel:+380988883298">098-888-32-98</a></p>
        </div>
        <button type="button" class="menu-mini-close">
            <img src="/images/icon-close.png" alt="">
        </button>
    </nav>
</section>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary d-none d-lg-flex">
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
                    <a class="nav-link" href="#">Замовникам</a>
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
<main class="site-content">
    <div class="container">
        <?= $this->render('@app/views/_alert'); ?>
    </div>
    <?= $content ?>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="banner-footer col-12 col-lg-6 align-self-center">
                <!--                <a href="/"><img class="logo" src="/images/logo.png" alt=""></a>-->
                <img class="logo-prozorro d-none d-sm-inline-block" src="/images/logo-prozorro.png" alt="">
                <p class="accredit d-none d-sm-inline-block">Акредитований<br>майданчик</p>
                <button type="button" id="miniListCallFooter" class="btn btn-primary d-lg-none float-right mt-2">
                    <img src="/images/menu-icon.png">
                </button>
                <nav class="menu-mini-list-footer">
                    <ul class="text-center">
                        <li><a href="/" class="link-primary">Про нас</a></li>
                        <li><a href="#" class="link-primary">Учасникам</a></li>
                        <li><a href="#" class="link-primary">Замовникам</a></li>
                        <li><a href="/public" class="link-primary">Публікації</a></li>
                        <li><a href="/category/novini" class="link-primary">Новини</a></li>
                        <li><a href="#" class="link-primary">Контакти</a></li>
                    </ul>
                    <button type="button" class="menu-mini-close-footer">
                        <img src="/images/icon-close.png" alt="">
                    </button>
                </nav>
            </div>
            <div class="col-12 col-lg-6 align-self-center text-right d-none d-lg-block">
        <span class="contact-info text-left">
          <a href="tel:+380000000000">+380000000000</a><br>
          <a href="tel:+380000000000">+380000000000</a>
        </span>
                <span class="contact-info text-left">
          <a href="tel:+380000000000">+380000000000</a><br>
          <a class="link-primary" href="mailto:mail@mail.ua">mail@mail.ua</a>
        </span>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary d-none d-lg-flex">
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
                            <a class="nav-link" href="#">Замовникам</a>
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
        <div style="padding: 30px 0 0; border-top: 1px solid #dcdde0; border-bottom: 1px solid #dcdde0" class="contact-info-mini text-center mt-3 d-lg-none">
            <p><a href="tel:+380000000000">+380000000000</a></p>
            <p><a href="tel:+380000000000">+380000000000</a></p>
            <p><a href="tel:+380000000000">+380000000000</a></p>
            <p><a href="mailto:mail@mail.ua">mail@mail.ua</a></p>
            <ul class="navbar-nav flex-row social justify-content-center">
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
        <div class="copyright mt-2">
            <div class="row">
                <div class="col d-none d-lg-block">
                    <p>&copy; 2017, <a class="link-copyright" href="/">Електронний торговий майданчик</a></p>
                </div>
                <div class="col text-center text-lg-right">Сайт зроблено — <a href="http://reactlogic.com.ua" class="link-copyright">«React Logic»</a></div>
            </div>
        </div>
    </div>
</footer><?php $this->endBody() ?>
<?php $this->endPage() ?>
