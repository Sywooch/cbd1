<?php

namespace app\models;

use dektrium\user\models\LoginForm;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;


/**
 * ContactForm is the model behind the contact form.
 */
class RegistrationForm extends \dektrium\user\models\RegistrationForm
{

    public $role;
    public $member_phone;
    public $fio;
    public $at_org;
    public $org_type;
    public $repeatpassword;
    public $email;
    public $username;
    public $password;
    public $captcha;
    public $document;
    public $documents;
    public $scan;

    public $oferta;

    public function scenarios()
    {
        return [
            'register' => ['role', 'email', 'username', 'password', 'repeatpassword', 'oferta'],
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
        ];
    }

//    public function checkRole($attribute){
//        if(($this->role === '1') && in_array($this->org_type, ['entity', 'individual'])){
//            $this->addError($attribute, Yii::t('app', 'Організатор торгів може бути тільки фінансова установа'));
//        }
//    }

    public function attributeLabels()
    {
        return [
            'role'		=> \Yii::t('app', 'Role'),
            'fio' => \Yii::t('app', 'Full name'),
            'email'		=> \Yii::t('app', 'Email'),
            'username'	=> \Yii::t('app', 'Username'),
            'password'	=> \Yii::t('app', 'Password'),
            'repeatpassword'    => \Yii::t('app', 'Repeat Password'),
            'member' => \Yii::t('app', 'MemeberFrom ID'),
            'member_phone' => \Yii::t('app', 'Phone number'),
            'at_org' => \Yii::t('app', 'Organization name'),
            'org_type' => \Yii::t('app', 'Organization type'),
            'captcha' => \Yii::t('app', 'Captcha'),
            'oferta' => Yii::t('app', 'Я ознайомлений та погоджуюсь з {Правилами проведення} торгів (аукціонів) з продажу майна неплатоспроможних банків в електронній формі, {Політикою конфіденційності} і {Договором оферти}',
                [
                    'Правилами проведення' => Html::a('Правилами проведення', ['/downloads-files/rules'], ['target' => '_blank']),
                    'Політикою конфіденційності' => Html::a('Політикою конфіденційності', '/%D0%9F%D0%BE%D0%BB%D1%96%D1%82%D0%B8%D0%BA%D0%B0%20%D0%BA%D0%BE%D0%BD%D1%84%D1%96%D0%B4%D0%B5%D0%BD%D1%86%D1%96%D0%B9%D0%BD%D0%BE%D1%81%D1%82%D1%96.pdf', ['target' => '_blank']),
                    'Договором оферти' => Html::a('Договором оферти', '/%D0%94%D0%BE%D0%B3%D0%BE%D0%B2%D1%96%D1%80%20%D0%BF%D1%83%D0%B1%D0%BB%D1%96%D1%87%D0%BD%D0%BE%D1%97%20%D0%9E%D1%84%D0%B5%D1%80%D1%82%D0%B8.pdf', ['target' => '_blank']),
                ]),
            'document' => Yii::t('app', 'Financial license'),
            'documents' => Yii::t('app', 'Інші документи компанії'),
            'scan' => Yii::t('app', 'Сканована копія рішення дирекціі ФГВФО про призначення'),
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }


        /** @var User $user */
        $user = Yii::createObject(User::className());
        $user->setScenario('register');
        $this->loadAttributes($user);

        if (!$user->register()) {
            return false;
        }

        Yii::$app->getUser()->login($user, 3600 * 24 * 30);

//        Files::uploadUserFiles($this, 'document', '17');
//        Files::uploadUserFiles($this, 'scan', '17');
//        Files::uploadUserFilesMulti($this, 'documents', '17');

        Yii::$app->session->setFlash(
            'info',
            Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email')
        );

        return true;
    }

}
