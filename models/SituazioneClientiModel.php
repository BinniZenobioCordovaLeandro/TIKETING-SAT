<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

class SituazioneClientiModel extends ActiveRecord
{
    use CachedActiveRecord;
    public static function tableName()
    {
        return 'ticketing.show_situazione_clienti';
    }

    public function rules()
    {
    return [
       [['cliente_finale'], 'safe'],
       [['nuovo', 'assegnato','in_attesa'], 'integer'],
      ];
    }

    public function attributeLabels(){
      return [
        'cliente_finale'=> 'Cliente Finale',
        'nuovo'=> 'Nuovo',
        'assegnato'=> 'Assegnato',
        'in_attesa'=> 'In Attesa'
      ];
    }
}
