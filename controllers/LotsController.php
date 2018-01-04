<?php

namespace app\controllers;

use api\Documents;
use api\Items;
use app\traits\AjaxValidationTrait;
use Yii;
use app\models\Files;
use app\models\Lots;
use app\models\Messages;
use app\models\LotSearch;
use app\models\Auctions;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\helpers\Html;
use api\Classifications;

/**
 * LotsController implements the CRUD actions for Lots model.
 */
class LotsController extends Controller
{


    use AjaxValidationTrait;

    public $layout = 'user';

    public function init()
    {
        parent::init();
        // throw new NotFoundHttpException();
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-document' => ['POST'],
                    'delete-item' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['org', 'admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['member'],
                    ]
                ],
            ],
        ];
    }

    public function actionClassifications($code=''){
        Yii::$app->response->format = 'json';
        $data = [];
        foreach(Classifications::find()->where(['like', 'id', $code])->orWhere(['like', 'description', $code])->limit(20)->all() as $classification){
            $data[] = ['value' => $classification->id . ' - ' . $classification->description];
        }
        return $data;
    }

    public function actionIndex()
    {
        $searchModel = new LotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMy()
    {
        $searchModel = new LotSearch();
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, [
            'LotSearch[user_id]' => Yii::$app->user->id,
        ]));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lots model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember(['/lots/view', 'id' => $id]);
        if (Yii::$app->request->post())
        {
            $model = $this->findModel($id);
            $files = new Files();
            $file = UploadedFile::getInstance($files, 'file');
            $file->name = $files->transliteration($file->name);
            $files->name = $file->name;
            $path = Yii::$app->params['uploadPath'].$file->name;

            $file->saveAs($path); // save file on server
            $files->saveFile(['name'=>$file->name,'path'=>Yii::$app->params['uploadPath'],'user_id'=>Yii::$app->user->identity->id,'auction_id'=>$model->auction->id,'lot_id'=>$model->id]);

            Yii::$app->session->setFlash('success', Yii::t('app', 'FileUploaded ID'));
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCopy($id){
        $auction = $this->findModel($id);
        $data = $auction->attributes;
        $data['lot_lock'] = '0';
        unset($data['id']);
        $model = new Lots($data);

        $this->ajaxValidation($model);

        if($model->load(Yii::$app->request->post()) && $model->save()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Auction saved as draft'));
            return $this->redirect(['update', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Lots;
        $model->user_id = Yii::$app->user->id;

        $this->ajaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Auction saved as draft'));
            return $this->redirect(['/lots/update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelsItems' => (empty($modelsItems)) ? [new Items()] : $modelsItems
        ]);
    }

    public function actionUploadDocument($id){

        $lot = $this->findModel($id);
        if(!Yii::$app->user->can('org') || ($lot->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }

        $model = new Files();
        $model->lot_id = $id;
        if($model->load(Yii::$app->request->post()) && $model->upload()){
            $data = Yii::$app->apiUpload->upload($model->path . '/' . $model->name);
            // $id = preg_match('/KeyID\=(.*)\&/', $data['get_url'], $matches);
            // $data['data']['title'] = 'd-' . $matches[1] . '_' . $data['data']['title'];

            $apiDocument = new Documents();
            $apiDocument->load(array_merge(
                $data['data'],
                [
                    'documentOf' => 'auction',
                    'documentType' => $model->type,
                    'relatedItem' => $lot->id,
                    'lot_id' => $lot->id,
                    'language' => 'ua',
                    'file_id' => $model->id,
                    'id' => explode('?', $data['address'])[0],
                ]), '');
            if($lot->apiAuction){
                foreach($lot->documents as $document){
                    $document->updateAttributes(['relatedItem' => $lot->apiAuction->unique_id]);
                }
                $apiDocument->relatedItem = $lot->apiAuction->unique_id;
                Yii::$app->api->addAuctionDocument($apiDocument);
            }
            $apiDocument->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', '{type} succesfully uploaded', ['type' => $model->lotDocumentTypes()[$model->type]]));
        }
        return $this->redirect(['/lots/update', 'id' => $id]);
    }

    public function actionReupload($id, $document_id){
        if((false == ($model = $this->findModel($id))) or (false == ($old_document = Documents::findOne(['unique_id' => $document_id])))){
            throw new NotFoundHttpException();
        }
        $file = Files::findOne($old_document->file_id);
        if($model->user_id != Yii::$app->user->identity->id){
            throw new ForbiddenHttpException();
        }
        if($file->load(Yii::$app->request->post()) && $file->updateLotFile()){
            $lot = $file->lot;
            $data = Yii::$app->apiUpload->upload($file->path . $file->name);
            $apiDocument = new Documents();
            $documentData =[
                'documentOf' => 'auction',
                'documentType' => $file->type,
                'lot_id' => $lot->id,
                'language' => 'ua',
                'file_id' => $file->id,
                'id' => explode('?', $data['address'])[0],
            ];
            if($lot->apiAuction){
                $documentData['relatedItem'] = $lot->apiAuction->unique_id;
            }

            $apiDocument->load(array_merge(
                $data['data'], $documentData), '');
            $apiDocument->save(false);

            if(Yii::$app->api->replaceAuctionDocument($old_document, $apiDocument)){
                $file->document->delete();
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Document succesfully updated'));
            return $this->redirect(['/lots/update', 'id' => $file->lot_id]);
        }
        return $this->render('reupload', ['model' => $file]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->validate();
        if(!Yii::$app->user->can('org') || ($model->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }

        $this->ajaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->apiAuction){
                $model->updateAttributes(['vdr' => '']);
                $model->updateAttributes(['address' => '']);
                $model->updateAttributes(['passport' => '']);
            }
            if(!$model->items){
                Yii::$app->session->setFlash('danger', Yii::t('app', 'You must add the auction items'));
                return $this->redirect(['update', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully saved'));
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionEdit($id){
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('org') || ($model->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }
        // if(!$model->apiAuction or $model->apiAuction->isEnded){
        //     throw new ForbiddenHttpException();
        // }
        $model->scenario = 'edit';

        $this->ajaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->edit()) {
            $notes_org = Yii::t('app','Інформацію про аукціон успішно оновлено. ') .": " .
                Html::a(
                    Yii::t('app', 'View auction'),
                    Url::to([
                        '/lots/view',
                        'id' => $model->id,
                    ], true));
            Yii::createObject(Messages::className())->sendMessage($model->user_id, $notes_org, true);

            Yii::$app->session->setFlash('success', Yii::t('app', 'Succesfully saved'));
            return $this->refresh();
            // return $this->redirect(['/public/view', 'id' => $model->apiAuction->unique_id]);
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionAddItems($id){
        $lot = $this->findModel($id);
        if($lot->apiAuction){
            throw new ForbiddenHttpException(Yii::t('app', 'Auction is published. You cannot add items'));
        }
        $item = new Items();
        $item->auction_id = $id;
        if($item->load(Yii::$app->request->post()) && $item->save()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Auction item added'));
            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->render('items', ['model' => $item, 'lot' => $lot]);
    }

    public function actionDeleteItem($id){
        if(false == ($item = Items::findOne(['id' => $id]))){
            if(false == ($item = Items::findOne(['unique_id' => $id]))){
                throw new NotFoundHttpException();
            }
        }
        if($item->auction){
            throw new ForbiddenHttpException(Yii::t('app', 'Auction is published. You cannot delete items'));
        }
        if($item->lot->user_id != Yii::$app->user->id){
            throw new ForbiddenHttpException();
        }
        $lot_id = $item->auction_id;
        $item->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Item deleted'));
        return $this->redirect(['/lots/update', 'id' => $lot_id]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->user_id != Yii::$app->user->id){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot delete auction ' . $model->name));
        }

        if($model->lot_lock==0)
        {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Auction was deleted'));
            return $this->redirect(['index']);
        }
        else
        {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Auction "{name}" was already published',['name' => $model->name]));
            return $this->redirect(['index']);
        }
    }

    public function actionRemoveAll(){
        $ids = ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        foreach ($ids as $id){
            $res = $this->actionDelete($id);
        }
        return $this->redirect(['index']);
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);

        if(!$model->items /*|| !$model->documents */){
            if(!$model->items || !$model->documents){
                Yii::$app->session->setFlash('danger', Yii::t('app', 'You must upload the documents and add the items'));
            }
            return $this->redirect(['update', 'id' => $id]);
        }
        if(!$model->validate()){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Fix the errors in lot'));
            return $this->redirect(['update', 'id' => $id]);
        }

        if(!Yii::$app->user->can('org') || Yii::$app->user->id != $model->user_id){
            throw new ForbiddenHttpException();
        }

        if($model->lot_lock==0)
        {
            $insert = [
                'user_id'       => $model->user_id,
                'name'          =>  $model->name,
                'lot_id'        =>  $model->id,
                'lot_num'       =>  $model->num,
                'date_start'    =>  $model->auction_date,
                'bidding_date'  =>  $model->bidding_date,
                'type_id'       =>  '1',
                //'date_stop' =>  $date->modify('+1 day')->format('Y-m-d H:i:s'),
            ];
            if($model->publishAuction($insert)){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Auction succesfully published'));
            }
            else{
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Oops. something went wrong.'));
            }

            // lock lot
            $model->setAttribute('lot_lock','1');
            // save
            $model->save(false);
            $baseAuction = \api\Auctions::findOne(['baseAuction_id' => $model->id]);
            // send message to org
            $notes_org = Yii::t('app','AuctionCreatedOnLot ID') .": " .
                Html::a(
                    Yii::t('app', 'View auction'),
                    Url::to([
                        '/lots/view',
                        'id' => $model->id,
                    ], true));
            Yii::createObject(Messages::className())->sendMessage($model->user_id, $notes_org, true);

            Yii::$app->session->setFlash('success', Yii::t('app', 'AuctionCreated ID'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else
        {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'LotInAuction ID'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Lots model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lots the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lots::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
