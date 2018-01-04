<?php

namespace app\models;

use app\models\User;
use Yii;
use yii\web\UploadedFile;
use app\models\Files;


class Profile extends \dektrium\user\models\Profile
{

    public $scan;
    public $document;
    public $documents;

    public function scenarios()
    {
        return [
            'organizer' => ['org_type', 'firma_full', 'region', 'city', 'postal_code', 'f_address', 'phone', 'member', 'inn', 'passport_number', 'licenseNumber', 'zkpo'],
        ];
    }

    public function rules()
    {
        return [
            'roleRequired' => ['role', 'required', 'message' => \Yii::t('app', 'Please choose your role')],
//            'checkRole' => [['org_type'], 'checkRole'],
            'usernameTrim' => ['username', 'filter', 'filter' => 'trim'],
            'usernamePattern' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.]+$/'],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique' => ['username', 'unique', 'targetClass' => $this->module->modelMap['User'],
                'message' => \Yii::t('user', 'This username has already been taken')],
            'usernameLength' => ['username', 'string', 'min' => 3, 'max' => 20],

            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailUnique' => ['email', 'unique', 'targetClass' => $this->module->modelMap['User'], 'message' => \Yii::t('user', 'This email address has already been taken')],

            'passwordRequired' => [['password', 'repeatpassword'], 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength' => ['password', 'string', 'min' => 6],
            'passwordMatch' => ['repeatpassword', 'compare', 'compareAttribute'=>'password', 'message'=>Yii::t('app', 'Passwords don\'t match')],

//            'memberphoneRequired' => ['member_phone', 'required'],
            'memberphoneMatch' => ['member_phone', 'match', 'pattern' => '/\+38\(([0-9]{3})\)-([0-9]{3})-([0-9]{2})-([0-9]{2})/'],

            'fullnameRequired' => ['fio', 'required', 'message' => \Yii::t('app', 'Please enter your fullname')],

//            'atorgRequired' => ['at_org', 'required'],

            'orgtypeRequired' => ['org_type', 'required', 'message' => \Yii::t('app', 'Please choose organization type')],

            'captchaRequired' => ['captcha', 'required'],
            'captcha' => ['captcha', 'captcha', 'when' => function(){ return !Yii::$app->request->isAjax; }],
            'oferta' => [['oferta'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'you must agree the security policy')],
            'document'  => [['document', 'scan'],   'file', 'maxSize' => 1572864,  'skipOnEmpty' => true],
            'documents' => [['documents'], 'file', 'maxFiles' => 10, 'maxSize' => 1572864,  'skipOnEmpty' => true],

            [['user_id', 'f_address', 'member', 'phone', 'role', 'postal_code', 'region'], 'required'],
            [['u_address'], 'required',
                'when' => function($model){ return $model->org_type != 'entity'; },
                'whenClient' => 'function(){return $("#profile-org_type input:checked").val() != "entity"}',
            ],
            [['licenseNumber'], 'required', 'when' => function($model){ return $model->org_type == 'financial'; },
                'whenClient' => 'function(){return $("#profile-org_type input:checked").val() == "financial"}',],
            [['inn', 'passport_number'], 'required',
                'when' => function($model){ return $model->org_type == 'individual'; },
                'whenClient' => 'function(){return $("#profile-org_type input:checked").val() == "entity"}',
            ],
            [['zkpo'], 'required',
                'when' => function($model){ return $model->org_type != 'individual'; },
                'whenClient' => 'function(){return $("#profile-org_type input:checked").val() != "individual"}',
            ],
            [['firma_full'],
                'required',
                'when' => function($model){ return $model->org_type != 'individual'; },
                'whenClient' => 'function(){return $("#profile-org_type input:checked").val() != "individual"}'],
            ['inn', 'integer'],
            [['zkpo'], 'safe'],
            [['postal_code'], 'integer',],
            [['f_address'], 'string', 'max' => 500],
            ['member_phone', 'match', 'pattern' => '/\+38\(([0-9]{3})\)-([0-9]{3})-([0-9]{2})-([0-9]{2})/'],
            ['site', 'url'],
            [['licenseNumber', 'city'], 'string', 'max' => 25],
            'document'  => [['document', 'scan'],   'file', 'maxSize' => 1572864,  'skipOnEmpty' => true],
            'documents' => [['documents'], 'file', 'maxFiles' => 10, 'maxSize' => 1572864,  'skipOnEmpty' => true],
            [['firma_full'], 'string', 'max' => 255],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'firma_full' => Yii::t('app','Full organization name'),
            'inn' => Yii::t('app','INN'),
            'zkpo' => Yii::t('app','ZKPO'),
            'u_address' => Yii::t('app','u_address'),
            'f_address' => Yii::t('app','f_address'),
            'member' => Yii::t('app','Contact person'),
            'phone' => Yii::t('app','Phone'),
            'site' => Yii::t('app','site'),
            'files' => Yii::t('app', 'files (jpg, doc, pdf)'),
            'member_phone' => Yii::t('app','Member phone'),
            'postal_code' => Yii::t('app', 'Postal Code'),
            'city' => Yii::t('app', 'City'),
            'region' => Yii::t('app', 'Region'),
            'licenseNumber' => Yii::t('app', 'License number'),
            'edrpou_bank' => Yii::t('app', 'Edrpou bank'),
            'mfo' => Yii::t('app', 'Mfo'),
            'bank_name' => Yii::t('app', 'Bank name'),
            'member_email' => Yii::t('app', 'Email'),
            'passport_number' => Yii::t('app', 'Passport code'),
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function register(){
        if(!$this->validate()){
            return false;
        }
        $valid = true;
        if($this->org_type == 'financial' && !UploadedFile::getInstance($this, 'document')){
            $this->addError('document', Yii::t('app', 'You must upload the financial license'));
            $valid = false;
        }
        if($this->role === '1' && !UploadedFile::getInstance($this, 'scan')){
            $this->addError('scan', Yii::t('app', 'Необхідно завантажити скановану копію рішення ФГВФО'));
            $valid = false;
        }
        if(!$valid){
            return false;
        }

        Files::uploadUserFiles($this, 'document', '17');
        Files::uploadUserFiles($this, 'scan', '17');
        Files::uploadUserFilesMulti($this, 'documents', '17');
        return $this->save(false);
    }

}