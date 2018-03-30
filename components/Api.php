<?php
/**
 * Created by PhpStorm.
 * User: slava
 * Date: 10.01.17
 * Time: 9:20
 */

namespace app\components;

use api\Auctions;
use api\Auctions as ApiAuctions;
use api\Awards;
use api\Bids;
use api\Cancellations;
use api\Documents;
use api\Questions;
use app\helpers\Date;
use app\models\Messages;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\httpclient\Client;

/**
 * @property integer $count
 * @property string $url
 * @property string $path
 * @property string $apiKey
 * @property string $fullPath
 * @property string $fullPublicPath
 **/
class Api extends Component
{

    public $url;
    public $public_url;
    public $path;
    public $apiKey;

    public $count = 0;

    public function getFullPath(){
        return $this->url . $this->path;
    }

    public function getFullPublicPath(){
        return $this->public_url . $this->path;
    }

    public function createBid(Bids $bid){
        $data = $this->request('auctions/' . $bid->apiAuction->id . '/bids', 'POST', ['data' => $bid->toArray()]);
        if(!isset($data['data'])) {
            $bid->delete();
            DMF($data);
        }
        $bid->load($data, '');
        $bid->access_token = isset($data['access']) ? $data['access']['token'] : NULL;
        return $bid->save(false);
    }

    public function request($address, $method = 'GET', $data = [], $additionalHeaders = [], $ap = 'main'){
        $client = new Client(['baseUrl' => ($ap == 'main' ? $this->fullPath : $this->fullPublicPath)]);
        
        $request = $client->createRequest()
            ->setMethod($method)
            ->setUrl($address)
            ->setHeaders(array_merge(
                $additionalHeaders,
                ['Authorization' => 'Basic ' . base64_encode($this->apiKey . ':')]
            ))
            ->setFormat(Client::FORMAT_JSON)
            ->setData($data);
        $response = $client->send($request);
        if($response->getStatusCode() == '412') {
            $request->setCookies([
                $response->cookies->get('AWSELB'),
                $response->cookies->get('SERVER_ID'),
            ]);
            $response = $client->send($request);
            return $response->getData();
        }
        return $response->getData();

    }

    public function updateBid(Bids $bid){
        $data = $this->request(
            'auctions/' . $bid->apiAuction->id . '/bids/' . $bid->id . '?acc_token=' . $bid->access_token,
            'PATCH',
            ['data' => $bid->toArray()]
        );
        if(isset($data['errors'])) {
            DMF($data);
        }

        $bid->load($data, '');
        return $bid->save(false);
    }

    public function registerBidDocument(Bids $bid, Documents $document){
        $documentData = $document->toArray();
        unset($documentData['documentType']);
        $data = $this->request('auctions/' . $bid->apiAuction->id . '/bids/' . $bid->id . '/documents?acc_token=' . $bid->access_token,
            'POST',
            ['data' => $documentData]);

        if(!isset($data['data'])) {
            var_dump($document->toArray());
            DMF($data);
        }

        return $document->updateAttributes([
            'id' => $data['data']['id'],
            'url' => $data['data']['url'],
        ]);
    }

    public function reuploadBidDocument(Bids $bid, Documents $old_document, Documents $document){

        $data = $this->request('auctions/' . $bid->apiAuction->id . '/bids/' . $bid->id . '/documents/' . $old_document->id . '?acc_token=' . $bid->access_token,
            'PATCH',
            ['data' => $document->toArray()]);

        if(!isset($data['data'])) {
            DMF($data);
        }

        return $document->updateAttributes([
                'id' => $data['data']['id'],
                'url' => $data['data']['url'],
            ]) && $old_document->delete();
    }

    public function activateBid(Bids $bid){
        $data = $this->request(
            'auctions/' . $bid->lot->apiAuction->id . '/bids/' . $bid->id . '?acc_token=' . $bid->access_token,
            'PATCH',
            [
                'data' => [
                    'status' => 'active',
                ],
            ]);
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }
        return $bid->load($data, '') && $bid->save(false);
    }

    public function deleteBid(Bids $bid){
        $data = $this->request(
            'auctions/' . $bid->lot->apiAuction->id . '/bids/' . $bid->id . '?acc_token=' . $bid->access_token,
            'DELETE',
            []);
        if(isset($data['data'])) {
            return $bid->delete();
        }
        return false;
    }

    public function createAuction(ApiAuctions $auction){

        $auctionData = $auction->toArray();

        if(YII_DEBUG) {
            $auctionData['procurementMethodDetails'] = 'quick, accelerator=1440';
            //$auctionData['submissionMethodDetails'] = 'quick(mode:no-auction)';
            $auctionData['submissionMethodDetails'] = 'quick';
        }

        $data = $this->request('auctions', 'POST', ['data' => $auctionData]);

        if(!isset($data['data']) || ((isset($data['status']) && $data['status'] == 'error'))) {
            var_dump($auction->toArray());
            $auction->delete();
            DMF($data);
        }

        /** @var Documents $document * */
        foreach($auction->documents as $document) {
            $this->request('auctions/' . $data['data']['id'] . '/documents?acc_token=' . $data['access']['token'],
                'POST',
                ["data" => $document->toArray()]);
        }

        if($auction->procurementMethodType == "dgfFinancialAssets" && $auction->lot->vdr) {
            $document = new Documents([
                'relatedItem' => $auction->unique_id,
                'documentOf' => "auction",
            ]);

            $vdr = $this->request('auctions/' . $data['data']['id'] . '/documents?acc_token=' . $data['access']['token'],
                'POST',
                ["data" => [
                    "url" => $auction->lot->vdr,
                    "title" => Yii::t("app", "VDR for auction lot"),
                    "documentType" => "virtualDataRoom",
                ]]);
            $document->load($vdr, '');
            $document->documentOf = "auction";
            $document->save(false);
        }
        if($auction->lot->address) {
            $document = new Documents([
                'relatedItem' => $auction->unique_id,
                'documentOf' => "auction",
                "url" => $auction->lot->address,
                "title" => Yii::t("app", "The procedure for review of asset data room"),
                "documentType" => "x_dgfAssetFamiliarization",
            ]);

            $assetFamiliarization = $this->request('auctions/' . $data['data']['id'] . '/documents?acc_token=' . $data['access']['token'],
                'POST',
                ["data" => [
                    "url" => $auction->lot->address,
                    "title" => Yii::t("app", "The procedure for review of asset data room"),
                    "documentType" => "x_dgfAssetFamiliarization",
                ]]);
            $document->load($assetFamiliarization, '');
            $document->documentOf = "auction";
            $document->save(false);
        }
        if($auction->lot->passport) {
            $document = new Documents([
                'relatedItem' => $auction->unique_id,
                'documentOf' => "auction",
            ]);

            $passport = $this->request('auctions/' . $data['data']['id'] . '/documents?acc_token=' . $data['access']['token'],
                'POST',
                ["data" => [
                    "url" => $auction->lot->passport,
                    "title" => Yii::t("app", "Asset passport"),
                    "documentType" => "x_dgfPublicAssetCertificate",
                ]]);
            $document->load($passport, '');
            $document->documentOf = "auction";
            $document->save(false);
        }
        if(!isset($data['data']) && YII_DEBUG) {
            var_dump($auctionData);
            DMF($data);
        }
        $auction->load($data['data'], '');
        $auction->access_token = isset($data['access']) ? $data['access']['token'] : '';

        return $auction->save(false);
    }

    public function updateAuction(Auctions $auction){
        $data = $this->request('auctions/' . $auction->id . '?acc_token=' . $auction->access_token, 'PATCH', ['data' => $auction->toArray()]);
        if(isset($data['data']) && YII_DEBUG) {
            $auction->load($data['data'], '');
            $auction->save(false);
            return true;
        } else {
            DMF($data);
            return false;
        }
    }

    public function addAuctionDocument(Documents $document){
        $documentData = $document->toArray();
        if($document->documentType == 'evaluationCriteria') {
            $document->title = 'Критерії оцінки';
        }
        elseif($document->documentType == 'x_dgfPublicAssetCertificate'){
            unset($documentData['hash']);
            unset($documentData['format']);
        }
        $data = $this->request('auctions/' . $document
                ->auction
                ->id . '/documents?acc_token=' . $document
                ->auction
                ->access_token,
            'POST',
            ["data" => $documentData]);

        if(!isset($data['data']) && YII_DEBUG) {
            print_r($document->toArray());
            DMF($data);
        }
        $document->id = $data['data']['id'];
        $document->save(false);

        return isset($data['data']);
    }

    public function replaceAuctionDocument($old_document, Documents $document){
        $documentData = $document->toArray();
        if($document->documentType == 'evaluationCriteria') {
            $document->title = 'Критерії оцінки';
        }
        $data = $this->request('auctions/' . $document
                ->auction
                ->id . '/documents/' . $old_document->id . '?acc_token=' . $document
                ->auction
                ->access_token,
            'PUT',
            ["data" => $documentData]);

        if(isset($data['errors']) && YII_DEBUG) {
            print_r($documentData);
            DMF($data['errors']);
        }
        $document->id = $data['data']['id'];
        $document->url = $data['data']['url'];
//         $document->load($data, '');
        return $document->save(false);
    }

    public function addCancellationDocument(Cancellations $cancellation, Documents $document, $description){
        $documentData = array_merge($document->toArray(), ['description' => $description, 'documentOf' => 'tender']);
        unset($documentData['relatedItem']);

        $data = $this->request(
            'auctions/' . $cancellation->auction->id . '/cancellations/' . $cancellation->id . '/documents?acc_token=' . $cancellation->auction->access_token,
            'POST',
            ['data' => $documentData]);
        if(!isset($data['data'])) {
            DMF($data);
        }
        $document->load(array_merge($data['data'], ['documentOf' => 'cancellation', 'relatedItem' => $cancellation->unique_id]), '');
        return $document->save(false);
    }

    public function replaceCancellationDocument(Cancellations $cancellation, Documents $old_document, Documents $document, $description){
        $documentData = array_merge($document->toArray(), ['description' => $description, 'documentOf' => 'tender']);

        unset($documentData['relatedItem']);

        $data = $this->request(
            'auctions/' . $cancellation->auction->id . '/cancellations/' . $cancellation->id . '/documents/' . $old_document->id . '?acc_token=' . $cancellation->auction->access_token,
            'PUT',
            ['data' => $documentData]);
        if(!isset($data['data'])) {
            DMF($data);
        }
        $document->load(array_merge($data['data'], ['documentOf' => 'cancellation', 'relatedItem' => $cancellation->unique_id]), '');
        $document->url = $data['data']['url'];
        return $document->save(false) && $old_document->delete();
    }

    public function createQuestion(Questions $model){
        $question = $model->toArray();

        $data = $this->request('auctions/' . $model->auctionId . '/questions', 'POST', ['data' => $question]);
        $model->scenario = 'publish';
        if(!isset($data['data']) && YII_DEBUG) {
            print_r($question);
            DMF($data);
        }
        $model->load($data['data'], '');
        $model->save(false);
    }

    public function answerQuestion(Questions $model){
        $question = $model->toArray();
        $question['date'] = Date::normalize(date('Y-m-d', time()));

        $data = $this->request('auctions/' . $model->auction->auction->id . '/questions/' . $model->id . '?acc_token=' . $model->auction->auction->access_token, 'PATCH', ['data' => $question]);
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }
        $model->load($data, '');
        $model->save(false);
    }

    public function addAuctionProtocol(Bids $bid){
        $auctionProtocol = $bid->auctionProtocol;

        $token = $auctionProtocol->author == 'bid_owner' ? $bid->access_token : $bid->apiAuction->access_token;

        $data = $this->request(
            'auctions/' . $bid
                ->apiAuction
                ->id . '/awards/' . $bid
                ->award
                ->id . '/documents?acc_token=' . $token,
            'POST',
            [
                'data' => array_merge($auctionProtocol->toArray(), [
                    'documentType' => 'auctionProtocol',
                    'author' => $auctionProtocol->author,
                ]),
            ]
        );
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }

        $auctionProtocol->load($data, '');
        $auctionProtocol->url = $data['data']['url'];

        return $auctionProtocol->save(false);
    }

    public function confirmAuctionProtocol(Bids $bid){
        $data = $this->request(
            'auctions/' . $bid->apiAuction->id . '/awards/' . $bid->award->id . '?acc_token=' . $bid->apiAuction->access_token,
            'PATCH', [
            'data' => [
                'status' => 'active',
            ],
        ]);
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }
        $award = $bid->award;
        $award->load($data, '');
        return $award->save(false);
    }

    public function confirmAward(Awards $award){
        $data = $this->request(
            'auctions/' . $award->auction->id . '/awards/' . $award->id . '?acc_token=' . $award->auction->access_token, 'PATCH',
            ['data' => ['status' => 'active']]
        );
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }
        $award->load($data, '');
        return $award->save(false);
    }

    public function addContractDocument(Bids $bid, Documents $document){
        $documentData = $document->toArray();
        unset($documentData['documentType']);

        $data = $this->request(
            'auctions/'
            . $bid->apiAuction->id
            . '/contracts/'
            . $bid->contract->id
            . '/documents?acc_token='
            . $bid->apiAuction->access_token,

            'POST',
            ['data' => $documentData]
        );
        if(!isset($data['data'])) {
            DMF($data);
        }
        $document->id = $data['data']['id'];
        $document->url = $data['data']['url'];
        $document->save(false);
        return $data;
    }

    public function confirmContract(Bids $bid, $date = NULL){
        $data = [
            'status' => 'active',
        ];
        if($date) $data['dateSigned'] = Date::normalize($date);

        $response = $this->request(
            'auctions/' . $bid->apiAuction->id . '/contracts/' . $bid->contract->id . '?acc_token=' . $bid->apiAuction->access_token,
            'PATCH',
            ['data' => $data]
        );

        if(!isset($response['data'])) {
            if(YII_DEBUG){
                DMF($response);
            }
            else {
                return false;
            }
        }

        $contract = $bid->contract;
        $contract->load($response, '');
        return $contract->save(false);
    }

    public function confirmDisqualification(Documents $document, Awards $award){
        $this->request(
            'auctions/' . $award->auction->id . '/awards/' . $award->id . '/documents?acc_token=' . $award->auction->access_token,
            'POST',
            ['data' => $document->toArray()]
        );

        $data = $this->request(
            'auctions/' . $award->auction->id . '/awards/' . $award->id . '?acc_token=' . $award->auction->access_token,
            'PATCH',
            ['data' => ['status' => 'unsuccessful']]);

        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }

        $award->load($data, '');
        return $award->save(false);
    }

    public function cancelBid(Bids $bid){
        $data = $this->request('auctions/' . $bid->apiAuction->id . '/awards/' . $bid->award->id . '?acc_token=' . $bid->access_token,
            'PATCH',
            ['data' => ['status' => 'cancelled']]);
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }
        $bid->load($data, '');
        return $bid->save(false);
    }

    public function createCancellation(Cancellations $cancellation){

        $path = $this->url . $this->path . 'auctions/' . $cancellation->auction->id . '/cancellations';
        $data = $this->request($path . '?acc_token=' . $cancellation->auction->access_token, 'POST', ['data' => $cancellation->toArray()]);
        if(!isset($data['data']) && YII_DEBUG) {
            DMF($data);
        }

        $auction_id = $cancellation->relatedItem;
        $cancellation->load($data, '');
        $cancellation->relatedItem = $auction_id;
        return $cancellation->save(false);
    }

    public function confirmCancellation(Cancellations $cancellation){
        $data = $this->request('auctions/' . $cancellation->auction->id . '/cancellations/' . $cancellation->id . '?acc_token=' . $cancellation->auction->access_token, 'PATCH', ['data' => ['status' => 'active']]);
        if(!isset($data['data'])) {
            DMF($data);
        }
        return $cancellation->updateAttributes(['status' => 'active']) && $cancellation->auction->updateAttributes(['status' => 'unsuccessful']);
    }


    public function parseAuctions($offset = '2015-01-01T21%3A00%3A03.340891%2B03%3A00', $resave = false, $rewind = false){
        do {
            ob_implicit_flush(true);

            echo $offset . "\n";

            if(YII_DEBUG){
                $temp = $this->request('auctions?offset=' . $offset . '&mode=_all_' . ($rewind == true ? '&descending=True' : ''), 'GET', [], [], 'public');
            }
            else{
                $temp = $this->request('auctions?offset=' . $offset . ($rewind == true ? '&descending=True' : ''), 'GET', [], [], 'public');
            }


            $data = $temp['data'];
            foreach($data as $item) {

                echo $item['id'] . ' - ' . $item['dateModified'] . "\r\n";

                if(false != ($auction = ApiAuctions::find()->where(['id' => $item['id']])->one())) {
                    if(($auction->dateModified != $item['dateModified']) || $resave == true) {
                        $auctiondata = $this->request('auctions/' . $item['id'], 'GET', [], [], 'public')['data'];

//                        if($auction->status == 'active.tendering' && $auctiondata['status'] == 'active.auction' && $auction->bids){
                        $this->parseBids($auction);
//                        }
                        $auction->load($auctiondata, '');
                        if(!$auction->save() && YII_DEBUG) {
                            $id = $auctiondata['id'];
                            echo "Tender $id saving error\n";
                            print_r($auction->errors);
                        }
                        if($resave == false) $this->count += 1;
                    }
                } else {
                    $auction = new ApiAuctions();
                    $auctiondata = $this->request('auctions/' . $item['id']);
                    $auction->load($auctiondata, '');
                    if(!$auction->save(false) && YII_DEBUG) {
                        echo "Auction $auction->id saving error\n<br>";
                        print_r($auction->errors);
//                        die();
                    }
                    $this->count += 1;
                }
            }

            $apiVersion = getenv('API_VERSION');
            $offset = str_replace("/api/{$apiVersion}/auctions?offset=", "", $temp['next_page']['path']);
        } while(count($data) > 0);
        echo "Получено записей:" . $this->count . "\n";
    }

    public function parseBids($auction){
        /** @var Bids $bid * */
        foreach($auction->ownBids as $bid) {
            if($bid->access_token) {
                $data = $this->request('auctions/' . $auction->id . '/bids/' . $bid->id . '?acc_token=' . $bid->access_token, 'GET');
                if(isset($data['data']['participationUrl']) && !$bid->participationUrl) {
                    $bid->load($data, '');
                    $bid->auction_id = $auction->unique_id;
                    $bid->save(false);

                    Yii::createObject(Messages::className())->sendMessage(
                        $bid->user_id,
                        Yii::t('app', 'Аукціон "{auction}" розпочався. Ви можете взяти участь, перейшовши за посиланням: {link}', [
                            'auction' => $auction->title,
                            'link' => Html::a(Yii::t('app', 'перейти'), $data['data']['participationUrl']),
                        ]),
                        true
                    );
                }
            }
        }
    }

    public function refreshAuction($id = ''){
        if(NULL === ($auction = Auctions::find()->where(['id' => $id])->one())) {
            $auction = new Auctions();
        }
        $auction->load($this->request('auctions/' . $id), '');
        $saved = $auction->save();
//        if(!$saved){
        //$auction->save(false);
        //print_r($auction->errors);
//        }
        return $saved;
    }

}
