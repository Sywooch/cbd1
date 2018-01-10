<?php

namespace app\controllers;

use api\Auctions;
use api\Documents;
use Yii;
use api\Bids;
use app\models\BidsSearch;
use app\models\Lots;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Files;
use app\models\Messages;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * BidsController implements the CRUD actions for Bids model.
 */
class BidsController extends Controller
{

    public $layout = 'user';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'activate' => ['POST'],
                    'confirm-award' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BidsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id = null)
    {
        if(isset($_POST['rows'])){

        }else{

        }
        $model = $this->findModel($id);
        // if($model->user_id != Yii::$app->user->id){
        //     throw new ForbiddenHttpException();
        // }
        /*else*/if(Yii::$app->user->can('org') && $model->status == 'draft1'){
        throw new ForbiddenHttpException();
    }
        if(($model->user_id === Yii::$app->user->id) && ($model->id && !$model->participationUrl) && $model->status == 'active'){
            $data = Yii::$app->api->request('auctions/' . $model->lot->apiAuction->id . '/bids/' . $model->id . '?acc_token=' . $model->access_token);
            $model->load($data, '');
            $model->save(false);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionAccept($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->user->can('member'))
        {
            return $this->redirect(['view', 'id' => $id]);
        }
        else
        {
            $model->updateAttributes(['accepted' => '1']);

            // if(!$model->isPublished){
            //     Yii::$app->api->createBid($model);
            // }

            $messages = new Messages();
            $messages->sendMessage(
                $model->user_id,
                Yii::t('app', 'Your bid accepted') . '. ' . Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $model->unique_id], true)),
                true);

            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionDecline($id)
    {
        if(!Yii::$app->user->can('admin'))
        {
            return $this->redirect(['view', 'id' => $id]);
        }
        $model = $this->findModel($id);

        $messages = new Messages();
        $note = Yii::$app->request->post('Messages')['notes'];
        $notes = 'Ваша заявка №'.$model->unique_id.' містить наступні помилки: "'.$note . '."<br>
        Щоб їх виправити, перейдіть за посиланням: ' . Html::a(Url::to(['/bids/update', 'id' => $model->unique_id], true), Url::to(['/bids/update', 'id' => $model->unique_id], true));
        $messages->sendMessage($model->user_id,$notes,true);

        $model->reason = $note;
        $model->accepted = 0;
        $model->save();

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionCancel($id){
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('member') || ($model->user_id != Yii::$app->user->id) || !$model->award || $model->award->status != 'pending.waiting'){
            throw new ForbiddenHttpException();
        }
        if(Yii::$app->api->cancelBid($model)){
            $text = Yii::t('app', 'Ви відмінили свою ставку на аукціон. {link}',
                [
                    'link' => Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $id], true)),
                ]);
            Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your bid was cancelled'));
        }
        else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Oops, something was wrong'));
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionCreate($id)
    {
        if(!Yii::$app->user->can('member')){
            throw new ForbiddenHttpException;
        }
        /** @var $auction Auctions*/
        if (false == ($auction = Auctions::findOne(['unique_id' => $id])))
        {
            throw new NotFoundHttpException('The auction not exist.');
        }
        if(false == ($lot = $auction->lot)){
            $auction->createLot();
            $lot = $auction->lot;
        }

        if($lot->apiAuction->isEnded){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Auction is ended. You cannot create bids'));
            return $this->redirect(['view', 'id' => $lot->id]);
        }
        if($lot->user_id == Yii::$app->user->id){
            throw new ForbiddenHttpException();
        }

        if($model = Bids::findOne(['user_id'=>Yii::$app->user->id, 'lot_id' => $lot->id]))
        {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'You already created bid for this auction'));
            return $this->redirect(['view', 'id' => $model->unique_id]);
        }

        $model = new Bids();
        $model->setScenario($auction->procurementMethodType);
        $model->load(['user_id' => Yii::$app->user->id, 'lot_id' => $lot->id, 'auction_id' => $lot->apiAuction->unique_id], '');

        if($model->load(Yii::$app->request->post()) && $model->validate()){
//            Yii::createObject(Messages::className())
//                ->sendMessage(
//                    $model->lot->user_id,
//                    Yii::t('app', 'There is new bid to your auction') . '. ' . Html::a(Yii::t('app', 'View'), ['/bids/view', 'id' => $model->unique_id]),
//                    true
//                );
            $model->save(false);
            if(getenv('TRICK')){
                $adminText = Yii::t('app', 'Створено нову заявку на участь в аукціоні. {link}', [
                    'link' => Html::a(Yii::t('app', 'View'), ['/bids/view', 'id' => $model->unique_id]),
                ]);
                Yii::createObject(Messages::className())->sendMessage(6, $adminText, true);
            }

            Yii::$app->api->createBid($model);

            if($model->lot->procurementMethodType == 'dgfFinancialAssets'){
                // Yii::$app->session->setFlash('success', Yii::t('app', 'You must upload the financial license'));
            }
            else{
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your bid successfully created'));
            }
            return $this->redirect(['view', 'id' => $model->unique_id]);

        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionCopy($id){
        if(false == ($auction = Auctions::findOne($id))){
            throw new NotFoundHttpException();
        }
        if(!$auction->lot){
            $lot = new Lots(array_merge($auction->attributes, ['user_id' => '0']));
            $lot->save(false);
            $auction->updateAttributes(['baseAuction_id' => $lot->id]);
        }
        $bid = new Bids(['user_id' => Yii::$app->user->id, 'lot_id' => $lot->id]);
        $bid->save();
    }

    public function actionUploadDocument($id){
        $model = $this->findModel($id);
        $file = new Files([
            'user_id' => Yii::$app->user->id,
            'bid_id' => $model->unique_id,
        ]);

        if($file->load(Yii::$app->request->post()) && $file->uploadBidFile()){

            $data = Yii::$app->apiUpload->upload($file->path . $file->name);

            $apiDocument = new Documents();
            $apiDocument->load(array_merge(
                $data['data'],
                [
                    'documentOf' => 'bid',
                    'documentType' => $file->type,
                    'relatedItem' => $model->unique_id,
                    'language' => 'ua',
                    'file_id' => $file->id,
                    // 'id' => explode('?', $data['address'])[0],
                ]), '');

            $apiDocument->save(false);

            Yii::$app->api->registerBidDocument($model, $apiDocument);

            $text = Yii::t('app', 'Заявка на участь в аукціоні "{name}" оновлена. {link}', ['name' => $model->apiAuction->title, 'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $model->unique_id], true))]);
            Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
            if(getenv('TRICK')){
                Yii::createObject(Messages::className())->sendMessage(6, $text, true);
            }

            if(($file->type == 'financialLicense') && (Yii::$app->user->identity->profile->org_type == 'entity')){
                Yii::$app->session->setFlash('success', Yii::t('app', '{type} succesfully uploaded', ['type' => Yii::t('app', 'Підписане повідомлення про те, що ви не є боржником та/або поручителем за даним кредитним договором')]));
            }
            else{
                Yii::$app->session->setFlash('success', Yii::t('app', '{type} succesfully uploaded', ['type' => $file->bidDocumentTypes()[$file->type]]));
            }

        }
        return $this->redirect(['view', 'id' => $id]);

    }

    public function actionReuploadDocument($id, $document_id){
        $model = $this->findModel($id);
        $old_document = $this->findDocument($id);
        $file = new Files([
            'user_id' => Yii::$app->user->id,
            'bid_id' => $model->unique_id,
        ]);

        if($file->load(Yii::$app->request->post()) && $file->uploadBidFile()){

            $data = Yii::$app->apiUpload->upload($file->path . $file->name);

            $api_document = new Documents();
            $api_document->load(array_merge(
                $data['data'],
                [
                    'documentOf' => 'bid',
                    'documentType' => $file->type,
                    'relatedItem' => $model->unique_id,
                    'language' => 'ua',
                    'file_id' => $file->id,
                    // 'id' => explode('?', $data['address'])[0],
                ]), '');

            $api_document->save(false);

            Yii::$app->api->reuploadBidDocument($model, $old_document, $api_document);

            Yii::$app->session->setFlash('success', Yii::t('app', '{type} succesfully uploaded', ['type' => $file->bidDocumentTypes()[$file->type]]));
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('reupload', ['model' => $model, 'document' => $old_document]);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->lot){
            $model->setScenario($model->lot->procurementMethodType);
        }
        if(!Yii::$app->user->can('member') || ($model->user_id != Yii::$app->user->id) || ($model->apiAuction && $model->apiAuction->isEnded)){
            throw new ForbiddenHttpException;
        }
        $file = new Files(['user_id' => Yii::$app->user->id, 'bid_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save(false);
            if(strtotime($model->lot->bidding_date_end) < time()) {
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Bidding date end'));
                return $this->redirect(['view', 'id' => $id]);
            }

            if(Yii::$app->api->updateBid($model)){
                $text = Yii::t('app', 'Заявка на участь в аукціоні "{name}" оновлена. {link}', ['name' => $model->apiAuction->title, 'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $model->unique_id], true))]);
                Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
                Yii::createObject(Messages::className())->sendMessage(6, $text, true);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Bid succesfully updated. Waiting for accepting by admin'));

                return $this->redirect(['view', 'id' => $id]);
            }
            else{
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Something went wrong'));
                return $this->redirect(['/bids/view', 'id' => $id]);
            }
        }
        else {
            return $this->render('update', [
                'model' => $model,
                'file'  => $file,
            ]);
        }
    }

    public function actionActivate($id){
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('member') or ($model->user_id != Yii::$app->user->id)){
            throw new ForbiddenHttpException();
        }
        if($model->activate()){
            Yii::createObject(Messages::className())
                ->sendMessage(
                    $model->lot->user_id,
                    Yii::t('app', 'There is new bid to your auction') . '. ' . Html::a(Yii::t('app', 'View'), ['/bids/view', 'id' => $model->unique_id]),
                    true
                );
            Yii::createObject(Messages::className())
                ->sendMessage(
                    $model->user_id,
                    Yii::t('app', 'Заявка на участь в аукціоні "{name}" створена. {link}',
                        [
                            'name' => $model->apiAuction->title,
                            'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $model->unique_id], true))
                        ]),
                    true
                );
            Yii::createObject(Messages::className())
                ->sendMessage(
                    $model->user_id,
                    Yii::t('app', 'Ваша заявка на участь в аукціоні "{name}" прийнята організатором аукціону. Вам буде надіслано повідомлення с посиланням для участі в аукціоні.',
                        [
                            'name' => $model->apiAuction->title,
                            'link' => Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $model->unique_id], true))
                        ]),
                    true
                );
            Yii::$app->session->setFlash('success', Yii::t('app', 'Bid status changed to {status}', ['status' => $model->statusName]));

            if($model->apiAuction->procurementMethodType == 'dgfInsider'){
                Yii::createObject(Messages::className())->sendMessage(
                    $model->user_id,
                    Yii::t('app', 'Аукціон "{auction}" розпочався. Ви можете взяти участь, перейшовши за посиланням: {link}', [
                        'auction' => $model->apiAuction->title,
                        'link' => Html::a(Yii::t('app', 'перейти'), $model->participationUrl),
                    ]),
                    true
                );
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot activate bid'));
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionUploadProtocol($id){
        $model = $this->findModel($id);
        $file = new Files([
            'user_id' => Yii::$app->user->id,
            'bid_id' => $id,
            'type' => 'auctionProtocol',
        ]);
        if(Yii::$app->request->isPost && $file->uploadAuctionProtocol($id)){
            if(Yii::$app->user->can('member')){
                Yii::createObject(Messages::className())
                    ->sendMessage(
                        $model->lot->user_id,
                        Yii::t('app', 'Користувач завантажив протокол аукціону') . '. ' . Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $model->unique_id], true)),
                        true
                    );
                Yii::createObject(Messages::className())
                    ->sendMessage(
                        $model->user_id,
                        Yii::t('app', 'Протокол торгів успішно завантажений. Очікується підтвердження протоколу торгів організатором аукціону') . '. ' . Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $model->unique_id], true)),
                        true
                    );
            }
            elseif(Yii::$app->user->can('org')){
                Yii::$app->api->confirmAuctionProtocol($model);
                $text = Yii::t('app', 'Організатор аукціону завантажив та підтвердив протокол аукціону. {link}', [
                    'link' => Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $id], true)),
                ]);
                Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Protocol has been confirmed'));
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Auction protocol successfully uploaded'));
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('upload-protocol', ['model' => $file]);
    }

    public function actionConfirmProtocol($id){
        $bid = $this->findModel($id);
        // if(!Yii::$app->user->can('org') || !$bid->lot || $bid->lot->user_id != Yii::$app->user->id){
        //     throw new ForbiddenHttpException();
        // }
        if(Yii::$app->api->confirmAuctionProtocol($bid)){
            $text = Yii::t('app', 'Організатор аукціону підтвердив укладення контракту. {link}', [
                'link' => Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $id], true)),
            ]);
            Yii::createObject(Messages::className())->sendMessage($bid->user_id, $text, true);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Protocol has been confirmed'));
        }
        else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'something went wrong'));
        }
        return $this->redirect(['/bids/view', 'id' => $id]);
    }

    public function actionConfirmAward($id){
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('org') || $model->apiAuction->lot->user_id != Yii::$app->user->id || !$model->award){
            throw new ForbiddenHttpException();
        }
        if($model->award->status == 'active'){
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Award already qualified'));
        }
        if(Yii::$app->api->confirmAward($model->award)){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Award has been confirmed'));

            $messages = new Messages();
            $messages->sendMessage(
                $model->user_id,
                Yii::t('app', 'You are the winner!') . '. ' . Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $model->unique_id], true)),
                true);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDeclineAward($id){
        $model = $this->findModel($id);
        if(!Yii::$app->user->can('org') || !$model->award){
            throw new ForbiddenHttpException();
        }
        $file = new Files([
            'user_id' => Yii::$app->user->id,
            'bid_id' => $id,
        ]);
        $award = $model->award;
        $award->setScenario('disqualify');

        if(Yii::$app->request->isPost){
            if(
                false != ($document = $file->uploadDisqualificationDocument($model))
                && $award->load(Yii::$app->request->post()) && $award->disqualify($document)
            ){
                $text = Yii::t('app', 'Ваша ставка була дискваліфікована. {link}', [
                    'link' => Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $id], true)),
                ]);
                Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Bid is disqualified'));
                return $this->redirect(['view', 'id' => $model->unique_id]);
            }
        }
        return $this->render('decline-award', ['model' => $award, 'file' => $file]);
    }

    public function actionUploadContract($id){
        $model = $this->findModel($id);
        $files = new Files();
        $files->load(Yii::$app->request->post());
        if(!Yii::$app->user->can('org') || ($model->lot && $model->lot->user_id != Yii::$app->user->id) || ($model->apiAuction && $model->apiAuction->isEnded)){
            // throw new ForbiddenHttpException();
        }
        if(!empty($_FILES)){
            if($files->uploadContractDocument($model)){
                Yii::$app->session->setFlash('success', Yii::t('app', 'Contract successfully uploaded'));
                return $this->redirect(['view', 'id' => $id]);
            }
            else{
                Yii::$app->session->setFlash('danger', Yii::t('app', 'Something went wrong'));
                return $this->refresh();
            }
        }
        return $this->render('upload-contract', ['model' => $files]);
    }

    public function actionConfirmContract($id, $date = false){
        $bid = $this->findModel($id);
        if(!Yii::$app->user->can('org') || ($bid->lot && ($bid->lot->user_id != Yii::$app->user->id))){
            // throw new ForbiddenHttpException();
        }
        $contract = $bid->contract;
        $contract->scenario = 'confirm';
        $this->performAjaxValidation($contract);

        if(Yii::$app->api->confirmContract($bid, $date)){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Contract is published successfully'));

            $messages = new Messages();
            $messages->sendMessage(
                $bid->user_id,
                Yii::t('app', 'Your contract was signed and published') . '. ' . Html::a(Yii::t('app', 'View'), Url::to(['/bids/view', 'id' => $bid->unique_id], true)),
                true);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    protected function performAjaxValidation($model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->get())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            \Yii::$app->response->data   = ActiveForm::validate($model);
            \Yii::$app->response->send();
            \Yii::$app->end();
        }
    }

    public function actionDelete($id){
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('member') or ($model->user_id != Yii::$app->user->id)){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot delete item on auction '). $model->apiAuction->title);
        }

        if(strtotime($model->lot->apiAuction->tenderPeriod_endDate) < time()){
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot delete item on auction ') . $model->apiAuction->title);
        }
        elseif(Yii::$app->api->deleteBid($model)){
            $text = Yii::t('app', 'Ви видалили свою ставку. {link}', [
                'link' => Html::a(Yii::t('app', 'Переглянути мої заявки'), Url::to(['/bids'], true))
            ]);
            Yii::createObject(Messages::className())->sendMessage($model->user_id, $text, true);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Bid succesfully removed'));
            return $this->redirect(['/bids']);
        }
        else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Cannot delete item on auction ') . $model->apiAuction->title);
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionRemoveAll(){
        $ids = ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        foreach ($ids as $id){
            $res = $this->actionDelete($id);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Bids::findOne(['unique_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findDocument($id)
    {
        if (($model = Documents::findOne(['unique_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
