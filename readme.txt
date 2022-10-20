=== Culqi ===
Contributors: Culqi Team
Tags: culqi, checkout, payment method, Perú, woocommerce
Donate link: https://culqi.com/
Requires at least: 4.5
Tested up to: 6.0.2
Stable tag: 3.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Podrás hacer pagos desde Woocommerce usando el Culqi Checkout v4, además de reembolsos, verificar estados de órdenes, logs y personalizaciones del checkout.

== Description ==

Conéctate a nuestra pasarela de pagos para aumentar tus ventas con múltiples opciones de pago. Nuestra app está diseñado para que tus clientes tengan una experiencia de compra rápida y segura, vende desde tu tienda virtual con nuestra pasarela de pago. Cobra pagos únicos. Con el respaldo de CREDICORP.

Caracteristicas y Beneficios de integrar nuestra app
Rápida configuración, solo ingresa tu llave pública y privada en tu configuración.
Te proporcionamos un checkout multipago.
Procesa pagos al instante que se ven reflejados en tu CulqiIPanel.
Compra segura garantizada.
Alta seguridad, con Certificación PCI- DSS, motor antifraude y almacenamiento seguro de las tarjetas de tus clientes.
Integramos nuestra última versión de nuestros checkout, el cual crea una buena experiencia para tus clientes.
Nuestro aplicativo soporta todos los navegadores desktop y mobile.
Funcionalidades soportadas en nuestra app
Configura nuestra app ingresando tu llave llave pública y privada , luego selecciona los métodos de pagos que deseas habilitar. A continuación te detallamos cada una de las opciones de pago:

Tarjetas de crédito y débito. Tus clientes solo deben deben ingresar los datos de su tarjeta y proceder a pagar
Banca móvil o internet. Generamos un código CIP con el cual tus clientes podrán pagar a través de este medio de pago.
Agentes o bodegas. Generamos un código CIP con el cual tus clientes podrán pagar a través de este medio de pago.
Billeteras móviles. Generamos un código QR con el cual tus clientes podrán pagar a través de este medio de pago.

Con este plugin podrás:

* Administrar los cargos
* Administrar las ordenes ( Banca móvil o internet, Billeteras Móviles, PagoEfectivo, Cuotéalo )
* Podrás activar el checkout de Culqi como método de pago.
* Hacer reembolsos con un click desde algún pedido de WooCommerce
* Tener un log de actividades de Culqi para cada pedido de WooCommerce.
* Personalizar tu Culqi checkout con los colores de tu marca y logo.


= Available Languages =

* Spanish


== Installation ==
1. Descomprimir y subir el archivo 'culqi-checkout' al directorio '/wp-content/plugins/'

2. Activar el plugin en la sección 'Plugins'

3. Ir a la configuración del plugin y activa tu configuración.

4. Selecciona el ambiente en el cual vas a realizar transacciones( Integración o producción).

3. Ingresa tu llave pública y llave secreta.

4. Selecciona los métodos de pagos que vas a activar.

4. Para usar Multipagos, debes configurar el Webhook.
- Debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`
- Debes elegir el evento : `order.status.changed`
- La URL que debes poner es: `https://{tuweb}/fullculqi-api/webhooks`

5. Establece el tiempo de expiración de las órdenes de pago( por defecto es 24 horas).

6. Tienes la posibilidad de personalizar tu checkout con tu logo y los colores de tu marca.

7. Finalmente guarda la configuración.

== FAQ ==

= Cómo obtengo las llaves de Culqi ? =

para obtener tus llaves sólo debes registrarte aqui : [https://www.culqi.com/](https://www.culqi.com/)

= Es compatible con WooCommerce ? =

Sí, podrás hacer pagos, ordenes y reembolsos usando WooCommerce.

= Puedo usar PagoEfectivo, Billeteras Móviles y Cuotéalo ? =

Sí, para trabajar con estas opciones de pago se requiere de ordenes de compra, estos se generán en automático. Pero debes configurar un webhook para recibir las notificaciones de pago.

= Cómo configurar el webhook de Culqi para Multipago ? =

- Debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`
- Debes elegir el evento : `order.status.changed`
- La URL que debes poner es: `https://{tuweb}/fullculqi-api/webhooks`

== Screenshots ==

1. Página de bienvenida.
2. Configuración del plugin
3. Personaliza tu checkout
4. Cargos
5. órdenes
6. Eventos de Culqi
7. Configurar Webhook
8. Reembolsar pago


== Changelog ==

= 1.0.0 =
* Feature: Checkout Culqi V4
* Feature: 3DS
