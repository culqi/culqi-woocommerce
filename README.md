# Culqi WooCommerce - Wordpress Plugin

Nuestro plugin integra por ti nuestro Checkout v4 y nuestra libreria JS 3DS, con el cual tendras la posibilidad de realizar cobros con tarjetas de crédito y débito, pagoEfectivo(banca móvil o internet, agentes y bodegas), billeteras móviles y cuotéalo tan solo con unos simples pasos de configuración.

> **Note**
> Recuerda que para usar cualquier plugins necesitas tener tu llave pública y llave privada (test o live), los cuales los puedes generar a través de tu Culqipanel.

>[!WARNING]
>This is a warning

## Requisitos ##

- Version PHP mínimo requerido es 5.6
- [Credenciales de Culqi](https://www.culqi.com)


## Instalación desde el repositorio de WP ##

Puedes descargar el plugin desde el repositorio de WordPress :
[https://wordpress.org/plugins/culqi-full-integration/](https://wordpress.org/plugins/culqi-full-integration/)


O puedes instalarlo desde el mismo WordPress. Ir a Plugins > Agregar nuevo

![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/08/install_plugin1.png "Add New Plugin")

Buscar "Culqi", instalar y activar

![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/07/plugin.jpg "Add New Plugin")


## Instalación desde Github ##

**1. Clonar Repositorio**
```git clone git@github.com:gonzalesc/wp-culqi-integration.git```

**2. Descargar Biblioteca Culqi**
```composer update```


## Credenciales de Culqi ##

Debes registrarte en [https://www.culqi.com](https://www.culqi.com) y luego acceder al panel de integración. Una vez ahi, irás a Desarrollo > ApiKey y así obtendrás tus credenciales:

![Alt text](https://www.letsgodev.com/wp-content/uploads/2019/04/apikey.png "Credenciales Culqi")

## Página de Bienvenida ##

Cuando actives el plugin, éste te redireccionará a una página de bienvenida donde deberás poner tus credenciales de Culqi y otras cosas más.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/07/welcome.jpg "Welcome Page")


## Página de Configuración ##

Una vez llenado todo correctamente, al presionar "Guardar", éste le enviará a la página de configuración del plugin.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_2_settings.jpg "Configure you Settings page")

## Servicios y Sincronización ##

### Cargos ###
![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_3_charges.jpg "Charges List")

### Ordenes ( PagoEfectivo ) ###
![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_4_orders.jpg "Order List")

### Clientes ###

El plugin registrará a los clientes en Culqi sólo si el comprador esta logueado en la tienda.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_5_customers.jpg "Customer List")

## Popup de Culqi para WooCommerce ##

Tienes activada la pasarela de pago para WooCommerce y sólo debes ir a personalizarlo.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_6_wc.jpg "WooCommerce Payment")


## Log de transacciones ##

El log siempre está habilitado, cada pedido tendrá un detalle de todos los pasos que sigue Culqi para hacer el pago. Aqui también se registrará los errores si los hay.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/07/log.jpg "Log")


## Datos recomendados en el Checkout ##

Culqi recomienda que estos campos sean obligatorios:

- Email
- Nombre
- Apellido
- Dirección
- Ciudad
- Código de pais ( ejem: para Perú es PE)
- Teléfono

De todos estos puntos el más importante es el `email`, los otros campos son muy necesarios para un tema de antifraude pero no son obligatorios. Yo recomiendo que tengas todos estos campos en tu checkout. El plugin no validará estos campos.


## Rembolso vía Woocommerce ##

Podrás hacer reembolsos usando la API de Culqi con un sólo click, además de reponer el stock si así lo especificas.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2020/06/woo_refund.jpg "Refund")


## Webhooks - Ordenes ( PagoEfectivo ) ##

Puedes habilitar Multipagos en la sección de configuración de la pasarela de pago Culqi. Cada vez que se genera una orden de pago, le llegará al cliente un email con el CIP de pago.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2021/03/base_7_webhook.jpg "Multipagos")


Cuando el cliente page su código CIP, Culqi avisará al comercio mediante un `evento` el cual debemos configurar: para ello, debemos entrar al panel de Culqi e ir a la sección de `eventos` y al submenu de `webhooks`. Finalmente le damos click al botón `Añadir` que está arriba a la derecha.

![Alt text](https://www.letsgodev.com/wp-content/uploads/2015/07/webhook_create.jpg "Event")


Debes elegir el evento : `order.status.changed`
y la URL que debes poner está en la sección Webhooks del plugin.

```
https://{tuweb}/fullculqi-api/webhooks
```


## Problemas Comunes desde el servicio de Culqi ##

El servicio de Culqi suele ser óptimo cuando se trata de registrar pagos simples pero, raras veces, cuando empiezas a interactuar con otros servicios puede traernos estos tipos de problemas, si sueles tener alguno, comunicate con ellos.

- `Ups! Algo salió mal en Culqi. Contáctate con soporte@culqi.com para obtener mas información` - *El servicio de Culqi, para el servicio solicitado, no está disponible en ese momento*

- `Endpoint request timed out` - *El endpoint del API de Culqi a agotado su tiempo de solicitud*


**Para hacer una verificación del servicio de Culqi, [te invito a seguir esta guía simple y sencilla](https://blog.letsgodev.com/tips-es/verificar-servicio-de-culqi-en-10-minutos/). Sólo te tomará 10 minutos.**
