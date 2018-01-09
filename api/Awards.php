<?php

namespace api;

use Yii;
use app\models\User;
use api\base\ActiveRecord;
use app\models\Messages;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "awards".
 *
 * @property integer $id
 * @property string $bid_id
 * @property string $title
 * @property string $description
 * @property string $status
 * @property integer $date
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property string $complaintPeriod_startDate
 * @property string $complaintPeriod_endDate
 * @property string $lotID
 * @property integer $created_at
 * @property integer $updated_at
 */
class Awards extends ActiveRecord
{

    private $_suppliers = [];


    public function behaviors()
    {
        return parent::behaviors();
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            'disqualify' => ['title', 'status', 'description', 'created_at', 'updated_at'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_awards';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required', 'when' => function($model){ return $model->scenario == 'disqualify'; }],
            [
                [
                    'id',
//                    'bid_id',
//                    'title',
                    'status',
                    'date',
                    'value_amount',
                    'value_valueAddedTaxIncluded',
//                    'complaintPeriod_startDate',
//                    'complaintPeriod_endDate',
//                    'lotID',
                ],
                'required',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'description',
                    'id',
                ],
                'string',
            ],
            [
                [
                    'value_amount',
                ],
                'number',
            ],
            [
                [
                    'bid_id',
                    'title',
                    'lotID',
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
                    'value_valueAddedTaxIncluded',
                ],
                'boolean',
            ],
            [
                [
                    'suppliers',
                    'auction_id',
                    'complaintPeriod_startDate',
                    'complaintPeriod_endDate',
                ],
                'safe',
            ],
        ];
    }

    public function fields()
    {
        return [
            'status',
            'suppliers',
            'value',
            'date',
            'id',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bid_id' => Yii::t('app', 'Bid ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Причина дискваліфікації'),
            'status' => Yii::t('app', 'Status'),
            'date' => Yii::t('app', 'Date'),
            'value_amount' => Yii::t('app', 'Value Amount'),
            'value_currency' => Yii::t('app', 'Value Currency'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'Value Value Added Tax Included'),
            'complaintPeriod_startDate' => Yii::t('app', 'Complaint Period Start Date'),
            'complaintPeriod_endDate' => Yii::t('app', 'Complaint Period End Date'),
            'lotID' => Yii::t('app', 'Lot ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getAwardOrganizations(){
        return $this->hasMany(AwardOrganizations::className(), ['award_id' => 'id']);
    }

    public function getAuction(){
        return $this->hasOne(Auctions::className(), ['id' => 'auction_id']);
    }

    public function getLot(){
        return $this->hasOne(Lots::className(), ['id' => 'baseAuction_id'])->via('auction');
    }

    public function getBid(){
        return $this->hasOne(Bids::className(), ['id' => 'bid_id']);
    }

    public function getDocuments(){
        return $this->hasMany(Documents::className(), ['relatedItem' => 'unique_id'])->andOnCondition(['documentOf' => 'award']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('bid');
    }

    public function getOrganizations(){
        return $this->hasMany(Organizations::className(), ['identifier_id' => 'organization_id'])->via('awardOrganizations');
    }

    public function setSuppliers($value){
        $this->_suppliers = $value;
    }

    public function getAwardsOrganizations(){
        return $this->hasMany(AwardOrganizations::className(), ['award_id' => 'id']);
    }

    public function getSuppliers(){
        return $this->hasMany(Organizations::className(), ['id' => 'organization_id'])->via('awardsOrganizations');
    }

    public function getIsPending(){
        return $this->status === 'pending.verification';
    }

    public function disqualify($document){
        if(Yii::$app->api->confirmDisqualification($document, $this)){
            $this->updateAttributes([
                'title' => 'Disqualified',
                'status' => 'unsuccesfull',
            ]);
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // first bid
        if($insert && $this->status == 'pending.verification' && $this->bid && $this->bid->user){
            $notes = Yii::t('app', 'Проводиться кваліфікація переможця аукціону. Ви зайняли перше місце в аукціоні та можете завантажити протокол торгів протягом 3-х діб: {link}', [
                'link' => Html::a(Yii::t('app', 'Переглянути та завантажити протокол'), Url::to(['/bids/upload-protocol', 'id' => $this->bid->unique_id], true)),
            ]);
            (new Messages())->sendMessage($this->bid->user->id , $notes, true);
        }
        // second bid
        if($insert && $this->status == 'pending.waiting' && $this->bid && $this->bid->user){
            $notes = Yii::t('app', 'Проводиться кваліфікація переможця аукціону. Ви зайняли друге місце в аукціоні та можете не забирати гарантійний внесок на протязі 20-ти днів на випадок дискваліфікації першого кандидата.{link}', [
                'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $this->bid->unique_id], true)),
            ]);
            (new Messages())->sendMessage($this->bid->user->id , $notes, true);
        }
        if(!$insert && isset($changedAttributes['status']) && $this->status == 'unsuccessful' && $this->title == 'Disqualified' && $this->bid && $this->bid->user){
            if($this->description != ''){
                $notes = t('app', 'Ваша ставка була дискваліфікована({link}). Причина: ' . $this->description, [
                    'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $this->bid->unique_id], true)),
                ]);
            }
            else{
                $notes = Yii::t('app', 'Ваша ставка була дискваліфікована по причині незавантаження протоколу торгів. {link}', [
                    'link' => Html::a(Yii::t('app', 'Переглянути'), Url::to(['/bids/view', 'id' => $this->bid->unique_id], true)),
                ]);
            }
            (new Messages())->sendMessage($this->bid->user_id , $notes, true);
        }
        foreach($this->_suppliers as $item){
            $organization = Organizations::findOne(['identifier_id' => $item['identifier']['id']]);
            if(!$organization) {
                $organization = new Organizations();
                $organization->load($item, '');
                if(!$organization->save(false) && YII_DEBUG){
                    echo "Organization save error:\n";
                    print_r($organization->errors);
                }
            }

            $link = new AwardOrganizations(['award_id' => $this->id, 'organization_id' => $organization->identifier_id]);
            if (!$link->save() && YII_DEBUG) {
                echo "AwardOrganization save error";
                print_r($link->errors);
            }
        }
    }

}
