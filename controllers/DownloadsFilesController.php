<?php 
namespace app\controllers;


use yii\web\Controller;
use yii\web\NotFoundHttpException;


class DownloadsFilesController extends Controller{

	public function actionRules(){
		return $this->render('rules');
	}

	public function actionExamples(){
		return $this->render('examples');
	}

	public function actionReglament(){
		return $this->render('reglament');
	}

}
?>