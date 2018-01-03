<?php

namespace api;

use api\base\ActiveRecord;
use app\helpers\Date;
use app\models\Files;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "documents".
 *
 * @property integer $id
 * @property string $documentType
 * @property string $title
 * @property string $description
 * @property string $format
 * @property string $url
 * @property integer $datePublished
 * @property integer $dateModified
 * @property string $language
 * @property string $documentOf
 * @property integer $relatedItem
 * @property integer $created_at
 * @property integer $updated_at
 */
class Documents extends ActiveRecord
{

    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_documents';
    }

    public function fields()
    {
        return [
            'id',
           'documentType',
            'title',
            'description',
//            'index',
            'format',
            'url',
            'hash',
            // 'author',
//            'datePublished' => function($model){
//                return Date::normalize($model->created_at);
//            },
//            'dateModified' => function($model){
//                return Date::normalize($model->updated_at);
//            },
            'relatedItem' => function($model){
            if($this->documentOf == 'award'){
                return Awards::findOne(['unique_id' => $this->relatedItem])->id;
            }
            return $this->documentOf == 'auction' ? $model->auction->id : $model->bid->id; },
            'language',
            'documentOf' => function($model){
                if(in_array($model->documentOf, ['bid', 'auction'])){
                    return 'tender';
                }
                else{
                    return 'lot';
                }
            },
//            'relatedItem',
//            'accessDetails',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id'], 'safe',],
            [
                [
//                    'documentType',
                    'title',
//                    'description',
//                    'format',
                    'url',
//                    'datePublished',
//                    'dateModified',
//                    'language',
//                    'documentOf',
//                    'relatedItem',
                ],
                'required',
            ],
            [
                [
                    'language'
                ],
                'default',
                'value' => 'ua',
            ],
            [
                [
                    'description',
                ],
                'string',
            ],
            [
                [
                    'lot_id',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'documentType',
                    'language',
                    'documentOf',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'id',
                    'title',
                    'format',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'url',
                ],
                'string',
                'max' => 500,
            ],
            [
                [
                    'datePublished',
                    'dateModified',
                ],
                'string',
                'max' => 35,
            ],
            [
                [
                    'hash',
                ],
                'string',
                'max' => 40,
            ],
            [['id'], 'unique'],
            [['relatedItem'/*, 'author'*/], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'documentType' => Yii::t('app', 'Document Type'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'format' => Yii::t('app', 'Format'),
            'url' => Yii::t('app', 'Url'),
            'datePublished' => Yii::t('app', 'Date Published'),
            'dateModified' => Yii::t('app', 'Date Modified'),
            'language' => Yii::t('app', 'Language'),
            'documentOf' => Yii::t('app', 'Document Of'),
            'relatedItem' => Yii::t('app', 'Related Item'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getType(){
        return $this->documentType;
    }

    public function getFile(){
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    public function getName(){
        return $this->title;
    }

    public function getLink()
    {
        return Html::a($this->title,$this->url);
    }

    public function getAuction(){
        return $this->hasOne(Auctions::className(), ['unique_id' => 'relatedItem']);
    }

    public function getBid(){
        return $this->hasOne(Bids::className(), ['unique_id' => 'relatedItem']);
    }

    public function getDocumentTypeName(){
        return isset($this->getDocumentTypes()[$this->type]) ? $this->getDocumentTypes()[$this->type] : Yii::t('app', 'No type');
    }

    public function getDocumentTypes()
    {
        return [
            ''  =>  Yii::t('app', 'No type'),
            'tenderNotice' => Yii::t('app', 'Паспорт торгів'),
            'technicalSpecifications' => Yii::t('app', 'Публічний паспорт активу'),
            'evaluationCriteria' => Yii::t('app', 'Критерії оцінки'),
            'clarifications' => Yii::t('app', 'Пояснення до питань, заданих учасниками'),
            'bidders' => Yii::t('app', 'Інформація про учасниів'),
            'illustration' => Yii::t('app', 'Ілюстрації'),
            'x_dgfPublicAssetCertificate' => Yii::t('app', 'Публічний паспорт активу'),
            'x_presentation' => Yii::t('app', 'Презентація'),
            'x_nda' => Yii::t('app', 'Договір про нерозголошення'),
            'x_dgfPlatformLegalDetails' => Yii::t('app', 'Юридична інформація Майданчиків'),
            'virtualDataRoom' => Yii::t('app', 'VDR for auction lot'),
            'x_dgfAssetFamiliarization' => Yii::t('app', 'Порядок ознайомлення з активом у віртуальній кімнаті даних'),
            'commercialProposal' => Yii::t('app', 'Цінова пропозиція'),
            'qualificationDocuments' => Yii::t('app', 'Документи, що підтверджують кваліфікацію'),
            'eligibilityDocuments' => Yii::t('app', 'Документи, що підтверджують відповідність'),
            'auctionProtocol' => Yii::t('app', 'Протокол торгів'),
            'financialLicense' => Yii::t('app', 'Фінансова ліцензія'),
        ];
    }

}
