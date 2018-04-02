<?php

namespace app\models\searchModels;

use app\models\Categoriesblog;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Posts;

/**
 * PostsSearch represents the model behind the search form about `app\models\Posts`.
 */
class PostsSearch extends Posts
{
    public $categorySlug;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cat_id'], 'integer'],
            [['title', 'text', 'picture','categorySlug','description','key_words',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params ,   $formName = 'PostsSearch')
    {
        $query = Posts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>[
                    'id'=> SORT_DESC
                ],
            ],
			'pagination'=>[
				'pageSize' => 6,
          ],
        ]);

        $this->load($params,$formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('category');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cat_id' => $this->cat_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'picture', $this->picture])
            ->andFilterWhere(['like', 'posts.description', $this->description])
            ->andFilterWhere(['like', 'key_words', $this->key_words])
            ->andFilterWhere(['like', 'categoriesblog.slug', $this->categorySlug]);

        return $dataProvider;
    }
}
