<?php 
/**
 * Controlador
 *
 * @name    posts.php
 * @author  PHPost Team
*/

/*
 * -------------------------------------------------------------------
 *  Definiendo variables por defecto
 * -------------------------------------------------------------------
 */

	$tsPage = "posts";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 0;		// NIVEL DE ACCESO A ESTA PAGINA

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT

	$tsTitle = $tsCore->settings['titulo']; 	// TITULO DE LA PAGINA ACTUAL

/*
 * -------------------------------------------------------------------
 *  Validando nivel de acceso
 * -------------------------------------------------------------------
 */

	// Nivel y permisos de acceso
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1)
    {
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso",$tsLevelMsg);
		//
		$tsContinue = false;
	}
	//
	if($tsContinue)
    {

/*
 * -------------------------------------------------------------------
 *  Estableciendo variables y archivos 
 * -------------------------------------------------------------------
 */    
	// Posts Class
	include(TS_CLASS."c.posts.php");
	$tsPosts = new tsPosts();
    
    // Category
	$category = isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : '';

/*
 * -------------------------------------------------------------------
 *  Tareas principales
 * -------------------------------------------------------------------
 */
    if(!empty($_GET['post_id'])){
        
        // DATOS DEL POST
    	$tsPost = $tsPosts->getPost();
    	//
    	if($tsPost['post_id'] > 0) {
    		// TITULO NUEVO
    		$tsTitle = $tsPost['post_title'].' - '.$tsTitle;
    		// ASIGNAMOS A LA PLANTILLA
    		$smarty->assign("tsPost",$tsPost);
    		// DATOS DEL AUTOR
    		$smarty->assign("tsAutor",$tsPosts->getAutor($tsPost['post_user']));						
			// DATOS DEL RANGO DEL PUTEADOR						
			$smarty->assign("tsPunteador",$tsPosts->getPunteador());
    		// RELACIONADOS
    		$tsRelated = $tsPosts->getRelated($tsPost['post_tags']);
    		$smarty->assign("tsRelated",$tsRelated);
    		// COMENTARIOS
    		$tsComments = $tsPosts->getComentarios($tsPost['post_id']);
    		$smarty->assign("tsComments",$tsComments);    
    	} else {
    		//
            if($tsPost[0] == 'privado'){
                $tsTitle = $tsPost[1].' - '.$tsTitle;
				$smarty->assign("tsPost",$tsPost);
                $tsPage = "post_privado";
            } else {
        		$tsTitle = $tsTitle.' - '.$tsCore->settings['slogan'];
        		//
        		$tsPage = "post.aviso";
        		$tsAjax = 0;
        		$smarty->assign("tsAviso",$tsPost);
        		//
        		$title = str_replace("-",",",$tsCore->setSecure($_GET['title']));
        		$title = explode(",",$title);
        		// RELACIONADOS
        		$tsRelated = $tsPosts->getRelated($title);
        		$smarty->assign("tsRelated",$tsRelated);
            }
    	}
    } else {
        // PAGINA
        $tsPage = "home";
        $tsTitle = $tsTitle.' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL
    	// ULTIMOS POSTS
    	$tsLastPosts = $tsPosts->getLastPosts($category);
    	$smarty->assign("tsPosts",$tsLastPosts);
        // se quito NULL, 604800, 0, 5 de getLastPosts()
		$smarty->assign("tsCarrousel",$tsPosts->getLastPosts());
    	// CAT
    	$smarty->assign("tsCat",$category);    	
        // TITULO
        if(!empty($category)) {
            $catData = $tsPosts->getCatData();
            $tsTitle = $tsCore->settings['titulo'].' - '.$catData['c_nombre'];
            $smarty->assign("tsCatData",$catData);
        }
        // DO <= PARA EL MENU
        $smarty->assign("tsDo",$_GET['do']);

    }

}
/*
 * -------------------------------------------------------------------
 *  Incluir plantilla
 * -------------------------------------------------------------------
 */

if(empty($tsAjax)) 
{
    // Asignamos t�tulo
	$smarty->assign("tsTitle",$tsTitle);
    // Incluir footer
	include(TS_ROOT . "/footer.php");
}