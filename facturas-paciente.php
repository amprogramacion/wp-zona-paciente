<?php
if (!empty($_SESSION['paciente'])) {
    ?>
    <div class="row">
        <div class="col-md-10">
            <h3>Tus facturas</h3>
            <p>Puedes ver o descargar tus facturas desde esta pantalla.</p>
        </div>
        <div class="col-md-2">
            <a href="javascript:history.back(-1);" class="btn btn-primary"><i class="fa fa-arrow-left"></i>Volver atrás</a>
        </div>
        <div class="col-md-12">
            <?php
            $facturas_paciente = zona_paciente_obtener_facturas($_SESSION['paciente']['paciente_token']);
            echo $facturas_paciente;
            ?>
        </div>
    </div>
    <?php
}