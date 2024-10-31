<?php
// Incluir el header del tema
get_header();
?>
<div class="container mt-5 mb-5">

    <div class="row">
        <div class="col-md-12">
            <a href="/zona-paciente" class="btn btn-primary float-right mx-3"><i class="fa fa-arrow-left"></i>Volver atrás</a>
            <h3><strong>Tus citas</strong></h3>
            <p>Desde aquí podrás gestionar tus citas.</p>
        </div>
        <div class="col-md-12">
            <?php
            $citas = json_decode(zp_ApiCall("https://neuroredacer.es/api/index.php?controller=citas&action=listado&max2weeks=1&token=".$_SESSION['paciente']->paciente_token), true);
            if (!empty($citas)) {
                foreach ($citas as $cita) {
                    $label_estado_cita = '<span class="badge badge-' . $cita['color_estado'] . '">' . $cita['icono_estado'] . ' ' . $cita['estado_cita'] . '</span> ';
                    $label_videollamada = !empty($cita['cita_videollamada']) ? ' <span class="badge" style="background-color: #001f3f; color: white;"><i class="fa fa-video"></i> VIDEOLLAMADA</span> ' : ' <span class="badge" style="background-color: #001f3f; color: white;"><i class="fa fa-hospital-user"></i> PRESENCIAL</span> ';

                    if (!empty($cita['cita_videollamada'])) {
                        $label_videollamada = '<span class="badge" style="background-color: #001f3f; color: white;"><i class="fa fa-video"></i> VIDEOLLAMADA</span> ';
                    } else if (!empty($cita['cita_grupal'])) {
                        $label_videollamada = ' <span class="badge" style="background-color: #001f3f; color: white;"><i class="fa fa-users"></i> GRUPAL</span> ';
                    } else {
                        $label_videollamada = ' <span class="badge" style="background-color: #001f3f; color: white;"><i class="fa fa-hospital-user"></i> PRESENCIAL</span> ';
                    }

                    echo '<h4>' . $label_estado_cita . $label_videollamada . 'Cita de <strong>' . $cita['especialidad'] . '</strong> el día <strong>' . date("d/m/Y", strtotime($cita['dia_cita'])) . '</strong> a las <strong>' . substr($cita['hora_inicio_cita'], 0, 5) . '</strong></h4>';
                    echo '<br>';
                    $horas_diferencia = abs(strtotime($cita['dia_cita'] . " " . $cita['hora_cita']) - strtotime("now")) / (60 * 60);
                    $mensaje_anulacion = "";
                    if ($horas_diferencia <= 24) {
                       // $mensaje_anulacion = ToolsCore::Alerta("info", "Información relevante", "Las citas que se anulen con menos de 24 horas de margen, deberán ser abonadas <strong>excepto cuando el motivo esté debidamente justificado.</strong>");
                    }
                    switch ($cita['id_estado']) {
                        case 1: //pdte confirmacion
                            echo $mensaje_anulacion;
                            echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="PreguntaSolicitudAnulacion(' . $cita['id'] . ');"><i class="fa fa-times"></i> Solicitar anulación de cita</a>';
                            break;
                        case 2: //confirmada
                            echo $mensaje_anulacion;
                            echo '<a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="PreguntaSolicitudAnulacion(' . $cita['id'] . ');"><i class="fa fa-times"></i> Solicitar anulación de cita</a>';
                            break;
                        case 3: //pdte anulacion
                            echo $mensaje_anulacion;
                            echo '<a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="CancelarSolicitudAnulacion(' . $cita['id'] . ');"><i class="fa fa-history"></i> Cancelar solicitud de anulación</a>';
                            break;
                        case 4: //anulada
                            echo "La cita ha sido anulada y ya no es válida.";
                            break;
                    }
                    if (!empty($cita['cita_videollamada'])) {
                        echo ' <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="IniciarConsultaVideollamada(' . $cita['id'] . ');"><i class="fa fa-history"></i> Iniciar Consulta por Videollamada</a>';
                    }
                    if (!empty($cita['motivo_anulacion_cita'])) {
                        echo "<br>Motivo de anulación: <i>" . $cita['motivo_anulacion_cita'] . "</i>";
                    }
                    echo '<hr>';
                    echo '<br>';
                }
            } else {
                 echo AcessoPacientes_Alerta("info", "Atención", "No tienes nignuna cita en el sistema.");
            }
            ?>
        </div>

    </div>
</div>
<?php
// Incluir el footer del tema
get_footer();
?>