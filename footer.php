<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * El footer permite mostrar la plantilla
 * Actualizacion Realizada: Smarty 3
 *
 * @name    footer.php
 * @author  PHPost Team
 */

/*
 * -------------------------------------------------------------------
 *  Realizamos tareas para mostrar la plantilla
 * -------------------------------------------------------------------
 */
 
    // Página solicitada
    $smarty->assign("tsPage",$tsPage);

    // Mostramos la plantilla
    if($smarty->templateExists("t.{$tsPage}.tpl")) $smarty->display("t.{$tsPage}.tpl");
    else die("0: Lo sentimos, se produjo un error al cargar la plantilla 't.{$tsPage}.tpl' en su theme actual. Contacte al administrador");