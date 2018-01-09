<?php

namespace api;

use Yii;
use app\models\Lots;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\helpers\Html;
use app\helpers\Date;
use app\models\Messages;
use api\base\ActiveRecord;
use yii\helpers\ArrayHelper;
use app\models\Lots as BaseAuctions;

/**
 * This is the model class for table "tenders".
 *
 * @property integer $id
 * @property integer $baseAuction_id
 * @property integer $dateModified
 * @property string $title
 * @property string $description
 * @property string $tenderID
 * @property integer $procuringEntity_id
 * @property string $procuringEntity_kind
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property double $guarantee_amount
 * @property string $guarantee_currency
 * @property integer $date
 * @property double $minimalStep_amount
 * @property string $minimalStep_currency
 * @property integer $minimalStep_valueAddedTaxIncluded
 * @property string $enquiryPeriod_startDate
 * @property string $enquiryPeriod_endDate
 * @property string $tenderPeriod_startDate
 * @property string $tenderPeriod_endDate
 * @property string $auctionPeriod_startDate
 * @property string $auctionPeriod_endDate
 * @property string $auctionUrl
 * @property string $awardPeriod_startDate
 * @property string $awardPeriod_endDate
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $procurementMethodType
 */

class Auctions extends ActiveRecord
{

    public $fieldsMode = 'minimal';

    private $_items = [];

    private $_awards = [];

    private $_contracts = [];

    private $_procuringEntity;

    private $_documents = [];

    private $_bids = [];

    private $_cancellations = [];

    private $_lots = [];

    private $_questions = [];

    public $statusNames = [];

    public function init()
    {
        parent::init();
        $this->statusNames = [
            'active.tendering' => Yii::t('app', 'Період прийому заявок'),
            'active.auction' => Yii::t('app', 'Йдуть торги по аукціону'),
            'active.qualification' => Yii::t('app', 'Кваліфікація переможця'),
            'active.awarded' => Yii::t('app', 'Standstill period'),
            'unsuccessful' => Yii::t('app', 'Торги не відбулися'),
            'complete' => Yii::t('app', 'Торги завершено'),
            'cancelled' => Yii::t('app', 'Торги відмінено'),
            'draft' => Yii::t('app', 'Чорновик')
        ];
    }

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_auctions';
    }

    public function fields()
    {

        $fields = [
            'auctionID',
            'dgfID',
            'dgfDecisionID',
            'dgfDecisionDate' => function($model){
                return (new \Datetime($model->dgfDecisionDate))->format('Y-m-d');
            },
            'description',
            'tenderAttempts' => function($model){ return $model->tenderAttempts > 0 ? $model->tenderAttempts : null; },
            'title',
            'items',
            'procurementMethodType',
            'value' => function($model){
                return [
                    'amount' => $model->value_amount,
                    'currency' => 'UAH',
                    'valueAddedTaxIncluded' => $model->value_valueAddedTaxIncluded == 1,
                ];
            },
            'guarantee' => function($model){
                return [
                    'amount' => $model->value_amount / 100 * 5,
                    'currency' => 'UAH',
                ];
            },
            'minimalStep' => function($model){
                return [
                    'amount' => $model->minimalStep_amount,
                    'valueAddedTaxIncluded' => $model->value_valueAddedTaxIncluded == 1,
                ];
            },
            'auctionPeriod' => function($model){
                return [
                    'startDate' => $model->auctionPeriod_startDate,
                ];
            },
            'date',
            'procuringEntity' => function($model){
                $entity = $model->procuringEntity->toArray();
                if($this->baseAuction->ownerName){
                    $entity['name'] = $this->baseAuction->ownerName;
                }
                return $entity;
            },
        ];
        if($this->fieldsMode != 'minimal'){
            $fields = array_merge($fields, ['documents']);
        }

        if(YII_DEBUG){
            return array_merge($fields, ['mode' => function($model){ return 'test'; }]);
        }
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['id'], 'unique'],
            [
                [
                    'id',
                    'title',
                    // 'description',
//                    'tenderID',
//                    'procuringEntity_id',
//                    'procuringEntity_kind',
                    'value_amount',
                    'value_currency',
                    'value_valueAddedTaxIncluded',
//                    'date',
                    'minimalStep_amount',
                    'minimalStep_valueAddedTaxIncluded',
                    // 'enquiryPeriod_startDate',
                    'enquiryPeriod_endDate',
                    'tenderPeriod_startDate',
                    'tenderPeriod_endDate',
//                    'dgfDecisionID',
//                    'dgfDecisionDate',
//                    'dgfID',
                    // 'tenderAttempts',
//                    'auctionPeriod_startDate',
//                    'auctionPeriod_endDate',
//                    'auctionUrl',
//                    'awardPeriod_startDate',
//                    'awardPeriod_endDate',
//                    'status',
//                    'items',
                ],
                'required',
            ],
            [
                [
                    'description',
                    'auctionUrl',
                ],
                'string',
            ],
            [
                [
                    'procuringEntity_id',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'value_amount',
                    'guarantee_amount',
                    'minimalStep_amount',
                ],
                'number',
            ],
            [
                [
                    'title',
                ],
                'string',
            ],
            [
                [
                    'id',
                    'status',
                    'access_token',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'auctionID',
                    'procurementMethod',
                    'procurementMethodType',
                    'owner',
                    'awardCriteria',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'procuringEntity_kind',
                    'enquiryPeriod_startDate',
                    'enquiryPeriod_endDate',
                    'tenderPeriod_startDate',
                    'tenderPeriod_endDate',
                    'auctionPeriod_startDate',
                    'auctionPeriod_endDate',
                    'awardPeriod_startDate',
                    'awardPeriod_endDate',
                    'date',
                    'dateModified',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'value_currency',
                    'guarantee_currency',
                    'minimalStep_currency',
                ],
                'string',
                'max' => 3,
            ],
            [
                [
                    'value_currency',
                    'guarantee_currency',
                    'minimalStep_currency',

                ],
                'default',
                'value' => 'UAH',
            ],
            // [
            //     [
            //         'status',
            //     ],
            //     'in',
            //     'range' => [
            //         'active.enquiries',
            //         'active.tendering',
            //         'active.auction',
            //         'active.qualification',
            //         'active.awarded',
            //         'unsuccessful',
            //         'complete',
            //         'cancelled',
            //     ],
            // ],
            [
                [
                    'value_valueAddedTaxIncluded',
                    'minimalStep_valueAddedTaxIncluded',
                ],
                'boolean',
            ],
            [
                [
                    'procuringEntity',
                    'documents',
                    'questions',
                    'awards',
                    'contracts',
                    'bids',
                    'items',
                    'cancellations',
                    'baseAuction_id',
                    'dgfDecisionID',
                    'dgfDecisionDate',
                    'dgfID',
                    'tenderAttempts',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'auctionID' => Yii::t('app', 'Auction identifier'),
            'auctionPeriod_endDate' => Yii::t('app', 'Auction Period End Date'),
            'auctionPeriod_startDate' => Yii::t('app', 'Дата проведення аукціону'),
            'auctionUrl' => Yii::t('app', 'Посилання на аукціон'),
            'awardPeriod_endDate' => Yii::t('app', 'Award Period End Date'),
            'awardPeriod_startDate' => Yii::t('app', 'Award Period Start Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'date' => Yii::t('app', 'date'),
            'description' => Yii::t('app', 'Загальний опис аукціону'),
            'dgfDecisionDate' => Yii::t('app', 'ФГВФО date'),
            'dgfDecisionID' => Yii::t('app', 'Рішення виконавчої дирекції ФГВФО про затвердження умов продажу'),
            'dgfID' => Yii::t('app', 'ФГВФО №'),
            'enquiryPeriod_endDate' => Yii::t('app', 'Enquiry Period End Date'),
            'enquiryPeriod_startDate' => Yii::t('app', 'Enquiry Period Start Date'),
            'guarantee_amount' => Yii::t('app', 'Розмір забезпечення тендерної пропозиції'),
            'guarantee_currency' => Yii::t('app', 'Валюта'),
            'id' => Yii::t('app', 'ID'),
            'minimalStep_amount' => Yii::t('app', 'Розмір мінімального кроку'),
            'minimalStep_currency' => Yii::t('app', 'Розмір мінімального кроку val'),
            'minimalStep_valueAddedTaxIncluded' => Yii::t('app', 'З ПДВ step'),
            'organization' => Yii::t('app', 'Organization'),
            'procurementMethodType' => Yii::t('app', 'Тип аукціону'),
            'procuringEntity_id' => Yii::t('app', 'Procuring Entity ID'),
            'procuringEntity_kind' => Yii::t('app', 'Procuring Entity Kind'),
            'status' => Yii::t('app', 'Auction status'),
            'statusName' => Yii::t('app', 'Auction status'),
            'tenderAttempts' => Yii::t('app', 'Tender attempts'),
            'tenderPeriod_endDate' => Yii::t('app', 'Період подання пропозицій до'),
            'tenderPeriod_startDate' => Yii::t('app', 'Період подання пропозицій з'),
            'title' => Yii::t('app', 'Загальна назва аукціону'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'value_amount' => Yii::t('app', 'Початкова ціна реалізації'),
            'value_currency' => Yii::t('app', 'Валюта'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'З ПДВ'),
        ];
    }

    public function getAccess_token(){
        return $this;
    }

    public function getIsEnded(){
        return in_array($this->status, [
            'active.awarded',
            'unsuccessful',
            'complete',
            'cancelled',
        ]);
    }

    public function getInQualification(){
        return in_array($this->status, [
            'active.qualification',
        ]);
    }

    public function getAuction(){
        return $this;
    }

    public function setItems($values){
        $this->_items = $values;
    }


    /** @returns ActiveQuery|Items[] */
    public function getItems(){
        return $this->hasMany(Items::className(), ['api_auction_id' => 'unique_id']);
    }

    public function getItemsClassifications(){
        return $this->hasMany(ItemsClassifications::className(), ['item_id' => 'id'])->via('items');
    }

    public function getClassifications(){
        return $this->hasMany(Classifications::className(), ['id' => 'classification_id'])->via('itemsClassifications');
    }

    public function setProcuringEntity($value){
        $this->_procuringEntity = $value;
    }

    public function getProcuringEntity(){
        return $this->hasOne(Organizations::className(), ['unique_id' => 'procuringEntity_id']);
    }

    public function getFeatures(){
        return $this->hasMany(Features::className(), ['relatedItem' => 'tenderID'])->andWhere(['featureOf' => 'tender']);
    }

    public function setDocuments($values){
        $this->_documents = $values;
    }

    public function getDocuments(){
        return $this->hasMany(Documents::className(), ['relatedItem' => 'unique_id'])->andOnCondition(['documentOf' => ['auction', 'tender']]);
    }

    public function setAwards($values){
        $this->_awards = $values;
    }

    public function getAwards(){
        return $this->hasMany(Awards::className(), ['auction_id' => 'id']);
    }

    public function setContracts($values){
        $this->_contracts = $values;
    }

    public function getContracts(){
        return !empty($this->_contracts) ? $this->_contracts : $this->hasMany(Contracts::className(), ['auction_id' => 'id']);
    }

    public function setBids($values){
        $this->_bids = $values;
    }

    public function setLots($values){
        $this->_lots = $values;
    }
    public function setCancellations($values){
        $this->_cancellations = $values;
    }

    public function getCancellations(){
        return $this->hasMany(Cancellations::className(), ['relatedItem' => 'unique_id']);
    }

    public function getCancellation(){
        return $this->hasOne(Cancellations::className(), ['relatedItem' => 'unique_id']);
    }

    public function getBaseAuction(){
        if($this->baseAuction_id == 0){
            $this->createLot();
        }
        return $this->hasOne(BaseAuctions::className(), ['id' => 'baseAuction_id']);
    }
    public function getLot(){
        return $this->getBaseAuction();
    }
    public function getBids(){
        return $this->hasMany(Bids::className(), ['auction_id' => 'unique_id'])->joinWith('awards')->orderBy(['api_awards.unique_id' => SORT_ASC])->where('api_awards.id is not null')/*->via('lot')*/;
    }


    public function getQuestions(){
        // return $this->hasMany(Questions::className(), ['relatedItem' => 'id']);
        $itemIds = ArrayHelper::map($this->items, 'id', 'id');
        return Questions::find()->where(['relatedItem' => array_merge($itemIds, [$this->id])])->all();
    }

    public function setQuestions($values){
        $this->_questions = $values;
    }

    public function createLot(){
        if(false == ($lot = Lots::findOne(['id' => $this->baseAuction_id]))){
            $lot = new Lots([
                'start_price' => $this->value_amount,
            ]);
            $lot->load($this->attributes, '');
            $lot->lot_lock = '3';
            $lot->date = Date::normalize(date('Y-m-d H:i:s', time()));
            $lot->load($this->attributes, '');
            $lot->save(false);
            $this->updateAttributes(['baseAuction_id' => $lot->id]);
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($this->lot){
            $this->lot->updateAttributes(['bidding_date_end' => $this->tenderPeriod_endDate]);
        }

        if(!$this->procuringEntity){
            if($this->_procuringEntity){
                if(false == ($procuringEntity = Organizations::findOne(['name' => $this->_procuringEntity['name']]))){
                    $procuringEntity = new Organizations;
                    $procuringEntity->load($this->_procuringEntity, '');
                    if(!$procuringEntity->save(false)){
                        echo "Procuring entity saving error:\n";
                        print_r($procuringEntity->errors);
                    }
                }
                $this->updateAttributes(['procuringEntity_id' => $procuringEntity->unique_id]);
            }
        }

        foreach($this->_questions as $item){
            if(false == ($question = Questions::findOne(['id' => $item['id']]))){
                $question = new Questions($item);
            }
            $question->scenario = 'parse';
            $question->load($item, '');
            $question->relatedItem = isset($item['relatedItem']) ? $item['relatedItem'] : $this->id;
            if(!$question->save(false)){
                echo "Question saving error\n";
                print_r($question->errors);
            }
            $question->save(false);
        }

        foreach($this->_items as $item){
            if(false == ($lot = Items::findOne(['description' => $item['description']]))){
                $lot = new Items();
            }
            $lot->load($item, '');
            $lot->api_auction_id = $this->unique_id;
            if(!$lot->save(false)){
                echo "Item saving error\n";
                print_r($lot->errors);
            }
        }

        foreach($this->_documents as $item){
            if(false == ($document = Documents::findOne(['id' => $item['id']]))){
                $document = new Documents();
            }
            $document->load($item, '');
            $document->documentOf = 'auction';
            $document->relatedItem = $this->unique_id;
            if(!$document->save(false) && YII_DEBUG){
                echo "Document saving error:\n$document->id\n";
                print_r($document->errors);
            }
        }

        foreach($this->_awards as $item){
            if(false == ($award = Awards::findOne(['id' => $item['id']]))){
                $award = new Awards;
            }
            $award->load($item, '');
            $award->auction_id = $this->id;
            if(!$award->save(false) && YII_DEBUG){
                echo "Award saving error\n";
                print_r($award->errors);
            }
        }

        foreach($this->_contracts as $item){
            if(false == ($contract = Contracts::findOne(['id' => $item['id']]))){
                $contract = new Contracts();
            }
            $contract->load($item, '');
            $contract->auction_id = $this->id;
            if(!$contract->save(false) && YII_DEBUG){
                echo "Contract saving error\n";
                print_r($contract->errors);
            }

        }

        foreach($this->_bids as $item){
            if(false == ($bid = Bids::findOne(['id' => $item['id']]))){
                $bid = new Bids();
            }
            $bid->load($item, '');
            $bid->auction_id = $this->unique_id;
            if(!$bid->save(false) && YII_DEBUG){
                echo "Bids saving error\n";
                print_r($bid->errors);
            }
        }

        foreach($this->_lots as $item){
            if(false == ($lot = Lots::findOne(['id' => $item['id']]))){
                $lot = new Lots;
            }
            $lot->load($item, '');
            if(!$lot->save(false)){
                echo "Lot saving error\n";
                print_r($lot->errors);
            }
        }

        foreach($this->_cancellations as $item){
            if(false == ($cancellation = Cancellations::findOne(['id' => $item['id']]))){
                $cancellation = new Cancellations();
            }
            $cancellation->load($item, '');
            $cancellation->relatedItem = $this->unique_id;
            if(!$cancellation->save(false) && YII_DEBUG){
                echo "Error cancellation saving";
                print_r($cancellation->errors);
            }
        }

        $this->_documents = [];

        if(isset($changedAttributes['status'])){
            $reasonStatuses = [
                'cancelled' => Yii::t('app', 'Було скасовано'),
                'unsuccessful' =>Yii::t('app', 'Не відбувся'),
                'complete' => Yii::t('app', 'Завершився'),
            ];
            if(in_array($this->status, ['cancelled', 'unsuccessful', 'complete'])){
                foreach($this->bids as $bid){
                    if($bid->user){
                        $text = Yii::t('app', 'Аукціон {auctionID} {action}. Ви можете переглянути результати аукціону, перейшовши за посиланням: {link}',[
                            'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/public/view', 'id' => $this->unique_id], true)),
                            'auctionID' => $this->auctionID,
                            'action' => $reasonStatuses[$this->status],
                        ]);
                        Yii::createObject(Messages::className())->sendMessage($bid->user_id, $text, true);
                    }
                }
                foreach($this->questions as $question){
                    if($question->organization && $question->organization->user){
                        $text = Yii::t('app', 'Аукціон {auctionID} {action}. Ви можете переглянути результати аукціону, перейшовши за посиланням: {link}',[
                            'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/public/view', 'id' => $this->unique_id], true)),
                            'auctionID' => $this->auctionID,
                            'action' => $reasonStatuses[$this->status],
                        ]);
                        Yii::createObject(Messages::className())->sendMessage($question->organization->user_id, $text, true);
                    }
                }
            }
        }
    }

    public function getTypeName(){
        return ArrayHelper::getValue(Lots::$procurementMethodTypes, $this->procurementMethodType, Yii::t('app', 'Невідомо'));
    }

    public function getStatusName(){
        return isset($this->statusNames[$this->status]) ? $this->statusNames[$this->status] : Yii::t('app', '(not set)');
    }

    public function getIsCancelled(){
        return $this->status === 'cancelled';
    }


    public function getTenderAttempts(){
        return $this->tenderAttempts ?: 0;
    }

    public function setTenderAttempts($value){
        $this->tenderAttempts = $value;
    }

    public function getValue_currency(){
        return Yii::t('app', $this->value_currency);
    }

    public function getMinimalStep_currency(){
        return Yii::t('app', $this->minimalStep_currency);
    }

    public function getTenderAttemptsString(){
        $types = [
            null => 'Невідомо',
            0 => 'Невідомо',
            1 => 'Лот виставляються вперше',
            2 => 'Лот виставляється вдруге',
            3 => 'Лот виставляється втретє',
            4 => 'Лот виставляється вчетверте',
        ];
        return isset($types[$this->tenderAttempts]) ? $types[$this->tenderAttempts] : $this->tenderAttempts . Yii::t('app', '-й раз');
    }

    public function getLicenseRequired(){
        $required = false;
        foreach($this->items as $item){
            if(mb_strpos($item->classification->id, '07') === 0){
                $required = true;
            }
        }
        return $required;
    }

    public function getEligibilityCriteria(){
        if($this->licenseRequired){
            return Yii::t('app', 'До участі допускаються лише ліцензовані фінансові установи.');
        }
    }


}
