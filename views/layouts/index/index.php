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
    <section class="access">
        <div class="container">
            <div class="container-in">
                <ul class="dropdown access_menu list-unstyled text-right">
                    <?php if(Yii::$app->user->isGuest): ?>
                        <li class="access_menu__item">
                            <a class="access_menu__link" href="/public"><?= Yii::t('app', 'Публікації'); ?></a>
                        </li>
                        <li class="access_menu__item">
                            <a class="access_menu__link" href="/registration/register"><?= Yii::t('app', 'Sign up'); ?></a>
                        </li>
                        <li id="dLabel" role="button" data-toggle="dropdown" data-target="#" class="access_menu__item clearfix">
                            <a class="access_menu__link access_menu__link--enter" href="#"><?= Yii::t('user', 'Sign in');?></a>
                            <div class="access_menu__button">
                                <span class="caret"></span>
                            </div>
                        </li>
                        <div class="access_login dropdown-menu" aria-labelledby="dLabel">
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['/user/security/login']),
                                'options' => [
                                    'class' => 'access_form clearfix'
                                ]
                            ]); ?>

                            <?= Html::activeInput('text', $model, 'login', ['class' => 'form-control access_form__field', 'id' => 'login', 'placeholder' => Yii::t('user', 'Login')])?>
                            <?= Html::activeInput('password', $model, 'password', ['class' => 'form-control access_form__field', 'id' => 'password' , 'placeholder' => Yii::t('user', 'Password')])?>

                            <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-default access_form__button'])?>

                            <div class="access_form__link text-right">
                                <a href="/user/forgot"><?= Yii::t('user', 'Forgot password?'); ?></a>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?= Html::button(Yii::t('app', 'Close'),['class' => 'btn btn-default access_close__button'])?>

                        </div>
                    <?php else: ?>
                        <?php if(Yii::$app->user->can('admin')): ?>
                            <li class="access_menu__item">
                                <?= Html::a(Yii::t('app', 'Лоти'), ['/lots'], ['id' => 'cabinet', 'class' => 'access_menu__link']); ?>
                            </li>
                        <?php elseif(Yii::$app->user->can('member')):?>
                            <li class="access_menu__item">
                                <?= Html::a(Yii::t('app', 'Мої заявки'), ['/bids'], ['id' => 'cabinet', 'class' => 'access_menu__link']); ?>
                            </li>
                        <?php elseif(Yii::$app->user->can('org')):?>
                            <li class="access_menu__item">
                                <?= Html::a(Yii::t('app', 'Мої лоти'), ['/lots'], ['id' => 'cabinet'], ['class' => 'access_menu__link']); ?>
                            </li>
                        <?php endif; ?>
                        <li class="access_menu__item">
                            <?= Html::a(Yii::t('app', 'Публікації'), ['/public'], ['class' => 'access_menu__link']); ?>
                        </li>
                        <li class="access_menu__item">
                            <?= Html::a(Yii::t('app', 'Профіль'), ['/settings/profile'], ['class' => 'access_menu__link']); ?>
                        </li>
                        <li class="access_menu__item">
                            <?= Html::a(Yii::t('app', 'Повідомлення') . $messagesLabel, ['/messages'], ['class' => 'access_menu__link']); ?>
                        </li>
                        <li class="access_menu__item">
                            <?= Html::a(Yii::t('app', 'Logout'), ['/user/security/logout'], [
                                'class' => 'access_menu__link',
                                'data' => ['method' => 'post']
                            ])?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </section>
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
                        <img class="hidden-xs hidden-sm" src="/images/prozorro-logo.png" alt="Логотип Прозорро">
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
        <nav class="navbar navbar-default menu">
            <div class="container-in">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <span class="glyphicon glyphicon-home"></span>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="main-menu">
                    <ul class="nav navbar-nav">
                        <?= $this->render('header') ?>
                    </ul>
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
