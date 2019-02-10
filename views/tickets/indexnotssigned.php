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
            'id_ticket',
            'cliente_finale',
            'prodotto',
            'categoria',
            'oggetto',
            [
                'attribute' => 'id_sorgente',
                'value' => function ($model) {
                    return \app\models\SorgenteModel::getLabel($model->id_sorgente);
                },
                'filter' => \app\models\SorgenteModel::listData(),
                'filterInputOptions' => ['prompt' => 'Select', 'class' => 'form-control'],
            ],
           ]
    ]);
    ?>
<?php Pjax::end();
