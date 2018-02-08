<?php

use yii\widgets\ListView;

?>

<div class="row">
    <div class="social col-xs-12 text-right">
        <a target="_blank" href="https://twitter.com/bidding_time"><img src="/images/twitter.png" alt="twitter"></a>
        <a target="_blank" href="https://www.facebook.com/biddingtime"><img src="/images/facebook.png" alt="facebook"></a>
        <a target="_blank" href="https://plus.google.com/108825905034831465864"><img src="/images/google-plus.png" alt="google+"></a>
    </div>
</div>
<div class="row">
    <aside class="sidebar col-md-4">
        <div class="widget widget--gray">
            <!--Kurs.com.ua main-ukraine-->
            <div id='kurs-com-ua-informer-main-ukraine-300x130-blue-container'><a href="//old.kurs.com.ua/ua/informer" id="kurs-com-ua-informer-main-ukraine-300x130-blue" title="Курс валют информер Украина" rel="nofollow" target="_blank">Информер курса валют</a></div>
            <script type='text/javascript'>
                (function() {
                    var iframe = '<ifr' + 'ame src="//old.kurs.com.ua/ua/informer/inf2?color=blue" width="100%" height="130" frameborder="0" vspace="0" scrolling="no" hspace="0"></ifr' + 'ame>';
                    var container = document.getElementById('kurs-com-ua-informer-main-ukraine-300x130-blue');
                    container.parentNode.innerHTML = iframe;
                })();
            </script>
            <noscript><img src='//old.kurs.com.ua/static/images/informer/kurs.png' width='52' height='26' alt='kurs.com.ua: курс валют в Украине!' title='Курс валют' border='0' /></noscript>
            <!--//Kurs.com.ua main-ukraine-->
        </div>
        <div class="widget widget--gray">
            <!--Kurs.com.ua regional 385x370 Київ blue-->
            <div id='kurs-com-ua-informer-regional-385x370-15-kiev-blue-container'><a href="//old.kurs.com.ua/ua/informer" id="kurs-com-ua-informer-regional-385x370-15-kiev-blue" title="Курс валют информер Украина" rel="nofollow" target="_blank">Информер курса валют</a></div>
            <script type='text/javascript'>
                (function() {var iframe = '<ifr'+'ame src="//old.kurs.com.ua/ua/informer/regional2/15/908?color=blue" width="100%" height="370" frameborder="0" vspace="0" scrolling="no" hspace="0"></ifr'+'ame>';var container = document.getElementById('kurs-com-ua-informer-regional-385x370-15-kiev-blue');container.parentNode.innerHTML = iframe;})();
            </script>
            <noscript><img src='//old.kurs.com.ua/static/images/informer/kurs.png' width='52' height='26' alt='kurs.com.ua: курс валют в Украине!' title='Курс валют' border='0' /></noscript>
            <!--//Kurs.com.ua regional 385x370 Київ blue-->
        </div>
        <div class="widget widget--gray">
            <!--Kurs.com.ua forex 300x203 eur/usd blue-->
            <div id='kurs-com-ua-informer-forex-300x203-eur-usd-blue-container'>Курс валют предоставлен сайтом <a href="//old.kurs.com.ua/informer/grafiki" id="kurs-com-ua-informer-forex-300x203-eur-usd-blue" title="Курс валют информер Украина" rel="nofollow" target="_blank">Информер курса валют</a></div>
            <script type='text/javascript'>
                (function() {var iframe = '<ifr'+'ame src="//old.kurs.com.ua/informer/forex/eur/usd?color=blue" width="100%" height="203" frameborder="0" vspace="0" scrolling="no" hspace="0"></ifr'+'ame>';var container = document.getElementById('kurs-com-ua-informer-forex-300x203-eur-usd-blue');container.parentNode.innerHTML = iframe;})();
            </script>
            <noscript><img src='//old.kurs.com.ua/static/images/informer/kurs.png' width='52' height='26' alt='old.kurs.com.ua: курс валют в Украине!' title='Курс валют' border='0' /></noscript>
            <!--//Kurs.com.ua forex 300x203 eur/usd blue-->
        </div>
    </aside>
    <article class="col-md-8">
        <section class="widget orbit-slider">
            <header class="orbit-stripe">
                <p class="blogger-m text-uppercase">ТОРГИ - ІНВЕСТИЦІЇ - ФІНАНСИ</p>
            </header>
            <div id="featured">
                <img class="img-responsive" src="/images/slider-pic-1.jpg" alt="Link" />
                <img class="img-responsive" src="/images/slider-pic-2.jpg" alt="Ezio" rel="ezioCaption" />
                <img class="img-responsive" src="/images/slider-pic-3.jpg" alt="Master Chief" />
                <img class="img-responsive" src="/images/slider-pic-4.jpg" alt="Marcus Fenix" rel="marcusCaption" />
            </div>
            <!-- <span class="orbit-caption" id="ezioCaption">This is an <em>awesome caption</em> for Ezio. <strong>Note:</strong> This whole image is linked</span>
            <span class="orbit-caption" id="marcusCaption">This is an <em>awesome caption</em> for Marcus with a <a href="http://www.zurb.com/playground" target="_blank" style="color: #fff">link</a></span> -->
        </section>

        <!-- <h5 class="blogger-m text-uppercase">Публікації</h5>
        <div class="row">
            <div class="col-md-12">
                <section class="widget widget--lined publications">
                    <?= ListView::widget([
                        'dataProvider'=>$dataProvider ,
                        'itemView' => '_forms/_auctions',
                        'layout' => "{items}",
                        'itemOptions' => [
                            'class' => 'publications__item'
                        ]
                    ]) ?>

                    <a href="/public/index">переглянути всi публікації</a>
                </section>
            </div>
        </div>
        <hr> -->

        <div class="row">
            <div class="col-md-6">
                <div class="widget widget--gray widget--lined">
                    <h5 class="widget__title blogger text-uppercase">
                        <b>Підписка на новини</b>
                    </h5>
                    <form class="subscribe">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-subscribe" id="name" placeholder="Ваше ім'я">
                            <span style="background-image:url('./images/avatar.png')" class="input-icon"></span>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-subscribe" id="email" placeholder="E-mail">
                            <span style="background-image:url('./images/email.png')" class="input-icon"></span>
                        </div>
                        <button type="submit" class="btn btn-custom">підписатися >></button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="widget widget--gray widget--lined support">
                    <div class="row">
                        <div class="col-xs-12 support__item">
                            <img src="/images/fgv-logo-uk.png" alt="">
                            <a href="#">Фонд гарантування вкладів візичних осіб</a>
                        </div>
                        <div class="col-xs-12 support__item">
                            <img src="/images/NBU.png" alt="">
                            <a href="#">Національний Банк України</a>
                        </div>
                        <div class="col-xs-12 support__item">
                            <img src="/images/prozorro-logo-green.png" alt="">
                        </div>
                        <!-- <div class="col-xs-12 support__item">
                           <a href="https://preply.com"><img src="/images/logo_esya.png" alt=""</a>
                       </div> -->
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
