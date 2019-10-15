<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control del portal/mi
 *
 * @name    c.portal.php
 * @author  PHPost Team
 */
class tsPortal{

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*\
								PUBLICAR POSTS
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /** getNews()
     * @access public
     * @param 
     * @return array
     */
     public function getNews(){
        // MURO
        include("c.muro.php");
        $tsMuro = new tsMuro();
        return $tsMuro->getNews(0);
     }    
}