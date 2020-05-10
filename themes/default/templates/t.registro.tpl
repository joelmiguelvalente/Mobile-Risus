{include file='sections/main_header.tpl'}
<h1 class="Titulo">Nuevo registro</h1>
<div id="login_error"></div>
<div id="registro" class="box">
    <div class="form-group">
        <label for="nick">Ingresa tu usuario</label>
	   <input name="nick" type="text" id="nick" class="input-text"  tabindex="1" placeholder="Ingrese un nombre de usuario &uacute;nico" />
    </div><div class="form-group">
        <label for="password">Contrase&ntilde;a deseada</label>
        <input name="password" type="password" class="input-text" id="password" tabindex="2" placeholder="Ingresa una contrase&ntilde;a segura" />
    </div><div class="form-group">
        <label for="email">E-mail</label>
        <input name="email" type="text" class="input-text" id="email" tabindex="4" placeholder="Ingresa tu direcci&oacute;n de email" />
    </div><div class="form-group">
        <label for="sexo">Sexo</label>
        <select name="sexo" id="sexo" tabindex="8" class="input-text" style="background-position-x: 98%!important;">
        	<option value="">Selecciona tu genero</option>
        	<option value="m">Masculino</option>
            <option value="f">Femenino</option>
        </select>
    </div><div class="form-line form-group">
        <input type="hidden" name="g-recaptcha-response" id="response" class="g-recaptcha">
        <div class="help"><span><em></em></span></div>
   </div><div class="form-group">
        <label>Al registrarme en {$tsConfig.titulo} estoy aceptando los <a href="{$tsConfig.web}/pages/terminos-y-condiciones/">T&eacute;rminos y condiciones</a> del sitio</label>
    </div>
    <div class="controls">
        <a href="#" onclick="registro.verify();return false;" class="btn_blue">Registrarme</a>
    </div>
</div>
<div class="line_separator"></div>
<script src="https://www.google.com/recaptcha/api.js?render={RC_PUK}"></script>
<script src="{$tsConfig.js}/registro.js?={$smarty.now}" type="text/javascript"></script>
<script type="text/javascript">
   var keyv3 = '{RC_PUK}';
   grecaptcha.ready(function () {
      grecaptcha.execute(keyv3{literal}, {action: 'homepage'}).then(function (token) {
         var response = document.getElementById('response');
         response.value = token;
      });
   });
   {/literal}
</script>
{include file='sections/main_footer.tpl'}
