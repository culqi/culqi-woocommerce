=== Culqi ===
Contributors: culqionline
Tags: culqi, checkout, payment method, Perú, woocommerce
Donate link: https://culqi.com/
Requires at least: 4.5
Tested up to: 6.0.2
Stable tag: 3.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Vende de forma segura con nuestra pasarela de pago en tu tienda virtual.

== Description ==

Conéctate a nuestra pasarela de pagos para aumentar tus ventas con múltiples opciones de pago. Nuestra app está diseñado para que tus clientes tengan una experiencia de compra rápida y segura, vende desde tu tienda virtual con nuestra pasarela de pago. Cobra pagos únicos. Con el respaldo de CREDICORP.

** Beneficios de CulqiOnline**

* Intégrate en pocos pasos, contando con tus credenciales del CulqiPanel, y elige el entorno donde activarás el checkout. Antes de habilitarlo a tus clientes, prueba todas las veces que necesitas en el ambiente de integración con nuestras tarjetas de pruebas.
* Contamos con una plataforma estable y segura: certificación PCI- DSS, y doble capa de seguridad con nuestro motor antifraude y autenticación 3DS.
* Personaliza tu checkout con el logo y colores de tu negocio.
* Visualiza tus ventas a través del CulqiPanel.
* Mejora la experiencia de compra de tus clientes con nuestra 'nueva versión de checkout desktop y mobile.

** Funcionalidades de nuestro plugin**

Inicia tu configuración del plugin de Culqi activando el Culqi Checkout. Selecciona el entorno donde habilitarás el checkout e ingresa tus credenciales del CulqiPanel. Se cargarán tus llaves automáticamente. Luego, marca los métodos de pagos que mostrarás en tu tienda virtual:

* Tarjetas de crédito y débito: Tus clientes solo deben deben ingresar los datos de su tarjeta y proceder a pagar
* Botón de pago Yape: Tus clientes podrán pagar con su número de celular.
* Billeteras móviles. Generamos un código QR para que tus clientes paguen con su billetera electrónica.
* Banca móvil o internet / Agentes o bodegas: Generamos un código único CIP, el cual tus clientes podrán pagar en los centros autorizados.
* Cuotéalo BCP: Financia los pagos de tus clientes en cuotas.

Con este plugin podrás:

* Administrar los cargos.
* Administrar las órdenes de pago ( Banca móvil o internet, Billeteras Móviles, PagoEfectivo, Cuotéalo ).
* Activar el checkout de Culqi como una forma de pago en tu tienda virtual.
* Realizar reembolsos con tan solo un click desde el pedido de WooCommerce.
* Contar con un log de actividades de Culqi para cada pedido de WooCommerce.
* Personalizar tu Culqi checkout con los colores y logo de tu marca.


= Available Languages =

* Spanish


== Installation ==

Para iniciar la integración con CulqiOnline, realiza lo siguiente:

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

1. Configuración del plugin
2. Personaliza tu checkout
3. Cargos
4. órdenes
5. Configurar Webhook


== Changelog ==

= 1.0.0 =
* Feature: Checkout Culqi V4
* Feature: 3DS

== Upgrade Notice ==

El plugin cuenta con la integración de Culqi Checkout v4 y Culqi 3DS