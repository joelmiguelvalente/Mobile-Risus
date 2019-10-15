<a href="<?php echo $URL; ?>" class="btn back">Volver</a>
<h3>Procedimiento</h3>
<h4>Solo si tienen el Smarty actualizado</h4>
<span>Buscan en <b>header.php</b>:</span>
<pre class='code code-php'><label>PHP</label><code>include TS_CLASS.'c.smarty.php';</code></pre>
<span>Y reemplazan por</span>
<pre class='code code-php'><label>PHP</label><code>include TS_SEC.'/inc/smarty/SmartyBC.class.php';</code></pre>
<span>Más abajo buscan</span>
<pre class="code code-php"><label>PHP</label><code>$smarty = new tsSmarty();</code></pre>
<span>Y reemplazan por</span>
<pre class='code code-php'><label>PHP</label><code>$smarty = new SmartyBC();</code></pre>
<hr>
<h4>En el caso que no tengan el Smarty actualizado</h4>
<span><strong>1)</strong> Buscan en <b>header.php</b>:</span>
<pre class='code code-php'><label>PHP</label><code>include TS_SEC.'/inc/smarty/SmartyBC.class.php';</code></pre>
<span>Y reemplazan por</span>
<pre class='code code-php'><label>PHP</label><code>include TS_SEC.'/inc/class/c.smarty.php';</code></pre>
<span>Más abajo buscan</span>
<pre class="code code-php"><label>PHP</label><code>$smarty = new tsSmarty();</code></pre>
<span>Y reemplazan por</span>
<pre class='code code-php'><label>PHP</label><code>$smarty = new tsSmarty();</code></pre>