<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Funciones globales
 *
 * @name    c.core.php
 * @author  PHPost Team
 */
class tsCore {
    
	// CONFIGURACIONES DEL SITIO
	var $settings;
   

	function __construct() {
		// CARGANDO CONFIGURACIONES
		$this->settings = $this->getSettings();
		$this->settings['web'] = $this->settings['url'];
		$this->settings['url'] = $this->settings['mobile'];
		$this->settings['domain'] = str_replace('http://','',$this->settings['url']);
		$this->settings['categorias'] = $this->getCategorias();
      $this->settings['default'] = $this->settings['url'].'/themes/default';
		$this->settings['tema_web'] = $this->getTema();
		$this->settings['images'] = $this->settings['default'].'/images';
		$this->settings['js'] = $this->settings['default'].'/js';
		$this->settings['news'] = $this->getNews();
	   
	}
	
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/	
	/*
		getSettings() :: CARGA DESDE LA DB LAS CONFIGURACIONES DEL SITIO
	*/
	function getSettings() {
		$query = db_exec(array(__FILE__, __LINE__), 'query', 'SELECT * FROM w_configuracion');
		return db_exec('fetch_assoc', $query);
	}
	/*
		getCategorias()
	*/
	function getCategorias() {
		// CONSULTA
		$query = db_exec(array(__FILE__, __LINE__), 'query', 'SELECT cid, c_orden, c_nombre, c_seo, c_img FROM p_categorias ORDER BY c_orden');
		// GUARDAMOS
		$categorias = result_array($query);
        //
        return $categorias;
	}
	/*
		getTema()
	*/
	function getTema()
    {
		$query = db_exec(array(__FILE__, __LINE__), 'query', 'SELECT * FROM w_temas WHERE tid = '.$this->settings['tema_id'].' LIMIT 1');
		$data = db_exec('fetch_assoc', $query);

        $data = $this->settings['web'] . '/themes/' . $data['t_path'];

		return $data;
	}
	/*
        getNews()
    */
   function getNews() {
      //
      $data = array();
		$query = db_exec(array(__FILE__, __LINE__), 'query', 'SELECT not_body FROM w_noticias WHERE not_active = \'1\' ORDER by RAND()');
		while($row = db_exec('fetch_assoc', $query)){
		  	$row['not_body'] = $this->parseBBCode($row['not_body'],'news');
         $data[] = $row;
		}
      //
      return $data;
   }
   // FUNCI�N CONCRETA PARA CENSURAR
   function parseBadWords($c, $s = FALSE) {
      $q = result_array(db_exec(array(__FILE__, __LINE__), 'query', 'SELECT word, swop, method, type FROM w_badwords '.($s == true ? '' : ' WHERE type = \'0\'')));
      foreach($q AS $badword) {
      	$c = str_ireplace((empty($badword['method']) ? $badword['word'] : $badword['word'].' '),($badword['type'] == 1 ? '<img title="'.$badword['word'].'" src="'.$badword['swop'].'" />' : $badword['swop'].' '),$c);
      }
      return $c;
	}        
	
	/*
		setLevel($tsLevel) :: ESTABLECE EL NIVEL DE LA PAGINA | MIEMBROS o VISITANTES
	*/
	function setLevel($tsLevel, $msg = false){
		global $tsUser;
		
		// CUALQUIERA
		if($tsLevel == 0) return true;
		// SOLO VISITANTES
		elseif($tsLevel == 1) {
			if($tsUser->is_member == 0) return true;
			else {
				if($msg) $mensaje = 'Esta pagina solo es vista por los visitantes.';
				else $this->redirectTo('/');
			}
		}
		// SOLO MIEMBROS
		elseif($tsLevel == 2){
			if($tsUser->is_member == 1) return true;
			else {
				if($msg) $mensaje = 'Para poder ver esta pagina debes iniciar sesi&oacute;n.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO MODERADORES
		elseif($tsLevel == 3){
			if($tsUser->is_admod || $tsUser->permisos['moacp']) return true;
			else {
				if($msg) $mensaje = 'Estas en un area restringida solo para moderadores.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO ADMIN
		elseif($tsLevel == 4) {
			if($tsUser->is_admod == 1) return true;
			else {
				if($msg) $mensaje = 'Estas intentando algo no permitido.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
			}
		}
		//
		return array('titulo' => 'Error', 'mensaje' => $mensaje, 'but' => 'Inicio');
	}

	/*
		redirect($tsDir)
	*/
	function redirectTo($tsDir){
		$tsDir = urldecode($tsDir);
		header("Location: $tsDir");
		exit();
	}
    /*
        getDomain()
    */
    function getDomain(){
        $domain = explode('/',str_replace('http://','',$this->settings['url']));
        if(is_array($domain)) {
        $domain = explode('.',$domain[0]);
        } else $domain = explode('.',$domain);
        //
        $t = count($domain);
        $domain = $domain[$t - 2].'.'.$domain[$t - 1];
        //
        return $domain;
    }
	/*
		currentUrl()
	*/
	function currentUrl(){
		$current_url_domain = $_SERVER['HTTP_HOST'];
		$current_url_path = $_SERVER['REQUEST_URI'];
		$current_url_querystring = $_SERVER['QUERY_STRING'];
		$current_url = "http://".$current_url_domain.$current_url_path;
		$current_url = urlencode($current_url);
		return $current_url;
	}
	/*
        getPagination($total, $per_page)
    */
    function getPagination($total, $per_page = 10){
        // PAGINA ACTUAL
        $page = empty($_GET['page']) ? 1 : (int) $_GET['page'];
        // NUMERO DE PAGINAS
        $num_pages = ceil($total / $per_page);
        // ANTERIOR
        $prev = $page - 1;
        $pages['prev'] = ($page > 0) ? $prev : 0;
        // SIGUIENTE 
        $next = $page + 1;
        $pages['next'] = ($next <= $num_pages) ? $next : 0;
        // LIMITE DB
        $pages['limit'] = (($page - 1) * $per_page).','.$per_page; 
        // TOTAL
        $pages['total'] = $total;
        //
        return $pages;
    }
	/*
		setJSON($tsContent)
	*/
	function setJSON($data = NULL, $type = 'encode'){
      if($type == 'encode') return json_encode($data);
      elseif($type == 'decode') return json_decode($data);            
	}
	/*
		setSecure()
	*/
	public function setSecure($var, $xss = FALSE) {
      $var = db_exec('real_escape_string', function_exists('magic_quotes_gpc') ? stripslashes($var) : $var);
      return $var;
   }

    /*
        antiFlood()
    */
    public function antiFlood($print = true, $type = 'post', $msg = '')
    {
        global $tsUser;
        //
        $now = time();
        $msg = empty($msg) ? 'No puedes realizar tantas acciones en tan poco tiempo.' : $msg;
        //
        $limit = $tsUser->permisos['goaf'];
        $resta = $now - $_SESSION['flood'][$type];
        if($resta < $limit) {
            $msg = '0: '.$msg.' Int&eacute;ntalo en '.($limit - $resta).' segundos.';
            // TERMINAR O RETORNAR VALOR
            if($print) die($msg);
            else return $msg;
        } else {
            // ANTIFLOOD
            $_SESSION['flood'][$type] = (empty($_SESSION['flood'][$type])) ? time() : $now;
            // TODO BIEN
            return true;
        }
    }
	
	/*
		setSEO($string, $max) $max : MAXIMA CONVERSION
		: URL AMIGABLES
	*/
	function setSEO($string, $max = false) {
		// ESPA�OL
		$espanol = array('�','�','�','�','�','�');
		$ingles = array('a','e','i','o','u','n');
		// MINUS
		$string = str_replace($espanol,$ingles,$string);
		$string = trim($string);
		$string = trim(preg_replace('/[^ A-Za-z0-9_]/', '-', $string));
		$string = preg_replace('/[ \t\n\r]+/', '-', $string);
		$string = str_replace(' ', '-', $string);
		$string = preg_replace('/[ -]+/', '-', $string);
		//
		if($max) {
			$string = str_replace('-','',$string);
			$string = strtolower($string);
		}
		//
		return $string;
	}
	/*
		parseBBCode($bbcode)
	*/
	function parseBBCode($bbcode = NULL, $type = 'normal') {
        // Class BBCode
        include_once(TS_EXTRA . 'bbcode.inc.php');
        $parser = new BBCode();
        
        // Seleccionar texto
        $parser->setText($bbcode);

        // Seleccionar tipo
        switch ($type) {
            // NORMAL
            case 'normal':
                // BBCodes permitidos
                $parser->setRestriction(array('url', 'code', 'quote', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler', 'swf', 'video', 'goear', 'hr', 'sub', 'sup', 'table', 'td', 'tr', 'ul', 'li', 'ol', 'notice', 'info', 'warning', 'error', 'success'));
                // SMILES
                $parser->parseSmiles();
                // MENCIONES
                $parser->parseMentions();
                break;
            // FIRMA
            case 'firma':
                // BBCodes permitidos
                $parser->setRestriction(array('url', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler'));
                break;
            // NOTICIAS
            case 'news':
                // BBCodes permitidos
                $parser->setRestriction(array('url', 'b', 'i', 'u', 's'));
                // SMILES
                $parser->parseSmiles();
                break;
            // SOLO SMILES (Esta opci�n se mantiene por compatibilidad con versiones anteriores, pero en su lugar se recomienda utilizar la opci�n "normal")
            case 'smiles':
                $parser->setRestriction(array('url', 'code', 'quote', 'quotePHPost', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 'align', 'spoiler', 'swf', 'goear', 'hr', 'li'));
                // SMILES
                $parser->parseSmiles();
                // MENCIONES
                $parser->parseMentions();
            break;
        }
        // Retornar resultado HTML
        return $parser->getAsHtml();
    }
    /**
     * @name setMenciones
     * @access public
     * @param string
     * @return string
     * @info PONE LOS LINKS A LOS MENCIONADOS
     */
    public function setMenciones($html){
        # GLOBALES
        global $tsUser;
        # HACK
        $html = $html.' ';
        # BUSCAMOS A USUARIOS
        preg_match_all('/\B@([a-zA-Z0-9_-]{4,16}+)\b/',$html, $users);
        $menciones = $users[1];
        # VEMOS CUALES EXISTEN
        foreach($menciones as $key => $user){
            $uid = $tsUser->getUserID($user);
            if(!empty($uid)) {
                $find = '@'.$user.' ';
                $replace = '@<a href="'.$this->settings['url'].'/perfil/'.$user.'" class="hovercard" uid="'.$uid.'">'.$user.'</a> ';
                $html = str_replace($find, $replace, $html);
            }
        }
        # RETORNAMOS
        return $html;
    }
   /*
        parseSmiles($st)
    */
    public function parseSmiles($bbcode){
        return $this->parseBBCode($bbcode, 'smiles');
    }
	/*
		parseBBCodeFirma($bbcode)
	*/
	function parseBBCodeFirma($bbcode){
	   return $this->parseBBCode($bbcode, 'firma');
	}
	/*
		setHace()
	*/
	function setHace($fecha, $show = false){
		$fecha = $fecha; 
		$ahora = time();
		$tiempo = $ahora-$fecha; 
		if($fecha <= 0) return 'Nunca';
		elseif(round($tiempo / 31536000) <= 0){ 
			if(round($tiempo / 2678400) <= 0){ 
				if(round($tiempo / 86400) <= 0){ 
					if(round($tiempo / 3600) <= 0){ 
						if(round($tiempo / 60) <= 0){ 
							if($tiempo <= 60){ $hace = 'instantes'; } 
						} else  { 
							$can = round($tiempo / 60); 
							$word = ($can <= 1) ?  'minuto' : 'minutos'; 
							$hace = $can. " ".$word; 
						} 
					} else { 
						$can = round($tiempo / 3600); 
						$word = ($can <= 1) ?  'hora' : 'horas';
						$hace = $can. " ".$word; 
					} 
				} else  { 
					$can = round($tiempo / 86400); 
					$word = ($can <= 1) ? 'd&iacute;a' : 'd&iacute;as'; 
					$hace = $can. " ".$word;
				} 
			} else  { 
				$can = round($tiempo / 2678400);  
				$word = ($can <= 1) ? 'mes' : 'meses'; 
				$hace = $can. " ".$word; 
			}
		} else {
			$can = round($tiempo / 31536000); 
			$word = ($can <= 1) ? 'a&ntilde;o' : 'a&ntilde;os'; 
			$hace = $can. " ".$word; 
		}
		//
		if($show == true) return 'Hace '.$hace;
		else return $hace;
	}
	/*
		getUrlContent($tsUrl)
	*/
	function getUrlContent($tsUrl){
	   // USAMOS CURL O FILE
	   if(function_exists('curl_init')){
    		// User agent
    		$useragent = $_SERVER['HTTP_USER_AGENT'];
    		//Abrir conexion  
    		$ch = curl_init();  
    		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    		curl_setopt($ch,CURLOPT_URL,$tsUrl);
    		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
    		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    		$result = curl_exec($ch);
    		curl_close($ch); 
        } else {
            $result = @file_get_contents($tsUrl);
        }
		return $result;
	}
    /*
        getIP
    */
    function getIP(){
	   if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) $ip = getenv('HTTP_CLIENT_IP');	
	   elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) $ip = getenv('HTTP_X_FORWARDED_FOR');
	   elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) $ip = getenv('REMOTE_ADDR');
	   elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) $ip = $_SERVER['REMOTE_ADDR'];
	   else $ip = 'unknown';
	   return $this->setSecure($ip);
    }
	/* 
		getIUP()
	*/
	function getIUP($array, $prefix = ''){
		// NOMBRE DE LOS CAMPOS
		$fields = array_keys($array);
		// VALOR PARA LAS TABLAS
		$valores = array_values($array);
		// NUMERICOS Y CARACTERES
		foreach($valores as $i => $val) {
		  $sets[$i] = $prefix.$fields[$i]." = '".$this->setSecure($val)."'"; // Version: 1.1.500.8
		}
		$values = implode(', ',$sets);
		//
		return $values;
	}
	/*
	 * Sacar imagen del post
	 * si hay mas de una imagenen, tomamos la 2 (casi siempre la 1 es de "bienvenido")
	 */
	function extraer_img($texto) {
      // del tipo [img=imagen] o [img=imagen]
      preg_match_all('/(\[img(\=|\]))((http|https)?(\:\/\/)?([^\<\>[:space:]]+)\.(jpg|jpeg|png|gif))(\]|\[\/img\])/i', $texto, $imgs);
      $total = count($imgs[3]);
      // Sacamos la mejor imagen posible ._.
      $img = (count($imgs[3]) > 1) ? $imgs[3][1] : $imgs[3][0];    
      if(empty($img)) $img = false;
      //
      return $img;
   }
	
}