<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EggData;

/**
 * EggDataSearch represents the model behind the search form about `app\models\EggData`.
 */
class EggDataSearch extends EggData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priode_id', 'count', 'cage_id', 'created_by'], 'integer'],
            [['source_type', 'created_at'], 'safe'],
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
        $query = EggData::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'priode_id' => $this->priode_id,
            'count' => $this->count,
            'cage_id' => $this->cage_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'source_type', $this->source_type]);

        return $dataProvider;
    }
}
