<?php

namespace app\models;

use api\Bids;
use api\Documents;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;


/**
 * @property string $user_id
 * @property string $bid_id
**/


class Files extends ActiveRecord
{

    public $file;

    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path','name','user_id', 'type'], 'required'],
            [['path','name'], 'string', 'max' => 255],
            [['user_id','auction_id','lot_id', 'bid_id', 'cancellation_id'], 'integer'],
            [['file'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'maxSize' => 3572864/*1572864*/, 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app', 'Path'),
            'name' => Yii::t('app', 'name'),
            'user_id' => Yii::t('app', 'user_id'),
            'auction_id' => Yii::t('app', 'auction_id'),
            'lot_id' => Yii::t('app', 'lot_id'),
            'type' => Yii::t('app', 'Document Type'),
            'file' => Yii::t('app', 'File'),
        ];
    }

    public function getLot(){
        return $this->hasOne(Lots::className(), ['id' => 'lot_id']);
    }

    public function getDocument(){
        return $this->hasOne(Documents::className(), ['file_id' => 'id']);
    }

    public function saveFile($insert)
    {
        Yii::$app->db->createCommand()->insert('files', [
            'type' => isset($insert['type']) ? $insert['type'] : 'document',
            'name' => $insert['name'],
            'path' => $insert['path'],
            'user_id' => $insert['user_id'],
            'auction_id' => isset($insert['auction_id']) ? $insert['auction_id'] : null,
            'lot_id' => $insert['lot_id'],
        ])
            ->execute();
        return $this->getFileID($insert['user_id'],$insert['lot_id'],$insert['name']);
    }
    public function updateFile($update)
    {
        Yii::$app->db->createCommand("UPDATE `files` SET name='".$update['name']."' WHERE `id`=" . $update['file_id'])->execute();
        return true;
    }

    private function getFileID($user,$lot,$name)
    {
        $res = $this->find()->where(['user_id'=>$user,'lot_id'=>$lot,'name'=>$name])->one();
        return $res->id;
    }

    public static function transliteration($str)
    {
        // ГОСТ 7.79B
        $transliteration = array(
            'Є' => 'YE', 'є' => 'ye',
            'Ї' => 'YI', 'ї' => 'yi',
            'І' => 'I', 'і' => 'i',
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'Yo', 'ё' => 'yo',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Cz', 'ц' => 'cz',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Shh', 'щ' => 'shh',
            'Ъ' => 'ʺ', 'ъ' => 'ʺ',
            'Ы' => 'Y`', 'ы' => 'y`',
            'Ь' => '', 'ь' => '',
            'Э' => 'E`', 'э' => 'e`',
            'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya',
            '№' => '#', 'Ӏ' => '‡',
            '’' => '`', 'ˮ' => '¨',
        );

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z.\-]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = trim($str, '-');

        return $str;
    }

    public function lotDocumentTypes(){
        return [
            '' => Yii::t('app', 'Choose document type'),
            'notice' => Yii::t('app', 'Офіційне повідомлення, що містить деталі аукціону'),
            'tenderNotice' => Yii::t('app', 'Паспорт торгів'),
            'technicalSpecifications' => Yii::t('app', 'Технічні спецификації'),
            'evaluationCriteria' => Yii::t('app', 'Критерії оцінки'),
            'clarifications' => Yii::t('app', 'Пояснення до питань, заданих учасниками'),
//            'bidders' => Yii::t('app', 'Інформація про учасниів'),
            'illustration' => Yii::t('app', 'Ілюстрації'),
            'x_dgfPublicAssetCertificate' => Yii::t('app', 'Публічний паспорт активу'),
            'x_presentation' => Yii::t('app', 'Презентація'),
            'x_nda' => Yii::t('app', 'Договір про нерозголошення'),
//            'x_dgfPlatformLegalDetails' => Yii::t('app', 'Юридична інформація Майданчиків'),
//            'virtualDataRoom' => Yii::t('app', 'VDR'),
//            'x_dgfAssetFamiliarization' => Yii::t('app', 'Порядок ознайомлення з активом у кімнаті даних'),
        ];
    }

    public function bidDocumentTypes(){
        return [
            '' => Yii::t('app', 'Choose document type'),
            'commercialProposal' => Yii::t('app', 'Цінова пропозиція'),
            'qualificationDocuments' => Yii::t('app', 'Документи, що підтверджують кваліфікацію'),
            'eligibilityDocuments' => Yii::t('app', 'Документи, що підтверджують відповідність'),
//            'auctionProtocol' => Yii::t('app', 'Протокол торгів'),
            'financialLicense' => Yii::t('app', 'Фінансова ліцензія'),
            // 'contractDocument' => Yii::t('app', 'Документи контракту'),
        ];
    }

    public static function documentType($type){
        $types = [
            'notice' => Yii::t('app', 'Офіційне повідомлення, що містить деталі аукціону'),

            'technicalSpecifications' => Yii::t('app', 'Технічні специфікації'),

            'evaluationCriteria' => Yii::t('app', 'Критерії оцінки'),

            'clarifications' => Yii::t('app', 'Пояснення до питань, заданих учасниками'),

            'bidders' => Yii::t('app', 'Інформація про учасниів'),

            'virtualDataRoom' => Yii::t('app', 'VDR'),

            'illustration' => Yii::t('app', 'Ілюстрації'),

            'x_dgfPublicAssetCertificate' => Yii::t('app', 'Публічний паспорт активу'),

            'x_presentation' => Yii::t('app', 'Презентація'),

            'x_nda' => Yii::t('app', 'Договір про нерозголошення'),

            'x_dgfPlatformLegalDetails' => Yii::t('app', 'Юридична інформація Майданчиків'),

            'x_dgfAssetFamiliarization' => Yii::t('app', 'Місце та форма прийому заяв на участь в аукціоні та банківські реквізити для зарахування гарантійних внесків'),

            'winningBid' => Yii::t('app','Документи виграшної ставки'),

            'notice' => Yii::t('app', 'Повідомлення про Договір'),

            'contractSigned' => Yii::t('app', 'Підписаний Договір'),

            'contractAnnexe' => Yii::t('app', 'Додатки до Договору'),

            'commercialProposal' => Yii::t('app', 'Цінова пропозиція'),

            'qualificationDocuments' => Yii::t('app', 'Документи, що підтверджують кваліфікацію'),

            'eligibilityDocuments' => Yii::t('app', 'Документи, що підтверджують відповідність'),

            'financialLicense' => Yii::t('app', 'Фінансова ліцензія'),

            'auctionProtocol' => Yii::t('app', 'Протокол торгів'),

            'tenderNotice' => Yii::t('app', 'Паспорт торгів'),

        ];
        return isset($types[$type]) ? $types[$type] : Yii::t('app', 'Auction document');
    }

    public function profileDocumentTypes(){
        return [
            '17' => Yii::t('app', 'Profile documents'),
        ];
    }

    public function upload(){
        $this->user_id = Yii::$app->user->id;

        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . "lots/" . Yii::$app->user->id . '/';
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name =  $this->file->baseName . '_' . time() . '_' . static::transliteration(static::lotDocumentTypes()[$this->type] . '.' . $this->file->extension);

            @mkdir(__DIR__ . '/' . $this->path, 0777);
            if(!$this->validate()){
                return false;
            }

            $this->file->saveAs(__DIR__ . '/' . $this->path . $this->name);

            return $this->save();
        }
        return false;
    }

    public function uploadBidFile(){

        $this->user_id = Yii::$app->user->id;

        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . "bids/" . Yii::$app->user->id . '/';
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name =  time() . '_' . static::transliteration(static::bidDocumentTypes()[$this->type] . '.' . $this->file->extension);

            @mkdir(__DIR__ . '/' . $this->path, 0777);
            if(!$this->validate()){
                return false;
            }

            $this->file->saveAs(__DIR__ . '/' . $this->path . $this->name);

            return $this->save();
        }
        return false;
    }

    public function bidUpload(){
        $this->user_id = Yii::$app->user->id;

        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . 'bids/' . Yii::$app->user->id . '/';
//            $this->name =  time() . '_' . static::transliteration(static::bidDocumentTypes()[$this->type]) . '.' . $this->file->extension;
            @mkdir(__DIR__ . '/' . $this->path, 0777);
            if(!$this->validate()){
                return false;
            }

            $this->file->saveAs(__DIR__ . '/' . $this->path . $this->name);

            return $this->save();
        }
        return false;
    }

    public function updateLotFile(){
        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            @unlink($this->path . $this->name);
            @mkdir(Yii::$app->params['uploadPath'] . 'lots/' . Yii::$app->user->id . '/', 0777);
            $this->path = Yii::$app->params['uploadPath'] . 'lots/' . Yii::$app->user->id . '/';
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name = time() . '_' . static::transliteration(static::lotDocumentTypes()[$this->type]) . '.' . $this->file->extension;
            $this->file->saveAs(__DIR__ . '/' . $this->path . $this->name);
            return $this->save(false);
        }
        return false;
    }

    public function uploadAuctionProtocol($bid_id){
        if(false == ($bid = Bids::findOne(['unique_id' => $bid_id]))){
            throw new NotFoundHttpException();
        }
        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . 'awards/' . $bid_id . '/';
            @mkdir(__DIR__ . '/' . $this->path, 0777);
//            $this->name = time() . '_' . static::transliteration($this->file->baseName . '.' . $this->file->extension);
            $this->name = $this->file->baseName . '.' . $this->file->extension;
            if($this->file->saveAs($this->path . $this->name)){
                $this->save(false);
                $data = Yii::$app->apiUpload->upload($this->path . $this->name);
                if($data){
                    $document = new Documents(array_merge(
                        $data['data'],
                        [
                            'documentOf' => 'bid',
                            'documentType' => 'auctionProtocol',
                            'relatedItem' => $bid_id,
                            'language' => 'ua',
                            'author' => Yii::$app->user->can('org') ? 'auction_owner' : 'bid_owner',
                            'file_id' => $this->id,
                            'id' => explode('?', $data['address'])[0],
                        ]));

                    $document->save(false);

                    return Yii::$app->api->addAuctionProtocol($bid);
                }
                else{
                    @unlink($this->path . $this->name);
                }
            }
        }
        return false;
    }

    public function uploadContractDocument(Bids $bid){
        $this->file = UploadedFile::getInstance($this, 'file');
        if($this->file){
            $this->path = Yii::$app->params['uploadPath'] . 'awards/' . $bid->unique_id . '/';
            @mkdir(__DIR__ . '/' . $this->path, 0777);
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name = time() . '_' . static::transliteration($this->file->baseName . '.' . $this->file->extension);
            if($this->file->saveAs(__DIR__ . '/' . $this->path . $this->name)){
                $this->save(false);
                $data = Yii::$app->apiUpload->upload($this->path . $this->name);
                if($data){
                    $document = new Documents();
                    $document->load(array_merge(
                        $data['data'],
                        [
                            'documentOf' => 'bid',
                            'documentType' => 'contractSigned',
                            'relatedItem' => $bid->unique_id,
                            'language' => 'ua',
                            'file_id' => $this->id,
                            'id' => explode('?', $data['address'])[0],
                        ]), '');
                    $document->save(false);
                    return Yii::$app->api->addContractDocument($bid, $document);
                }
                else{
                    @unlink($this->path . $this->name);
                }
            }
        }
        return false;
    }

    public function uploadCancellationDocuments($id){
        if(false == ($bid = Bids::findOne(['unique_id' => $bid_id]))){
            throw new NotFoundHttpException();
        }
        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . 'awards/' . $bid_id . '/';
            @mkdir(__DIR__ . '/' . $this->path, 0777);
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name = time() . '_' . static::transliteration($this->file->baseName . '.' . $this->file->extension);
            if($this->file->saveAs($this->path . $this->name)){
                $this->save(false);
                $data = Yii::$app->apiUpload->upload($this->path . $this->name);
                if($data){
                    $document = new Documents();
                    $document->load(array_merge(
                        $data['data'],
                        [
                            'documentOf' => 'bid',
                            'relatedItem' => $bid_id,
                            'language' => 'ua',
                            'file_id' => $this->id,
                            'id' => explode('?', $data['address'])[0],
                        ]), '');
                    $document->save(false);
                    return Yii::$app->api->addCancellationDocuments($bid);
                }
                else{
                    @unlink($this->path . $this->name);
                }
            }
        }
        return false;
    }

    public function uploadDisqualificationDocument($bid){
        if(false != ($this->file = UploadedFile::getInstance($this, 'file'))){
            $this->path = Yii::$app->params['uploadPath'] . 'reasons/' . $bid->award->unique_id . '/';
            @mkdir(__DIR__ . '/' . $this->path, 0777);
            $this->name = $this->file->baseName . '.' . $this->file->extension;
//            $this->name = time() . '_' . static::transliteration($this->file->baseName . '.' . $this->file->extension);
            if($this->file->saveAs($this->path . $this->name)){
                $this->save(false);
                $data = Yii::$app->apiUpload->upload($this->path . $this->name);
                if($data){
                    $document = new Documents();
                    $document->load(array_merge(
                        $data['data'],
                        [
                            'documentOf' => 'award',
                            'relatedItem' => $bid->award->unique_id,
                            'language' => 'ua',
                            'file_id' => $this->id,
                            'id' => explode('?', $data['address'])[0],
                        ]), '');
                    $document->save(false);
                    return $document;
                }
                else{
                    @unlink($this->path . $this->name);
                }
            }
        }
        return false;
    }

    public static function uploadUserFiles($model, $attribute, $type){
        $file =  UploadedFile::getInstance($model, $attribute);
        if($file){
            $path = Yii::$app->params['uploadPath'] . 'user_files/' . Yii::$app->user->id . '/';
            @mkdir(__DIR__ . '/' . $path, 0777);

            $name = $file->baseName . '.' . $file->extension;
//            $name = time() . static::transliteration($file->baseName) . '.' . $file->extension;
            if($file->saveAs($path . $name)){
                $fileModel = new Files([
                    'type' => $type,
                    'path' => $path,
                    'name' => $name,
                    'user_id' => Yii::$app->user->id,
                ]);
                $model->$attribute = $file->baseName . '.' . $file->extension;

                $fileModel->save(false);
            }
        }
    }

    public static function uploadUserFilesMulti($model, $attribute, $type){
        $files =  UploadedFile::getInstances($model, $attribute);
        foreach($files as $file){
            $path = Yii::$app->params['uploadPath'] . 'user_files/' . Yii::$app->user->id . '/';
            @mkdir(__DIR__ . '/' . $path, 0777);
            $name = $file->baseName . '.' . $file->extension;
//            $name = time() . static::transliteration($file->baseName) . '.' . $file->extension;
            if($file->saveAs($path . $name)){
                $fileModel = new Files([
                    'type' => $type,
                    'path' => $path,
                    'name' => $name,
                    'user_id' => Yii::$app->user->id,
                ]);
                $model->$attribute = $file->baseName . '.' . $file->extension;

                $fileModel->save(false);
            }
        }
    }

    public function getFullPath(){
        return $this->path . $this->name;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        unlink($this->path . $this->name);
    }

}
