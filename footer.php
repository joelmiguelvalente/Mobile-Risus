<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * El footer permite mostrar la plantilla
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

    // 
    $smarty_next = false;
    
    // Verificamos si la plantilla existe 
    // Si no existe mostramos la que está en default
    if(!$smarty->template_exists("t.$tsPage.tpl")){        
        $smarty->setTemplateDir(TS_ROOT . DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'templates'); //Donde se encuentran los templates
        $smarty->setCompileDir(TS_SEC . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'm_' . TS_TEMA . DIRECTORY_SEPARATOR . 'compiled'); // Donde se compilan
            	
    	if($smarty->template_exists("t.$tsPage.tpl")) $smarty_next = true;
    } else $smarty_next = true;
    
    // Mostramos la plantilla
    if($smarty_next == true) $smarty->display("t.$tsPage.tpl");
    else die("0: Lo sentimos, se produjo un error al cargar la plantilla. Contacte al administrador");