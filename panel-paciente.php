<?php
 if (!empty($_SESSION['paciente'])) {
     ?>
<style>
    .icono-pulse {
        width: 64px;
    }
</style>
<div class="container my-4">
    <div class="row">
        <div class="col-md-12">
            <h3>Esta es la nueva Zona de Pacientes.</h3>
            <p>Desde aquí podrás consultar tus facturas o tus bonos y descargar tus facturas.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/perfil/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-perfil">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/perfil.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Perfil</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/facturas/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-facturas">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/facturas.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Facturas</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/bonos/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-bonos">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/bonos.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Bonos</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/documentos/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-documentos">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/documentos.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Documentos</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/mis-citas/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-mis-citas">
                <?php
                $label_citas = "";
                if (!empty($mis_citas)) {
                    if (count($mis_citas) == 1) {
                        $label_citas = '<div class="badge badge-success float-right">1 CITA</div>';
                    } else if (count($mis_citas) > 1) {
                        $label_citas = '<div class="badge badge-success float-right">' . count($mis_citas) . ' CITAS</div>';
                    }
                }
                ?>
                <?= $label_citas; ?>
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/mis-citas.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Mis Citas</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/solicitar-cita/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-solicitar-cita">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/solicitar-cita.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Cita Previa</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="/zona-paciente/avisos/" class="btn btn-primary btn-block p-3 mb-3 tuto-btn-avisos">
                <?php
                $label_avisos = "";
                if (!empty($avisos_paciente)) {
                    if (count($avisos_paciente) == 1) {
                        $label_avisos = '<div class="badge badge-warning float-right">1 AVISO</div>';
                    } else if (count($avisos_paciente) > 1) {
                        $label_avisos = '<div class="badge badge-warning float-right">' . count($avisos_paciente) . ' AVISOS</div>';
                    }
                }
                echo $label_avisos;
                ?>
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/avisos.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Avisos</div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
            <a href="<?php echo esc_url(add_query_arg('action', 'logout', home_url())); ?>" class="btn btn-danger btn-block p-3 mb-3 tuto-btn-salir">
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/logout.png'; ?>" class="icono-pulse">
                <br><div class="card-button h5 mt-2">Salir</div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div id="btn-suscripcion-notificaciones"></div>
        </div>
    </div>
</div>
<script>
    WonderPush.push(function () {
        WonderPush.push(["init", {
                webKey: "54a86a3691fd1c0f8db3d0938767669c90858595853ad19272b565f5633b1f23",
                subscriptionDialog: {
                    triggers: {},
                    title: "¿Quieres suscribirte a las notificaciones?",
                    message: "Puedes darte de baja en cualquier momento.",
                    positiveButton: "Suscribirme",
                    negativeButton: "Más tarde"
                }
            }]);

        WonderPush.showSubscriptionSwitch(
                document.getElementById('btn-suscripcion-notificaciones'),
                {
                    sentence: "Suscribirse a las notificaciones: "
                }
        );
    });
</script>
<?php
 }