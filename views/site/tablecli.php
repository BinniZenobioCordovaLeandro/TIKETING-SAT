<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
?>

<?php Pjax::begin(); ?>
    <?php
    echo GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
        [
          'attribute' => 'cliente_finale',
          'value' => function($data){
            return "<a style=\"cursor:pointer;\" id=\"".str_replace(" ","_",$data->cliente_finale)."\" onclick=\"window.location.href='/tickets/ticket?TicketsSearchModel%5Bcliente_finale%5D=".str_replace("'","\'",str_replace(" ","%20",$data->cliente_finale))."'\" target=\"_self\">".$data->cliente_finale."</a>";
          },
          'format' => 'raw'
        ],
        'nuovo',
        'assegnato',
        'in_attesa'
      ]
    ]);
    ?>
<?php Pjax::end();
