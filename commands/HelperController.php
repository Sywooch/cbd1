<?php


namespace app\commands;


use api\Bids;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use api\Organizations;
use app\models\Profile;
use app\models\Messages;
use \yii\console\Controller;


class HelperController extends Controller
{

    public function actionCleardb(){
        Yii::$app->db->createCommand('delete from user where username not in ("organizator", "neiron", "member", "member1")')->execute();
        Yii::$app->db->createCommand('delete from api_organizations where user_id is null or user_id not in(select id from user)')->execute();
        Yii::$app->db->createCommand('delete from api_identifiers where id not in (select identifier_id from api_organizations)')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_auctions')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_awards')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_award_organizations')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_bids')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_cancellations')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_contracts')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_documents')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_items')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_items_classifications')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_questions')->execute();
        Yii::$app->db->createCommand('TRUNCATE api_prolongations')->execute();
        Yii::$app->db->createCommand('TRUNCATE email_tasks')->execute();
        Yii::$app->db->createCommand('TRUNCATE files')->execute();
        Yii::$app->db->createCommand('TRUNCATE lots')->execute();
        Yii::$app->db->createCommand('TRUNCATE messages')->execute();
        Yii::$app->db->createCommand('TRUNCATE token')->execute();
    }

    public function actionTestEmail(){
        Yii::createObject(Messages::className())->sendMessage(6, Url::to(['/public'], true), true);
    }

    public function actionRegOrg(){
        foreach(Profile::find()->all() as $profile){
            if(false == (Organizations::findOne(['user_id' => $profile->user_id]))){
                try{
                    $this->registerOrganization($profile);
                }
                catch(\Exception $e){
                    echo "фуск\n";
                }
            }
        }
    }

    private function registerOrganization($model){
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

    public function actionMemoryMail(){
        $text = 'У вас є запитання без відповіді. ' . '<a href="https://proumstrade.com.ua/questions/answer?id=5956">Переглянути</a>';
        \Yii::createObject(Messages::className())->sendMessage(13, $text, true);
        $text = 'У вас є запитання без відповіді. ' . '<a href="https://proumstrade.com.ua/questions/answer?id=5957">Переглянути</a>';
        \Yii::createObject(Messages::className())->sendMessage(13, $text, true);
    }

    public function actionSendUrls(){
        foreach(Bids::find()->where(['!=', 'participationUrl', ''])->all() as $bid){
            if(!$bid->award){
                Yii::createObject(Messages::className())->sendMessage(
                    $bid->user_id,
                    Yii::t('app', 'Аукціон "{auction}" розпочався. Ви можете взяти участь, перейшовши за посиланням: {link}', [
                        'auction' => $bid->apiAuction->title,
                        'link' => Html::a(Yii::t('app', 'перейти'), $bid->participationUrl),
                    ]),
                    true
                );
            }
        }
    }

}