  <?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md">
        <div class="card">
            <div class="card-header card-header-primary" style="opacity: .9;">
                <h4 class="card-title">Dettaglio Ticket <b><?= $id ?></b></h4>
                <p class="card-category">Provenienza : <?= $model->sorgente->descrizione ?><br/>
                Data scadenza : <?= $model->data_scadenza ?>
                <?php
                if($model->operatore_gpi){
                    echo "<br/>Operatore GPI : ".$model->operatore_gpi;
                }
                if($model->operatore_cliente){
                    echo "<br/>Operatore Cliente : ".$model->operatore_cliente;
                }
                ?>
                </p>
            </div>
            <div class="card-body">
                <fieldset style=" font-family: sans-serif; border: 1px solid black; border-radius: 5px; padding: 20px; margin: 20px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Richiedente</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Cliente</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->cliente_finale ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Nominativo</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->nominativo_richiedente ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Email</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->email_richiedente ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Telefono</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->telefono_richiedente ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Altro telefono</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->altro_richiedente){ echo $model->altro_richiedente; } ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Sede</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->sede_richiedente ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Ubicazione</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->ubicazione_richiedente){ echo $model->ubicazione_richiedente; } ?>">
                            </div>
                        </div>
                    </div>

                    </fieldset>
                <!-- Dettagli ticket -->
                <fieldset style=" font-family: sans-serif; border: 1px solid black; border-radius: 5px; padding: 20px; margin: 20px;">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Dettagli</h3>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Prodotto</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->prodotto ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Categoria</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->categoria){ echo $model->categoria; } ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Oggetto</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->oggetto ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Testo</label>
                                <p><?= $model->testo ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                        if($model->note!=""){
                     ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Note</label>
                                <p><?= $model->note ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Stato</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->getStato()->one()){ echo $model->getStato()->one()->codice; } ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Urgenza</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->getUrgenza()->one()){ echo $model->getUrgenza()->one()->codice; } ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Impatto</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->getImpatto()->one()){ echo $model->getImpatto()->one()->codice; } ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Priorità</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->getPriorita()->one()){ echo $model->getPriorita()->one()->codice; } ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">SLA</label>
                                <input type="text" class="form-control" disabled="" value="<?= $model->slt ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group bmd-form-group">
                                <label class="bmd-label-floating">Solleciti</label>
                                <input type="text" class="form-control" disabled="" value="<?php if($model->solleciti) { echo $model->solleciti; } ?>">
                            </div>
                        </div>
                    </div>
                    </fieldset>
            </div>
        </div>
    </div>
</div>
