<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'FULLCULQI_WC_DIR' , FULLCULQI_DIR . 'includes/3rd-party/plugins/woocommerce/' );
define( 'FULLCULQI_WC_URL' , FULLCULQI_URL . 'includes/3rd-party/plugins/woocommerce/' );

require_once FULLCULQI_WC_DIR . 'class-fullculqi-wc-process.php';
require_once FULLCULQI_WC_DIR . 'class-fullculqi-wc-main.php';

if( is_admin() )
	require_once FULLCULQI_WC_DIR . 'class-fullculqi-wc-admin.php';