<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://culqi.com
 * @since             1.0.0
 * @package           Culqi_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Culqi WooCommerce
 * Plugin URI:        https://www.culqi.com/docs/
 * Description:       Plugin Culqi WooCommerce. Acepta tarjetas de crédito y débito en tu tienda online.
 * Version:           2.1.1
 * Author:            Brayan Cruces, Willy Aguirre
 * Author URI:        http://culqi.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       culqi-woocommerce
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//require home_url() . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/WC_Culqi.class.php';
require plugin_dir_path( __FILE__ ) . 'WC_Culqi.class.php';

function wc_culqi_styles()
{
	// Register the style like this for a plugin:
	wp_register_style( 'custom-style', plugins_url( '/assets/css/waitMe.css', __FILE__ ), array(), '1.0.0', 'all' );
	// For either a plugin or a theme, you can then enqueue the style:
	wp_enqueue_style( 'custom-style' );
}
function wc_culqi_scripts()
{
	// Register the script like this for a plugin:
	wp_register_script( 'custom-script', plugins_url( '/assets/js/waitMe.js', __FILE__ ), array('jquery') );
	// For either a plugin or a theme, you can then enqueue the script:
	wp_enqueue_script( 'custom-script' );
}

DEFINE('PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('plugins_loaded', 'init_wc_culqi_payment_gateway', 0);
    add_action('woocommerce_checkout_process', 'some_custom_checkout_field_process');
    /**
     * Validacion de campos antes de pasar a Culqi
     */
    function some_custom_checkout_field_process() {
        error_log("[CULQI]...Validando...");
        if(preg_match('/^[^0-9±!@£$%^&*_+§¡€#¢§¶•ªº«\\<>?:;|=.,]{2,50}$/', $_POST['billing_first_name'])) {
            //error_log("Nombre correcto");
        } else {
            wc_add_notice('Por favor, ingresa un <strong>nombre </strong>válido', 'error' );
        }
        if(preg_match('/^[^0-9±!@£$%^&*_+§¡€#¢§¶•ªº«\\<>?:;|=.,]{2,50}$/', $_POST['billing_last_name'])) {
            //error_log("Apellido correcto");
        } else {
            wc_add_notice('Por favor, ingresa un <strong>apellido </strong>válido.', 'error' );
        }
		if(strlen ($_POST['billing_email'])>4 && strlen ($_POST['billing_email'])<50) {
			//error_log("Email correcto");
		} else {
			wc_add_notice('Por favor, ingresa un <strong>e-mail </strong>válido. Usa menos de 50 caracteres y más de 4.', 'error' );
		}
        if(strlen ($_POST['billing_phone'])>5 && strlen ($_POST['billing_phone'])<15 &&
				  preg_match('/^[1-9][0-9]*$/', $_POST['billing_phone']) ) {
            //error_log("Teléfono correcto");
        } else {
            wc_add_notice('Por favor, ingresa un <strong>número telefónico </strong>válido. Solo numeros', 'error' );
        }
        if(strlen ($_POST['billing_country'])>1 && strlen ($_POST['billing_country'])<3) {
            //error_log("País correcto");
        } else {
            wc_add_notice('Por favor, ingresa un <strong>país </strong>válido.', 'error' );
        }
        if(strlen ($_POST['billing_city'])>2 && strlen ($_POST['billing_city'])<30) {
            //error_log("Ciudad correcto");
        } else {
            wc_add_notice('Por favor, ingresa una <strong>ciudad </strong>válida.', 'error' );
        }
        if(strlen ($_POST['billing_address_1'])>5 && strlen ($_POST['billing_address_1'])<100) {
            //error_log("Dirección correcto");
        } else {
            wc_add_notice('Por favor, ingresa una <strong>dirección </strong>válida.', 'error' );
        }
    }
          
    function woocommerce_culqi_add_gateway($methods) {
      $methods[] = 'WC_Culqi';
      return $methods;
    }
      
    add_filter('woocommerce_payment_gateways', 'woocommerce_culqi_add_gateway');
}