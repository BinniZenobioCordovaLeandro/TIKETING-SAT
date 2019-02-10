<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class StatoModel
 *
 * @package  app\models;
 */
class StatoModel extends ActiveRecord
{
   use CachedActiveRecord;
   public static function tableName(){
           return 'ticketing.stato';
   }
   public static function listData(){
       $arr=array();
       foreach (static::find()->select(['id_stato', 'codice'])->all() as $client){
            $arr[$client->id_stato]=$client->codice;
       }
       return $arr;
    }
    public static function getLabel($id){
        $obj = (static::findOne(['id_stato' => $id]));
        if(is_null($obj)) return "$id";
        return $obj->codice ;
    }
    public static function getColor($id){
        $obj = (static::findOne(['id_stato' => $id]));
        if(is_null($obj)) return "$id";
        return $obj->color ;
    }
   public function getTickets(){
       return $this->hasMany(TicketsModel::className(), ['id_stato' => 'stato'])->all();
   }

}
