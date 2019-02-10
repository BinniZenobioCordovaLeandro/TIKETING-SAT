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
              'header' => 'Ticket Info',
              'contentOptions' => ['style' => 'text-align: center;'],
              'value' => function($data){
                return "<a href=\"#\" onclick=\"$('#modal').modal('show').find('.modal-body').load('/tickets/detail?id=".$data->id_ticket."');\" ><i class=\"material-icons\" style='color: rgba(212,113,22,.8)'>info</i></a>";
              },
             'format' => 'raw'
            ],
            [
              'attribute' => 'id_ticket',
              'value' => function($data){
                if ($data->id_sorgente == 4) {
                  return "<a href=\"https://servicedesk.grupposandonato.it/gsd/front/ticket.form.php?id=".substr($data->id_ticket,3)."\" target=\"_self\">$data->id_ticket</a>";
                }
                return "<a>$data->id_ticket</a>";
              },
              'format' => 'raw'
            ],
            'cliente_finale',
            [
                'attribute' => 'urgenza',
                'value' => function ($model) {
                    return \app\models\UrgenzaModel::getLabel($model->urgenza);
                },
                'filter' => \app\models\UrgenzaModel::listData(),
                'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            ],
            [
                'attribute' => 'priorita',
                'contentOptions' => function ($model) {
                    $color = \app\models\PrioritaModel::getColor($model->priorita);
                    $array_color = array('style'=>'background-color: '.$color.'; color: rgba(255,255,255,.9); opacity: 0.7; text-shadow:1px 2px 1px rgba(0,0,0,.9);box-shadow: inset 1px 2px 4px rgba(0,0,0,.5);pading: 5px;');
                    return $array_color;
                },
                'value' => function ($model) {
                    return \app\models\PrioritaModel::getLabel($model->priorita);
                },
                'filter' => \app\models\PrioritaModel::listData(),
                'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            ],
            [
                'attribute' => 'data_scadenza',
                'format' => 'dateTime',
                'filter' => yii\widgets\MaskedInput::widget([
                    'name' => 'TicketsSearchModel[datascadenza]',
                    'model' => $searchModel,
                    'mask' => '99/99/9999',
                    'attribute' => 'datascadenza',
                ])
            ],
            'oggetto',
            [
              'attribute' => 'operatore_gpi',
              'contentOptions' => ['style' => 'font-weight: bold;'],
            ],
            // [
            //   'attribute' => 'id_operatore_gpi',
            //   'value' => function($model){
            //     return \app\models\OperatoriModel::getLabel($model->id_operatore_gpi);
            //   },
            //   'filter' => \app\models\OperatoriModel::listData(),
            //   'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            //   // 'filterInputOptions' => ['class'=>'form-control input-sm', 'id'=>'hey']
            // ],
            [
                'attribute' => 'stato',
                'contentOptions' => function ($model) {
                    $color = \app\models\StatoModel::getColor($model->stato);
                    $array_color = array('style'=>'background-color: '.$color.'; color: rgba(255,255,255,.9); opacity: 0.7; text-shadow:1px 2px 1px rgba(0,0,0,.9);box-shadow: inset 1px 2px 4px rgba(0,0,0,.5);pading: 5px;');
                    return $array_color;
                },
                'value' => function ($model) {
                    return \app\models\StatoModel::getLabel($model->stato);
                },
                'filter' => \app\models\StatoModel::listData(),
                'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            ],
            [
                'attribute' => 'id_sorgente',
                'value' => function ($model) {
                    return \app\models\SorgenteModel::getLabel($model->id_sorgente);
                },
                'filter' => \app\models\SorgenteModel::listData(),
                'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            ],
            'solleciti',
            [
                'attribute' => 'data_modifica',
                'format' => 'dateTime',
                'filter' => yii\widgets\MaskedInput::widget([
                    'name' => 'TicketsSearchModel[datamodifica]',
                    'model' => $searchModel,
                    'mask' => '99/99/9999',
                    'attribute' => 'datamodifica',
                ])
            ],
            [
                'attribute' => 'data_apertura',
                'format' => 'dateTime',
                'filter' => yii\widgets\MaskedInput::widget([
                    'name' => 'TicketsSearchModel[dataapertura]',
                    'model' => $searchModel,
                    'mask' => '99/99/9999',
                    'attribute' => 'dataapertura',
                ])
            ],
            'gruppi',
           ]
    ]);
    ?>
<?php Pjax::end();
