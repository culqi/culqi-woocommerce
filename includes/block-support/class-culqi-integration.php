<?php
/**
 * Integración de Culqi con el Block Checkout de WooCommerce.
 *
 * @package Culqi
 */

namespace Culqi;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Culqi_Integration extends AbstractPaymentMethodType {

    /**
     * Debe coincidir exactamente con $this->id en WC_Gateway_Culqi.
     *
     * @var string
     */
    protected $name = 'culqi';

    /**
     * Carga los settings del gateway desde la base de datos al inicializar el bloque
     */
    public function initialize(): void {
        // woocommerce_culqi_settings es la opción que WooCommerce guarda
        // automáticamente para el gateway con id 'culqi'.
        $this->settings = get_option( 'woocommerce_culqi_settings', [] );
    }

    /**
     * Indica si el gateway está activo. El Block Checkout lo usa para
     * decidir si mostrar Culqi como opción de pago.
     */
    public function is_active(): bool {
        return ( $this->settings['enabled'] ?? 'no' ) === 'yes';
    }

    /**
     * Registra el script JS del block y retorna su handle.
     * WooCommerce Blocks encola este script solo en páginas de checkout.
     *
     * @return string[]
     */
    public function get_payment_method_script_handles(): array {
        // @wordpress/scripts genera un .asset.php con versión y dependencias
        // automáticas al hacer el build. Si no existe (sin bundler), se usan
        // las dependencias manuales como fallback.
        $asset_file = PLUGIN_CULQI_PATH . 'assets/js/culqi-block.asset.php';
        $asset      = file_exists( $asset_file ) ? require $asset_file : [];
        $version    = $asset['version'] ?? PLUGIN_VERSION;
        $deps       = $asset['dependencies'] ?? [
            'wc-blocks-registry',
            'wc-settings',
            'wp-element',
            'wp-html-entities',
        ];

        wp_register_script(
            'culqi-block',
            PLUGIN_CULQI_URL . 'assets/js/culqi-block.js',
            $deps,
            $version,
            true // cargar en footer
        );

        return [ 'culqi-block' ];
    }

    /**
     * Datos que se exponen al JS del block.
     * Accesibles en JS via: wc.wcSettings.getSetting('culqi_data')
     *
     * @return array<string, mixed>
     */
    public function get_payment_method_data(): array {
        $config = culqi_get_config();

        // Construir lista de íconos activos — misma lógica que get_icon()
        // en WC_Gateway_Culqi para mantener consistencia visual.
        $payment_methods = [];
        if ( ! empty( $config->payment_methods ) ) {
            $cleaned         = stripslashes( $config->payment_methods );
            $payment_methods = json_decode( $cleaned, true ) ?? explode( ',', $config->payment_methods );
        }

        $icons = [];
        if ( in_array( 'tarjeta', $payment_methods, true ) ) {
            $icons[] = [
                'id'  => 'cards',
                'src' => PLUGIN_CULQI_URL . 'assets/images/cards.svg',
                'alt' => 'Tarjetas',
            ];
        }
        if ( in_array( 'yape', $payment_methods, true ) ) {
            $icons[] = [
                'id'  => 'yape',
                'src' => PLUGIN_CULQI_URL . 'assets/images/yape.svg',
                'alt' => 'Yape',
            ];
        }
        if ( array_intersect( [ 'billetera', 'bancaMovil', 'agente', 'cuotealo' ], $payment_methods ) ) {
            $icons[] = [
                'id'  => 'pagoefectivo',
                'src' => PLUGIN_CULQI_URL . 'assets/images/pagoefectivo.svg',
                'alt' => 'PagoEfectivo',
            ];
        }

        return [
            'title'       => $this->get_setting( 'title', 'Culqi' ),
            'description' => $this->get_setting( 'description', '' ),
            'logo_url'    => PLUGIN_CULQI_URL . 'assets/images/culqi-logo.svg',
            'icons'       => $icons,
            'supports'    => $this->get_supported_features(),
            'ajax_url'    => rest_url( 'culqi-api/get-payment-url/' ),
        ];
    }
}
