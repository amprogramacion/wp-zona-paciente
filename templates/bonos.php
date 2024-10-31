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
        <div class="row">
            <div class="contenido-bonos"></div>
        </div>
    </div>
</div>
<script>
    function ConsultarBonos() {
        var token = '<?= $_SESSION['paciente']->paciente_token; ?>';
        $.ajax({
            url: "https://neuroredacer.es/api/index.php",
            type: 'GET',
            data: {token: token, controller: 'bonos', action: 'listado'},
            success: function (data) {
                var res = JSON.parse(data);
                var contenido = "";
                res.forEach(function (bono, indice, array) {
                    if (bono.estado == 1 || bono.estado == 2 || bono.estado == 5) {
                        contenido += '<div class="card mt-2">';
                        contenido += '<img class="card-img" src="<?php echo plugin_dir_url(__FILE__) . '../assets/images/logo_bonos.png'; ?>" alt="">';
                        contenido += '<div class="card-img-overlay">';

                        contenido += '<div class="row mb-1">';
                        contenido += '<div class="col-8">';
                        contenido += '<span class="float-left"><strong>Bono ' + bono.nombre_bono + '</strong></span>';
                        contenido += '</div>'; //col-8
                        contenido += '<div class="col-4">';
                        switch (bono.estado) {
                            case "1":
                                contenido += '<span class="badge badge-warning float-right" style="padding: 2px;">PDE. PAGO</span>';
                                break;
                            case "2":
                                contenido += '<span class="badge badge-success float-right">ACTIVO</span>';
                                break;
                            case "5":
                                contenido += '<span class="badge badge-warning float-right">SUSPENDIDO</span>';
                                break;
                        }
                        contenido += '</div>'; //col-4
                        contenido += '</div>'; //row mb-1


                        contenido += '<div class="row mb-1">';

                        //bono.sesiones_totales
                        for (var a = 1; a <= 10; a++) {

                            if (a <= bono.sesiones_restantes) {
                                var icono = "<?php echo plugin_dir_url(__FILE__) . '../assets/images/checked_box.svg'; ?>";
                            } else {
                                var icono = "<?php echo plugin_dir_url(__FILE__) . '../assets/images/cross_box.svg'; ?>";
                            }
                            contenido += '<div class="col">';
                            if (a <= bono.sesiones_totales) {
                                contenido += '<img src="' + icono + '" class="img-fluid">';
                            }
                            contenido += '</div>';
                            if (a % 5 == 0) {
                                contenido += '</div>';
                                contenido += '<div class="row mb-1">';
                            }

                        }
                        contenido += '</div>'; //row mb-1


                        contenido += '<div class="row">';
                        contenido += '<div class="col-12"><em><small>';
                        if (bono.sesiones_restantes == 0) {
                            contenido += 'No quedan sesiones. ';
                        } else if (bono.sesiones_restantes == 1) {
                            contenido += 'Queda 1 sesión. ';
                        } else {
                            contenido += 'Quedan ' + bono.sesiones_restantes + ' sesiones. ';
                        }
                        contenido += 'Adquirido el ' + bono.fecha_compra_bono_js;
                        if (bono.estado == "1") {
                            contenido += ' - <a href="app.html?page=condiciones-compra-bono&id_bono=' + bono.id_bono + '">PAGAR AHORA</a>';
                        }
                        contenido += '</small></em></div>'; //col-12
                        contenido += '</div>'; //row


                        contenido += '</div>'; //card-img-overlay
                        contenido += '</div>'; //card
                    }
                }); //end foreach

                $(".contenido-bonos").html(contenido);

            } //end success
        }); //end ajax
    } //end function
    $(document).ready(function () {
        ConsultarBonos();
    });
    </script><br><br><br><br><br><br>
<?php
get_footer();
?>