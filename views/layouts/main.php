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
<section class="site-header banner bg-dark d-flex align-items-center">
<div class="container">
    <div class="row no-gutters">
        <!-- logos -->
        <div class="col-6 col-sm-4 col-lg-5 align-self-center">
            <a href="/"><img src="/zupb/img/logo.png" alt="ЗУПБ" class="logo"></a>
            <p class="logo-slogan d-none d-lg-inline-block">Акредитований майданчик<br>Prozorro.Sale</p>
        </div>
        <!-- contact info -->
        <div class="col-12 col-sm-6 col-lg-7 align-self-center text-right d-none d-sm-inline-block">
                <span class="contact-info text-left d-none d-lg-inline-block">
                    <a class="link-contacts" href="tel:+380442271891">044-227-18-91</a><br>
                    <a class="link-contacts" href="mailto:tbzupb@gmail.com">tbzupb@gmail.com</a>
                </span>
            <!-- <span class="contact-info text-left d-none d-lg-inline-block">
                <a class="link-contacts" href="tel:+380508883298">050-888-32-98</a><br>
                <a class="link-contacts" href="tel:+380988883298">098-888-32-98</a>
            </span> -->
            <span class="contact-info-reg text-right d-lg-inline-block">
                <?php if(Yii::$app->user->isGuest):?>
                    <a class="btn btn-link btn-overlap" href="/user/login">Увійти</a><br>
                    <a class="btn btn-link btn-overlap" href="/registration/register">Реєстрація</a>
                <?php else:?>
                    <?= Menu::widget([
                        'items' => [
                            [
                                'label' => Yii::t('app','Cabinet'),
                                'url' => Yii::$app->user->can('org') ? ['/lots'] : ['/bids'],
                                'template' => '<a class="btn btn-primary btn-lg" href="{url}">{label}</a>',
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
                            'label' => Yii::t('app', 'Logout'),
                            'url' => ['/user/logout'],
                            'template' => '<a data-method="post" class="btn btn-primary btn-lg" href="{url}">{label}</a>',
                            'visible' => !Yii::$app->user->isGuest,
                            'options' => [
                                'class' => 'nav-item',
                            ],
                           ]

                        ],
                        'options' => [
                            'class' => 'nav d-inline-flex',
                        ],
                        'encodeLabels' =>'false',
                    ]);?>
                <?php endif; ?>

                </span>
        </div>

        <!-- start hidden -->
        <div class="col-6 col-sm-2 d-inline-block d-lg-none align-self-center text-right">
            <button type="button" id="miniRegCall" class="btn btn-primary d-sm-none">
                <img src="/zupb/images/icon-user.png">
            </button>
            <button type="button" id="miniListCall" class="btn btn-primary ml-3">
                <img src="/zupb/images/menu-icon.png">
            </button>
        </div>
        <!-- end hidden -->
    </div>
</div>
<?php if(Yii::$app->user->isGuest):?>
    <nav id="menuMiniReg" class="menu-mini-reg d-sm-none">
        <h4 class="menu-mini-title font-weight-bold text-center">Вхід та реєстрація</h4>
        <a href="#" class="btn btn-primary btn-block btn-lg">Вхід</a>
        <a href="#" class="btn btn-warning btn-block btn-lg">Реєстрація</a>
        <button type="button" class="menu-mini-close">
            <img src="/zupb/images/icon-close.png" alt="close">
        </button>
    </nav>
<?php endif; ?>


<nav id="menuMiniList" class="menu-mini-list d-lg-none">
    <ul class="text-center">
        <li><a href="#" class="link-primary">Про нас</a></li>
        <li><a href="#" class="link-primary">Учасникам</a></li>
        <li><a href="#" class="link-primary">Замовникам</a></li>
        <li><a href="/public" class="link-primary">Публікації</a></li>
        <li><a href="#" class="link-primary">Новини</a></li>
        <li><a href="#" class="link-primary">Контакти</a></li>
    </ul>

    <div class="contact-info-mini text-center">
        <p><a href="tel:+380442271891">044-227-18-91</a></p>
        <p><a href="mailto:tbzupb@gmail.com">tbzupb@gmail.com</a></p>
    </div>

    <button type="button" class="menu-mini-close">
        <img src="/zupb/images/icon-close.png" alt="">
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
<footer class="site-footer align-items-center">
    <div class="container">
        <div class="row">
            <div class="banner-footer col-12 col-lg-6 align-self-center">
                <a href="#"><img class="logo" src="/zupb/img/logo.png" alt="logo"></a>
                <p class="logo-slogan d-none d-sm-inline-block">Акредитований<br>майданчик</p>

                <button type="button" id="miniListCallFooter" class="btn btn-primary d-lg-none float-right mt-2">
                    <img src="/zupb/images/menu-icon.png">
                </button>

                <nav class="menu-mini-list-footer">
                    <ul class="text-center">
                        <li><a href="#" class="link-primary">Про нас</a></li>
                        <li><a href="#" class="link-primary">Учасникам</a></li>
                        <li><a href="#" class="link-primary">Замовникам</a></li>
                        <li><a href="/public" class="link-primary">Публікації</a></li>
                        <li><a href="/category/novini" class="link-primary">Новини</a></li>
                        <li><a href="#" class="link-primary">Контакти</a></li>
                    </ul>
                    <button type="button" class="menu-mini-close-footer">
                        <img src="/zupb/images/icon-close.png" alt="">
                    </button>
                </nav>

            </div>

            <div class="col-12 col-lg-6 align-self-center text-right d-none d-lg-block">
            <span class="contact-info text-left d-lg-inline-block">
                <a class="link-contacts" href="tel:+380442271891">044-227-18-91</a><br>
                <a class="link-contacts" href="mailto:tbzupb@gmail.com">tbzupb@gmail.com</a>
            </span>
                <!-- <span class="contact-info text-left d-lg-inline-block">
                <a class="link-contacts" href="tel:+380508883298">050-888-32-98</a><br>
                <a class="link-contacts" href="tel:+380988883298">098-888-32-98</a>
                </span> -->
            </div>
        </div>
        <nav class="navbar navbar-expand-lg bg-primary bg-dark d-none d-lg-flex">
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
                                <img src="/zupb/images/vk.png" alt="vk">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="/zupb/images/fb.png" alt="fb">
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <img src="/zupb/images/gplus.png" alt="g+">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div style="padding: 30px 0 0; border-top: 1px solid #dcdde0; border-bottom: 1px solid #dcdde0" class="contact-info-mini text-center mt-3 d-lg-none">
            <p><a class="link-contacts" href="tel:+380442271891">044-227-18-91</a></p>
            <!-- <p><a class="link-contacts" href="tel:+380508883298">050-888-32-98</a></p>
            <p><a class="link-contacts" href="tel:+380988883298">098-888-32-98</a></p> -->
            <p><a class="link-contacts" href="mailto:tbzupb@gmail.com">tbzupb@gmail.com</a></p>
            <ul class="navbar-nav flex-row social justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/zupb/images/vk.png" alt="vk">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/zupb/images/fb.png" alt="fb">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <img src="/zupb/images/gplus.png" alt="g+">
                    </a>
                </li>
            </ul>
        </div>

        <div class="copyright mt-2">
            <div class="row">
                <div class="col d-none d-lg-block">
                    <p>&copy; 2017, <a href="http://www.zub.com.ua/" class="link-copyright">zub.com.ua</a></p>
                </div>
                <div class="col text-center text-lg-right">Сайт зроблено — <a href="https://reactlogic.com" class="link-copyright">«React Logic»</a></div>
            </div>
        </div>
    </div>
</footer><?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
