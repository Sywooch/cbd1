<?php


namespace app\commands;


use Yii;
use yii\helpers\Url;
use api\Organizations;
use app\models\Profile;
use app\models\Messages;
use \yii\console\Controller;


class HelperController extends Controller
{

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

}