=== Culqi Integracion ===
Contributors: gonzalesc
Tags: culqi, full integration, payment method, peru, woocommerce
Donate link: https://www.paypal.me/letsgodev
Requires at least: 5.6
Tested up to: 5.7.2
Stable tag: 5.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Podrás hacer pagos desde Woocommerce usando el servicio de Culqi, además de reembolsos, estados, logs y personalizaciones del modal de pago.

== Description ==

Culqi Integracion te permite sincronizar todos los pagos, ordenes y clientes con tu WordPress.

Con este plugin podrás:

* Administrar los cargos
* Administrar las ordenes ( PagoEfectivo )
* Administrar los clientes
* Si usas WooCommerce podrás activar el popup de Culqi como método de pago.
* Hacer reembolsos con un click desde algún pedido de WooCommerce
* Tener un log de actividades de Culqi para cada pedido de WooCommerce.
* Agregar el logo de tu comercio a tu modal/popup de Culqi


> <strong>WooCommerce Culqi Pago con un click</strong><br>
>
> Mira la **nueva versión premium** disponible en ([https://www.letsgodev.com/product/woocommerce-culqi-pago-con-un-click/](http://bit.ly/304QRdF))
>
> * Permite hacer el pago sin hacer pasos extras.
> * Permite usar el popup de Culqi.
> * Permite usar un formulario para tarjeta de crédito.
> * Permite guardar tarjetas como una billetera.
> * Irá directamente a la sección "Gracias por su compra"
> * Es compatible con el plugin de Suscripciones de Culqi.
> * Aumentarán tus conversiones de compra al disminuir los pasos de pago.
> * Soporte Premium
>



> <strong>Woocommerce Culqi Integración Suscripciones</strong><br>
>
> Mira la **nueva versión premium** disponible en ([https://www.letsgodev.com/product/wordpress-culqi-integracion-subscripciones/](http://bit.ly/2UZO7j9))
>
> * Permite sincronizar planes y suscripciones desde Culqi con un click.
> * Permite crear y borrar planes.
> * Permite crear productos con pagos recurrentes.
> * Podrás relacionar uno o varios productos con un plan de pago de Culqi.
> * Podras usar la pasarela de pago de WooCommerce para pagos recurrentes.
> * Podrás restringir contenido de acuerdo al tipo de suscripción.
> * Soporte Premium
>



> <strong>Woocommerce Culqi Pagos Diferidos</strong><br>
>
> Mira la **nueva versión premium** disponible en ([https://www.letsgodev.com/product/woocommerce-culqi-pagos-diferidos/](https://bit.ly/2UkbDF1))
>
> * Permite autorizar pagos.
> * Permite capturar pagos con un click.
> * Existe un nuevo estado llamado Diferido en tus pedidos
> * Puedes personalizar el correo para este nuevo estado
> * Es compatible con el plugin de Culqi Pago con un Click
> * Es compatible con el plugin de Culqi Botones de pago
> * Soporte Premium
>



> <strong>Wordpress Culqi Integración Botones de Pago</strong><br>
>
> Mira la **nueva versión premium** disponible en ([https://www.letsgodev.com/product/wordpress-culqi-integracion-botones-de-pago/](http://bit.ly/2oMUffe))
>
> * Permite colocar botones de pago en tu website.
> * Puedes personalizar cada botón
> * Puedes usar botones de diferentes monedas y con diferentes montos
> * Email personalizado por cada pago
> * No necesitas tener instalado un ecommerce
> * Soporte Premium
>


= Github =

Fork me in [https://github.com/gonzalesc/wp-culqi-integration.git](https://github.com/gonzalesc/wp-culqi-integration.git)

= Available Languages =

* English
* Spanish


= Woocommerce Payme ( Alignet ) =
Pasarela de pago Payme para Woocommerce con la mejor comisión en Perú [https://www.letsgodev.com/product/woocommerce-payme-alignet/](http://bit.ly/2V0wCiG)


== Installation ==
1. Descomprimir y subir el archivo 'culqi-integration' al directorio '/wp-content/plugins/'

2. Activar el plugin en la sección 'Plugins'

3. Ir a la configuración del plugin y poner su llave pública y llave secreta

4. Para usar Multipagos, debes activarlo en la pasarela de pago Culqi y debes configurar el Webhook.
- Debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`
- Debes elegir el evento : `order.status.changed`
- La URL que debes poner es: `https://{tuweb}/fullculqi-api/webhooks`


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

= Tengo problemas cuando sincronizo los pagos =

Necesitamos validar si el servicio de Culqi está disponible, para ello te sugiero hagas una prueba que te tomará 10 minutos, por favor sigue esta guía : [https://blog.letsgodev.com/tips-es/verificar-servicio-de-culqi-en-10-minutos/](http://bit.ly/2V0wJe6)


== Screenshots ==

1. Página de bienvenida.
2. Configuración del plugin
3. Cargos
4. Método de pago para WooCommerce
5. Eventos de Culqi
6. Configurar Webhook
7. Reembolsar pago


== Changelog ==

= 2.0.4 =
* Fix: check if the product class exists

= 2.0.3 =
* Fix: sync to Culqi entities

= 2.0.2 =
* Fix: fix: upgrade process

= 2.0.0 =
* Feature: new structure
* Feature: new webhook section
* Feature: the customer will be registered in Culqi
* Feature: new section called "orders"
* Feature: new section called "customers"

= 1.5.2 =
* Fix: we convert amount field in string because json_decode modify the precision of decimals

= 1.5.1 =
* Feature: status on multipayment
* Feature: add new addon

= 1.5.0 =
* Feature: refund and status

= 1.4.8 =
* Fix: if the product has get_name method

= 1.4.7 =
* Fix: if method exist

= 1.4.6 =
* Fix: when the product doesnt exist on the order

= 1.4.5 =
* Fix: double function

= 1.4.4 =
* Fix: description on checkout page
* Compatilibity Woocommerce 3.9.x

= 1.4.3 =
* Fix: scripts JS

= 1.4.2 =
* Fix: we increase parameters in the payment process

= 1.4.1 =
* Fix: add filters to one click

= 1.4.0 =
* Fix: Update the culqi-php by composer

= 1.3.7 =
* Feature: set language to the culqi modal

= 1.3.6 =
* Fix: records in payment class

= 1.3.5 =
* Fix: include wc class

= 1.3.4 =
* Fix: add params to hooks that disabled multipayments

= 1.3.3 =
* Fix: hook to disabled multipayments and installments

= 1.3.2 =
* Fix: when the cip expired so the order is cancelled

= 1.3.1 =
* Fix: change the load of css file

= 1.3.0 =
* Feature : multtipayment enabled

= 1.2.0 =
* Feature : add addons section

= 1.1.1 =
* Feature : we add new hooks to admin settings

= 1.1.0 =
* Feature : Enable Logo in the modal

= 1.0.8 =
* Feature : Enable installments

= 1.0.7 =
*Fix: Log always is active

= 1.0.6 =
* Fix : Time to modal

= 1.0.5 =
* Tweet : remove automatic sync

= 1.0.4 =
* Fix: AntiFraud only Email

= 1.0.3 =
* Fix : capture payment is true

= 1.0.2 =
* fix: form receipt filters in do payment section

= 1.0.1 =
* Filters created

= 1.0.0 =
* Ready