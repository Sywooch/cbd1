<?php

namespace app\models;

use Yii;
use app\models\Profile;
use app\models\Messages;
use api\Organizations;
use yii\helpers\Html;
use yii\helpers\Url;
use dektrium\user\helpers\Password;



class User extends \dektrium\user\models\User
{

    private $_profile;

    public function scenarios()
    {
        return [
            'register' => ['username', 'email', 'password', 'role'],
            'connect'  => ['username', 'email'],
            'create'   => ['role','username', 'email', 'password'],
            'update'   => ['username', 'email', 'password'],
            'settings' => ['username', 'email', 'password'],
        ];
    }

    public function rules()
    {
        return [
            'roleRequired' => ['role', 'required'],
            'orgTypeRequired' => [['org_type'], 'required'],
            'usernameRequired' => ['username', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'usernameMatch' => ['username', 'match', 'pattern' => '/^[-a-zA-Z0-9_\.@]+$/'],
            'usernameLength' => ['username', 'string', 'min' => 3, 'max' => 25],
            'usernameUnique' => ['username', 'unique'],
            'usernameTrim' => ['username', 'trim'],
            'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'emailPattern' => ['email', 'email'],
            'emailLength' => ['email', 'string', 'max' => 255],
            'emailUnique' => ['email', 'unique'],
            'emailTrim' => ['email', 'trim'],
            'passwordRequired' => ['password', 'required', 'on' => ['register']],
            'passwordLength' => ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],
            'passwordMatch' => ['repeatpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],

            ['verifyCode','captcha', 'captchaAction'=>'auth/open/captcha'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'role'              => \Yii::t('app', 'Role'),
            'username'          => \Yii::t('app', 'Username'),
            'email'             => \Yii::t('app', 'Email'),
            'registration_ip'   => \Yii::t('app', 'Registration ip'),
            'unconfirmed_email' => \Yii::t('app', 'New email'),
            'password'          => \Yii::t('app', 'Password'),
            'repeatpassword'    => \Yii::t('app', 'Repeat Password'),
            'created_at'        => \Yii::t('app', 'Registration time'),
            'confirmed_at'      => \Yii::t('app', 'Confirmation time'),
            'fax'               => \Yii::t('app', 'fax'),
            'captcha'           => \Yii::t('app', 'captcha'),
        ];
    }

    public function getOrganization(){
        return $this->hasOne(Organizations::className(), ['user_id' => 'id']);
    }

    public function getUserFiles(){
        return $this->hasMany(Files::className(), ['user_id' => 'id'])->andWhere(['type' => '17']);
    }

    public function confirm()
    {
        $result = (bool) $this->updateAttributes(['confirmed_at' => time()]);
        $text = Yii::t('app', 'Your account has been confirmed. {link}',
            [
                'link' => Html::a(Yii::t('app', 'Перейти'), Url::to(['/user/login'], true))
            ]);

        $message = Yii::createObject(Messages::className());
        $message->sendMessage($this->id, $text, true);

        return $result;
    }

    public function afterDelete()
    {
        foreach($this->userFiles as $file){
            $file->delete();
        }
        parent::afterDelete();
    }

    /** @inheritdoc */
    public function afterSave($insert, $changedAttributes)
    {
        // parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            if ($this->_profile == null) {
                $this->_profile = \Yii::createObject(Profile::className());
                $this->_profile->org_type = $this->org_type;
            }
            $this->_profile->link('user', $this);
        }
    }

}

