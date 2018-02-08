<?php


namespace app\controllers;


class ParticipantController extends \yii\web\Controller
{

	public function actionPravoVymogy(){
		return $this->render('pravo-vymogy');
	}
	
	public function actionMaino(){
		return $this->render('maino');
	}
	
	public function actionReglament(){
		return $this->render('reglament');
	}

}
