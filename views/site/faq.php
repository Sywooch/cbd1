<?php
/**
 * Created by PhpStorm.
 * User: neiron
 * Date: 04.12.15
 * Time: 13:56
 */

use yii\helpers\Html;

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading"><span class="glyphicon glyphicon-question-sign"></span><strong> <?= Html::encode($this->title) ?></strong></div>
    <div class="panel-body">
		<div class="site-about">

				<div>

				<?php
	                // echo $this->render('../../web/man.html');
	                echo $this->render('../../web/faq.html');
	                // echo $this->render('../../web/pb.html');
	            ?>
				</div>
				
				
		    
		</div>
	</div>
</div>