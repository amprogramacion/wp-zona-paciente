<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4>Zona de Paciente</h4>
                <p>Inicia sesión con tu <strong>DNI</strong> y contraseña para acceder a tu zona privada.</p>
                <?php
                if (!empty($_REQUEST['login'])) {
                    $dni = str_replace(array("-", "'", "´", "`"), array("", "", "", ""), $_REQUEST['dni']);
                    $LoginResponse = AccesoPacientes_Login($dni, $_REQUEST['pass']);
                    if (!empty($LoginResponse['error'])) {
                        switch ($LoginResponse['error']) {
                            case "DNI_NOT_FOUND":
                                echo AcessoPacientes_Alerta("danger", "Ha ocurrido un error", "El DNI introducido no está registrado como paciente en el sistema.");
                                break;
                            case "INVALID_PASSWORD":
                                echo AcessoPacientes_Alerta("danger", "Ha ocurrido un error", "La contraseña es incorrecta.");
                                break;
                            default:
                                echo AcessoPacientes_Alerta("danger", "Ha ocurrido un error", "Ha ocurrido un error al intentar iniciar sesión: " . $LoginResponse['error']);
                                break;
                        }
                    } else {
                        if (!empty($LoginResponse['paciente_info'])) {
                            $_SESSION['paciente'] = $LoginResponse['paciente_info'];
                            echo AcessoPacientes_Alerta("success", "Correcto", "Un momento, por favor...");
                            echo AccesoPacientes_Redir(0, "");
                        } else {
                            echo AcessoPacientes_Alerta("danger", "Ha ocurrido un error", "No existe información del paciente en la base de datos.");
                        }
                    }
                }
                if ($_GET['logout'] == "true") {
                    echo AcessoPacientes_Alerta("success", "La sesión se ha cerrado", "Has salido de la zona de pacientes.");
                }
                ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="dni">DNI</label> 
                        <input id="dni" name="dni" placeholder="Introduce tu DNI" type="text" required="required" class="form-control" value="<?= $ac_dni; ?>">
                    </div>
                    <div class="form-group">
                        <label for="pass">Contraseña</label> 
                        <input id="pass" name="pass" placeholder="Escribe tu contraseña" type="password" class="form-control" value="<?= $ac_pwd; ?>">
                    </div> 
                    <div class="form-group">
                        <button name="login" type="submit" value="login" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
                <div class="text-center">
                    <a href="/recuperar-pass">¿Has olvidado tu contraseña? Haz click aquí</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <p>Descarga ya nuestra aplicación para pacientes desde Google Play y App Store</p>
                <div class="row">
                    <div class="col-6">
                        <a href="https://play.google.com/store/apps/details?id=com.amprogramacion.neuroredacer.paciente" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/google_play.png'; ?>"></a>
                    </div>
                    <div class="col-6">
                        <a href="https://apps.apple.com/tt/app/neuroredacer/id1584882894" target="_blank"><img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/app_store.png'; ?>"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <p> Si quieres leer nuestro manual de uso de la aplicación móvil de NeuroRedacer puedes hacerlo en el <a href="<?php echo plugin_dir_url(__FILE__) . 'assets/TUTORIAL_APP_PACIENTE_V1.pdf'; ?>" target="_blank"><strong> siguiente enlace </strong> </a> </p>
            </div>
        </div>
    </div>
</div>
