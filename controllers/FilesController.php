<?php

namespace app\controllers;

use Yii;
use app\models\Files;
use yii\web\NotFoundHttpException;

class FilesController extends \yii\web\Controller
{

    public function actionDownload($id)
    {

        $model = $this->findModel($id);

        $filename = $model->path . $model->name;

        if(file_exists($filename)){

    //Get file type and set it as Content Type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            header('Content-Type: ' . finfo_file($finfo, $filename));
            finfo_close($finfo);

    //Use Content-Disposition: attachment to specify the filename
            header('Content-Disposition: attachment; filename='.basename($filename));

    //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

    //Define file size
            header('Content-Length: ' . filesize($filename));

            ob_clean();
            flush();
            readfile($filename);
            exit;
        }

        Yii::$app->response->xSendFile($model->path . $model->name);
    }

    protected function findModel($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
