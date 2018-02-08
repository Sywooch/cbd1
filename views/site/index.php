<?php

/* @var $this yii\web\View */
use yii\widgets\ListView;
use app\models\Lots;
use api\Auctions;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app','ЗУПБ | Земельна універсальна промислова біржа');

$this->registerJs(<<<JS
    $('#index-form *').on('change', function(){
       $('#index-form').submit();
    });
JS

);

if(!$searchModel->isClear()){
    $this->registerJs(<<<JS

        $('html, body').animate({
            scrollTop: $("#index-form").offset().top
        }, 0);

JS

    );

}
?>
<main class="site-content">
    <section class="jumbotron">
        <div class="overlay"></div>
        <div class="container jumbotron-content text-center text-lg-left">
            <div class="row justify-content-between">
                <div class="col-lg-8">
                    <p class="jumbotron-title">Земельна універсальна <br>промислова біржа</p>
                    <p class="jumbotron-subtitle">Продаж майна банків, що ліквідуються</p>
                </div>
            </div>
        </div>
    </section>
    <section class="publications">
        <div class="container">
            <div class="row">
                <div class="col-12 publications-search publications-overlapped">
                    <?php $form = ActiveForm::begin([
                        'method' => 'get',
                        'action' => ['/'],
                        'id' => 'index-form'
                    ])?>

                    <div class="input-group mb-2">
                        <?= Html::activeInput('text', $searchModel, 'main_search',
                            ['class' => 'form-control', 'placeholder'=>'Назва або ідентифікатор аукціону'])?>
                        <span class="input-group-btn">
                          <?= Html::submitButton(Html::img('/images/icon-search.png'), ['class' => 'btn btn-primary'])?>
                      </span>
                    </div>
                    <input class="link-secondary text-light p-0" data-toggle="collapse" data-target="#expandedSearch" type="button" value="Розширений пошук">
                    <img src="/images/icon-plus.png" alt="">
                    <div class="collapse mt-3 <?= $searchModel->isClear() ? '' : 'show'?>" id="expandedSearch">
                        <div class="row no-gutters">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-lg-3 mb-0">
                                        <label for="search-filter-status">Стан торгів</label>
                                        <?=Html::activeDropDownList($searchModel, 'status',
                                            array_merge(['' => Yii::t('app' ,'Статус')], (new Auctions())->statusNames),
                                            [
                                                'class' => 'form-control',
                                                'id' => 'search-filter-status'
                                            ]) ?>
                                    </div>
                                    <div class="form-group col-md-6 col-lg-3 mb-0">
                                        <label for="search-filter-type">Тип аукціону</label>
                                        <?=Html::activeDropDownList($searchModel, 'type',
                                            array_merge(['' => Yii::t('app' ,'Тип аукціону')], Lots::$procurementMethodTypes),
                                            [
                                                'class' => 'form-control',
                                                'id' => 'search-filter-type'
                                            ]) ?>
                                    </div>


                                    <div class="form-group col-md-6 col-lg-3 mb-0">
                                        <label for="search-filter-type">CAV</label>
                                        <?= Html::activeInput('text', $searchModel, 'cav',
                                            ['class' => 'form-control', 'placeholder'=>'CAV', 'id' =>'search-filter-region'])?>
                                    </div>

                                    <div class="form-group col-md-6 col-lg-3 mb-0">
                                        <label for="search-filter-admin">Організатор торгів</label>

                                        <?= Html::activeInput('text', $searchModel, 'org_name',
                                            ['class' => 'form-control', 'placeholder'=>'Організатор', 'id' =>'search-filter-region'])?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row no-gutters">
                            <div class="col mt-3">
                                <a class="link-secondary text-light" href="/" id="reset-btn" name="search-clear">Очистити фільтр</a>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end();?>
                </div>

                <?= ListView::widget([
                    'dataProvider'=>$dataProvider ,
                    'itemView' => '@app/views/public/_forms/_auctions',
                    'layout' => "{items}",
                ]) ?>
                <a class="btn btn-primary btn-block mt-4" href="/public/index">Дивитись всi публікації <img class="ml-2" src="/images/arrow-white.png" alt=""></a>
            </div>
        </div>
    </section>
    <section class="how-it-works">
        <div class="container">
            <h2 class="how-it-works-title text-center text-sm-left">Як це працює</h2>
            <div class="row no-gutters">
                <div class="col-lg-8">
                    <div class="how-it-works-list row no-gutters font-weight-bold">
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">1. </span>Оголошення</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Замовник публікує оголошення, яке містить опис активу (майна), початкову ціну, умови участі, розмір гарантійного внеску та іншу інформацію.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">2. </span>Реєстрація</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Умовою участі в електронних торгах є проходження процедури ідентифікації. Зареєстрований користувач отримує доступ до особистого кабінету та балансу.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">3. </span>Заява</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Заява містить закриту цінову пропозицію (не менше початкової ціни лота). Подання заяви передбачає автоматичне списання (блокування) суми гарантійного внеску з балансу користувача.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">4. </span>Аукціон</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Відбувається за умови участі 2 і більше учасників. Проводиться у формі анонімних торгів — доступні лише порядкові номери учасників та їх кількість.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">5. </span>Кваліфікація</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Переможцем аукціону визнається учасник, що подав найвищу цінову пропозицію. У разі невиконання переможцем умов продажу — він дискваліфікується.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 how-it-works-list-item">
                            <div class="row">
                                <div class="col-md-3 how-it-works-list-title">
                                    <p><span style="color:#008AAB">6. </span>Договор</p>
                                </div>
                                <div class="col-md-9 how-it-works-list-description">
                                    <p>Договір укладається за межами майданчика протягом 20 робочих днів з дня формування протоколу торгів. Переможець сплачує <a class="link-primary" href="#">винагороду</a> організатору з суми гарантійного внеску.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <aside class="d-none d-lg-block col-lg-4">
                    <div class="register-block text-center text-lg-left">
                        <h3>Учасникам</h3>
                        <p class="mb-4">Купуйте майно банків вигідно.<br> Доступ до всіх аукціонів</p>
                        <a href="#" class="btn btn-warning btn-block">Реєстрація учасника</a>
                        <h3>Замовникам</h3>
                        <p class="mb-4">Продавайте швидко.<br> Не втрачайте вартість активів</p>
                        <a href="#" class="btn btn-primary btn-block">Реєстрація замовника</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <section class="statistics">
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-4 col-lg-3">
                    <p class="text-center text-md-left"><span class="statistics-numbers">100</span> млн. грн.<br> щоденна втрата вартості непроданих активів
                    </p>
                </div>
                <div class="col-md-4 col-lg-3">
                    <p class="text-center text-md-left"><span class="statistics-numbers">100</span> млрд. грн.<br> оціночна вартість<br>майна банків
                    </p>
                </div>
                <div class="col-md-4 col-lg-3">
                    <p class="text-center text-md-left"><span class="statistics-numbers">65</span> банків<br> в стадії ліквідації
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="latest-news">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-sm-8 latest-news-title">
                    <h2 class="text-left mb-0">Останні новини</h2>
                </div>
                <div class="col-sm-4 text-sm-right mt-2">
                    <a href="#" class="btn btn-outline-primary mb-3 d-none d-sm-inline-block">Всі новини</a>
                </div>
            </div>
            <p class="d-sm-none text-center">
                <a href="/" class="btn btn-outline-primary btn-lg btn-block mb-4">Всі новини</a>
            </p>
        </div>
    </section>
    <section class="about-us">
        <div class="container">
            <h2 class="about-us-title mb-4">Про нас</h2>
            <div class="row align-items-center no-gutters">
                <div class="col-lg-8 order-2 order-sm-1">
                    <p class="pr-4 mb-4">Перерозподіл бюджету, не змінюючи концепції, викладеної вище, свідомо концентрує споживчий медіамікс, розширюючи частку ринку. Суспільство споживання регулярно індукує емпіричний виставковий стенд. Стратегічне планування відштовхує суспільний
                        медійний канал. Виробництво спотворює комплексний рекламний блок. Медіамікс відштовхує бюджет на розміщення. Правда, фахівці відзначають, що портрет споживача синхронізує колективний поведінковий таргетинг.</p>
                    <a href="#" class="btn btn-outline-primary mb-3">Детальніше</a>
                </div>
                <div class="col-lg-4 order-1 order-sm-2">
                    <img class="img-fluid mb-3" src="images/io-centers-2673325_960_720.jpg" alt="">
                </div>
            </div>
        </div>
    </section>
</main>