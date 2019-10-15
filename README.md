# Mobile-Risus

## Solo si tienen el Smarty actualizado
Buscan en **header.php**:
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
$smarty = new tsSmarty();
```
Y reemplazan por
``` PHP
$smarty = new tsSmarty();
```
