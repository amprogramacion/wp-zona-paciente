<?php
if (!empty($_SESSION['paciente'])) {
    ?>
    <div class="row">
        <div class="col-md-10">
            <h3>Tus citas</h3>
            <p>Desde aquí podrás gestionar tus citas.</p>
        </div>
        <div class="col-md-2">
            <a href="javascript:history.back(-1);" class="btn btn-primary"><i class="fa fa-arrow-left"></i>Volver atrás</a>
        </div>
        <div class="col-md-12">
            <?php
            $citas_paciente = json_decode(zona_paciente_obtener_citas($_SESSION['paciente']['paciente_token']), true);
            if (!empty($citas_paciente)) {
                $contenido = '<div class="listView iconed">';
                foreach ($citas_paciente as $cita) {
                    $contenido .= '<a href="javascript:void(0);" onclick="javascript:AbrirModalOpcionesCita(' . $cita['id'] . ', ' . $cita['id_estado'] . ');" class="listItem">';
                    $contenido .= '<div class="iconBox bg-' . $cita['color_estado'] . '">' . $cita['icono_estado'] . '</div>';
                    $contenido .= '<div class="text">';
                    $contenido .= '<div>';

                    $fecha = DateTime::createFromFormat('Y-m-d', $cita['dia_cita'])->format('d-m-Y');
                    $hora = DateTime::createFromFormat('Y-m-d H:i:s', $cita['dia_cita'] . ' ' . $cita['hora_inicio_cita'])->format('H:i');

                    $contenido .= '<strong>' . $fecha . ' a las ' . $hora . '</strong>';
                    $contenido .= '<div class="text-muted">';
                    $contenido .= $cita['especialidad'] . ' - ' . $cita['estado_cita'];

                    if ($cita['es_domicilio'] == 1) {
                        $contenido .= ' (DOMICILIO)';
                    }

                    $contenido .= '</div>'; // text-muted
                    $contenido .= '</div>'; // inner div
                    $contenido .= '</div>'; // text
                    $contenido .= '</a>'; // link
                }
                $contenido .= '</div>';
                echo $contenido;
            } else {
                echo "No tienes citas en las próximas dos semanas.";
            }
            ?>
        </div>
    </div>
    <?php
}