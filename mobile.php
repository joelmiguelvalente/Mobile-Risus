<?php 
/* 
  * NUEVA CONFIGURACIÓN PARA EL SCRIPT Y SMARTY 
  * ARCHIVO PARA HTTPS://NEWRISUS.COM [MOBILE]
  @author Miguel92 - Copyright 2020
*/

// Patch URL actual
$smarty->assign('tsUrlPatch', str_replace($tsCore->settings['url'], '', urldecode($tsCore->currentUrl())));
/*
 * -------------------------------------------------------------------
 *  DEFINIMOS EL DIRECTORIO DE LA CARPETA TEMPLATES
 * -------------------------------------------------------------------
*/
define('Temp', TS_ROOT.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.TS_TEMA.DIRECTORY_SEPARATOR.'templates');
/*
 * -------------------------------------------------------------------
 *  DEFINIMOS EL DIRECTORIO DE LA CARPETA CACHE
 * -------------------------------------------------------------------
*/
define('TS_CACHE', TS_SEC.DIRECTORY_SEPARATOR.'cache');
/*
 * -------------------------------------------------------------------
 *  DEFINIMOS LAS RUTAS DE LOS TEMPLATES (T.PAGINA.TPL)
 * -------------------------------------------------------------------
*/
$smarty->setTemplateDir(array(
  /* ↓ Templates ↓ */
  'One' => Temp, 
));
/*
 * -------------------------------------------------------------------
 *  CONFIGURAMOS ALGUNOS ELEMENTOS DE SMARTY
 * -------------------------------------------------------------------
*/
$smarty->setCompileDir(TS_CACHE.DIRECTORY_SEPARATOR.'Mobile');

/* COMPRIME EL HTML PARA MÁS VELOCIDAD */
$smarty->loadFilter('output', 'trimwhitespace');

/* HABILITA SEGUIRDAD PREDETERMINADA */
$smarty->enableSecurity(); 

/* Establecer los directorios donde se almacenan los archivos de configuración, lo dejamos así porque no hemos creado directorios de configuraciones. (https://www.smarty.net/docs/en/variable.compile.dir.tpl) */
$smarty->setConfigDir(array('url' => $tsCore->settings['url'],'title' => $tsCore->settings['titulo']));

/* COMPROBAMOS QUE TENGA TODO OK */
// $smarty->testInstall();

/*
 * -------------------------------------------------------------------
 *  LAS CLAVES DEL RECAPTCHA V2|V3
 * -------------------------------------------------------------------
*/
define('RC_PUK', $tsCore->settings['pkey']);
/*
 * -------------------------------------------------------------------
 * DAMOS LA UBICACIÓN GEOGRÁFICA (https://www.php.net/manual/es/timezones.php)
 * -------------------------------------------------------------------
*/
date_default_timezone_set("America/Argentina/Buenos_Aires");
/*
 * -------------------------------------------------------------------
 * FORZAMOS EL IDIOMA EN ESPAÑOL
 * -------------------------------------------------------------------
*/
setlocale(LC_ALL, "es_ES");
setlocale(LC_TIME, 'spanish');
/* 
 * -------------------------------------------------------------------
 * PLUGINS ADICIONALES CREADOS POR LOS
 * USUARIOS DE PHPOST (https://phpost.net/foro/)
 * -------------------------------------------------------------------
*/
/* 
 * PLUGIN: NOBBCODE 
 * AUTOR: 1TSR4SC11 (https://www.phpost.net/foro/perfil/8214-1tsr4sc11/)
*/
function smarty_modifier_nobbcode($nobbcode = ''){
  $nobbcode = preg_replace('/\[([^\]]*)\]/', '', $nobbcode); 
  $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
  $nobbcode = preg_replace($regex, ' ', $nobbcode);
  return $nobbcode;
}
/* 
 * PLUGIN: KMG 
 * AUTOR: KMario19 (https://www.phpost.net/foro/perfil/6266-kmario19/)
*/
function smarty_modifier_kmg($number){
  $pre = 'KMG';
  if ($number >= 1000) {
    for ($i=-1; $number>=1000; ++$i) { $number /= 1000; }
    return round($number,1).$pre[$i];
  } else return $number;
}
/*
 * -------------------------------------------------------------------
 * PLUGIN DE SMARTY QUE EXTRAÑAMENTE NO LOS TIENE ¿?
 * -------------------------------------------------------------------
*/
/* NUMBER_FORMAT */
function smarty_modifier_number_format($string, $decimals = 0, $dec_sep=",", $thous_sep = ".") { 
  return number_format($string,$decimals,$dec_sep,$thous_sep);
}
/* LETRACAPITAL Smarty 3 (ucfirst) */
function smarty_modifier_ucfirst($string) {
  return ucfirst($string);
}