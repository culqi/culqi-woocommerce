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
 * Version:           2.2.0
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
    function init_wc_culqi_payment_gateway() {
        if (!class_exists('WC_Payment_Gateway')) {
            return;
        }
        DEFINE('PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');
        class WC_culqi extends WC_Payment_Gateway
        {
            public function __construct() {
                global $woocommerce;
                $this->includes();
                $this->id = 'culqi';
                $this->icon = home_url() . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/assets/images/cards.png';
                $this->method_title = __('Culqi', 'WC_culqi');
                $this->method_description = __('Acepta tarjetas de crédito, débito o prepagadas. ¡Y ahora, pagos en efectivo!.', 'WC_culqi');
                $this->order_button_text = __('Pagar', 'WC_culqi');
                $this->has_fields = false;
                $this->supports = array(
                    'products'
                );
                $this->init_form_fields();
                $this->init_settings();
                $this->title = 'Tarjeta de crédito/débito o paga con efectivo';
                $this->description = 'Paga con tarjeta de crédito, débito o paga en efectivo (nuevo).';

                // Obtener credenciales y entorno
                $this->culqi_codigoComercio = $this->get_option('culqi_codigoComercio');
                $this->culqi_key = $this->get_option('culqi_key');
                
                // Orders
                $this->enabled_multipayment = $this->get_option('enabled_multipayment');  
                $this->orders_expiration_default = $this->get_option('orders_expiration_default');  
                


                $this->culqi_nombre_comercio = get_bloginfo('name');
                add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'crear_cargo'));// Crear Cargo 

                add_action('woocommerce_api_process_order', array($this, 'procesar_orden')); // Pasar a estado pendiente
                add_action('woocommerce_api_cq_webhook', array($this, 'actualizar_orden')); // Actualizar estado 
               

                add_action('woocommerce_receipt_culqi', array(&$this, 'receipt_page'));
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
                if (!$this->is_valid_for_use()) $this->enabled = false;
            }
            public function pathModule()
            {
                $dir = home_url() . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/';
                return $dir;
            }
            /**
             * Enviar correos
             */
            public function mailNotifyPayment($id_order, $email, $status, $message) {
                $wc_sp = new Culqi();
                $mailer = WC()->mailer();
                switch ($status) {
                    case 'pending':
                        $msg_status = 'Se genero una orden de compra';
                        break;
                    case 'success':
                        $msg_status = "El pago de su pedido  $id_order fue aceptado";
                        break;
                    case 'cancelled':
                        $msg_status = "Su pedido $id_order no fue aceptado";
                        break;
                    default:
                        $msg_status = 'Información de Orden';
                        break;
                }
                $subject = $this->culqi_nombre_comercio . ' -  ' . $msg_status;
                add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
                wp_mail($email, $subject, $body, 'header');
            }
            public function acuse($js) {
                global $woocommerce;
                if (function_exists('wc_enqueue_js')) {
                    wc_enqueue_js($js);
                } else {
                    $woocommerce->add_inline_js($js);
                }
            }

            function get_post_id_by_meta_key_and_value($key, $value) {
                global $wpdb;
                $meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape($key)."' AND meta_value='".$wpdb->escape($value)."'");
                if (is_array($meta) && !empty($meta) && isset($meta[0])) {
                    $meta = $meta[0];
                }		
                if (is_object($meta)) {
                    return $meta->post_id;
                }
                else {
                    return false;
                }
            }

            /**
             * Incluye dependencias
             *
             */
            private function includes() {
                // Cargamos Requests y Culqi PHP
                include_once("includes/libraries/culqi-php/lib/culqi.php");
            } 
         
            /**
             * Crear Cargo (recibe token y procesa venta)
             * Via WC_API
             */
            function crear_cargo() {
                if (isset($_POST['token_id']) && isset($_POST['order_id'])) {
                    global $wpdb, $woocommerce;
					$order = new WC_Order($_POST['order_id']);
					$numeroPedido = str_pad($order->id, 2, "0", STR_PAD_LEFT);
					$total = str_replace('.', '', number_format($order->get_total(), 2, '.', ''));
					$total = str_replace(',', '',$total);
                    $culqi = new Culqi\Culqi(array('api_key' => $this->culqi_key));
	                // Generamos un Código de pedido único (ejemplo)
	                $pedidoId = $this->generateRandomString(4)."-".$numeroPedido;
	                error_log("Número de pedido: ". $pedidoId);
	                error_log("Token: ". $_POST['token_id'] );
					/**
					 * Validando y formateando datos (one more time)							 *
					 *
					 */
					 $dataUser = $order->get_user();
					 $fono = $dataUser->billing_phone;
					 $descripcion = '';
					 $i = 1;
					 $separador = ' - ';
					 foreach ($order->get_items() as $product ){
							 if($i == count($order->get_items())){
									 $separador = '';
							 }
							 $descripcion .= $product['name'].$separador;
							 $i++;
					 }
					 if(strlen ($descripcion)>5 && strlen ($descripcion)<60) {
							 error_log("Descripción correcto");
					 } else {
							 $descripcion = "Compra";
					 }
					 $datos_ciudad = "";
					 $datos_correo = "";
					 $datos_apellido = "";
					 $datos_nombre = "";
					 $datos_telefono = "";
					 $datos_direccion = "";
					 if ($order->billing_city == null) {
							 $datos_ciudad = "Ciudad";
					 } else {
							 $datos_ciudad = $order->billing_city;
					 }
					 if ($order->billing_first_name == null){
							 $datos_nombre = "Nombre";
					 } else {
							 $datos_nombre = $order->billing_first_name;
					 }
					 if ($order->billing_last_name == null){
							 $datos_apellido = "Apellido";
					 } else {
							 $datos_apellido = $order->billing_last_name;
					 }
					 if ($order->billing_email == null){
							 $datos_correo = "correo@tienda.com";
					 } else {
							 $datos_correo = $order->billing_email;
					 }
					 if ($order->billing_phone == null){
							 $datos_telefono = "12313123";
					 } else {
							 $datos_telefono = $order->billing_phone;
					 }
					 if ($order->billing_address_1 == null) {
							 $datos_direccion = "Avenida 123";
					 } else {
							 $datos_direccion = $order->billing_address_1;
					 }
                    // Creando Cargo
                    try {
											$charge = $culqi->Charges->create(array(
                            "amount" => $total,
                            "antifraud_details" => array(
                                "address" => $datos_direccion,
                                "address_city" => $datos_ciudad,
                                "country_code" => $order->billing_country,
                                "first_name" => $datos_nombre,
                                "last_name" => $datos_apellido,
                                "phone_number" => $datos_telefono,
                            ),
                            "capture" => true,
                            "currency_code" => $order->order_currency,
                            "description" => $descripcion,
                            "email" => $datos_correo,
                            "installments" => (int)$_POST['installments'],
                            "metadata" => array(
                                "order_id" => (string)$pedidoId
                            ),
                            "source_id" => $_POST['token_id']
                        ));
                        if($charge->object == "charge") { 
                            $order->update_meta_data('_culqi_finished_payment', "true"); 
                            $order->save();
                            $order->payment_complete();
                        }
                        echo wp_send_json($charge);
                    } catch(Exception $e) {
                        // ERROR: El cargo tuvo algún error o fue rechazado
                        //echo 'Se dio una excepcion';
                        
                        echo wp_send_json($e->getMessage());
                    }
               } else {
                    global $woocommerce;
                    $woocommerce->cart->empty_cart();
               }
               exit;
            } 


           function procesar_orden () {
               
               if (isset($_POST['order_id'])) { 
                   global $wpdb, $woocommerce;
				   $order = new WC_Order($_POST['order_id']);                 

                  
                   $order->update_status( 'on-hold', 'Se eligio Pago en efectivo via Culqi.' );       

                   $order->reduce_order_stock();

                   $woocommerce->cart->empty_cart();

                    // Return 
                    echo wp_send_json( array(
                        'result'    => 'success',
                        'redirect'  => $this->get_return_url($order)
                    ));
                         
               } 

               exit;
           }       

           function actualizar_orden () {  

                error_log('Llego peticion evento de Culqi'); 
                
                $inputJSON = file_get_contents('php://input');
                error_log($inputJSON); 
                $input= json_decode($inputJSON);        
                $data = json_decode($input->data);                      
                

                if($input->object == 'event' && $input->type == 'order.status.changed') {

                   global $wpdb, $woocommerce;  

                   $foundPostId = $this->get_post_id_by_meta_key_and_value('_culqi_order_id', $data->id);
                   
                   if($foundPostId) { 
                      error_log('Order encontrada: '. $foundPostId);

                     if(get_metadata('post',$foundPostId, '_culqi_finished_payment', true) != 'true') {

                       if($data->state == 'paid') {
                         error_log('Estado: Pagada'); 
 
                         $order = new WC_Order($foundPostId);                       
                         $order->payment_complete();

                         echo wp_send_json( array(
                            'result'    => 'success'
                         ));
                       } 

                       if($data->state == 'expired') {
                         error_log('Estado: Expirada');

                         $order = new WC_Order($foundPostId);   

                         error_log($order->get_status());

                         $order->update_status( 'cancelled', 'La orden no fue pagada a tiempo.' );
 
                         echo wp_send_json( array(
                             'result'    => 'success'
                         ));                          
                       } 


                     }                 
                     else {
                         error_log("No se realizó ninguna acción.");
                     }
                      
                                         
                     
                   }

                   error_log('La orden no fue encontrada en este comercio.');               

                   
                }               

                //wp_send_json_error();


                exit; 

           }    


            function is_valid_for_use() {
                if (!in_array(get_woocommerce_currency(), array('PEN', 'USD'))) return false;
                return true;
            }
            public function admin_options() {
                ?>
                    <h3><?php _e('Culqi', 'wc_culqi_payment_gateway'); ?></h3>
                    <table class="form-table">
                        <?php
                        if ($this->is_valid_for_use()) :
                            $this->generate_settings_html();
                        ?>

                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_culqi_orders_webhook_url">URL de webhook </label>
                            </th>
                            <td class="forminp">
                                <fieldset>
                                    <legend class="screen-reader-text"><span>URL de webhook</span></legend>
                                    <input class="input-text regular-input " type="text" name="woocommerce_culqi_orders_webhook_url" id="woocommerce_culqi_orders_webhook_url" style="" value="<?php echo get_site_url(null, 'index.php?wc-api=cq_webhook', 'https') ?>" placeholder="" disabled="disabled">
                                    <p class="description">Esta es la URL que tendras que ingresar en Culqi Panel cuando actives Multipagos.</p>
                                </fieldset>
                            </td>
                        </tr>
   
                         
                        <?php     
                        else :
                        ?> 

                        
                        <div class="inline error">
                            <p>
                                <strong>
                                    <?php _e('Gateway Disabled', 'wc_culqi_payment_gateway'); ?>
                                </strong>:
                                <?php _e('Error en la configuración.', 'wc_culqi_payment_gateway'); ?>
                            </p>
                        </div>
                        <?php
                        endif;
                        ?>
                    </table>
                <?php
            }
            function init_form_fields() {
                global $woocommerce;
                $this->form_fields = array(
                    'enabled' => array
                    (
                        'title' => __('Habilitar/Deshabilitar', 'wc_culqi_payment_gateway'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar Culqi', 'wc_culqi_payment_gateway'),
                        'default' => 'yes'
                    ),
                    'culqi_codigoComercio' => array
                    (
                        'title' => __('Llave Pública', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Ingresar Llave Pública', 'wc_culqi_payment_gateway'),
                        'default' => ''
                    ),
                    'culqi_key' => array
                    (
                        'title' => __('Llave Secreta', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Ingresar Llave Secreta', 'wc_culqi_payment_gateway'),
                        'default' => ''
                    ),

                    'enabled_multipayment' => array
                    (
                        'title' => __('Habilitar multipagos', 'wc_culqi_payment_gateway'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar pagos con tarjetas + pago en efectivo', 'wc_culqi_payment_gateway'),
                        'default' => 'no'
                    ), 

                    'orders_expiration_default' => array
                    (
                        'title' => __('Tiempo de duración máxima de las órdenes', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Ingresar el número de horas que tendra el cliente para pagar su orden. Ejem: 24', 'wc_culqi_payment_gateway'),
                        'default' => '24'
                    ), 

                 

                );
            } 


            function process_payment($order_id) {
                $order = new WC_Order($order_id);
                $order->reduce_order_stock();
                return array
                (
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                );
            }
            function receipt_page($order_id) {
                $order = new WC_Order($order_id); 
                

                $numeroPedido = str_pad($order->id, 2, "0", STR_PAD_LEFT);
                /**
                 * Datos de la compra
                 *
                 */
                $total = str_replace('.', '', number_format($order->get_total(), 2, '.', ''));
                $total = str_replace(',', '',$total);
                $dataUser = $order->get_user();
                $fono = $dataUser->billing_phone;
                $descripcion = '';
                $i = 1;
                $separador = ' - ';
                foreach ($order->get_items() as $product ) {
                    if($i == count($order->get_items())){
                        $separador = '';
                    }
                    $descripcion .= $product['name'].$separador;
                    $i++;
                }
                if(strlen ($descripcion)>5 && strlen ($descripcion)<60) {
                    error_log("Descripción correcto");
                } else {
                    $descripcion = "Compra";
                }
                $datos_ciudad = "";
                $datos_correo = "";
                $datos_apellido = "";
                $datos_nombre = "";
                $datos_telefono = "";
                $datos_direccion = "";
                if ($order->billing_city == null) {
                    $datos_ciudad = "Ciudad";
                } else {
                    $datos_ciudad = $order->billing_city;
                }
                if ($order->billing_first_name == null){
                    $datos_nombre = "Nombre";
                }else {
                    $datos_nombre = $order->billing_first_name;
                }
                if ($order->billing_last_name == null){
                    $datos_apellido = "Apellido";
                }else {
                    $datos_apellido = $order->billing_last_name;
                }
                if ($order->billing_email == null){
                    $datos_correo = "integrate@culqi.com";
                } else {
                    $datos_correo = $order->billing_email;
                }
                if ($order->billing_phone == null){
                    $datos_telefono = "12313123";
                } else {
                    $datos_telefono = $order->billing_phone;
                }
                if ($order->billing_address_1 == null) {
                    $datos_direccion = "Avenida 123";
                } else {
                    $datos_direccion = $order->billing_address_1;
                }
                /*  End Crear Cargo  */ 



                /* Crear orden (solo si esta activo)*/                 

                try { 

                    if ($this->enabled_multipayment == 'yes') {
                        $culqi = new Culqi\Culqi(array('api_key' => $this->culqi_key));

                        $orderResponse = $culqi->Orders->create(
                            array(
                                "amount" => (int) $total,
                                "currency_code" => $order->get_currency(),
                                "description" => 'Venta desde Plugin WooCommerce',        
                                "order_number" => "woo-".rand(1, 99999),  
                                "client_details" => array( 
                                        "first_name"=> $datos_nombre, 
                                        "last_name" => $datos_apellido,
                                        "email" => $datos_correo, 
                                        "phone_number" => $datos_telefono
                                ),
                                "expiration_date" => time() + $this->orders_expiration_default*60*60,  
                                "confirm" => false
                            )
                        ); 

                        $order->update_meta_data('_culqi_order_id', $orderResponse->id); 
                        $order->save();

                        $culqi_order_id = get_metadata('post',$order_id, '_culqi_order_id', true); 
                    }               

                    error_log($this->enabled_multipayment);

               
                }

                catch(Exception $e) {
                        // ERROR: La orden tuvo algún error o fue rechazado
                        //echo 'Se dio una excepcion';
                        error_log($e);
                        error_log($e->getMessage());
                }

                ?>
                <div id="info_payment">
                    <span>Realiza la compra presionando <strong>Pagar</strong><br>Si deseas cambiar de medio de pago presiona <strong>Cancelar</strong></span><br><br>
                    <button id="pagar-now">Pagar</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btn-back">Cancelar</button>
                    <div id="culqi_notify" style="padding:10px 0px;"></div>
							  </div>

                <script src="https://checkout.culqi.com/js/v3/"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
				<script defer src="<?php echo plugins_url("/assets/js/waitMe.js", __FILE__ ) ?>"></script>
				<link rel='stylesheet' href='<?php echo plugins_url("/assets/css/waitMe.css", __FILE__ ) ?>' type='text/css' media='all' />

                <script>

                    Culqi.publicKey = '<?php echo $this->culqi_codigoComercio ?>';

                    Culqi.settings({
                        title: '<?php echo $this->culqi_nombre_comercio; ?>',
                        currency: '<?php echo get_woocommerce_currency(); ?>',
                        description: '<?php echo $descripcion; ?>',
                        amount: <?php echo $total; ?>,  
                        <?php if ($this->enabled_multipayment == 'yes') { ?>                   
			            order: '<?php echo trim($culqi_order_id); ?>' <?php }  ?>
                    }); 

                    Culqi.options({ 
                        installments: true
                    });

                    function run_waitMe(){
                        $('#info_payment').waitMe({
                            effect: 'orbit',
                            text: 'Procesando pago.... No cierres esta ventana por favor.',
                            bg: 'rgba(255,255,255,0.7)',
                            color:'#28d2c8'
                        });
                    }

                    // Recibimos Token del Culqi.js
                    function culqi() {


                        if(Culqi.error) {
                            // Mostramos JSON de objeto error en consola
                            console.log(Culqi.error);
							$('#info_payment > #culqi_notify').html('<p style="color:#e54848; font-weight:bold">'+ Culqi.error.user_message + '</p>');
                        } else if (Culqi.token){
                            console.log(Culqi.token.id);
                            $(document).ajaxStart(function(){
                                $('#culqi_notify').empty();
                                run_waitMe();
                            });
                            $(document).ajaxComplete(function(){
                                $('#info_payment').waitMe('hide');
                            });
                            $.ajax({
                                url: "index.php?wc-api=WC_culqi",
                                type: "POST",
                                data: {token_id: Culqi.token.id, order_id: "<?php echo $numeroPedido ?>", installments: Culqi.token.metadata.installments },
                                dataType: 'json',
                                success: function(data) {
									if(data === "Error de autenticación") {
										var err_msg = data + ": verificar si su Llave Secreta es la correcta";
										$('#info_payment > #culqi_notify').html('<p style="color:#e54848; font-weight:bold">'+ err_msg + '</p>');
									} else {
										var result = "";
                                    if(data.constructor == String){
                                        result = JSON.parse(data);
                                    }
                                    if(data.constructor == Object){
                                        result = JSON.parse(JSON.stringify(data));
                                    }
                                    if (result.object === "error") {
                                        // Mostrar error
                                        var message = result.user_message;
                                        $('#info_payment > #culqi_notify').html('<p style="color:#e54848; font-weight:bold">'+ message + '</p>');
                                    } else {
										if (result.object === "charge") {
	                                        $('#notify').empty();
	                                        $("#info_payment").remove();
	                                        $('div.woocommerce').append("<h1 style='text-align: center;'>Pago Exitoso</h1>" +
	                                        "<p style='color:#46e6aa; font-weight:bold'>Pago realizado exitosamente</p>" +
	                                        "<br><button id='home'>Seguir comprando</button>");
	                                        // Procesar Venta en WooCommerce
	                                        $.ajax({
	                                            url: "index.php?wc-api=WC_culqi",
	                                            type: "POST",
	                                            data: {emptyCart: 1},
	                                            success: function (data) {
	                                                // console.log(data);
	                                            }
	                                        });
		                                    } else {
					                   			$('#info_payment > #culqi_notify').html('<p style="color:#e54848; font-weight:bold">ERROR EN LA RESPUESTA JSON</p>');
											}
										}
									}
                                },
                                error: function() {
                                    $('#culqi_notify').empty();
                                    $('#culqi_notify').html('Hubo algún problema en el procesamiento de la compra. Intenta nuevamente por favor.');
                                }
                            });
                        }                         

                        <?php if($this->enabled_multipayment == 'yes') { ?> 
                            else if (Culqi.order) {

                                console.log("Order confirmada correctamente");
                                console.log(Culqi.order);  

                                $(document).ajaxStart(function(){
                                    $('#culqi_notify').empty();
                                    run_waitMe();
                                });
                                $(document).ajaxComplete(function(){
                                    $('#info_payment').waitMe('hide');
                                }); 
                                
                                // Procesar Venta en WooCommerce
	                           $.ajax({
	                              url: "index.php?wc-api=process_order",
	                              type: "POST",
	                              data: {order_id: "<?php echo $order->id ?>"},
	                              success: function (data) {
	                                console.log(data);                                    
                                    window.location.replace(data.redirect);
	                              }
	                           });


                            }
                       <?php } ?> 
                       
                        
                    };
                    // End culqi()
                    $(document).ready(function() {
                        $('div.woocommerce').prepend("<h1 style='text-align: center;' id='title-result'></h1>");
                        $("#info_payment").on('click','#refresh', function(){
                            var url = '<?php echo wc_get_checkout_url(); ?>';
                            window.location.replace(url);
                        });
                        $("div.woocommerce").on('click','#home', function(){
                            var url = '<?php echo home_url(); ?>';
                            window.location.replace(url);
                        });
                        $('#pagar-now').on('click', function (e) {
                            Culqi.open();
							$('#culqi_notify').empty();
                            e.preventDefault();
                        });
                        $('#btn-back').on('click', function(e){
                            var url = '<?php echo wc_get_checkout_url(); ?>';
                            window.location.replace(url);
                        });
                    });
                </script>

                <?php
            }
            function generateRandomString($length = 10) {
                $characters = '0123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            public function restore_order_stock($order_id) {
                $order = new WC_Order($order_id);
                if (!get_option('woocommerce_manage_stock') == 'yes' && !sizeof($order->get_items()) > 0) {
                    return;
                }
                foreach ($order->get_items() as $item) {
                    if ($item['product_id'] > 0) {
                        $_product = $order->get_product_from_item($item);
                        if ($_product && $_product->exists() && $_product->managing_stock()) {
                            $old_stock = $_product->stock;
                            $qty = apply_filters('woocommerce_order_item_quantity', $item['qty'], $this, $item);
                            $new_quantity = $_product->increase_stock($qty);
                            do_action('woocommerce_auto_stock_restored', $_product, $item);
                            $order->add_order_note(sprintf(__('Item #%s stock incremented from %s to %s.', 'woocommerce'), $item['product_id'], $old_stock, $new_quantity));
                            $order->send_stock_notifications($_product, $new_quantity, $item['qty']);
                        }
                    }
                }
            }
        }
        function woocommerce_culqi_add_gateway($methods) {
            $methods[] = 'WC_culqi';
            return $methods;
        }
        add_filter('woocommerce_payment_gateways', 'woocommerce_culqi_add_gateway');
    }
}
