<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\components\CachedActiveRecord;

/**
 * Class OrigineRichiestaModel
 *
 * @package  app\models;
 */
class OrigineRichiestaModel extends ActiveRecord
{

   use CachedActiveRecord;

   public static function tableName(){
       return 'ticketing.origine_richiesta';
   }

   public function getTickets()
   {
       return $this->hasMany(TicketsModel::className(), ['origine' => 'id_origine'])->all();
   }
}
