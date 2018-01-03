<?php

namespace app\components;



use Yii;

/**
 * Created by PhpStorm.
 * User: slava
 * Date: 19.01.17
 * Time: 10:21
 */
class Mailer extends \dektrium\user\Mailer
{
    public $viewPath = '@app/views/user/mail';

    public function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ?
                Yii::$app->params['adminEmail']
                : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], array_merge($params, ['module' => $this->module]))
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }

}
