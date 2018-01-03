<?php

use yii\helpers\Url;
use app\models\Posts;
use yii\helpers\Html;

$lang == 'ru' ? $this->title = $model->title_ru : $this->title = $model->title;
$this->params['keywords'] = $model->key_words_ru && $lang == 'ru' ? $model->key_words_ru : $model->key_words;
$this->params['description'] = $model->description_ru && $lang == 'ru' ? $model->description_ru : $model->description;
$this->params['link_ru'] = $lang == 'ru' ? Url::to(['/ru/tender/'.$model->slug.'']) : Url::to(['/tender/'.$model->slug.'']);
$this->params['lang_ru'] = $lang == 'ru'? 'ru' : 'uk';
$this->params['link_uk'] = $lang == 'ru' ? Url::to(['/tender/'.$model->slug.'']) : Url::to(['/ru/tender/'.$model->slug.'']);
$this->params['lang_uk'] = $lang == 'ru'? 'uk' : 'ru';

?>



<main class="site-content">
    <article class="news-post">
        <div class="container">
            <div class="row justify-content-between">
                <div class="news-post-content col-md-8">
                    <h3 class="news-post-title"><?=$model->h1_ru && $lang == 'ru'? $model->h1_ru : $model->h1?></h3>
                    <time class="news-date mb-4"><?= date('d/m/Y', $model->created_at)?></time>
                    <?php if($model->picture){
                        echo Html::img(['/uploads/posts/' . $model->picture], ['class' => 'news-post-image img-fluid']);
                    }else{
                        echo Html::img(['/images/img-article.jpg'], ['class' => 'news-post-image img-fluid']);
                    }?>
                    <p><?=$model->text_ru && $lang == 'ru'? $model->text_ru : $model->text?></p>
                </div>
                <aside class="other-news col-md-3">
                    <ul>
                        <h4 class="other-news-title">Читайте також</h4>
                        <?php $posts = Posts::find()->orderBy(['id' => SORT_DESC])->limit(4)->all();
                        foreach($posts as $post):?>
                            <li class="other-news-item">
                                <time class="news-date"><?= date('d/m/Y', $post->created_at)?></time>
                                <p><?=Html::a($post->title, ['/site/view', 'name' => $post->slug], ['class' => 'link-primary'])?></p>
                            </li>
                        <?php endforeach;
                        ?>
                    </ul>
                </aside>
            </div>
        </div>
    </article>
</main>