<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class ImpattoModel
 *
 * @package  app\models;
 */
class FunzionalitaModel extends ActiveRecord
{

   use CachedActiveRecord;

 public static function tableName(){
         return 'ticketing.funzionalità';
 }

 public function rules(){
    return [
        [['id'], 'integer'],
        [['codice'], 'string'],
        [['descrizione'], 'string'],
    ];
  }

  public function attributeLabels(){
       return [
           'id' => 'id',
           'codice' => 'string',
           'descrizione' => 'string'
       ];
   };

 public static function getFunzionalita($codice){
     $obj = (static::findOne(['id' => $codice]));
     if(is_null($obj)) return "$id";
     return $obj->codice;
 }

 public static function getDescrizione($codice){
     $obj = (static::findOne(['id' => $codice]));
     if(is_null($obj)) return "$id";
     return $obj->descrizione;
 }


}
