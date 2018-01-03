<?php

namespace app\models;

use Yii;
use api\Bids;
use api\Documents;
use api\Questions;
use api\Items;
use app\helpers\Date;
use api\Organizations;
use yii\db\ActiveRecord;
use api\Auctions as ApiAuctions;
use yii\web\UploadedFile;

/**
 * This is the model class for table "lots".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $unit_code
 * @property double $start_price
 * @property string $step
 * @property string $address
 * @property integer $delivery_time
 * @property string $delivery_term
 * @property string $requires
 * @property string $payment_term
 * @property string $payment_order
 * @property string $member_require
 * @property integer $requisites_id
 * @property string $notes
 * @property integer $dogovor_id
 * @property string $date
 * @property integer $auction_date
 * @property integer $status
 * @property string $term_procedure
 * @property string $bidding_date
 */

class Lots extends ActiveRecord
{

    public $clarificationFile;

    private $_clarification;

    public static $procurementMethodTypes = [
//        'dgfOtherAssets' => 'оголошення аукціону з продажу майна банків',
//        'dgfFinancialAssets' => 'оголошення аукціону з продажу прав вимоги за кредитами.',
        'dgfOtherAssets' => 'майно банків',
        'dgfFinancialAssets' => 'права вимоги за кредитами.',
        'dgfInsider' => 'голандський аукціон',
    ];

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'lots';
    }

    public function scenarios(){
        return array_merge(parent::scenarios(), [
            'edit' => ['dgfDecisionID', 'dgfDecisionDate', 'name', 'dgfID', 'description', 'tenderAttempts', 'clarification', 'num'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'user_id',
                    'name',
                    'description',
                    'start_price',
                    'step',
                    'date',
                    'procurementMethodType',
                    'dgfDecisionID',
                    'dgfDecisionDate',
                    'num',
                    'auction_date',
                    'auction_time',
                ],
                'required',
            ],
            [['user_id', 'status','lot_lock', 'nds', 'tenderAttempts'], 'integer'],
            [['start_price','step'], 'number'],
            [['date','bidding_date', 'bidding_date_end', 'address', 'step_down'], 'safe'],
            [['name', 'description', 'delivery_term', 'delivery_time', 'requires', 'notes','delivery_time', 'delivery_term', 'requires'], 'string', 'max' => 1600],
            [['procurementMethodType'], 'in', 'range' => array_keys(static::$procurementMethodTypes)],
            [['tenderAttempts'], 'in', 'range' => [null, 1, 2, 3, 4, 5, 6, 7, 8]],
//            [['bidding_date'], 'validateBiddingDate'],
//            [['bidding_date_end'], 'validateBiddingDateEnd'],
            [['auction_date'], 'validateAuctionDate', 'when' => function($model){ return !$model->apiAuction; }],
            [['auction_time'], 'validateAuctionTime'],
//            [['auction_date_end'], 'validateAuctionDateEnd'],
            [[/*'bidding_date', 'bidding_date_end', */'auction_date'/*, 'auction_date_end'*/], 'isNotBehind'],
            [['num'], 'string', 'max' => 25],
            [['vdr'], 'string', 'max' => 255],
            [['passport'],'url', 'defaultScheme' => ''],
            [['clarificationFile'], 'file', 'skipOnEmpty' => true],
            [['ownerName'], 'safe'],
        ];
    }

    public function isNotBehind($attribute, $params = []){
        // if(strtotime($this->$attribute) <= time()){
        //     $this->addError($attribute, Yii::t('app', 'Attribute "{attribute}" must be greater than the current time', ['attribute' => $this->getAttributeLabel($attribute)]));
        // }
    }
//
//    public function validateBiddingDate($attribute, $params = []){
//        if(strtotime($this->$attribute) < time()){
//            $this->addError($attribute, Yii::t('app', 'Bidding date start cannot be less than current date'));
//        }
//    }
//
//    public function validateBiddingDateEnd($attribute, $params = []){
//        if(strtotime($this->$attribute) <= strtotime($this->bidding_date)){
//            $this->addError($attribute,  Yii::t('app', 'Bidding date end cannot be less than bidding date start'));
//        }
//    }

    public function validateAuctionDate(){
        if(strtotime($this->auction_date) < strtotime(date('d-m-Y', time()))){
            $this->addError('auction_date', Yii::t('app', 'Auction start date cannot be less than bidding end date'));
        }
        elseif(strtotime($this->auction_date . ' ' . $this->auction_time) < (time() + 600)){
            $this->addError('auction_time', Yii::t('app', 'Auction start time cannot be less than bidding end date'));
        }
    }

    public function validateAuctionTime($attribute, $params = []){

    }

//    public function validateAuctionDateEnd($attribute, $params = []){
//        if(strtotime($this->$attribute) <= strtotime($this->auction_date)){
//            $this->addError($attribute, Yii::t('app', 'Auction end date cannot be less than auction date end'));
//        }
//    }

    public function getClarification(){
        return $this->_clarification ?: $this->hasOne(Documents::className(), ['relatedItem' => 'id'])->via('apiAuction');
    }

    public function setClarification($value){
        $this->_clarification = $value;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'user_id' => Yii::t('app', 'User ID'),
            'num'   =>  Yii::t('app', 'LotNumber ID'),
            'name' => Yii::t('app', 'Lot name'),
            'description' => Yii::t('app', 'LotsDescrioption ID'),
            'start_price' => Yii::t('app', 'Price ID'),
            'nds'   =>  Yii::t('app', 'NDS ID'),
            'step' => Yii::t('app', 'Step ID'),
            'address' => Yii::t('app', 'AddressTo ID'),
            'delivery_time' => Yii::t('app', 'DeliveryTime ID'),
            'delivery_term' => Yii::t('app', 'DeliveryTerm ID'),
            'requires' => Yii::t('app', 'Вимоги до строків та обсягів'),
            'payment_term' => Yii::t('app', 'PaymentTerm ID'), // auclots
            'payment_order' => Yii::t('app', 'Порядок формування цін договору'), // auclots
            'member_require' => Yii::t('app', 'MemberRequire ID'), // auclots
            'term_procedure'    => Yii::t('app', 'Term_procedure ID'), // auclots
            'requisites_id' => Yii::t('app', 'Requisites ID'), // auclots
            'notes' => Yii::t('app', 'LotsNotes ID'),
            'dogovor_id' => Yii::t('app', 'Проект договора'), // auclots
            'date' => Yii::t('app', 'Created At'),
            'auction_date'  =>  Yii::t('app', 'AuctionDate ID'),
            'auction_time' => Yii::t('app', 'Auction start time'),
            'auction_date_end'  =>  Yii::t('app', 'Auction Date End'),
            'status' => Yii::t('app', 'Status ID'),
            'published' => Yii::t('app', 'Publish status'),
            'status' => Yii::t('app', 'Status'),
            'lot_lock'  =>  Yii::t('app', 'Status ID'),
            'bidding_date' => Yii::t('app', 'BiddingDate ID'),
            'step_down' => Yii::t('app', 'Circle ID'),
            'procurementMethodType' => Yii::t('app', 'Auction announcement'),
            'tenderAttempts' => Yii::t('app', 'Tender attempts'),
            'dgfDecisionID' => Yii::t('app', 'DGF decision id'),
            'dgfDecisionDate' => Yii::t('app', 'від'),
            'organizerName' => Yii::t('app', 'Organizer'),
            'bidding_date_end' => Yii::t('app', 'Bidding Date End'),
            'auction_id' => Yii::t('app', 'Auction Id'),
            'auctionID' => Yii::t('app', 'Auction identifier'),
            'statusName' => Yii::t('app', 'Auction status'),
            'vdr' => Yii::t('app', 'VDR for auction lot'),
            'passport' => Yii::t('app', 'Asset passport'),
            'clarificationFile' => Yii::t('app', 'Clarification document'),
            'ownerName' => Yii::t('app', 'Auction owner name'),
            'auctionUrl' => Yii::t('app', 'Auction Url'),
            'protocol' => Yii::t('app' , 'Протокол торгів'),
            'guarantee' => Yii::t('app', 'Гарантійний внесок')
        ];
    }

    public function getItems(){
        return $this->hasMany(Items::className(), ['auction_id' => 'id']);
    }

//    public function save($validate = true, $attr = NULL){
//
////        $lot = Auclots::findOne(['name' => $this->aukname]);
////        //var_dump($lot); exit;
////        //$this->address = $lot->address;
////        $this->bidding_date = $lot->bidding_date;
////        $this->auction_date = $lot->auction_date;
////        $this->payment_term = $lot->payment_term;
////        $this->payment_order = $lot->payment_order;
////        $this->member_require = $lot->member_require;
////        $this->term_procedure = $lot->term_procedure;
////        $this->requisites_id = $lot->requisites_id;
////        $this->dogovor_id = $lot->dogovor_id;
//
//        return parent::save($validate, $attr);
//    }

    public function getProcuringEntity(){
        return $this->hasOne(Organizations::className(), ['user_id' => 'id'])->via('organizer');
    }

    public function getOrganizer(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrganizerName(){
        return $this->organizer->username . ' (' . $this->organizer->at_org . ')';
    }

    public function getDocuments(){
        return $this->hasMany(Documents::className(), ['lot_id' => 'id'])->andWhere(['documentOf' => 'auction']);
    }

    public function getTempDocuments(){
        return $this->hasMany(Documents::className(), ['lot_id' => 'id']);
    }

    public function getApiAuction(){
        return $this->hasOne(ApiAuctions::className(), ['baseAuction_id' => 'id']);
    }
    public function getQuestions(){
        return $this->hasMany(Questions::className(), ['relatedItem' => 'unique_id'])->via('apiAuction');
    }
    public function getBids()
    {
        return $this->hasMany(Bids::className(), ['lot_id' => 'id']);
    }

    public function getAuctionId(){
        return $this->apiAuction ? $this->apiAuction->id : false;
    }

    public function getStep_percent(){
        if(!$this->start_price){
            return 0;
        }
        return round($this->step / $this->start_price * 100, 2);
    }


    public function publishAuction($data){

        $this->auction_date = date('d-m-Y', strtotime($this->auction_date)) . ' ' . $this->auction_time;

//        Yii::$app->user->identity->organization->updateAttributes([
//            'name' => $this->ownerName,
//            'contactPoint_name' => $this->ownerName,
//        ]);

        $apiAuctionData = [
            'title' => $this->name,
            'description' => $this->description,
            'procurementMethodType' => $this->procurementMethodType,
            'value' => [
                'amount' => $this->start_price,
                'currency' => 'UAH',
                'valueAddedTaxIncluded' => $this->nds == 1,
            ],
            'guarantee' => [
                'amount' => $this->start_price / 100 * 5,
                'currency' => 'UAH',
            ],
            'minimalStep' => [
                'amount' => $this->step,
                'currency' => 'UAH',
                'valueAddedTaxIncluded' => $this->nds == 1,
            ],
            // 'enquiryPeriod' => [
            //     'startDate' => Date::normalize(date('Y-m-d H:i:s')),
            //     'endDate' =>  Date::normalize($this->auction_date),
            // ],
            // 'tenderPeriod' => [
            //     'startDate' => Date::normalize(date('Y-m-d H:i:s')),
            //     'endDate' => Date::normalize($this->auction_date),
            // ],
            'auctionPeriod' => [
                'startDate' =>Date::normalize($this->auction_date),
                //'endDate' => Date::normalize($this->auction_date_end),
            ],
            'tenderAttempts' => $this->tenderAttempts,
            'dgfID' => $this->num,
            'dgfDecisionID' => $this->dgfDecisionID,
            'dgfDecisionDate' => $this->dgfDecisionDate,
            'baseAuction_id' => $this->id,
            'procuringEntity_id' => Yii::$app->user->identity->organization->unique_id,
        ];
        $apiAuction = new ApiAuctions();
        $apiAuction->load($apiAuctionData, '');
        $apiAuction->save(false);

        foreach($this->items as $item){
            $item->updateAttributes([
                'api_auction_id' => $apiAuction->unique_id,
            ]);
        }
        foreach($this->documents as $document){
            $document->updateAttributes(['relatedItem' => $apiAuction->unique_id]);
        }

        return Yii::$app->api->createAuction($apiAuction);
    }

    public function edit(){
        if(
            parent::save()
            && false != ($file = UploadedFile::getInstance($this, 'clarificationFile'))
            && $file->saveAs(Yii::$app->params['uploadPath'] . "clarifications/{$this->id}-" . time() . '_' . "clarification.{$file->extension}")
        ){
            // $this->dgfDecisionDate = \Datetime::createFromFormat('dmY', $this->dgfDecisionDate)->format('Y-m-d');
            $document = new Documents([
                'relatedItem' => $this->apiAuction->unique_id,
                'documentType' => 'clarifications',
                'documentOf' => 'auction',
            ]);
            $document->load(Yii::$app->apiUpload->upload(Yii::$app->params['uploadPath'] . "clarifications/{$this->id}-" . time() . '_' . "clarification.{$file->extension}"), '');
            $document->save(false);
            $auction = $this->apiAuction;

            $auction->updateAttributes([
                'title' => $this->name,
                'description' => $this->description,
                'dgfDecisionDate' => $this->dgfDecisionDate,
                'dgfDecisionID' => $this->dgfDecisionID,
                'dgfID' => $this->num,
                'tenderAttempts' => (int)$this->tenderAttempts,
            ]);

            return Yii::$app->api->AddAuctionDocument($document)
                && Yii::$app->api->updateAuction($this->apiAuction)
                && $document->save(false);
        }
        else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'You must upload the clarification document'));
            $this->addError('clarificationFile', Yii::t('app', 'You must upload the clarification document'));
            return false;
        }
    }

    public function lock(){
        return $this->updateAttributes(['lot_lock' => '1']);
    }

}
