<?php
/**
 * Created by PhpStorm.
 * User: wolodymyr
 * Date: 15.02.17
 * Time: 13:01
 */

namespace app\commands;


use Yii;
use api\Questions;
use yii\helpers\Html;
use app\models\EmailTasks;
use yii\console\Controller;
use yii\helpers\ArrayHelper;


class MailController extends Controller
{
    public function actionProcess(){
        $tasks = EmailTasks::find()->where(['process' => '0'])->limit(60)->all();
        $ids = ArrayHelper::map($tasks, 'id', 'id');
        EmailTasks::updateAll(['process' => '1'], ['id' => $ids]);

        foreach($tasks as $task){
            try{
                $this->send($task);
            } catch(\Exception $e){
                Yii::error('Error while sending email');
                echo $e->getMessage() . "\n";
                // $task->updateAttributes(['process' => '0']);
            }
        }
        foreach($this->checkUnanswered() as $question){
            $user = $question->organizator;
            if(!isset($user->email)) continue;
            // $task = new EmailTasks([
            //     'email' => $user->email,
            //     'message' => Yii::t('app', 'У вас є питання без відповіді: {question}', [
            //         'question' => Html::a($question->title, Url::to(['/questions/answer', 'id' => $question->unique_id])),
            //     ])
            // ]);
            // $task->save(false);
        }
        echo "Task done\n";
    }

    private function send($task){
        $sended = Yii::$app->mailer->compose()
            ->setTo($task->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject(Yii::$app->name)
            ->setHtmlBody($task->message)
            ->send();
        if(!YII_DEBUG){
            $task->delete();
        }
        else{
            echo $sended ? "Sended\n" : "Fail";
        }
    }

    private function checkUnanswered(){
        $questions = Questions::find()->where(['<', 'updated_at', time() - ((86400 / 144) - 1)])->andWhere(['answer' => ''])->all();
        $ids = ArrayHelper::map($questions, 'id', 'id');
        Questions::updateAll(['updated_at' => time()], ['id' => $ids]);
        return $questions;
    }
}
