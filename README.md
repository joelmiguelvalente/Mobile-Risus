# Mobile-Risus

* 1 - Descargan el MOD
* 2 - Crean una carpeta en la raíz llamada **m** u otro nombre que deseen
* 3 - Descomprimen el MOD dentro de la carpeta creada
* 4 - En configuración asignan la url *https://m.laweb.com/*
* 5 - En su cpanel crean un subdominio para la versión móvil
* 6 - y por el momento es todo

## Actualización: 14/10/2019
* Compatible con Risus 1.3
* ~~Ya no contiene el Smarty~
* El caché se almacenará en la misma carpeta que el theme normal
* PHP 7+
* Cambio un poco el aspecto al menú
* Se agregó los bbcode [JBBCode].
* No se agregó ningún editor
* _Falta reparar el Registro_

## Actualización: 10/05/2020
* Smarty 3.1.34-dev-7
* Cambio un poco el aspecto al menú
* Actualización de JBBCode.
* Actualización de jQuery 3.5.0.
* No se agregó ningún editor
* Registro funcional y actualizado con Recaptcha v3

### Archivo mobile.php
Este contendrá algunas configuraciones para esta versión:
* **date_default_timezone_set()** Ubicación geográfica
* **setlocale()** Idioma del sitio
* Plugins **nobbcode**, **kmg**, **number_format** y **ucfirst**

### Registro
Se a editado solo dejando los campos:
* Nombre
* Contraseña
* Email
* Sexo

### Otros cambios:
He tomado la liberta de modificar un poco el estilo para que 
sea un poco más diferentes