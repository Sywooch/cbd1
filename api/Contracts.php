<?php

namespace api;

use api\base\ActiveRecord;
use Couchbase\Document;
use Yii;

/**
 * This is the model class for table "contracts".
 *
 * @property string $id
 * @property string $awardID
 * @property string $contractID
 * @property string $contractNumber
 * @property string $title
 * @property string $description
 * @property double $value_amount
 * @property string $value_currency
 * @property integer $value_valueAddedTaxIncluded
 * @property string $status
 * @property string $period_startDate
 * @property string $period_endDate
 * @property string $dateSigned
 * @property string $date
 * @property integer $created_at
 * @property integer $updated_at
 * @property Document[] $documents
 * @property Awards $award
 * @property string $hint
 * @property Prolongations[] $prolongations
 */
class Contracts extends ActiveRecord
{

    public $_prolongations = [];
    public $_documents = [];

    /**
     * @inheritdoc
     */
    public static function tableName(){
        return 'api_contracts';
    }

    public function behaviors(){
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */

    public function scenarios(){
        return array_merge(parent::scenarios(), [
            'confirm' => ['dateSigned', 'status', 'contractNumber'],
        ]);
    }

    public function rules(){
        return [
            [['title'], 'string'],
            [['value_valueAddedTaxIncluded',], 'boolean'],
            [['value_valueAddedTaxIncluded',], 'default', 'value' => false],
            [
                [
                    'id',
                    'awardID',
                    'contractNumber',
                    'status',
                ],
                'required',
            ],
            [
                [
                    'description',
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
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'id',
                    'awardID',
                    'contractID',
                    'contractNumber',
                    'auction_id',
                ],
                'string',
                'max' => 255,
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
                    'status',
                ],
                'string',
                'max' => 25,
            ],
            [
                [
                    'period_startDate',
                    'period_endDate',
                    'dateSigned',
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
            ['dateSigned', 'checkDateSigned'],
            [['prolongations', 'documents', 'datePaid'], 'safe'],
        ];
    }

    public function checkDateSigned($attribute, $params = []){
        $start = strtotime($this->award->complaintPeriod_startDate);
        if(!$this->award->complaintPeriod_endDate) {
            $end = time();
        } else {
            $end = strtotime($this->award->complaintPeriod_endDate);
        }
        $dateSigned = strtotime($this->$attribute);
        if(($dateSigned < $start) || ($dateSigned > $end)) {
            $this->addError($attribute, Yii::t('app', 'Дата підписання контракту має бути між {start} та {end}', [
                'start' => Yii::$app->formatter->asDatetime($start),
                'end' => Yii::$app->formatter->asDatetime($end),
            ]));
        }
    }

    public function getHint(){
        $start = strtotime($this->award->complaintPeriod_startDate);
        if(!$this->award->complaintPeriod_endDate) {
            $end = time();
        } else {
            $end = strtotime($this->award->complaintPeriod_endDate);
        }
        return Yii::t('app', 'Дата підписання контракту має бути між {start} та {end}', [
            'start' => Yii::$app->formatter->asDatetime($start),
            'end' => Yii::$app->formatter->asDatetime($end),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => Yii::t('app', 'ID'),
            'awardID' => Yii::t('app', 'Award ID'),
            'contractID' => Yii::t('app', 'Contract ID'),
            'contractNumber' => Yii::t('app', 'Номер контракту'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'value_amount' => Yii::t('app', 'Value Amount'),
            'value_currency' => Yii::t('app', 'Value Currency'),
            'value_valueAddedTaxIncluded' => Yii::t('app', 'Value Value Added Tax Included'),
            'status' => Yii::t('app', 'Status'),
            'period_startDate' => Yii::t('app', 'Period Start Date'),
            'period_endDate' => Yii::t('app', 'Period End Date'),
            'dateSigned' => Yii::t('app', 'Дата підписання угоди'),
            'date' => Yii::t('app', 'Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getAward(){
        return $this->hasOne(Awards::className(), ['id' => 'awardID']);
    }

    public function getProlongations(){
        return !empty($this->_prolongations) ? $this->_prolongations : $this->hasMany(Prolongations::className(), ['contractID' => 'id']);
    }

    public function setProlongations($prolongations){
        $this->_prolongations = $prolongations;
    }

    public function setDocuments($values){
        $this->_documents = $values;
    }

    public function getDocuments(){
        if(!empty($this->_documents)){
            return $this->_documents;
        }
        return $this->hasMany(Documents::className(), ['relatedItem' => 'contractID']);
    }

    public function afterSave($insert, $changedAttributes){
        foreach($this->_prolongations as $item) {
            if(false == ($prolongation = Prolongations::findOne(['id' => $item['id']]))) {
                $prolongation = new Prolongations();
            }
            $prolongation->load($item, '');
            $prolongation->contractID = $this->id;
            if(!$prolongation->save(false)) {
                echo "Prolongation save error\n:";
                print_r($prolongation->errors);
            }
        }
        foreach($this->_documents as $item){
            if(false == ($document = Documents::findOne(['id' => $item['id']]))){
                $document = new Documents();
                $document->load($item, '');
                $document->relatedItem = $this->contractID;
                if(!$document->save(false)){
                    echo "Contract documents saving error\n";
                    print_r($document->errors);
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

}
