<?php
namespace app\models\search;
use app\models\SituazioneClientiModel;
use yii\data\ActiveDataProvider;

class SituazioneClientiSearchModel extends SituazioneClientiModel
{
  public function rules()
  {
  return [
     [['cliente_finale'], 'safe'],
     [['nuovo', 'assegnato','in_attesa'], 'integer']
    ];
  }
  public function search($params){
    $query = SituazioneClientiModel::find();

    $dataProvider  = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 20,
      ],
      'sort' => [
        'defaultOrder'  => ['cliente_finale'=>SORT_DESC],
      ],
    ]);

    $this->load($params);
    if(!$this->validate()){
      return $dataProvider;
    }
    $query->andFilterWhere(['like','cliente_finale', $this->cliente_finale]);
    $query->andFilterWhere(['in','nuovo', $this->nuovo]);
    $query->andFilterWhere(['in','assegnato', $this->assegnato]);
    $query->andFilterWhere(['in','in_attesa', $this->in_attesa]);

    return $dataProvider;
  }
}
