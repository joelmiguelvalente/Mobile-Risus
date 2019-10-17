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

## Realizar los pasos del 1 al 5
https://www.phpost.net/foro/topic/27955-mod-risus-mobile-kmario19/
En el **PASO 2** no agregaremos ya que lo moveremos
``` PHP
 if($tsCore->settings['isMobile']) {
echo '<div style="background: #B8DCFF;width: 100%;overflow: hidden;font-size: 15px;padding: 16px;color: #000;text-align: center;">Navega mejor por '.$tsCore->settings['titulo'].' en nuestra versión mobile <a class="mBtn btnOk" href="'.$tsCore->settings['mobile'].'" onclick="document.cookie = \'mobile=;expires=Thu, 01 Jan 1970 00:00:01 GMT;\'">Entrar</a></div>';
    }
 ```
 Y buscamos en Raíz/themes/default/templates/sections/**main_footer.tpl** y arriba de
``` HTML
</body>
</html>
```
agregamos esto
``` HTML
{if $tsConfig.isMobile == 1}
<div class="navegarMobile">Navega mejor por {$tsConfig.titulo} en nuestra versi&oacute;n mobile <a class="btn btn-success" href="{$tsConfig.mobile}" onclick="document.cookie = \'mobile=;expires=Thu, 30 Jan 2024 00:00:01 GMT;\'">Entrar</a></div>
{/if}
```
Y en **extras.css** agregaremos, el cual lo pueden editar a gusto
``` CSS
.navegarMobile {
	position: fixed!important;
	width: 80%;
	padding: 10px;
	bottom: 0;
	background-color: #1268B1;
	z-index: 999;
	font-size: 12px;
	color: #EEE;
}
.navegarMobile .btn {
	padding: 2px;
	font-size: 11px;
}
```

---
## Si han actualizado el Smarty
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
## En el caso que volver a su versión original
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
