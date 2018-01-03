<?php

namespace api;

use api\base\ActiveRecord;
use app\models\Lots;
use app\models\User;
use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property string $dateAnswered
 * @property string $answer
 * @property string $questionOf
 * @property string $relatedItem
 * @property integer $created_at
 * @property integer $updated_at
 */
class Questions extends ActiveRecord
{

    private $_organization;

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_questions';
    }

    public function scenarios()
    {
        return [
            'parse' => ['created_at', 'title', 'description', 'questionOf', 'answer', 'dateAnswered'],
            'create' => ['created_at', 'title', 'description', 'questionOf'],
            'answer' => ['answer', 'updated_at', 'dateAnswered'],
            'publish' => ['id'],
        ];
    }

    public function fields(){
        $fields = [
            'title',
            'description',
            'answer',
            'id',
            'date',
            'questionOf',
            'relatedItem',
             'author' => function($model){
                 $author = $model->author->toArray();
                 unset($author['additionalIdentifiers']);
                 return $author;
             }
        ];
        if(!$this->author){
            unset($fields['author']);
        }
        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['author_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'answer'], 'string'],
            [['title', 'relatedItem'], 'string', 'max' => 255],
            [['date', 'dateAnswered'], 'string', 'max' => 35],
            [['questionOf'], 'string', 'max' => 25],
//            [['relatedItem'], 'exist', 'targetClass' => Lots::className(), 'targetAttribute' => 'id'],
            [['id', 'organization'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'date' => Yii::t('app', 'Date'),
            'dateAnswered' => Yii::t('app', 'Date Answered'),
            'answer' => Yii::t('app', 'Answer'),
            'questionOf' => Yii::t('app', 'Question Of'),
            'relatedItem' => Yii::t('app', 'Related Item'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getOrganization(){
        return $this->hasOne(Organizations::className(), ['unique_id' => 'author_id']);
    }

    public function getAuthor(){
        return $this->organization;
    }

    public function getTargetName(){
        $target = $this->questionOf == 'tender' ? $this->auction : $this->item;
        return $this->questionOf == 'tender' ? $target->title : $target->description;
    }

    public function setAuthor($organization){
        $this->_organization = $organization;
    }

    public function getLot(){
        if($this->questionOf == 'tender'){
            return $this->hasOne(Lots::className(), ['id' => 'baseAuction_id'])->via('auction');
        }
        else{
            return $this->hasOne(\app\models\Lots::className(), ['id' => 'auction_id'])->via('item');
        }
    }

    public function getAuction(){
        if($this->questionOf == 'tender'){
            return $this->hasOne(Auctions::className(), ['id' => 'relatedItem']);
        }
        else{
            return $this->hasOne(Items::className(), ['id' => 'relatedItem']);
        }
    }

    public function getAuctionId(){
        if($this->questionOf == 'tender'){
            return $this->relatedItem;
        }
        else{
            return $this->item->auction->id;
        }
    }

    public function getItem(){
        return $this->hasOne(Items::className(), ['id' => 'relatedItem']);
    }

    public function getOrganizator(){
        return $this->hasOne(User::className(), ['id' => 'user_id'])->via('lot');
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($this->_organization){
            if(false == ($organization = Organizations::findOne(['name' => $this->_organization['name']]))){
                $organization = new Organizations;
                $organization->load($this->_organization, '');
            }
            $organization->load($this->_organization, '');
            if(!$organization->save(false)){
                echo "Author saving error:\n";
                print_r($organization->errors);
            }
            $this->updateAttributes(['author_id' => $organization->unique_id]);
        }

    }

}
