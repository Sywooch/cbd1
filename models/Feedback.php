<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Feedback extends Model
{

    public $name;

    public $email;

    public $text;

    public function rules(){
        return [
            [['email'], 'email'],
            [['name' ,'email', 'text'], 'required'],
            [['name', 'email'], 'string', 'max' => 255],
            ['text', 'string', 'max' => 5000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Ім\'я'),
            'email' => Yii::t('app', 'Email'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    public function send(){
        if(!$this->validate()){
            return false;
        }
        return Yii::$app->mailer->compose()
            ->setSubject(Yii::t('app', 'Нове звернення'))
            ->setFrom(getenv('SMTP_LOGIN'))
            ->setTo('info@biddingtime.com.ua')
            ->setTextBody(<<< TXT
Нове звернення на сайті biddingtime.com.ua.
Ім'я відправника - {$this->name}.
Текст звернення:
'{$this->text}'.
Пошта відправника - {$this->email} 
TXT

            )
            ->send();
    }

}