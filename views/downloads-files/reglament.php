<?php

use yii\helpers\Html;


$this->title = Yii::t('app','Reglament');

?>

<div class="container">
    <div class="row">
        <ul>
			<b><li><?= Html::a('Договір публічної Оферти','/oferta.pdf', ['target' => '_blank']);?></li>
			<li><?= Html::a ('Політика конфіденційності','/Політика конфіденційності.pdf',['target' => '_blank']);?></li>
			<li><?= Html::a ('Регламент роботи ЕТМ','/reglament.pdf', ['target' => '_blank']);?></li></b>
        </ul>
    </div>
</div>