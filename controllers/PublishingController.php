<?php

namespace app\controllers;

use Yii;
use app\models\Publishing;
use app\models\Files;
use yii\web\UploadedFile;
use app\models\PublishingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use SoapClient;

class PublishingController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PublishingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()&&Yii::$app->user->can('admin'))
        {
            $files = new Files();
            $file = UploadedFile::getInstance($files, 'file');
            $file->name = $files->transliteration($file->name);
            $files->name = $file->name;
            $path = Yii::$app->params['uploadPath'].$file->name;

            $file->saveAs($path); // save file on server
            $files->saveFile([
                'name'=>$file->name,
                'path'=>Yii::$app->params['uploadPath'],
                'user_id'=>Yii::$app->user->identity->id,
                'auction_id'=>$model->id]);

            Yii::$app->session->setFlash('success', Yii::t('app', 'FileUploaded ID'));
            $model->setAttribute('status','4');
            $model->save(false);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionDoc($id)
    {
        // Выключаем WSDL кэширование
        ini_set ('soap.wsdl_cache_enabled', 0);

        // Создаём экземпляр объекта Soap и передаём ему свои учетные данные
        $soap = new SoapClient('https://api.livedocx.com/1.2/mailmerge.asmx?WSDL');
        $soap->LogIn(
            array(
                'username' => 'neiron',
                'password' => 'neiron22'
            )
        );

        // Путь к файлу шаблона
        $data = file_get_contents('../uploads/protocol_template.doc');

        // Установка расширения файла .doc и параметров кодирования
        $soap->SetLocalTemplate(
            array(
                'template' => base64_encode($data),
                'format'   => 'doc'
            )
        );

        // Задаём значения переменным
        $model = $this->findModel($id);

        $members = $this->getClients($model->id); $str_members=null; //var_dump($members); exit;

        foreach ($members as $n => $value)
        {
            $str_members = $str_members."заявка №".$value['id']." вiд ".$value['at_org'].", ";
        }

        $tradeLog = $this->getTradeLog($model->id); $str_log=null; //var_dump($tradeLog); exit;

        foreach ($tradeLog as $n => $value)
        {
            $str_log = $str_log.$value['date']." ".$value['comment']." ".$value['at_org']."
            ";
        }

        $fieldValues = array (
            'date'  => Yii::$app->formatter->asDatetime($model->date_start),
            'aukname'  => $model->name,
            'lot_num'   =>  $model->lot_num,
            'lot_name'  =>  $model->lotName,
            'price' =>  number_format($model->lotPrice,2)." ".$model->NDS,
            'proc'  =>  $model->lotStep,
            'last_price' => $model->last_price,
            'winner'    =>  $tradeLog['0']['at_org'],
            'clients'   =>  $str_members,
            'tradelog'   =>  $str_log,

        );
        // Передаём переменные в наш LiveDocx объект
        $soap->SetFieldValues(
            array (
                'fieldValues' => $this->assocArrayToArrayOfArrayOfString($fieldValues)
            )
        );

        // Формируем документ
        $soap->CreateDocument();
        $result = $soap->RetrieveDocument(
            array(
                'format' => 'doc'
            )
        );
        $doc = base64_decode($result->RetrieveDocumentResult);

        // Разрываем сессию с SOAP
        $soap->LogOut();

        // Отдаём вордовский файл
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        $fileName = "protocol.doc";
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/msword");
        header("Content-Transfer-Encoding: binary");
        echo $doc;
        die;

    }
    private function assocArrayToArrayOfArrayOfString ($assoc)
    {
        $arrayKeys   = array_keys($assoc);
        $arrayValues = array_values($assoc);
        return array ($arrayKeys, $arrayValues);
    }
    private function getTradeLog($id)
    {
        $log = Yii::$app->db->createCommand("
                                SELECT trade_logs.comment, trade_logs.date, user.at_org from trade_logs
                                LEFT JOIN user ON (trade_logs.user_id=user.id)
                                WHERE auk_id=:id ORDER by date DESC");
        $log->bindValue(':id', $id);
        $result = $log->queryAll();
        return $result; //$result['0']['at_org'];
    }
    private function getClients($id)
    {
        $clients =Yii::$app->db->createCommand("SELECT bidding.id, user.at_org from bidding
                                                LEFT JOIN user ON (bidding.user_id=user.id)
                                                WHERE bidding.auction_id=:id and bidding.status=1;");
        $clients->bindValue(':id', $id);
        $result = $clients->queryAll();
        return $result;

    }
    protected function findModel($id)
    {
        if (($model = Publishing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
