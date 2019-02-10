<?php
$output="";

$vechioDato=""; // variabile di controllo., // guarda il vechio codigo che lascio sotto di questo.

$output .='<div class="row"><div class="col col-md-12"><div class="card"><div class="card-header" data-background-color="#cccccc">Ticket</div><div class="card-content"><div class="row hidden-xs hidden-sm hidden-md"><div class="col-lg-3"></div><div class="col-lg-9"><div class="row">';
foreach ($elencoStati as $stato) {
  $output .= '<div class="col-lg-4 text-center">';
  $output .= $stato->codice;
  $output .= '<h5 style="text-decoration: underline;"><b></b></h5></div>';
}
$output .= '</div></div></div>';
foreach ($sorgente as $dato) {
  $nuovoDato=$dato->id_sorgente;
  if ($nuovoDato != $vechioDato) {
    $output .= '<div class="row form-group" style="border: 1px rgb(110, 158, 193,0.1) solid;border-top: 0px;"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-3" style="background: rgb(110, 158, 193,.1);">';
    $output .= '<a href="'.$dato->external_link.'" target="_blank" alt="'.$dato->codice.'"><b>'.$dato->descrizione.'</b></a>';
    $output .= '</div><div class="col-xs-12 col-sm-12 col-md-12 col-lg-9"><div class="row">';
    foreach ($elencoStati as $stato) {
      $somma = 0;
      $output .= '<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border: 1px rgb(110, 158, 193,0.1) solid;border-bottom: 0px;border-top: 0px;"><div class="row hidden-lg hidden-xl"><div class="col-lg-4 text-center">';
      $output .= '<h6><b>'.$stato->codice.'</b></h6>';
      $output .= '</div></div><div class="row"><div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">';
      foreach ($elencoPriorita as $priorita) {
        $output .= '<div class="row"><div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'.$priorita->codice.'</div>';
        $countTicket = 0;
        foreach ($datiDash as $dato1) {
          if (($dato->id_sorgente == $dato1->id_sorgente)&&($stato->id_stato == $dato1->stato)&&($priorita->id_priorita == $dato1->priorita)) {
            $countTicket = intval($dato1->count);
            $somma+=intval($dato1->count);
          }
        }
        $output .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">'.$countTicket.'</div>';
        $output .= '</div>';
      }
      $output .= '</div>';
      $output .= '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right"><h4><b>'.$somma.'</b></h4></div>';
      $output .= '</div></div>';
    }
    $output .= '</div></div></div>';
  }
  $vechioDato = $nuovoDato;
}
$output .= '</div></div></div>';
?>

<?php echo $output; ?>
