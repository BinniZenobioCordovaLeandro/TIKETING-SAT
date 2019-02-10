  <?php

$i=0;
echo '<div class="row">';
foreach( $sorgenti as $sorgente) {
    echo $this->render('_widgetTicket', [
        'sorgente' => $sorgente,
    ]);
    $i++;
    if($i==3){
        echo '</div>';
        $i=0;
    }
}
if($i!=0){
   echo '</div>';
}
?>
<hr style="border-top: 1px solid #8c8b8b;">
<?php

$i=0;
echo '<div class="row">';
foreach( $totali as $totale) {
    echo $this->render('_totale', [
        'totale' => $totale,
    ]);
    $i++;
    if($i==4){
        echo '</div>';
        $i=0;
    }
}
if($i!=0){
   echo '</div>';
}
?>
