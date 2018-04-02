<?php

namespace app\models;


use Yii;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;

/**
 */
class Posts extends \yii\db\ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return 'posts';
    }
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
            ]
        ];
    }

    public function rules()
    {
        return [
            [['title', 'text', 'cat_id','description','key_words','h1','short_text'], 'required'],
            [['text','short_text','slug','text_ru','short_text_ru'], 'string'],
            [['cat_id', 'created_at'], 'integer'],
            [['title', 'picture','description','key_words','h1','title_ru','description_ru','key_words_ru','h1_ru'], 'string', 'max' => 255],
            [['title','slug'], 'unique'],
            [['imageFile'],'file','extensions' => 'png, jpg, gif']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'imageFile' => Yii::t('app', 'Picture'),
            'cat_id' => Yii::t('app', 'Cat ID'),
            'h1' => Yii::t('app', 'H1'),
            'short_text' => Yii::t('app','Short text'),
            'title_ru' => Yii::t('app', 'Title ru'),
            'text_ru' => Yii::t('app', 'Text ru'),
            'cat_id_ru' => Yii::t('app', 'Cat ID ru'),
            'h1_ru' => Yii::t('app', 'H1 ru'),
            'short_text_ru' => Yii::t('app','Short text ru'),
            'slug' => Yii::t('app','Slug'),
        ];
    }

    public function getPicture()
    {
        return $this->picture ? '/'. $this->picture : false ;
    }

    public function getCategory()
    {
        return $this->hasOne(Categoriesblog::className(),['id' => 'cat_id']);
    }

    public function getShort($length = 600){
        return mb_strlen($this->text, 'utf-8') > $length ? mb_substr($this->text, 0, $length, 'utf-8') . '...' : $this->text;
    }

    public function getLogo()
    {
        return $this->picture ? '/'. $this->picture : false ;
    }

    public function upload(){

        if(false != ($file = UploadedFile::getInstance($this, 'picture'))){
            $filename = time() . $file->baseName . '.' . $file->extension;
            $path = 'uploads/posts';
            if($file->saveAs($path . DIRECTORY_SEPARATOR . $filename)){
                $this->picture = $filename;
                return $this->save();
            }
        }
        return $this->save();
    }

}
