<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class PrioritaModel
 *
 * @package  app\models;
 */
class PrioritaModel extends ActiveRecord
{

   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.priorita';
   }

   public static function listData(){
       $arr=array();
       foreach (static::find()->select(['id_priorita', 'codice'])->all() as $client){
            $arr[$client->id_priorita]=$client->codice;
       }
       return $arr;
    }

    public static function getLabel($id){
        $obj = (static::findOne(['id_priorita' => $id]));
        if(is_null($obj)) return "$id";
        return $obj->codice ;
    }
    public static function getColor($id){
      $obj = (static::findOne(['id_priorita' => $id]));
      if(is_null($obj)) return "$id";
      return $obj->color;
    }
   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['priorita' => 'id_priorita'])->all();
   }
}
