<a href="<?php echo $URL; ?>" class="btn back">Volver</a>
<h3>Procedimiento</h3>
				<span><strong>1)</strong> Generar la siguiente consulta:</span>
				<pre class='code code-sql'><label>SQL</label><code>ALTER TABLE `w_configuracion` ADD `mobile` TINYTEXT NOT NULL AFTER `url`;</code></pre>
				
				<span><strong>2)</strong> En el archivo <strong>header.php</strong> buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>// Limpiar variables...
cleanRequest();</code></pre>
				<span>Agregar debajo:</span>
				<pre class='code code-php'><label>PHP</label><code>// Salir de version mobil por 1 dia
if(isset($_GET['mobile'])) setcookie('mobile', $_GET['mobile'], time() + 86400);</code></pre>
				<span>Al final de ese mismo archivo agregar:</span>
				<pre class='code code-php'><label>PHP</label><code>if($tsCore->settings['isMobile']) {
     echo '&lt;div style="background: #B8DCFF;width: 100%;overflow: hidden;font-size: 15px;padding: 16px;color: #000;text-align: center;"&gt;Navega mejor por '.$tsCore->settings['titulo'].' en nuestra versi&oacute;n mobile &lt;a class="mBtn btnOk" href="'.$tsCore->settings['mobile'].'" onclick="document.cookie = \'mobile=;expires=Thu, 01 Jan 1970 00:00:01 GMT;\'"&gt;Entrar&lt;/a&gt;&lt;/div&gt;';
}</code></pre>

				<span><strong>3)</strong> En <strong>c.core.php</strong> buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>function tsCore()</code></pre>
				<span>Agregar arriba:</span>
				<pre class='code code-php'><label>PHP</label><code>public function isMobile() {
	$mobiles = array( "midp", "240x320", "blackberry", "netfront", "nokia", "panasonic", "portalmmm", "sharp", "sie-", "sonyericsson", "symbian", "windows ce", "windows phone", "benq", "mda", "mot-", "opera mini", "philips", "pocket pc", "sagem", "samsung", "sda", "sgh-", "vodafone", "xda", "iphone", "android" );
	foreach($mobiles as $mobileClient) { 
		if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), $mobileClient)) return true;
	} 
	return false; 
}</code></pre>
				<span>Mas abajo buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>$this->settings['novemods'] = $this->getNovemods();</code></pre>
				<span>Agregar debajo:</span>
				<pre class='code code-php'><label>PHP</label><code>$this->settings['isMobile'] = $this->isMobile();</code></pre>
				<span>Mas abajo buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>function getTema()
{</code></pre>
				<span>Agregar debajo:</span>
				<pre class='code code-php'><label>PHP</label><code>if($this->isMobile() && !empty($this->settings['mobile']) && empty($_COOKIE['mobile']) && empty($_GET['mobile'])) {
	header('Location: '.$this->settings['mobile']);
}</code></pre>

				<span><strong>4)</strong> En <strong>c.admin.php</strong> buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>'url' => $tsCore->setSecure($tsCore->parseBadWords($_POST['url'])),</code></pre>
				<span>Agregar debajo:</span>
				<pre class='code code-php'><label>PHP</label><code>'mobile' => $tsCore->setSecure($tsCore->parseBadWords($_POST['mobile'])),</code></pre>
				<span>Mas abajo buscar:</span>
				<pre class='code code-php'><label>PHP</label><code>`url` = \'' . $c['url'] . '\',</code></pre>
				<span>Agregar al lado:</span>
				<pre class='code code-php'><label>PHP</label><code>`mobile` = \'' . $c['mobile'] . '\',</code></pre>

				<span><strong>5)</strong> En <strong>templates/admin_mods/m.admin_configs.tpl</strong> buscar:</span>
				<pre class='code code-html'><label>HTML</label><code>&lt;dl&gt;
    &lt;dt&gt;&lt;label for="ai_url"&gt;Direcci&oacute;n del sitio:&lt;/label&gt;&lt;/dt&gt;
    &lt;dd&gt;&lt;input type="text" id="ai_url" name="url" maxlength="32" value="{$tsConfig.url}" /&gt;&lt;/dd&gt;
&lt;/dl&gt;</code></pre>
				<span>Agregar debajo:</span>
				<pre class='code code-html'><label>HTML</label><code>&lt;dl&gt;
    &lt;dt&gt;&lt;label for="mobile"&gt;Direcci&oacute;n del sitio para m&oacute;viles:&lt;/label&gt;&lt;/dt&gt;
    &lt;dd&gt;&lt;input type="text" id="mobile" name="mobile" maxlength="40" value="{$tsConfig.mobile}" /&gt;&lt;/dd&gt;
&lt;/dl></code></pre>