=== Culqi ===
Contributors: Culqi Team
Tags: culqi, checkout, payment method, peru, woocommerce
Donate link: https://culqi.com/
Requires at least: 5.6
Tested up to: 6.0.2
Stable tag: 3.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Podrás hacer pagos desde Woocommerce usando el Checkout de Culqi, además de reembolsos, estados, logs y personalizaciones del modal de pago.

== Description ==

Culqi Checkout te permite sincronizar todos los pagos, ordenes con tu WordPress.

Con este plugin podrás:

* Administrar los cargos
* Administrar las ordenes ( PagoEfectivo )
* Si usas WooCommerce podrás activar el popup de Culqi como método de pago.
* Hacer reembolsos con un click desde algún pedido de WooCommerce
* Tener un log de actividades de Culqi para cada pedido de WooCommerce.
* Agregar el logo de tu comercio a tu modal/popup de Culqi
* Colocar en el checkout los colores de tu marca


= Available Languages =

* Spanish


== Installation ==
1. Descomprimir y subir el archivo 'culqi-checkout' al directorio '/wp-content/plugins/'

2. Activar el plugin en la sección 'Plugins'

3. Ir a la configuración del plugin y activa tu configuración.

4. Selecciona el ambiente en el cual vas a realizar transacciones( Integración o producción).

3. Ingresa tu llave pública y llave secreta.

4. Selecciona mos métodos de pagos que vas a activar.

4. Para usar Multipagos, debes activarlo en la pasarela de pago Culqi y debes configurar el Webhook.
- Debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`
- Debes elegir el evento : `order.status.changed`
- La URL que debes poner es: `https://{tuweb}/fullculqi-api/webhooks`

5. Establece el tiempo de expiración de las órdenes de pago( por defecto es 24 horas).

6. Tienes la posibilidad de personalizar tu checkout con tu logo y los colores de tu marca.

7. Finalmente guarda las configuración.

== FAQ ==

= Cómo obtengo las llaves de Culqi ? =

Es fácil!, sólo debes registrarte aqui : [https://www.culqi.com/](https://www.culqi.com/)

= Es compatible con WooCommerce ? =

Sí, podrás hacer pagos, ordenes y reembolsos usando WooCommerce

= Puedo usar PagoEfectivo ? =

Sí, ese modelo se llama ordenes de compra ( Multipago ) y es permitido por el plugin. Pero debes configurar un webhook para recibir las notificaciones de pago.

= Cómo configurar el webhook de Culqi para Multipago ? =

- Debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`
- Debes elegir el evento : `order.status.changed`
- La URL que debes poner es: `https://{tuweb}/fullculqi-api/webhooks`

== Screenshots ==

1. Página de bienvenida.
2. Configuración del plugin
3. Cargos
4. Método de pago para WooCommerce
5. Eventos de Culqi
6. Configurar Webhook
7. Reembolsar pago


== Changelog ==

= 1.0.0 =
* Feature: Checkout Culqi V4
* Feature: 3DS
