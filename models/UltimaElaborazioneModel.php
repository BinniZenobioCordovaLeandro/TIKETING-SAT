<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class UrgenzaModel
 *
 * @package  app\models;
 */
class UltimaElaborazioneModel extends ActiveRecord
{

   public static function tableName(){
           return 'ticketing.ultima_elaborazione';
   }

}
