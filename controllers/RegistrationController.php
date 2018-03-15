<?php

namespace app\controllers;

use app\models\Messages;
use app\traits\AjaxValidationTrait;
use Yii;
use app\models\RegistrationForm;
use yii\filters\AccessControl;
use app\models\Profile;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\rbac\DbManager;

// For api inject
use api\Organizations;
use api\Identifiers;


class RegistrationController extends \dektrium\user\controllers\RegistrationController
{

    public $user;

    USE AjaxValidationTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['register', 'connect', 'resend'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend', 'organizer'], 'roles' => ['@']],
                ]
            ],
        ];
    }

    public function actionRegister()
    {
        $model = \Yii::createObject(RegistrationForm::className());
        $model->setScenario('register');

        $this->ajaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {

            $role = Yii::$app->authManager->getRole($model->role == '1' ? 'org' : 'member');

            // Задаем роль
            Yii::$app->authManager->assign($role, Yii::$app->user->identity->id);

            return $this->redirect('/registration/organizer');
        }

        return $this->render('register', [
            'model'  => $model,
        ]);
    }

    public function actionOrganizer(){

        $model = Profile::findOne(Yii::$app->user->identity->getId());
        $model->setScenario('organizer');

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->register()) {

            $this->registerOrganization($model);

            $admins = User::find()->leftJoin('auth_assignment', '`auth_assignment`.`user_id` = `user`.`id`')->where(['auth_assignment.item_name' => 'admin'])->all();
            foreach($admins as $admin){
                Yii::createObject(Messages::className())->sendMessage(
                    $admin->id,
                    Yii::t('app', 'Був зареєстрований новий користувач. {link}', [
                        'link' => Html::a(Yii::t('app', 'Перейти'), Url::to(['/user/admin'], true)),
                    ]),
                    true
                );
            }

            Yii::$app->user->identity->updateAttributes(['confirmed_at' => null]);
            Yii::$app->session->setFlash('info', Yii::t('app', 'You must relogin for apply changes'));
            return $this->refresh();
        }

        return $this->render('organizer', [
            'model' => $model,
        ]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    private function registerOrganization($model){
        Organizations::deleteAll(['user_id' => $model->user_id]);
        $orgAttributes = [
            'name' => $model->firma_full,
            'identifier' => [
                'legalName' => $model->firma_full,
                'scheme' => 'UA-EDR',
                'id' => $model->zkpo ?: $model->inn,
            ],
            'contactPoint' => [
                'name' => $model->member,
                'telephone' => $model->phone,
            ],
            'address' => [
                'region' => $model->region,
                'countryName' => 'Україна',
                'streetAddress' => $model->u_address,
                'postalCode' => $model->postal_code,
                'locality' => $model->city,
            ],
            'user_id' => $model->user_id,
        ];
        $organization = new Organizations;
        $organization->load($orgAttributes, '');
        $organization->save(false);
    }

}
