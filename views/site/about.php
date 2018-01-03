<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Регламент';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-globe"></span><strong> <?= Html::encode($this->title) ?></strong></div>
    <div class="panel-body">
		<div class="site-about">

				<div>
				<?php
	                echo $this->render('../../web/about_txt.html');
	                // echo $this->render('../../web/pb.html');
	            ?>
				</div>
				
				
		    
		</div>
	</div>
</div>