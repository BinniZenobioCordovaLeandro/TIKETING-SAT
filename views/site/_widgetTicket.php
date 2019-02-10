<?php 

$dettagli = $sorgente->getRiepTicket();

if(count($dettagli)!=0){
?>

<div class="col-md-4">
    <div class="card">
        <div class="card-header" data-background-color="#cccccc">
            <?= $sorgente->descrizione; ?>
        </div>
        <div class="card-content">
            <?php
                foreach($dettagli as $dettaglio){ 
                    echo $this->render('_dettaglioWidget', [
                        'dettaglio' => $dettaglio,
                    ]); 
                } ?>
        </div>
        <div class="card-footer">
            <div class="stats">
                <i class="material-icons">access_time</i> ultima elaborazione <?php if( $sorgente->elaborazione ) { echo $sorgente->elaborazione->timestamp_elaborazione; } else { echo "NON DEFINITA"; } ?>
            </div>
        </div>
    </div>
</div>

<?php

}