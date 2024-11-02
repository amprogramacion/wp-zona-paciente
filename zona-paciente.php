<?php
/*
  Plugin Name: Zona de Paciente
  Description: Plugin para gestionar la zona de pacientes.
  Version: 1.1
  Author: Ángel
 */

// Asegúrate de que no se acceda directamente al archivo
if (!defined('ABSPATH')) {
    exit;
}

function incluir_bootstrap() {
    // Incluir Bootstrap CSS desde un CDN
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');

    // Incluir Bootstrap JS desde un CDN
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'incluir_bootstrap');

// Aquí puedes añadir tus funciones y hooks
function crear_pagina_paciente() {
    $pagina = array(
        'post_title' => 'Zona de Pacientes',
        'post_content' => '[shortcode_zona_paciente]',
        'post_status' => 'publish',
        'post_type' => 'page',
    );

    // Verifica si la página ya existe
    if (null == get_page_by_title('Zona de Pacientes')) {
        wp_insert_post($pagina);
    }
}

register_activation_hook(__FILE__, 'crear_pagina_paciente');

// Iniciar sesión en el hook 'init' de WordPress
function iniciar_sesion_personalizada() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

add_action('init', 'iniciar_sesion_personalizada');

function cerrar_sesion_personalizada() {
    // Iniciar la sesión si no está ya iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Destruir todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();

    // Redirigir al usuario después de cerrar sesión
    wp_redirect(home_url()); // Redirige a la página principal u otra URL deseada
    exit;
}

function manejar_logout() {
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        cerrar_sesion_personalizada();
    }
}

add_action('init', 'manejar_logout');

function contenido_zona_paciente() {
    // Aquí puedes añadir tu lógica para mostrar la zona de pacientes
    ob_start();
    if (!empty($_SESSION['paciente'])) {
        switch ($_GET['action']) {
            case "facturas":
                include(plugin_dir_path(__FILE__) . 'facturas-paciente.php');
                break;
            case "bonos":
                include(plugin_dir_path(__FILE__) . 'bonos-paciente.php');
                break;
            case "perfil":
                include(plugin_dir_path(__FILE__) . 'perfil-paciente.php');
                break;
            case "mis-citas":
                include(plugin_dir_path(__FILE__) . 'miscitas-paciente.php');
                break;
            case "avisos":
                include(plugin_dir_path(__FILE__) . 'avisos-paciente.php');
                break;
            default:
                include(plugin_dir_path(__FILE__) . 'panel-paciente.php');
                break;
        }
    } else {
        include(plugin_dir_path(__FILE__) . 'login-paciente.php');
    }
    return ob_get_clean();
}

add_shortcode('shortcode_zona_paciente', 'contenido_zona_paciente');

function AccesoPacientes_Login($dni, $password) {

    $data = [
        'dni' => $dni,
        'pwd' => $password,
        'controller' => 'login',
        'action' => 'login'
    ];
    $responsejson = zona_paciente_apicall($data);
    $response = json_decode($responsejson, true);
    if (is_array($response)) {
        if ($response['error'] === true) {
            return array("error" => $response['msg_error']);
        } else {
            return array("paciente_info" => $response['paciente_info']);
        }
    } else {
        return array("error" => 'RESPONSE_ERROR');
    }
}

function AcessoPacientes_Alerta($tipo, $titulo, $texto, $dismiss = false, $boton = false, $accion = false) {

    switch ($tipo) {
        case "danger":
        case "info":
        case "primary":
        case "secondary":
            $icono = "ri-information-line";
            break;
        case "warning":
        case "success":
        case "light":
            $icono = "ri-alert-fill";
            break;
    }
    if ($boton != false) {
        $bot = '<p><a href="' . $accion . '" class="btn btn-' . $tipo . '">' . $boton . '</a></p>';
    } else {
        $bot = '';
    }
    if ($dismiss == true) {
        $dismi = ' alert-dismissible';
        $dismib = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    } else {
        $dismi = '';
        $dismib = '';
    }
    return '<div class="alert alert-' . $tipo . $dismi . '">
                ' . $dismib . '
                <div class="iq-alert-icon"><i class="' . $icono . '"></i></div> 
                <div class="iq-alert-text"><b>' . $titulo . '</b><br>' . $texto . $bot . '</div>
              </div>';
}

function AccesoPacientes_Redir($time, $url) {
    return '<META HTTP-EQUIV="Refresh" CONTENT="' . $time . '; URL=' . $url . '">';
}

function zona_paciente_rewrite_rules() {
    add_rewrite_rule('^zona-paciente/([^/]*)/?', 'index.php?zona-paciente=$matches[1]', 'top');
}

add_action('init', 'zona_paciente_rewrite_rules');

function zona_paciente_query_vars($query_vars) {
    $query_vars[] = 'zona-paciente';
    return $query_vars;
}

add_filter('query_vars', 'zona_paciente_query_vars');

function zona_paciente_template_include($template) {
    $mi_plugin_pagina = get_query_var('zona-paciente');
    if (file_exists(plugin_dir_path(__FILE__) . 'templates/' . $mi_plugin_pagina . '.php')) {
        return plugin_dir_path(__FILE__) . 'templates/' . $mi_plugin_pagina . '.php';
    }
    return $template;
}

add_filter('template_include', 'zona_paciente_template_include');

function zp_CambiarEmailDefinitivo($id_cliente, $id_paciente) {
    
}

function zp_GuardarToken($id_paciente, $token) {
    
}

function zp_CambiarEmailTemporal($email) {
    
}

function zp_ApiCall($url) {
    $ch = curl_init();

// Establecer la URL con los parámetros
    curl_setopt($ch, CURLOPT_URL, $url);

// Indicar que queremos recibir la respuesta como una cadena de texto
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud cURL
    $response = curl_exec($ch);

// Verificar si hubo un error
    if ($response === false) {
        $error = curl_error($ch);
        echo 'Error en cURL: ' . $error;
    } else {
        // Procesar la respuesta
        return $response;
    }

// Cerrar la sesión cURL
    curl_close($ch);
}

//Comprobar actualizacioes

add_action('admin_menu', 'zona_paciente_add_admin_menu');
add_action('admin_init', 'zona_paciente_settings_init');

function zona_paciente_add_admin_menu() {
    add_options_page('Configuración de Actualización', 'Zona Paciente - Configuración de Actualización', 'manage_options', 'zona_paciente', 'zona_paciente_options_page');
}

function zona_paciente_settings_init() {
    register_setting('zona_paciente', 'zona_paciente_settings');

    add_settings_section(
            'zona_paciente_section',
            __('Credenciales del Repositorio', 'zona_paciente'),
            null,
            'zona_paciente'
    );

    add_settings_field(
            'zona_paciente_username',
            __('Usuario', 'zona_paciente'),
            'zona_paciente_username_render',
            'zona_paciente',
            'zona_paciente_section'
    );

    add_settings_field(
            'zona_paciente_password',
            __('Contraseña', 'zona_paciente'),
            'zona_paciente_password_render',
            'zona_paciente',
            'zona_paciente_section'
    );
}

function zona_paciente_username_render() {
    $options = get_option('zona_paciente_settings');
    ?>
    <input type='text' name='zona_paciente_settings[zona_paciente_username]' value='<?php echo $options['zona_paciente_username']; ?>'>
    <?php
}

function zona_paciente_password_render() {
    $options = get_option('zona_paciente_settings');
    ?>
    <input type='password' name='zona_paciente_settings[zona_paciente_password]' value='<?php echo $options['zona_paciente_password']; ?>'>
    <?php
}

function zona_paciente_options_page() {
    ?>
    <form action='options.php' method='post'>
        <h2>Zona Paciente - Configuración de Actualización</h2>
        <?php
        settings_fields('zona_paciente');
        do_settings_sections('zona_paciente');
        submit_button();
        ?>
    </form>
    <?php
}

add_filter('pre_set_site_transient_update_plugins', 'zona_paciente_comprobar_actualizacion');

function zona_paciente_comprobar_actualizacion($transient) {
    if (empty($transient->checked))
        return $transient;

    // URL del archivo JSON
    $remote = wp_remote_get('https://tu-servidor.com/mi-plugin-update.json');

    if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200) {
        $remote = json_decode($remote['body']);
        if ($remote && version_compare($remote->version, '1.0', '>')) {
            $plugin = plugin_basename(__FILE__);
            $transient->response[$plugin] = (object) [
                        'slug' => 'mi-plugin',
                        'new_version' => $remote->version,
                        'url' => 'https://tu-servidor.com',
                        'package' => $remote->download_url,
            ];
        }
    }

    return $transient;
}

function zona_paciente_obtener_facturas($token) {
    $data = [
        'token' => $token,
        'controller' => 'facturas',
        'action' => 'listadov2'
    ];
    return zona_paciente_apicall($data);
}

function zona_paciente_obtener_citas($token) {
    $data = [
        'token' => $token,
        'controller' => 'citas',
        'action' => 'listado',
        'max2weeks' => 1
    ];
    return zona_paciente_apicall($data);
}

function zona_paciente_apicall($data) {
    $url = 'https://neuroredacer.es/api/index.php';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return 'Error:' . curl_error($ch);
    } else {
        // Mostrar la respuesta
        return $response;
    }
    curl_close($ch);
}
