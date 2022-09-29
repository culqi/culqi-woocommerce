<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'MPCULQI_WC_DIR' , MPCULQI_DIR . 'includes/3rd-party/plugins/woocommerce/' );
define( 'MPCULQI_WC_URL' , MPCULQI_URL . 'includes/3rd-party/plugins/woocommerce/' );

require_once MPCULQI_WC_DIR . 'class-fullculqi-wc-process.php';
require_once MPCULQI_WC_DIR . 'class-fullculqi-wc-main.php';

if( is_admin() )
	require_once MPCULQI_WC_DIR . 'class-fullculqi-wc-admin.php';
