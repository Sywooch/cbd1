<?php

namespace api;

use Yii;
use app\models\User;
use app\models\Files;
use api\base\ActiveRecord;
use app\models\Lots as BaseLots;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "bids".
 *
 * @property string $id
 * @property integer $date
 * @property string $status
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property string $participationUrl
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 */
class Bids extends ActiveRecord
{

    private $_documents = [];
    public $lotName;
    public $oferta;
    public $_tenderers = [];
    public $accept;

    public function behaviors()
    {
        return parent::behaviors();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['dgfOtherAssets'] = $scenarios[ActiveRecord::SCENARIO_DEFAULT];
        $scenarios['dgfFinancialAssets'] = $scenarios[ActiveRecord::SCENARIO_DEFAULT];
        $insiderScenario = $scenarios[ActiveRecord::SCENARIO_DEFAULT];
        unset($insiderScenario['value_amount']);
        if(($key = array_search('value_amount', $insiderScenario)) !== false) {
            unset($insiderScenario[$key]);
        }
        $scenarios['dgfInsider'] = $insiderScenario;
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_bids';
    }

    public function fields(){
        $data = [
            'status' => function($model){
                return $model->status;
            },
            'qualified' => function($model){
                return true; /*$model->accepted == 1;*/
            },
            // 'documents',
            'tenderers' => function($model){
                return [
                    $model->organization,
                ];
            }
        ];
       if(in_array($this->lot->procurementMethodType, ['dgfFinancialAssets', 'dgfInsider'])){
        $data['eligible'] = function($model){ return true; };
       }
        if($this->scenario !== 'dgfInsider'){
            $data['value'] = function($model){
                return [
                    'amount' => $model->value_amount,
                    'valueAddedTaxIncluded' => $model->apiAuction->value_valueAddedTaxIncluded,
                ];
            };
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
//                    'status',
                    'value_amount',
                    'accept',
                    'oferta'
                ],
                'required',
            ],
            [['value_valueAddedTaxIncluded'], 'boolean'],
            [['value_valueAddedTaxIncluded'], 'default', 'value' => true],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'value_amount',
                ],
                'number',
            ],
            [
                [
                    'id',
                    'status',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'status',
                ],
                'string',
                'max' => 25,
            ],
            [
                [
                    'value_currency',
                ],
                'string',
                'max' => 3,
            ],
            [
                [
                    'date',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'documents',
                    'user_id','lot_id','file_id','accepted', 'access_token', 'participationUrl', 'auction_id',
                ],
                'safe',
            ],
            [['value_amount'], 'validateSum'],
            // [['status'], 'in', 'range' => ['active', 'draft']],
            [['status'], 'default', 'value' => 'draft'],
            [['reason'], 'string', 'max' => 500],
            [['oferta', 'organization_id', 'tenderers'], 'safe'],
            'accept' => [['accept'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Це поле обов\'язкове для заповнення')],
            'oferta' => [['oferta'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Необхідно погодитися з умовами обробки персональних даних')],
        ];
    }

    public function validateSum($attribute, $params = []){
        if($this->lot){
            if($this->$attribute < ($this->lot->start_price + $this->lot->step)){
                $this->addError($attribute, Yii::t('app', 'Amount must be greater than lot price. {sum}', [
                    'sum' => ($this->lot->start_price + $this->lot->step) . ' грн',
                ]));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unique_id' => Yii::t('app', 'Our ID'),
            'id' => Yii::t('app', 'ID'),
            'date' => Yii::t('app', 'PublicDate'),
            'status' => Yii::t('app', 'Status'),
            'value_amount' => Yii::t('app', 'Value Amount'),
            'value_currency' => Yii::t('app', 'Value Currency'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'Value Added Tax Included'),
            'participationUrl' => Yii::t('app', 'Participation Url'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'reason' => Yii::t('app', 'Reason'),
            'accepted' => Yii::t('app', 'Accept status'),
            'lotName' => Yii::t('app', 'Auction'),
            'statusName' => Yii::t('app', 'Auction status'),
            'organizationName' => Yii::t('app', 'Organization name'),
            'oferta' => Yii::t('app', 'Accept {agreement}',['agreement' => Html::a(Yii::t('app', 'license agreement'), ['/oferta.pdf'], ['target' => '_blank'])]),
            'documents' => Yii::t('app', 'Documents'),
            'accept' => Yii::t('app', 'Зобов\'язуюся сплатити гарантійний внесок'),
            'auctionID' => Yii::t('app', 'ID Аукціону'),
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrganization(){
        if($this->user_id != '0'){
            return $this->hasOne(Organizations::className(), ['user_id' => 'id'])->via('user');
        }
        else{
            return $this->hasOne(Organizations::className(), ['unique_id' => 'organization_id']);
        }
    }

    public function getBidParameters(){
        return $this->hasMany(BidParameters::className(), ['bid_id' => 'id']);
    }

    public function getParameters(){
        return $this->hasMany(Parameters::className(), ['id' => 'parameter_id'])->via('bidParameters');
    }

    public function setDocuments($values){
        $this->_documents = $values;
    }

    public function getDocuments(){
        return Documents::find()->where(['relatedItem' => [$this->id, $this->unique_id]])->andWhere(['!=', 'id', ''])->all();
    }

    public function setTenderers($values){
        $this->_tenderers = $values;
    }

    public function getTenderers(){
        return $this->_tenderers;
    }

    public function getFinancialLicense(){
        return Files::findOne(['bid_id' => $this->unique_id, 'type' => 'financialLicense']);
    }

    public function getLot()
    {
        return $this->hasOne(BaseLots::className(), ['id' => 'lot_id']);
    }

    public function getApiAuction()
    {
        return $this->hasOne(Auctions::className(), ['unique_id' => 'auction_id']);
    }

    public function getAuctionID(){
        return $this->apiAuction ? $this->apiAuction->auctionID : '';
    }

    public function getOrganizator()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('lot');
    }

    public function getFile()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    public function getDocument()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    public function getOrgAuctionProtocol(){
        return Documents::findOne([
            'relatedItem' => [$this->unique_id, $this->id],
            'documentType' => 'auctionProtocol',
            'author' => 'auction_owner',
        ]);
    }

    public function getMemberAuctionProtocol(){
        return Documents::findOne([
            'relatedItem' => [$this->unique_id, $this->id],
            'documentType' => 'auctionProtocol',
            'author' => 'bid_owner',
        ]);
    }

    public function getAuctionProtocol(){
        if(Yii::$app->user->can('org')){
            return $this->getOrgAuctionProtocol();
        }
        else{
            return $this->getMemberAuctionProtocol();
        }
    }

    public function getContractDocuments(){
        return $this->hasMany(Documents::className(), ['relatedItem' => 'unique_id'])
        ->andWhere([
            'documentOf' => 'bid',
            'documentType' => 'contractSigned',
        ]);
    }

    public function getContract(){
        return $this->hasOne(Contracts::className(), ['awardID' => 'id'])->via('award');
    }

    public function getAward(){
        return $this->hasOne(Awards::className(), ['bid_id' => 'id']);
    }

    public function getIsWinner(){
        return $this->award ? $this->award->status == 'active' : false;
    }

    public function getIsFirst(){
        return $this->award ? !in_array($this->award->status, ['cancelled', 'pending.waiting', 'unsuccessful']) : false;
    }

    public function getIsSecond(){
        return $this->award ? in_array($this->award->status, ['pending.waiting']) : false;
    }


    public function getAwards(){
        return $this->hasOne(Awards::className(), ['bid_id' => 'id']);
    }

    public function getIsAccepted(){
        return $this->accepted == '1';
    }

    public function getIsDeclined(){
        return $this->accepted == '2';
    }

    public function getAccepted()
    {

        if($this->accepted==0)
        {
            return "Не узгоджено";
        }
        if($this->accepted==1)
        {
            return "Узгоджено";
        }
        if($this->accepted==2)
        {
            return "Відхилено";
        }
    }

    public function getIsAwarded(){
        if($this->award && $this->award->status === 'active'){
            return true;
        }
        return false;
    }

    public function getIsPublished(){
        return (bool) $this->id;
    }

    public function getStatusName(){
        return Yii::t('app', $this->status == 'active' ? 'Active' : 'Not active');
    }

    public function activate(){
        return Yii::$app->api->activateBid($this);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        foreach($this->_documents as $item){
            if(false == ($document = Documents::findOne(['id' => $item['id']]))){
                $document = new Documents();
            }
            $document->load($item, '');
            $document->documentOf = 'bid';
            $document->relatedItem = $this->unique_id;
            $document->save(false);
        }
        foreach($this->_tenderers as $item){
            if(false == ($organization = Organizations::findOne(['name' => $item['name']]))){
                $organization = new Organizations();
            }
            $organization->load($item, '');
            $organization->save(false);
            $this->updateAttributes(['organization_id' => $organization->unique_id]);
        }
    }

}

