=== Culqi ===
Contributors: culqionline
Tags: culqi, checkout, payment method, Perú, woocommerce
Donate link: https://culqi.com/
Requires at least: 4.5
Tested up to: 6.4.3
Stable tag: 3.1.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Conéctate a nuestra pasarela de pago CulqiOnline de forma segura y estable en tu tienda virtual.

== Description ==

¡Vende de forma segura y estable con nuestra pasarela de pago CulqiOnline desde tu tienda virtual! Aceptamos todas las tarjetas, PagoEfectivo, las principales billeteras móviles y Cuotéalo BCP para que tus clientes tengan todos los métodos de pago disponibles en un solo checkout.

<strong> Beneficios de CulqiOnline </strong>

* <strong>Intégrate en pocos pasos: ingresa tus credenciales del CulqiPanel</strong> y elige el entorno donde activarás el checkout. Antes de habilitarlo a tus clientes, podrás probar todas las veces que necesites en el ambiente de integración con nuestras tarjetas de pruebas.
* <strong>Estabilidad y seguridad:</strong> Contamos con certificación PCI- DSS y doble capa de seguridad con nuestro motor antifraude y autenticación 3DS.
* Personaliza tu checkout con el logo y colores de tu negocio.
* <strong>Control de tus ventas a través del CulqiPanel.</strong>
* <strong>Mejor experiencia de compra para tus clientes con nuestra nueva versión de checkout en desktop y mobile.</strong>

<strong> Funcionalidades de nuestro plugin </strong>

Inicia tu configuración del plugin de Culqi activando el Culqi Checkout. Selecciona el entorno donde habilitarás el checkout e ingresa tus credenciales del CulqiPanel. Se cargarán tus llaves automáticamente. 
Luego, marca los métodos de pagos que mostrarás en tu tienda virtual:

* Tarjetas de crédito y débito: Tus clientes solo deben deben ingresar los datos de su tarjeta y proceder a pagar.
* Botón de pago Yape: Tus clientes podrán pagar con su número de celular.
* Billeteras móviles. Generamos un código QR para que tus clientes paguen con su billetera electrónica.
* Banca móvil o internet / Agentes o bodegas: Generamos un código único CIP, el cual tus clientes podrán pagar en los centros autorizados.
* Cuotéalo BCP: Financia los pagos de tus clientes en cuotas.

<strong>Con este plugin podrás</strong>

* Administrar los cargos.
* Administrar las órdenes de pago (PagoEfectivo, Billeteras Móviles, Cuotéalo).
* Activar el checkout de Culqi como una forma de pago en tu tienda virtual.
* Realizar reembolsos con tan solo un click desde el pedido de WooCommerce.
* Contar con un log de actividades de Culqi para cada pedido de WooCommerce.
* Personalizar tu Culqi checkout con los colores y logo de tu marca.


= Available Languages =

* Spanish


== Installation ==

Para iniciar la integración con CulqiOnline, realiza lo siguiente:

1. Selecciona la pestaña de "plugins", dirígete al buscador y digita "<strong>Culqi</strong>".
2. Instala el plugin de <strong>Culqi</strong> y actívalo.
3. Dirígete a la pestaña "Woocommerce", opción "Culqi", "Settings".
4. Activa la opción de CulqiCheckout, elige el entorno donde activarás el checkout e ingresa las credenciales del CulqiPanel.
5. Selecciona el comercio que deseas integrar y se cargarán la llave pública y privada.
6. Selecciona los métodos de pagos que deseas mostrar en tu tienda virtual.
7. Digita el plazo máximo que tendrá el cliente para pagar con pagoefectivo, billeteras móviles y cuotéalo BCP.
8. Personaliza el checkout con los colores y logo de tu negocio.
9. Guarda los cambios para visualizarlo en tu tienda virtual.


Si necesitas ayuda durante el proceso de integración, contáctate con nuestros especialistas: team.integración@culqi.com o escríbenos al WhatsApp: 996373833

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