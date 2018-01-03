<?php

namespace api;

use Yii;
use yii\base\Model;
use app\helpers\Date;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Files;
use app\models\Messages;
use yii\web\UploadedFile;
use api\base\ActiveRecord;

/**
 * This is the model class for table "cancellations".
 *
 * @property string $id
 * @property string $reason
 * @property string $status
 * @property string $date
 * @property string $cancellationOf
 * @property string $relatedItem
 * @property integer $created_at
 * @property integer $updated_at
 */
class Cancellations extends ActiveRecord
{

    public $files;
    private $_documents = [];

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_cancellations';
    }

    public function fields()
    {
        return [
            'reason',
            'status' => function($model){ return 'pending'; },
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cancellationOf', 'reason'], 'required'],
            [['reason', 'description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'relatedItem'], 'string', 'max' => 255],
            [['status', 'cancellationOf'], 'string', 'max' => 25],
            [['date'], 'string', 'max' => 35],
            [['date'], 'default', 'value' => Date::normalize(date('Y-m-d H:i:s', time()))],
            [['files'], 'file', 'maxFiles' => 10, 'skipOnEmpty' => true],
            [['description'], 'string', 'max' => 500],
            [['documents'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'reason' => Yii::t('app', 'Reason'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'date' => Yii::t('app', 'Date'),
            'cancellationOf' => Yii::t('app', 'Cancellation Of'),
            'relatedItem' => Yii::t('app', 'Related Lot'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'files' => Yii::t('app', 'Document'),
        ];
    }

    public function getAuction(){
        return $this->hasOne(Auctions::className(), ['unique_id' => 'relatedItem']);
    }

    public function getLot(){
        return $this->hasOne(Lots::className(), ['id' => 'baseAuction_id'])->via('auction');
    }

    public function uploadDocument(){
        $fileModel = new Files();
        if(false != ($file = UploadedFile::getInstance($fileModel, 'file'))){
            @mkdir(Yii::$app->params['uploadPath'] . 'cancellations/' . $this->unique_id, 0777);
            $path = Yii::$app->params['uploadPath'] . 'cancellations/' . $this->unique_id . '/';
            $name = $file->baseName . '.' . $file->extension;

            if($file->saveAs($path . $name)){
                $fileModel = new Files([
                    'type' => 'cancellationFile',
                    'cancellation_id' => $this->unique_id,
                    'name' => $name,
                    'path' => $path,
                ]);
                $fileModel->save(false);
                $document = new Documents([
                    'relatedItem' => $this->unique_id,
                    'documentOf' => 'cancellation',
                    'file_id' => $fileModel->id,
                ]);
                $document->load(Yii::$app->apiUpload->upload($fileModel->fullPath), '');
                $document->documentOf = 'cancellation';
                $document->save(false);
                return Yii::$app->api->addCancellationDocument($this, $document, $this->description);
            }
        }
        return false;
    }

    public function reuploadDocument($old_document){
        $fileModel = new Files();
        if(false != ($file = UploadedFile::getInstance($fileModel, 'file'))){
            @mkdir(Yii::$app->params['uploadPath'] . 'cancellations/' . $this->unique_id, 0777);
            $path = Yii::$app->params['uploadPath'] . 'cancellations/' . $this->unique_id . '/';
            $name = $file->baseName . '.' . $file->extension;

            if($file->saveAs($path . $name)){
                $fileModel = new Files([
                    'type' => 'cancellationFile',
                    'cancellation_id' => $this->unique_id,
                    'name' => $name,
                    'path' => $path,
                ]);
                $fileModel->save(false);
                $document = new Documents([
                    'relatedItem' => $this->unique_id,
                    'documentOf' => 'cancellation',
                    'file_id' => $fileModel->id,
                ]);
                $document->load(Yii::$app->apiUpload->upload($fileModel->fullPath), '');
                $document->documentOf = 'cancellation';
                $document->save(false);
                return Yii::$app->api->replaceCancellationDocument($this, $old_document, $document, $this->description);
            }
        }
        return false;
    }

    public function setDocuments($values){
        $this->_documents = $values;
    }

    public function getDocuments(){
        return $this->hasMany(Documents::className(), ['relatedItem' => 'unique_id'])->andOnCondition(['documentOf' => ['cancellation']]);
    }

    public function confirm(){
        foreach($this->auction->bids as $bid){
            Yii::createObject(Messages::className())
                ->sendMessage(
                    $bid->user_id,
                    Yii::t('app', 'Auction has been cancelled') . '. ' .
                    Html::a(Yii::t('app', 'View cancelled auction'),
                        Url::to(['/public/view', 'id' => $bid->apiAuction->unique_id], true)),
                    true
                );
        }
        return Yii::$app->api->confirmCancellation($this);
    }

    public function afterSave($insert, $changedAttributes){
        foreach($this->_documents as $item){
            if(false == ($document = Documents::findOne(['id' => $item['id']]))){
                $document = new Documents();
            }
            $document->load($item, '');
            $document->relatedItem = $this->unique_id;
            $document->documentOf = 'cancellation';
            $document->save(false);
        }
    }

}