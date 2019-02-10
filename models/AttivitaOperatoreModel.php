<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticketing.attivita_operatore".
 *
 * @property string $id_attivita
 * @property string $id_operatore
 * @property string $operatore
 * @property string $prodotto
 * @property string $codice_attivita
 * @property string $titolo_attivita
 * @property string $data_attivita
 * @property string $ore_attivita
 * @property string $descrizione_attivita
 * @property string $stato
 * @property string $richiedente
 */
class AttivitaOperatoreModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticketing.attivita_operatore';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_attivita', 'id_operatore'], 'number'],
            [['operatore', 'prodotto', 'codice_attivita', 'titolo_attivita', 'descrizione_attivita', 'stato', 'richiedente'], 'string'],
            [['data_attivita', 'ore_attivita'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_attivita' => 'Id Attivita',
            'id_operatore' => 'Id Operatore',
            'operatore' => 'Operatore',
            'prodotto' => 'Prodotto',
            'codice_attivita' => 'Codice Attivita',
            'titolo_attivita' => 'Titolo Attivita',
            'data_attivita' => 'Data Attivita',
            'ore_attivita' => 'Ore Attivita',
            'descrizione_attivita' => 'Descrizione Attivita',
            'stato' => 'Stato',
            'richiedente' => 'Richiedente',
        ];
    }

    public function getOreAttivitaOperatore($codiceOperatore,$data){
      // return records.
         $sql = "select a.operatore,a.data_attivita,sum(a.ore_attivita) from ticketing.attivita_operatore a where operatore = :codiceOperatore and data_attivita = :data group by a.operatore,a.data_attivita";
         $command= Yii::$app->db->createCommand($sql);
         $command->bindParam(':codiceOperatore', $codiceOperatore);
         $command->bindParam(':data', $data);
         $result = $command->queryAll();
         return $result;
    }
}
