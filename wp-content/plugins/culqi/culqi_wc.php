<?php
/**
 * Plugin Name: WooCommerce Culqi
 *
 * Description: WooCommerce.
 * Author: Culqi
 * Author URI:
 * Version: 1.0.3
 * License:
 * Text Domain: wc_culqi_payment_gateway
 * Domain Path: languages/
 */

date_default_timezone_set('America/Lima');
ini_set('display_errors', 0);
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Comprobar si WooCommerce está activo.
 **/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    //cargar plugin
    add_action('plugins_loaded', 'init_wc_culqi_payment_gateway', 0);

    add_action('woocommerce_checkout_process', 'some_custom_checkout_field_process');

    function some_custom_checkout_field_process() {

        error_log("Validando");

        if(preg_match('/^[^0-9±!@£$%^&*_+§¡€#¢§¶•ªº«\\<>?:;|=.,]{2,50}$/', $_POST['billing_first_name'])) {

            error_log("Nombre correcto");

        } else {
            wc_add_notice('Por favor ingresa un <strong>nombre </strong>válido', 'error' );
        }

        if(preg_match('/^[^0-9±!@£$%^&*_+§¡€#¢§¶•ªº«\\<>?:;|=.,]{2,50}$/', $_POST['billing_last_name'])) {

            error_log("Apellido correcto");

        } else {
            wc_add_notice('Por favor ingresa un <strong>apellido </strong>válido.', 'error' );
        }

        if(strlen ($_POST['billing_phone'])>5 && strlen ($_POST['billing_phone'])<15) {

            error_log("Teléfono correcto");

        } else {
            wc_add_notice('Por favor ingresa un <strong>número telefónico </strong>válido.', 'error' );
        }

        if(strlen ($_POST['billing_country'])>1 && strlen ($_POST['billing_country'])<3) {

            error_log("País correcto");

        } else {
            wc_add_notice('Por favor ingresa un <strong>país </strong>válido.', 'error' );
        }

        if(strlen ($_POST['billing_city'])>2 && strlen ($_POST['billing_city'])<30) {

            error_log("Ciudad correcto");

        } else {
            wc_add_notice('Por favor ingresa una <strong>ciudad </strong>válida.', 'error' );
        }

        if(strlen ($_POST['billing_address_1'])>5 && strlen ($_POST['billing_address_1'])<100) {

            error_log("Dirección correcto");

        } else {
            wc_add_notice('Por favor ingresa una <strong>dirección </strong>válida.', 'error' );
        }


    }

    function init_wc_culqi_payment_gateway()
    {

        //salir si no esta activa la clase
        if (!class_exists('WC_Payment_Gateway')) {
            return;
        }

        //definir ruta del plugin
        DEFINE('PLUGIN_DIR', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)) . '/');

        /**
         * Pasarela pago: Culqi
         *
         * Proporciona a culqi un Estándar de pasarela de pago.
         *
         * @class       WC_culqi
         * @extends     WC_Payment_Gateway
         * @version     2.1
         * @package
         * @author      Culqi
         */
        class WC_culqi extends WC_Payment_Gateway
        {

            public function __construct()
            {

                global $woocommerce;

                $this->includes();
                $this->id = 'culqi';
                //$this->icon = plugins_url( 'Culqi-WooCommerce/assets/images/cards.png', dirname( __FILE__ ) );
                $this->icon = home_url() . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/assets/images/cards.png';
                $this->method_title = __('Culqi', 'WC_culqi');
                $this->method_description = __('Acepta tarjetas de crédito, débito o prepagadas.', 'WC_culqi');
                //boton para seleccionar culqi enz la lista de pasarelas de pago
                $this->order_button_text = __('Pagar', 'WC_culqi');
                $this->has_fields = false;
                $this->supports = array(
                    'products',
                    'refunds'
                );
                //Cargue los campos de formulario.
                $this->init_form_fields();

                //Cargue los ajustes.
                $this->init_settings();

                // definicion de variables
                $this->title = 'Tarjeta de crédito o débito';
                $this->description = 'Paga con tarjeta de crédito, débito o prepagada de todas las marcas.';
                $this->sp_idSocio = $this->get_option('sp_idSocio');
                $this->sp_key = $this->get_option('sp_key');
                $this->sp_modo = $this->get_option('sp_modo');
                $this->sp_nombre_comercio = get_bloginfo('name');
                //$this->sp_valid_vence = $this->get_option('sp_valid_vence');
                //$this->sp_vence = $this->get_option('sp_vence');

                //add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
                add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'check_response'));//1
                /** Process the checkout **/
                //add_action('woocommerce_after_order_notes', array($this, 'my_custom_checkout_field'));
                //add_action('woocommerce_checkout_process', array($this, 'some_custom_checkout_field_process'));
                add_action('woocommerce_receipt_culqi', array(&$this, 'receipt_page'));
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

                if (!$this->is_valid_for_use()) $this->enabled = false;

                // Active logs.
                if ('test' == $this->sp_modo) {
                    if (class_exists('WC_Logger')) {
                        $this->log = new WC_Logger();
                    } else {
                        $this->log = WC_culqi::woocommerce_instance()->logger();
                    }
                }
            }

            /////CUSTOM////

            public function process_refund( $order_id, $amount = null ) {
                // Do your refund here. Refund $amount for the order with ID $order_id

                Culqi::$llaveSecreta = $this->sp_key;
                Culqi::$codigoComercio = $this->sp_idSocio;

                if ($this->sp_modo == 'prod') {
                    Culqi::$servidorBase = 'https://pago.culqi.com';
                } else {
                    Culqi::$servidorBase = 'https://integ-pago.culqi.com';
                }

                error_log("ID de la transaccion: ". get_post_meta( $order_id, '_transaction_id', true ));

                try {

                    $anulacion= Pago::anular(get_post_meta( $order_id, '_transaction_id', true ));

                    error_log("Respuesta de anulación: ". $anulacion["codigo_respuesta"]);

                    if ($anulacion["codigo_respuesta"] == "devolucion_exitosa") {

                        return true;

                    } else {

                        return false;

                    }

                } catch (InvalidParamsException $e) {

                    error_log("Error en la anulación: ". $e->getMessage());

                    return false;
                }

            }

            /////////


            public function pathModule()
            {
                $dir = home_url() . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/';
                return $dir;
            }

            public function log_payment($mensaje = '')
            {
                $this->log->add($this->id, 'Respuesta no válida Recibido de Culqi. Error: ' . $mensaje);
                return "error al recepcionar los datos";
            }

            /**
             * Respuesta Inmediata
             *
             * @access public
             * @param  nothing
             * @return string
             */
            // public function custom_thankyou_page()
            // {
            //$order = new WC_Order($id_order);

            // $order    = new WC_Order( $id_order );


            // $usuario = $order->billing_first_name;
            // $nombre_comercio = $this->sp_nombre_comercio;

            // if (!isset($order->id))
            //     $mensaje = $this->log_payment("numero de orden invalido");

            // if ($order->get_status() <> 'pending')
            //     $mensaje = $this->log_payment("estado de pedido");

            // if ($order->get_total() <> $data['importe'])
            //     $mensaje = $this->log_payment("importe del pedido");

            // /**
            //  * Validar aquí el vencimiento del pago.
            //  */
            // //$data['fecha_vencimiento']

            // if ('PEN' <> $order->get_order_currency())
            //     $mensaje = $this->log_payment("moneda del pedido");

            // $woocommerce->cart->empty_cart();
            // }


            public function mailNotifyPayment($id_order, $email, $status, $message)
            {
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
                //include_once('CULQI_mail_sp.php');
                $subject = $this->sp_nombre_comercio . ' -  ' . $msg_status;
                add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

                wp_mail($email, $subject, $body, 'header');
            }

            public function acuse($js)
            {
                global $woocommerce;
                if (function_exists('wc_enqueue_js')) {
                    wc_enqueue_js($js);
                } else {
                    $woocommerce->add_inline_js($js);
                }
            }

            /**
             * Includes.
             *
             * @return void
             */
            private function includes()
            {
                include_once("culqi.php");
            }

            /**
             * Comprueba la respuesta del API.
             * Diferido
             *
             * @access public
             * @param  nothing
             * @return void
             */
            function check_response()
            {
                if (isset($_POST['respuesta'])) {

                    global $wpdb;

                    Culqi::$llaveSecreta = $this->sp_key;
                    Culqi::$codigoComercio = $this->sp_idSocio;

                    $respuesta = json_decode(Culqi::decifrar($_POST['respuesta']), TRUE);

                    error_log("Respuesta del checkout: ". json_encode($respuesta));

                    //$respuesta = json_encode($respuesta);

                    error_log("Número de pedido: ". $respuesta["numero_pedido"]);

                    $pos = stripos($respuesta["numero_pedido"], '-') + 1;
                    $orderId = substr($respuesta["numero_pedido"], $pos);
                    $order    = new WC_Order( $orderId );


                    //Save to DB
                    /*
                    $table_name = $wpdb->prefix.'wp_culqi_order';
                    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                        //table not in database. Create new table

                        $sql = "CREATE TABLE `wp_culqi_order` (
	                      `id` int(10) NOT NULL auto_increment,
	                      `id_transaccion` varchar(255),
	                      `numero_pedido` varchar(255),
	                      `codigo_respuesta` varchar(255),
	                      `mensaje_respuesta` varchar(255),
	                      `token` varchar(255),
	                      `referencia_transaccion` varchar(255),
	                      `codigo_autorizacion` varchar(255),
	                      PRIMARY KEY( `id` )
                        );";

                        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                        dbDelta( $sql );
                    }
                    else{

                        $wpdb->insert(
                            'wp_culqi_order',
                            array(
                                'id_transaccion' => $respuesta["id_transaccion"],
                                'numero_pedido' => $orderId,
                                'codigo_respuesta' => $respuesta["codigo_respuesta"],
                                'mensaje_respuesta' => $respuesta["mensaje_respuesta"],
                                'token' => $respuesta["ticket"],
                                'referencia_transaccion' => $respuesta["referencia_transaccion"],
                                'codigo_autorizacion' => $respuesta["codigo_autorizacion"]
                            )
                        );
                    }
*/
                    //

                    $dataIdTran = $respuesta["id_transaccion"];
                    $dataBancoEm = $respuesta["nombre_emisor"];
                    $dataEstado =  $respuesta["codigo_respuesta"];
                    $dataCodRespuesta = $respuesta["codigo_respuesta"];
                    $dataCodigoAutor = $respuesta["codigo_autorizacion"];
                    $dataTicket = $respuesta["ticket"];
                    $dataMarca = $respuesta["marca"];


                    $data['idTran'] = $orderId;
                    $data['numTH'] = $respuesta["numero_tarjeta"];
                    $data['nombreTH'] = $respuesta["nombre_tarjeta_habiente"] . " " . $respuesta["apellido_tarjeta_habiente"];
                    $data['bancoEm'] = $dataBancoEm;
                    $data['trnEstado'] = $dataEstado;
                    $data['trnCodAutor'] = $dataCodigoAutor;
                    $data['marcaTarjeta'] = $dataMarca;
                    $data['trnCodRespueta'] = $dataCodRespuesta;
                    $data['mensajeRespuesta'] = $respuesta["mensaje_respuesta_usuario"];
                    $data['dataTicket'] =  $dataTicket;


                    $message = array(
                        'shop_name'          => $this->sp_nombre_comercio,
                        'shop_url'           => $_SERVER['SERVER_NAME'],
                        'user'               => $order->billing_first_name,
                        'id_order'           => $order->id,
                        'resultado'          => $dataEstado,
                        'num_transaccion'    => $dataTicket,
                        'descripcion_trn'    => $respuesta["mensaje_respuesta_usuario"],
                        'tarjeta_marca'      => $dataMarca,
                        'moneda'             => $order->get_order_currency(),
                        'num_aut'            => $dataCodigoAutor,
                        'total_importe'      => number_format($order->get_total(), 2, '.', ''),
                        'history_url'        => $_SERVER['SERVER_NAME'].'/mi-cuenta/',
                        'my_account_url'     => $_SERVER['SERVER_NAME'],
                        'guest_tracking_url' => $_SERVER['SERVER_NAME']
                    );


                    if ($dataCodRespuesta == "venta_exitosa") {
                        error_log("Venta Exitosa");

                        $order->payment_complete($dataTicket);

                        $this->mailNotifyPayment($order->id, $order->billing_email, "success", $message);

                    } else {

                        error_log("Venta Denegada");

                        $order->update_status( 'cancelled' );

                        $this->restore_order_stock($order->id);

                        $this->mailNotifyPayment($order->id, $order->billing_email, "cancelled", $message);
                    }

                    echo json_encode($data);

                }else{
                    global $woocommerce;
                    $woocommerce->cart->empty_cart();
                }
                exit;


            }

            /**
             * Compruebe si este pasarela de pago está habilitada y disponible en el país del usuario
             *
             * @access public
             * @return bool
             */
            function is_valid_for_use()
            {
                if (!in_array(get_woocommerce_currency(), array('PEN', 'USD'))) return false;
                return true;

            }

            /**
             * Las opciones del panel de administración
             *
             * @since 1.0.0
             */
            public function admin_options()
            {
                //configuracion por woocomerce
                ?>
                <h3><?php _e('Culqi', 'wc_culqi_payment_gateway'); ?></h3>
                <table class="form-table">
                    <?php
                    if ($this->is_valid_for_use()) :
                        // Generate the HTML For the settings form
                        $this->generate_settings_html();
                    else :
                        ?>
                        <div class="inline error">
                            <p>
                                <strong>
                                    <?php _e('Gateway Disabled', 'wc_culqi_payment_gateway'); ?>
                                </strong>:
                                <?php _e('Realizar la configuraci&oacute;n correcta: Moneda, Comercio, Llave Comercio..', 'wc_culqi_payment_gateway'); ?>
                            </p>
                        </div>
                        <?php
                    endif;
                    ?>
                </table><!--/.form-table-->
                <?php
            }

            /**
             * Inicializar configuración de la pasarela de pago. Los campos de formulario
             *
             * @access public
             * @return void
             */
            function init_form_fields()
            {
                global $woocommerce;

                $this->form_fields = array(
                    'enabled' => array
                    (
                        'title' => __('Habilitar/Deshabilitar', 'wc_culqi_payment_gateway'),
                        'type' => 'checkbox',
                        'label' => __('Habilitar Culqi', 'wc_culqi_payment_gateway'),
                        'default' => 'yes'
                    ),
                    'sp_idSocio' => array
                    (
                        'title' => __('Código de comercio', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Ingresar código de comercio en Culqi.', 'wc_culqi_payment_gateway'),
                        'default' => ''
                    ),
                    'sp_key' => array
                    (
                        'title' => __('Llave de cifrado', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Ingresar llave secreta para el cifrado y descifrado.', 'wc_culqi_payment_gateway'),
                        'default' => ''
                    ),
                    'sp_modo' => array
                    (
                        'title' => __('Ambiente', 'wc_culqi_payment_gateway'),
                        'type' => 'select',
                        'required' => true,
                        'description' => __('Seleccionar ambiente de integración o producción', 'wc_culqi_payment_gateway'),
                        'default' => 'test',
                        'options' => array(
                            'test' => __('Integración', 'wc_culqi_payment_gateway'),
                            'prod' => __('Producción', 'wc_culqi_payment_gateway'),
                        ),
                    )/*,
                    'sp_vence' => array(
                        'title' => __('Tiempo vencimiento', 'wc_culqi_payment_gateway'),
                        'type' => 'text',
                        'required' => true,
                        'description' => __('Indicar la cantidad de horas para la fecha de vencimiento', 'wc_culqi_payment_gateway'),
                        'default' => '4'
                    ),*/
                    /*'sp_valid_vence' => array(
                        'title' => __('Validar hora vencimiento', 'wc_culqi_payment_gateway'),
                        'type' => 'select',
                        'required' => true,
                        'description' => __('Activar vencimiento de una orden.', 'wc_culqi_payment_gateway'),
                        'default' => 'si',
                        'options' => array(
                            '0' => __('Si', 'wc_culqi_payment_gateway'),
                            '1' => __('No', 'wc_culqi_payment_gateway'),
                        ),
                    )*/
                );
            }

            /**
             * Checkout: Procesar el pago y devolver el resultado
             *
             * @access public
             * @param int $order_id
             * @return array
             */
            function process_payment($order_id)
            {
                $order = new WC_Order($order_id);
                $order->reduce_order_stock();
                return array
                (
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                );
            }


            /**
             * Envio de la trama a Culqi
             *
             * @param  $order_id Order ID.
             * @return string
             */
            function receipt_page($order_id)
            {

                $order = new WC_Order($order_id);
                $numeroPedido = str_pad($order->id, 2, "0", STR_PAD_LEFT);

//                $wcCulqui = new Culqi();

                Culqi::$llaveSecreta = $this->sp_key;
                Culqi::$codigoComercio = $this->sp_idSocio;

                if ($this->sp_modo == 'prod') {
                    Culqi::$servidorBase = 'https://pago.culqi.com';
			$entornoPago = 	'https://pago.culqi.com';	

                } else {
                    Culqi::$servidorBase = 'https://integ-pago.culqi.com';
			$entornoPago = 	'https://integ-pago.culqi.com';	
                }

                $total = str_replace('.', '', number_format($order->get_total(), 2, '.', ''));
                $total = str_replace(',', '',$total);

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

                }else {

                    $datos_nombre = $order->billing_first_name;

                }

                if ($order->billing_last_name == null){

                    $datos_apellido = "Apellido";

                }else {

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

                $data = Pago::crearDatospago(array(
                    Pago::PARAM_NUM_PEDIDO => $this->generateRandomString(4)."-".$numeroPedido,
                    Pago::PARAM_MONEDA => get_woocommerce_currency(),
                    Pago::PARAM_MONTO => $total,
                    Pago::PARAM_DESCRIPCION => $descripcion,
                    Pago::PARAM_COD_PAIS => "PE",
                    Pago::PARAM_CIUDAD => "Lima",
                    Pago::PARAM_DIRECCION => $datos_direccion,
                    Pago::PARAM_NUM_TEL => $datos_telefono,
                    "correo_electronico" => $datos_correo,
                    "id_usuario_comercio" => $datos_correo,
                    "nombres" => $datos_nombre,
                    "apellidos" => $datos_apellido,
		    "plugin_culqi" => "{'plataforma': 'WordPress-Woocomerce','version': '1.0.3'}"
                ));

                $informacionVenta = $data[Pago::PARAM_INFO_VENTA];

                $codigoRespuesta = $data["codigo_respuesta"];

                $respuesta = $data["mensaje_respuesta"];

                //$respuesta_usuario = $data["mensaje_respuesta_usuario"];

                error_log("Respuesta Venta:" . $codigoRespuesta);
                error_log($respuesta);
                //error_log("Respuesta Usuario:" . $respuesta_usuario);

                ?>
                <div id="info_payment">
                    <span id="notify"></span>
                    <span>Realiza la compra presionando <strong>Pagar</strong><br>Si deseas cambiar de medio de pago presiona <strong>Cancelar</strong></span><br><br>
                    <button id="pagar-now">Pagar</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btn-back">Cancelar</button>
                </div>

                <script src="<?php echo $entornoPago?>/api/v1/culqi.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

                <script>
                    checkout.codigo_comercio = "<?php echo $this->sp_idSocio ?>";
                    checkout.informacion_venta = "<?php echo $informacionVenta ?>";
                    var intentos = 3;

                    $( document ).ready(function() {

                        $(' div.woocommerce').prepend("<h1 style='text-align: center;' id='title-result'></h1>");

                        $("#info_payment").on('click','#actualizar-now', function(){
                            var url = '<?php echo WC_Cart::get_checkout_url(); ?>';
                            window.location.replace(url);
                        });

                        $("div.woocommerce").on('click','#home', function(){
                            var url = '<?php echo home_url(); ?>';
                            window.location.replace(url);
                        });

                        $('#pagar-now').on('click', function (e) {
                            checkout.abrir();
                            e.preventDefault();
                        });
                        $('#btn-back').on('click', function(e){
                            var url = '<?php echo WC_Cart::get_checkout_url(); ?>';
                            window.location.replace(url);
                        });
                    });

                    function culqi(checkout) {
                        console.log(checkout.respuesta);
                        if (checkout.respuesta != "checkout_cerrado" &&
                            checkout.respuesta != "venta_expirada" &&
                            checkout.respuesta != "error" &&
                            checkout.respuesta != "parametro_invalido")
                        {
                            $.ajax({
                                url: "/index.php?wc-api=WC_culqi",
                                type: "POST",
                                data: {respuesta: checkout.respuesta},
                                success: function (data) {
                                    console.log(data);
                                    var obj = JSON.parse(data);
                                    checkout.cerrar();
                                    if (obj.trnEstado == "venta_exitosa") {
                                        $('.order_details').empty();
                                        $('#notify').empty();
                                        $("#info_payment").remove();
                                        $(' span.title-checkout ').removeClass("title-checkout");
                                        $(' span.title-thankyou ').removeClass("title-thankyou").addClass("title-checkout");

                                        $(' div.woocommerce ').append("<h1 style='text-align: center;'>Pago Exitoso</h1>" +
                                            "<table>" +
                                            "<thead><tr><th colspan='2' style='text-align: center;'>Detalle de la compra</th></tr></thead>" +
                                            "<tbody>" +
                                            "<tr><td>N&uacute;mero de Transacci&oacute;n:</td>" +
                                            "<td>" + obj.idTran + "</td></tr> " +
                                            "<tr><td>Nombre del Tarjeta Habiente:</td>" +
                                            "<td>" + obj.nombreTH + "</td> </tr> <tr> <td>N&uacute;mero del Tarjeta Habiente:</td> " +
                                            "<td>" + obj.numTH + "</td> </tr> <tr> <td>Marca Tarjeta:</td>" +
                                            "<td>" + obj.marcaTarjeta + "</td> </tr> <tr> <td>Detalle de la Transacci&oacute;n:</td>" +
                                            "<td>" + obj.mensajeRespuesta + "</td></tbody> </table>" +
                                            "<br><button id='home'>Seguir comprando</button>");

                                        $.ajax({
                                url: "/index.php?wc-api=WC_culqi",
                                            type: "POST",
                                            data: {emptyCart: 1},
                                            success: function (data) {
                                                // console.log(data);
                                            }
                                        });

                                    } else {
                                        intentos--;

                                        $("div.woocommerce").on('click', '#home', function () {
                                            var url = '<?php echo home_url(); ?>';
                                            window.location.replace(url);
                                        });

                                        var texto = '';
                                        if (intentos == 0) {
                                            var actualizar = $("#info_payment");
                                            actualizar.empty();
                                            actualizar.html("<span><strong>" + obj.mensajeRespuesta + "</strong></span><br>" +
                                                "<br><span>Superaste el número de intentos.<br></span><button id='actualizar-now'>Regresar</button>");

                                        } else if (intentos == 1) {
                                            $('#notify').html("<strong>" + obj.mensajeRespuesta + "</strong><br><br>");
                                        } else {
                                            $('#notify').html("<strong>" + obj.mensajeRespuesta + "</strong><br><br>");
                                        }
                                    }

                                }
                            });

                        } else if (checkout.respuesta == "checkout_cerrado") {
                            $("div.woocommerce").on('click', '#home', function () {
                                var url = '<?php echo home_url(); ?>';
                                window.location.replace(url);
                            });

                            $('#notify').html("<strong>" + "Cerraste el formulario de pago" + "</strong><br><br>");

                        } else if (checkout.respuesta == "venta_expirada") {
                            $("div.woocommerce").on('click', '#home', function () {
                                var url = '<?php echo home_url(); ?>';
                                window.location.replace(url);
                            });

                            var actualizar = $("#info_payment");
                            actualizar.empty();
                            actualizar.html("<span><strong>" + "La venta ha expirado, regresa al paso anterior para que puedas terminar la compra." + "</strong></span><br>" +
                                "<br><span><br></span><button id='actualizar-now'>Regresar</button>");
                            $('#notify').html("");


                        } else if (checkout.respuesta == "error" || checkout.respuesta == "parametro_invalido") {
                            $("div.woocommerce").on('click', '#home', function () {
                                var url = '<?php echo home_url(); ?>';
                                window.location.replace(url);
                            });

                            var actualizar = $("#info_payment");
                            actualizar.empty();
                            actualizar.html("<span><strong>" + "Ocurrió un error inesperado, regresa al paso anterior para que puedas terminar la compra." + "</strong></span><br>" +
                                "<br><span><br></span><button id='actualizar-now'>Regresar</button>");
                            $('#notify').html("");

                        }
                    }

                </script>

                <?php

                get_footer();

                exit;
            }

            function generateRandomString($length = 10)
            {
                $characters = '0123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }

            public function restore_order_stock($order_id)
            {
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

        /**
         * añadir pasarela de pago a WooCommerce.
         *
         * @param   array $methods Métodos de pago WooCommerce.
         *
         * @return  array          metodos de pago con culqi
         */
        function woocommerce_culqi_add_gateway($methods)
        {
            $methods[] = 'WC_culqi';
            return $methods;
        }

        add_filter('woocommerce_payment_gateways', 'woocommerce_culqi_add_gateway');
    }
}
