<?php
// Incluir el header del tema
get_header();
?>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <a href="/zona-paciente" class="btn btn-primary float-right mx-3"><i class="fa fa-arrow-left"></i> Volver atrás</a>
            <h3><strong>Tu Perfil</strong></h3>
            <p>Desde aquí podrás cambiar la contraseña de acceso al panel de clientes, o actualizar tus datos personales.</p>
            <hr>
        </div>
        <div class="col-md-12 tuto-cambiar-email">
            <h3><strong>Cambiar el Correo Electrónico</strong></h3>
            <?php
            if (!empty($_REQUEST['confirmar_cambio_email'])) {
                if ($_REQUEST['token'] == $_SESSION['paciente']->paciente_token) {
                    zp_CambiarEmailDefinitivo($_SESSION['paciente']->id_cliente, $_SESSION['paciente']->id);
                    $nuevo_token = md5(strtotime("now") . rand(100000, 999999));
                    zp_GuardarToken($_SESSION['paciente']->id, $nuevo_token);
                    $_SESSION['paciente_info'] = zp_InfoById($_SESSION['paciente']->id);
                    echo AcessoPacientes_Alerta("success", "Información actualizada", "Hemos actualizado tu correo electrónico. Por seguridad, deberás volver a iniciar sesión en tus otros dispositivos.");
                }
            }
            if (!empty($_POST['cambiaremailbtn'])) {
                zp_CambiarEmailTemporal($_REQUEST['email']);
                echo AcessoPacientes_Alerta("success", "Revisa tu correo", "Hemos enviado un email de verificación para realizar el cambio de correo electrónico. Espera...");
                echo AccesoPacientes_Redir(5, "/perfil");
            } else {
                ?>
                <form method="POST">
                    <div class="form-group tuto-cambiar-email-2">
                        <label for="email">Dirección de correo electrónico<strong>*</strong></label>
                        <input type="email" name="email" class="form-control" id="email" value="<?= $_SESSION['paciente']->paciente_email; ?>">
                        <small class="form-text text-muted">Es la dirección que usaremos para enviarte notificaciones o facturas del sistema.</small>
                    </div>

                    <button type="submit" name="cambiaremailbtn" value="cambiaremailbtn" class="btn btn-success">Cambiar correo electrónico</button>
                    <p><i>Nota*: Te enviaremos un email de confirmación a tu nueva dirección de correo electrónico.</i></p>
                </form>
                <?php
            }
            ?>
            <hr>
        </div>
        <div class="col-md-12 tuto-cambiar-pass">
            <h3><strong>Cambiar la Contraseña</strong></h3>
            <?php
            if (!empty($_REQUEST['cambiarpassbtn'])) {
                $validar = true;
                if (!password_verify($_REQUEST['pass_actual'], $_SESSION['paciente_info']['paciente_pass'])) {
                    echo AcessoPacientes_Alerta("danger", "La contraseña actual es incorrecta");
                } else if ($_POST['pass1'] != $_POST['pass2']) {
                    echo AcessoPacientes_Alerta("danger", "Las nuevas contraseñas no coinciden");
                } else if (strlen($_POST['pass1']) < 4) {
                    echo AcessoPacientes_Alerta("danger", "La nueva contraseña debe tener al menos 4 caracteres");
                } else {
                    $pwd_crypted = password_hash($_POST['pass1'], PASSWORD_DEFAULT);
                    zp_ActualizarPass($_SESSION['paciente_info']['id_cliente'], $_SESSION['paciente_info']['id'], $pwd_crypted);
                    echo AcessoPacientes_Alerta("success", "La contraseña ha sido modificada correctamente");
                }
            }
            ?>
            <form method="POST">
                <div class="form-group tuto-cambiar-pass-1">
                    <label for="pass_actual">Contraseña actual</label>
                    <input type="password" name="pass_actual" class="form-control" id="pass_actual">
                    <small class="form-text text-muted">Escribe tu contraseña actual para cambiarla.</small>
                </div>
                <div class="tuto-cambiar-pass-2">
                    <div class="form-group">
                        <label for="pass1">Nueva contraseña</label>
                        <input type="password" name="pass1" class="form-control" id="pass1">
                        <small class="form-text text-muted">Escribe tu nueva contraseña.</small>
                    </div>
                    <div class="form-group">
                        <label for="pass2">Repite la Nueva contraseña</label>
                        <input type="password" name="pass2" class="form-control" id="pass2">
                        <small class="form-text text-muted">Repite la nueva contraseña</small>
                    </div>
                </div>
                <button type="submit" name="cambiarpassbtn" value="cambiarpassbtn" class="btn btn-success">Cambiar contraseña</button>
            </form>
        </div>
    </div>
</div>
<?php
// Incluir el footer del tema
get_footer();
?>