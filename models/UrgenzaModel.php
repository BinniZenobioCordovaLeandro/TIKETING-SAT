<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class UrgenzaModel
 *
 * @package  app\models;
 */
class UrgenzaModel extends ActiveRecord
{

   use CachedActiveRecord;

   public static function tableName(){
           return 'ticketing.urgenza';
   }

   public static function listData(){
       $arr=array();
       foreach (static::find()->select(['id_urgenza', 'codice'])->all() as $client){
            $arr[$client->id_urgenza]=$client->codice;
       }
       return $arr;
    }

    public static function getLabel($id){
        $obj = (static::findOne(['id_urgenza' => $id]));
        if(is_null($obj)) return "$id";
        return $obj->codice ;
    }

   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['urgenza' => 'id_urgenza'])->all();
   }
}
