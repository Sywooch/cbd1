

<footer class="container-fluid footer purple-back">
    <img class="logo img-responsive" src="/css/dist/images/ums-logo2.jpg" alt="">
    <span class="copyright hidden-xs hidden-sm">&copy <?= date('Y') ?> Всі права захищені.</span>
    <address class="text-right">
        <a href="tel:+380442282914">
            <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span><span class="hidden-sm hidden-xs">(044)337-23-64, (068)257-38-98</span>
        </a>
        <a href="mailto:office@uisce.com.ua">
            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><span class="hidden-sm hidden-xs">office@uisce.com.ua</span>
        </a>
        <a href="#">
            <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span><span class="hidden-md hidden-sm hidden-xs">04071, м. Київ, вул. Костянтинівська, буд. 2А, 3-й поверх</span>
        </a>
    </address>
</footer>


<?php
$questions = \api\Questions::find()->where(['<', 'updated_at', time() - 86399])->andWhere(['answer' => ''])->all();