# Culqi WooCommerce( Wordpress & Woocommerce )

Version: 2.2.0  

### Pasos para la integración del Plugin de Culqi:

#### 1. Registrarse en Culqi   `<link>` : <https://www.culqi.com/>

Así podrás tener acceso al ambiente de pruebas de Culqi `<link>` : <https://integ-panel.culqi.com/>
donde encontrarás tus llaves `<link>` : <https://integ-panel.culqi.com/#/desarrollo/llaves/> 

`Llave publica: pk_test_xxxxxxxxxxxxxx`

`Llave privada: sk_test_xxxxxxxxxxxxxx`

#### 2. Descargar  el Plugin de Culqi 2.2.0 

`<link>` : <https://github.com/culqi/culqi-woocommerce/releases/tag/v2.2.0> 

![Imgur](https://i.imgur.com/5jnekNE.png)

#### 3. Subir el Plugin de Culqi en tu administrador de Wordpress

##### 3.1
![Imgur](https://i.imgur.com/a87KPfJ.png)

##### 3.2
![Imgur](https://i.imgur.com/ExyKrGf.png)

##### 3.3
![Imgur](https://i.imgur.com/bdzxZYJ.png)

#### 4. Configurar el Plugin de Culqi en tu Plugin de Woocommerce

##### 4.1
![Imgur](https://i.imgur.com/4vZDmFJ.png)
> Aquí van tus llaves que mencionabamos en el paso 1 ( Registrarse en Culqi ).

### Finalmente debes tener a Culqi como pasarela de pago de esta manera:

![Imgur](https://i.imgur.com/E1vxg3j.png)

> Debes usar las tarjetas de prueba que Culqi te ofrece para hacer las pruebas necesarias

`<link>` : <https://culqi.com/docs/#/desarrollo/tarjetas/> 

### Pase a producción:

#### 1. Cumplir con los requisitos técnicos

`<link>` : < https://culqi.com/docs/#/desarrollo/produccion/> 

#### 2. Activar comercio desde tu panel de integración de Culqi

![Imgur](https://i.imgur.com/wVOz6cc.png)

> Si tienes más dudas con respecto al proceso de "Activación de comercio" escribenos a unete@culqi.com

Cuando te envien los accesos a tu panel de producción de Culqi debes reemplazar
tus llaves de pruebas por tus llaves de producción como en el paso 4.2 

`Llave publica: pk_live_xxxxxxxxxxxxxx`

`Llave privada: sk_live_xxxxxxxxxxxxxx`

> En el ambiente de producción podrás comenzar a usar tarjetas reales.


 Si tienes dudas de integración escríbenos a https://culqi.com/soporte
