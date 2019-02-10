  <?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\widgets\Pjax;

$strSubQry="";
$comma=' , ';
$orderArray=[];
$columnsArray=array();
$columnsArray[] = array('attribute' => 'operatore_gpi', 'label'=>'Operatore GPI' ,'format'=>'raw', 'value'=>function($data){
  // onclick="window.location.href='b.php'"
    return "<a style=\"cursor:pointer;\" id=\"".str_replace(" ","_",$data['operatore_gpi'])."\" onclick=\"window.location.href='/tickets/ticket?TicketsSearchModel%5Boperatore_gpi%5D=".str_replace("'","\'",str_replace(" ","%20",$data['operatore_gpi']))."'\" target=\"_self\">".$data['operatore_gpi']."</a>";
  });
$orderArray['operatore_gpi'] =[
                'asc' => [
                    new Expression('operatore_gpi NULLS LAST ')
                ],
                'desc' => [
                    new Expression('operatore_gpi DESC NULLS LAST ')
                ]
            ];
foreach($stati as $stato){
  $strSubQry = $strSubQry . $comma. strtolower(str_replace(" ", "", $stato->codice)) .' int ';
  $orderArray[strtolower(str_replace(" ", "", $stato->codice))] = [
              'asc' => [
                  new Expression(strtolower(str_replace(" ", "", $stato->codice)).' NULLS LAST ')
              ],
              'desc' => [
                  new Expression(strtolower(str_replace(" ", "", $stato->codice)).' DESC NULLS LAST ')
              ]
          ];
  $columnsArray[] = array('attribute' => strtolower(str_replace(" ", "", $stato->codice)) ,
      'label' => $stato->codice);
};
$sqlQuery="SELECT * FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('SPOC - SERVICE DESK') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore $$,$$ SELECT ts.id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) AS ct(operatore_gpi text ".$strSubQry.")";
$count = Yii::$app->db->createCommand("SELECT count(*) FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('SPOC - SERVICE DESK') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore  $$,$$ SELECT id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) as ct(operatore_gpi text ".$strSubQry.")")->queryScalar();
$dataProviderUno = new SqlDataProvider([
    'sql' => $sqlQuery,
    'totalCount' => $count,
    'pagination' => [
        'pageSize' => 10
    ],
    'sort' => [
        'attributes' => $orderArray
    ]
]);
$sqlQuery="SELECT * FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('ONSITE FDG') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore $$,$$ SELECT ts.id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) AS ct(operatore_gpi text ".$strSubQry.")";
$count = Yii::$app->db->createCommand("SELECT count(*) FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('ONSITE FDG') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore  $$,$$ SELECT id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) as ct(operatore_gpi text ".$strSubQry.")")->queryScalar();
$dataProviderDue = new SqlDataProvider([
    'sql' => $sqlQuery,
    'totalCount' => $count,
    'pagination' => [
        'pageSize' => 10
    ],
    'sort' => [
        'attributes' => $orderArray
    ]
]);
$sqlQuery="SELECT * FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('SPECIALISTI') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore $$,$$ SELECT ts.id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) AS ct(operatore_gpi text ".$strSubQry.")";
$count = Yii::$app->db->createCommand("SELECT count(*) FROM crosstab($$ SELECT o.nome_operatore, t.stato, count(*) from operatori_gpi o join tickets t on o.nome_operatore = t.operatore_gpi where o.servizio in ('SPECIALISTI') and o.attivo = 1 and t.stato in (select id_stato from stato where show_situazione_operatore = true) group by o.nome_operatore, t.stato order by o.nome_operatore  $$,$$ SELECT id_stato from ticketing.stato ts where ts.show_situazione_operatore = true order by ts.id_stato $$) as ct(operatore_gpi text ".$strSubQry.")")->queryScalar();
$dataProviderTre = new SqlDataProvider([
    'sql' => $sqlQuery,
    'totalCount' => $count,
    'pagination' => [
        'pageSize' => 10
    ],
    'sort' => [
        'attributes' => $orderArray
    ]
]);
echo '<div class="row">';
  echo '<div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4"><div class=""><div class="card border-primary mb-3"><div class="card-header" data-background-color="#cccccc">SPOC - SERVICE DESK</div><div class="card-body col-sm-12" style="padding-top:.5em; padding-bottom:.5em;">';
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProviderUno,
        'formatter' => [ 'class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'showHeader' => true,
        // 'columns' => $columnsArray
        'columns' => $columnsArray
    ]);
    Pjax::end();
  echo '</div></div></div></div>';
  echo '<div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4"><div class=""><div class="card border-primary mb-3"><div class="card-header" data-background-color="#cccccc">ONSITE FDG</div><div class="card-body col-sm-12" style="padding-top:.5em; padding-bottom:.5em;">';
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProviderDue,
        'formatter' => [ 'class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'showHeader' => true,
        'columns' => $columnsArray
    ]);
    Pjax::end();
  echo '</div></div></div></div>';
  echo '<div class="col-xs-12 col-sm-6 col-lg-4 col-xl-4"><div class=""><div class="card border-primary mb-3"><div class="card-header" data-background-color="#cccccc">SPECIALISTI</div><div class="card-body col-sm-12" style="padding-top:.5em; padding-bottom:.5em;">';
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProviderTre,
        'formatter' => [ 'class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'showHeader' => true,
        'columns' => $columnsArray
    ]);
    Pjax::end();
  echo '</div></div></div></div>';
echo '</div>';
?>
