<?php

namespace api;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "api_prolongations".
 *
 * @property integer $unique_id
 * @property string $id
 * @property string $contractID
 * @property string $dateCreated
 * @property string $status
 * @property string $description
 * @property string $datePublished
 * @property string $decisionID
 * @property string $reason
 * @property Documents[] $documents
 */

class Prolongations extends ActiveRecord
{

    public $_documents;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_prolongations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'contractID', 'status', 'decisionID'], 'required'],
            [['description', 'reason'], 'string'],
            [['id', 'contractID', 'decisionID'], 'string', 'max' => 255],
            [['dateCreated', 'status', 'datePublished'], 'string', 'max' => 35],
            [['id'], 'unique'],
            [['documents'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unique_id' => Yii::t('app', 'Unique ID'),
            'id' => Yii::t('app', 'ID'),
            'contractID' => Yii::t('app', 'Contract ID'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
            'datePublished' => Yii::t('app', 'Date Published'),
            'decisionID' => Yii::t('app', 'Decision ID'),
            'reason' => Yii::t('app', 'Reason'),
        ];
    }

    public function getReason(){
        $reasons = [
            'dgfPaymentImpossibility' => Yii::t('app', 'Відсутність оплати'),
            'dgfLackOfDocuments' => Yii::t('app', 'Відсутні деякі з обов\'язкових документів'),
            'dgfLegalObstacles' => Yii::t('app', 'Юридичні обставини'),
            'other' => Yii::t('app', 'Інше'),
        ];
        return $reasons[$this->reason];

    }

    public function setDocuments($values){
        $this->_documents = $values;
    }

    public function getDocuments(){
        if(!empty($this->_documents)){
            return $this->documents;
        }
        return $this->hasMany(Documents::className(), ['relatedItem' => 'id']);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        foreach($this->_documents as $item){
            if(false == ($document = Documents::findOne(['id' => $item['id']]))){
                $document = new Documents();
            }
            $document->load($item, '');
            $document->relatedItem = $this->id;
            if(!$document->save(false)){
                echo "Prolongation document saving error\n";
                print_r($document->errors);
            }
        }
        echo $this->id."\n";
    }
}
