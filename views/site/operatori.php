<?php
use yii\bootstrap\Modal;
 ?>
<!-- <a href="http://prdtasksat01.gpi.it/satplan/view/presenti.php" target="_blank">copia di http://prdtasksat01.gpi.it/satplan/view/presenti.php</a>
<b>Events : <?php
// echo $countEvents;
?></b> -->
<div id="contenitore" class="" style="background: rgba(255,255,255,.5) !important; box-shadow: 0px 0px 3px 25px rgba(255,255,255,.5)">
  <?= \yii2fullcalendar\yii2fullcalendar::widget(array(
    'events' => $events,
    'options' => [
                  'lang' => 'it',
                  //... more options to be defined here!
                ],
    'header'=> [
                  'left'=> 'prev,next,today',
                  'center'=> 'title',
                  'right'=> '',
              ],
    'id' => 'calendar',
    'clientOptions'=>[
      'weekends'=>false,
      // 'hiddenDays' => [1,2,3],
      'now'=>date('Y-m-d\Th:m\Z'),
      'editable' => false,
      'droppable' => false,
      'footer'=> [
        'left'=> 'prev,next,today',
        ],
    ],
  ));
  ?>
</div>

<style media="screen">
  .fc-scroller{
    overflow: visible !important;
    height: auto !important;
  }
</style>
