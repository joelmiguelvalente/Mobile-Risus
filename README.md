# Mobile-Risus

* 1 - Descargan el MOD
* 2 - Crean una carpeta en la raíz llamada **m** u otro nombre que deseen
* 3 - Descomprimen el MOD dentro de la carpeta creada
* 4 - En configuración asignan la url *https://m.laweb.com/*
* 5 - En su cpanel crean un subdominio para la versión móvil
* 6 - y por el momento es todo

### Falta:
* Actualizar a Jquery 3.4.1
* Actualizar a Recaptcha v2 o v3

---
## Solo si tienen el Smarty actualizado
Buscan en Raíz/m/**header.php**:
``` PHP
include TS_CLASS.'c.smarty.php';
```
Y reemplazan por
``` PHP
include TS_SEC.'/inc/smarty/SmartyBC.class.php';
```
Más abajo buscan
``` PHP
$smarty = new tsSmarty();
```
Y reemplazan por
``` PHP
$smarty = new SmartyBC();
```
---
## En el caso que **NO tengan el Smarty actualizado**
1) Buscan en **header.php**:
``` PHP
include TS_SEC.'/inc/smarty/SmartyBC.class.php';
```
Y reemplazan por
``` PHP
include TS_SEC.'/inc/class/c.smarty.php';
```
Más abajo buscan
``` PHP
$smarty = new SmartyBC();
```
Y reemplazan por
``` PHP
$smarty = new tsSmarty();
```
