<?php

use yii\helpers\Html;


$this->title = Yii::t('app','Examples');

?>

<div class="container">
    <div class="row">
        <ul>
			<!-- <b><li><?= Html::a('Заява-на-участь-в-аукціоні-конкурсі ФДМУ','/Заява-на-участь-в-аукціоні-конкурсі ФДМУ.doc', ['target' => '_blank']);?></li> -->
			<li><?= Html::a ('Заявка на участь від ФО','/Заявка на участь від ФО.doc',['target' => '_blank']);?></li>
			<li><?= Html::a ('Заявка на участь від ЮО','/Заявка на участь від ЮО.doc',['target' => '_blank']);?></li>
			<!-- <li><?= Html::a ('Заява на аукціоні з продажу майна банкрутів','/Заява на аукціоні з продажу майна банкрутів.doc', ['target' => '_blank']);?></li></b> -->
        </ul>
    </div>
</div>