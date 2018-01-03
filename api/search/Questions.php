<?php

namespace api\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\Questions as QuestionsModel;

/**
 * Questions represents the model behind the search form about `api\Questions`.
 */
class Questions extends QuestionsModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'author_id', 'created_at', 'updated_at'], 'integer'],
            [['id', 'title', 'description', 'date', 'dateAnswered', 'answer', 'questionOf', 'relatedItem'], 'safe'],
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
    public function search($params)
    {
        $query = QuestionsModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'unique_id' => $this->unique_id,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'dateAnswered', $this->dateAnswered])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'questionOf', $this->questionOf])
            ->andFilterWhere(['like', 'relatedItem', $this->relatedItem]);

        return $dataProvider;
    }
}
