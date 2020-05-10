function gget(data, sin_amp){
	var r = data+'=';
	if(!sin_amp) r = '&'+r;
	switch(data){
		case 'key':
			if(global_data.user_key!='') return r+global_data.user_key;
		break;
		case 'postid':
			if(global_data.postid!='') return r+global_data.postid;
		break;
		case 'fotoid':
			if(global_data.fotoid!='') return r+global_data.fotoid;
		break;
	}
	return '';
}
var modal = {
	abrir: function(type) {
		$('#cuerpo').hide();
		$('#footer').hide();
		$('.modal.modal-' + type).show();
		return false;
	},
	cerrar: function(todo) {
		$('.modal').hide();
		if(!todo) {
			$('#cuerpo').show();
			$('#footer').show();
			var pos = $('.post_herramientas').offset();
			$('body').scrollTo(pos.top-200);
		}
		return false;
	},
	action: function(type) {
		switch(type) {
			case 'share':
				post_share();
			break;
			case 'fav':
				post_fav();
			break;
		}
	},
	alerta: function(text) {
		$('#cuerpo').hide();
		$('#footer').hide();
		$('.modal.modal-alerta p').html(text);
		$('.modal.modal-alerta').show();
		return false;
	},
	home: function(text) {
		$('#cuerpo').hide();
		$('#footer').hide();
		$('.modal.modal-home p').html(text);
		$('.modal.modal-home').show();
		return false;
	}
}
function login_ajax(){
	var el = new Array(), params = '';
	el['nick'] = $('#login #nickname');
	el['pass'] = $('#login #password');
	el['redir'] = $('#login #redir');
	el['error'] = $('#login_error');
	el['button'] = $('#login input[type="submit"]');
	if (empty($(el['nick']).val())) {
		$(el['nick']).focus();
		return;
	}
	if (empty($(el['pass']).val())) {
		$(el['pass']).focus();
		return;
	}
	$(el['error']).css('display', 'none');
	$(el['button']).attr('disabled', 'disabled').addClass('disabled');
	params = 'nick='+encodeURIComponent($(el['nick']).val())+'&pass='+encodeURIComponent($(el['pass']).val()); 
	if (!empty($(el['redir']).val())) {
		params += '&redir='+el['redir'].val(); 
	}
	$.ajax({
		type: 'post', url: global_data.url + '/login-user.php', cache: false, data: params,
		success: function (h) {
			if(h.charAt(0) == '0') {
					$(el['error']).html(h.substring(3)).show();
					$(el['nick']).focus();
					$(el['button']).removeAttr('disabled').removeClass('disabled');
			} else {
				if(!empty($(el['redir']).val())) {	
					location.href = global_data.url + $(el['redir']).val();
				} else {
					location.reload();
				}
			}
		},
		error: function() {
			$(el['error']).html(lang['error procesar']).show();
		}
	});
}
 function seguir_leyendo() {
	$('.post_contenido').removeClass('short');
	$('.load-more').remove();
	return false;	
}
function dar_puntos() {
	var puntos = $('.post_puntuar select').val();
	if(puntos == null || isNaN(puntos) != false || puntos < 1) {
		return false;
	} else {		
		$('.post_puntuar').html('Puntos agregados');
		$('.post_puntos').text(parseInt($('.post_puntos').attr('puntos'))+parseInt(puntos));
	}
	$.ajax({
		type: 'POST',
		url: global_data.url + '/posts-votar.php',
		data: 'puntos=' + puntos + gget('postid'),
	});	
}
function load_tab(tab) {
	if(tab == 'related') {
		$('#tab_comments').hide();
		$('#btn_tab_comments').removeClass('active');
	} else {
		$('#tab_related').hide();
		$('#btn_tab_related').removeClass('active');
	}
	//
	$('#btn_tab_' + tab).addClass('active');
	$('#tab_' + tab).show().addClass('active');
}
function comentar_post() {
	$('#add_comment').attr({'disabled':'disabled'});
	var textarea = $('#body_comment');
	var text = textarea.val();
	if(text == ''){
		textarea.focus();
		$('#add_comment').removeAttr('disabled');
		return;
	}
	$('#comment_loading').show();
	var auser = $('#auser_post').val();
	$.ajax({
		type: 'POST',
		url: global_data.url + '/comentario-agregar.php',
		data: 'comentario=' + encodeURIComponent(text) + '&postid=' + gget('postid') + '&auser=' + auser,
		success: function(h){
			switch(h.charAt(0)){
				case '0': //Error
					$('.box_comentario #error').html(h.substring(3)).show();
					$('#add_comment'). removeAttr('disabled');
					break;
				case '1': //OK
					$('#no-comments').remove();
					$('.box_comentario #error').hide();
					$('#nuevos').prepend(h.substring(3)).show();
					textarea.val('');
					$('#add_comment'). removeAttr('disabled');
				break;
			}
			$('#comment_loading').hide();
		}
	});	
}
function post_share() {
	modal.cerrar(true);
	$.ajax({
		type: 'post',
		url: global_data.url + '/posts-share.php',
		data: gget('postid', true),
		success: function (h) {
			switch(h.charAt(0)){
				case '0': //Error
					modal.alerta(h.substring(2));
					break;
				case '1': //OK
					var total = parseInt($('#total_shares').text());
					$('#total_shares').text(total+1);
					modal.alerta(h.substring(2));
					break;
			}
		}
	});
}
function post_fav() {
	modal.cerrar(true);
	$.ajax({
		type: 'post',
		url: global_data.url + '/posts-fav.php',
		data: gget('postid', true),
		success: function (h) {
			switch(h.charAt(0)){
				case '0': //Error
					modal.alerta(h.substring(3));
					break;
				case '1': //OK
					var total = parseInt($('#total_favs').text());
					$('#total_favs').text(total+1);
					modal.alerta(h.substring(3));
					break;
			}
		}
	});
}
function more_related() {
	if(scrollContinue == false) return false;
	else scrollContinue = false;
	var tags = $('#tags_related').val();
	if(totalRelated >= 10) {
		$('#tab_related #loader').show();
		$.ajax({
			type: 'post',
			url: global_data.url + '/posts-related.php',
			data: 'tags=' + tags + '&start=' + totalRelated,
			success: function (h) {
				$('#tab_related .result').append(h);
				var total = $('#total_related').val();
				if(total < 10 || total == 0) $('#tab_related .load-more').attr('onClick', 'return false');
				else totalRelated = totalRelated  + total;
				$('#total_related').remove();
			},
			complete: function() {
				$('#tab_related #loader').hide();
				scrollContinue = true;	
			}
		});
	}
}
function more_comments() {
	if(scrollContinue == false) return false;
	else scrollContinue = false;
	if(totalComments >= 10) {
		$('#tab_comments #loader').show();
		$.ajax({
			type: 'post',
			url: global_data.url + '/posts-comments.php',
			data: 'start=' + totalComments + gget('postid'),
			success: function(h) {
				$('#tab_comments .post_comentarios').append(h);
				var total = $('#total_comments').val();
				if(total < 10 || total == 0) $('#tab_comments .load-more').attr('onClick', 'return false');
				else totalComments = parseInt(totalComments)+parseInt(total);
				$('#total_comments').remove();
			},
			complete: function() {
				$('#tab_comments #loader').hide();
				scrollContinue = true;	
			}
		});
	}
}
function load_more(type) {
	if(scrollContinue == false) return false;
	else scrollContinue = false;
	if(totalPosts >= 25) {
		$('.load-more').attr({'disabled':'disabled'});
		$.ajax({
			type: 'post',
			url: global_data.url + '/posts-load_more.php',
			data: 'start=' + totalPosts + '&type=' + type + (type == 'perfil' ? '&user='+$('#info').val() : ''),
			success: function (h) {
				$('ul[class=posts]').append(h);
				var total = $('#total_posts').val();
				total = parseInt(total);
				var msg = 'No hay m&aacute;s posts'; 
				if(total == 0 || total < 25) $('.load-more').html(msg).attr('onClick', 'return false');
				else totalPosts = totalPosts + total;
				$('#total_posts').remove();
			},
			complete: function (){
				$('.load-more').removeAttr('disabled');
				scrollContinue = true;
			}
		});
	}
}
function carrousel(id) {
	var total = $('.carrousel').attr('total');
	if(id > total) id = 1;
	else if(id == 0) id = total; 
	$('.carrousel li.c_item').removeClass('active');
	$('.carrousel #cpost_' + id).addClass('active');
}
function responder_mensaje(id){
	$('#add_comment').attr({'disabled':'disabled'});
	var textarea = $('#body_comment');
	var text = textarea.val();
	if(text == ''){
		textarea.focus();
		$('#add_comment').removeAttr('disabled');
		return;
	}
	$('#comment_loading').show();
	$('#respuesta').val(''); // LIMPIAMOS
	$.ajax({
		type: 'post',
		url: global_data.url + '/mensajes-respuesta.php',
		data: 'id=' + id + '&body=' + text,
		success: function (h) {
			switch(h.charAt(0)){
				case '0':
					$('.box_comentario #error').html(h.substring(3)).show();
					$('#add_comment').removeAttr('disabled');
				break;
				case '1':
					$('#no-comments').remove();
					$('.box_comentario #error').hide();
					$('#misMps').append(h.substring(3)).show();
					textarea.val('');
					$('#add_comment').removeAttr('disabled');
				break;
			}
			$('#comment_loading').hide();
		}
		
	})
}
function seguir(userid, type) {
	$('#userFollow button').toggle();
	$.ajax({
		type: 'post',
		url: global_data.url + '/notificaciones-ajax.php',
		data: 'type=user&obj=' + userid + '&tipo=' + type,
		success: function (h) {
			switch(h.charAt(0)){
				case '0':
					$('#userFollow button').toggle();
					$('.head_perfil #error').html(h.substring(3)).show();
				break;
				case '1':
					$('.head_perfil #error').hide();
				break;
			}
		}
		
	})
}
function votar_foto(voto) {
voto = (voto == 'pos') ? 'pos' : 'neg';
// VARS
var total_votos = parseInt($('#votos_total_' + voto).text());
total_votos = (isNaN(total_votos)) ? 0 : total_votos;
$('#votos_total_' + voto).parent().attr({'disabled':'disabled'});
//
$.ajax({
	type: 'POST',
	url: global_data.url + '/comentario-votar.php?do=fotos',
	data: 'voto=' + voto + '&fotoid=' + gget('fotoid'),
	success: function(h){
		switch(h.charAt(0)){
			case '0': //Error
				$('#error').html(h.substring(3)).show();
				$('#votos_total_' + voto).parent().removeAttr('disabled');
				break;
			case '1': //OK
				$('#error').hide();
				total_votos = total_votos + 1;
				$('#votos_total_' + voto).text(total_votos);
				break;
		}
	}
});	
}
function comentar_foto() {
	$('#add_comment').attr({'disabled':'disabled'});
	var textarea = $('#body_comment');
	var text = textarea.val();
	if(text == ''){
		textarea.focus();
		$('#add_comment').removeAttr('disabled');
		return;
	}
	$('#comment_loading').show();
	$.ajax({
		type: 'POST',
		url: global_data.url + '/comentario-agregar.php?do=fotos',
		data: 'comentario=' + encodeURIComponent(text) + '&postid=' + gget('fotoid'),
		success: function(h){
			switch(h.charAt(0)){
				case '0': //Error
					$('.box_comentario #error').html(h.substring(3)).show();
					$('#add_comment'). removeAttr('disabled');
					break;
				case '1': //OK
					$('#no-comments').remove();
					$('.box_comentario #error').hide();
					$('#nuevos').prepend(h.substring(3)).show();
					textarea.val('');
					$('#add_comment'). removeAttr('disabled');
				break;
			}
			$('#comment_loading').hide();
		}
	});	
}
function more_fotos() {
	if(scrollContinue == false) return false;
	else scrollContinue = false;
	if(totalFotos >= 10) {
		$('.load-more').attr({'disabled':'disabled'});
		$.ajax({
			type: 'post',
			url: global_data.url + '/posts-more_fotos.php',
			data: 'start=' + totalFotos,
			success: function (h) {
				$('ul[class=fotos]').append(h);
				var total = $('#total_fotos').val();
				total = parseInt(total);
				var msg = 'No hay m&aacute;s fotos'; 
				if(total == 0 || total < 10) $('.load-more').html(msg).attr('onClick', 'return false');
				else totalFotos = totalFotos + total;
				$('#total_fotos').remove();
			},
			complete: function (){
				$('.load-more').removeAttr('disabled');
				scrollContinue = true;
			}
		});
	}
}

var mps = {
	datos: new Array(),
	verify: function() {
		var cont = false;
		// Para
		var para = $('#para');
		var vpara = para.val();
		if(vpara.length <= 3) {
			para.addClass('error').focus();
			return this.error('Coloca un destinatario v&aacute;lido');
		} else cont = true;
		if(!/[^0-9]/.test(vpara)) {
			para.addClass('error').focus();
			return this.error('El nombre de usuario no puede contener solo numeros');
		} else cont = true;
		if(vpara.toLowerCase() == global_data.user_nick.toLowerCase()) {
			para.addClass('error').focus();
			return this.error('Escribe otro destinatario');
		} else cont = true;
		if(cont) {
			para.removeClass('error');
			this.error(false);
			this.datos['para'] = vpara;
		}
		// Asunto
		this.datos['asunto'] = $('#asunto').val();
		// Mensaje
		var mensaje = $('#mensaje');
		var vmensaje = mensaje.val();
		if(vmensaje.length<=0) {
			mensaje.addClass('error').focus();
			return this.error('Escribe un mensaje para enviar');
		} else cont = true;
		if(cont) {
			mensaje.removeClass('error');
			this.error(false);
			this.datos['mensaje'] = vmensaje;
		}
		// Todo bien
		mps.send();
	},
	send: function() {
		$('.mp_nuevo input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
		var params = '';
		var amp = '';
		for(var campo in this.datos){
			params += amp + campo + '=' + encodeURIComponent(this.datos[campo]);
			amp = '&';
		}
		$.ajax({
			type: 'POST',
			url: global_data.url + '/mensajes-nuevo.php',
			data: params,
			success: function(h){
				if(h.charAt(0) == '0') {
					mps.error(h.substring(3), true);
				} else if(h.charAt(0) == '1') {
					location.href = h.substring(3);
				} else {
					mps.error(h, true);
				}
				$('.mp_nuevo input[type="submit"]').removeAttr('disabled').removeClass('disabled');
			}
		})
	},
	error: function(msj, foco) {
		if(msj.length > 0) {
			$('#error').html(msj).show();
			if(foco) $('#error').focus();
		} else {
			$('#error').html('').hide();
		}
	}
}

function votar_comment(id, voto) {
	voto = (voto == 'pos') ? 'pos' : 'neg';
	var total_votos = parseInt($('#cid_' + id + ' .likes').text());
	total_votos = (isNaN(total_votos)) ? 0 : total_votos;
	$('#cid_' + id + ' .buttons a').attr({'disabled':'disabled'});
	total_votos =  (voto == 'pos') ? total_votos+1 : total_votos-1;
	$('#cid_' + id + ' .likes').css({'color' : (total_votos < 0) ? '#900' : '#18A718'}).text(total_votos);
	//
	$.ajax({
		type: 'POST',
		url: global_data.url + '/comentario-votar.php?do=posts',
		data: 'voto=' + voto + '&cid=' + id,
		success: function(h){
			if(h.charAt(0) == '1') {
				$('#cid_' + id + ' .buttons').remove();
			} else {
				alert(h);
			}
			$('#cid_' + id + ' .buttons a').removeAttr('disabled');
		}
	});
}

function citar_comentario(id, nick){
	var textarea = $('#body_comment');
	textarea.focus();
	textarea.val(((textarea.val()!='') ? textarea.val() + '\n' : '') + '[quote=' + nick + ']' + htmlspecialchars_decode($('#citar_comm_'+id).html(), 'ENT_NOQUOTES') + '[/quote]\n');
}

function menu_toggle() {
    $("#brandday").toggleClass("sidebar-visible");
}
// NEWS
var news = {
    total: 0,
    count: 1,
    slider: function(){
        if(news.total > 1){
            if(news.count < news.total) news.count++;
            else news.count = 1;
            $('#top_news > li').hide();
            $('#new_' + news.count).fadeIn();
            setTimeout("news.slider()",7000);
        }
    }       
}

var muro = {
    stream: {
        total: 0, // TOTAL DE PUBLICACIONES CARGADAS
        show: 10, // CUANTOS SE MUESTRAN POR CADA CARGA
        // COMPARTIR
        compartir: function(){
			$('#add_comment').attr({'disabled':'disabled'});       
			if($('.box_comentario textarea').val() != '' || $('.box_comentario textarea').val().length > 0) {            
				muro.stream.loader(true);
				var data = $('.box_comentario textarea').val();
				$.ajax({
					type: 'POST',
					url: global_data.url + '/muro-stream.php?do=post',
					data: 'data=' + encodeURIComponent(data) + '&pid=' + $('#info').val(),
					success: function(h){
						switch(h.charAt(0)){
							case '0': //Error
								$('.box_comentario #error').html(h.substring(3)).show();
								break;
							case '1': //OK
								// ESCONDEMOS SI ES EL PRIMER COMENTARIO
								if($('#perfil_wall .emptyData')) $('#perfil_wall .emptyData').hide();
								$('.box_comentario #error').hide();
								$('.shouts').prepend($(h.substring(3)).show());
								$('.box_comentario textarea').val('');
								break;
						}
					},
					complete: function (){
						// LOADER/ STATUS
						muro.stream.loader(false);
						$('#add_comment').removeAttr('disabled');
					}
				});
			} else {
				$('.box_comentario textarea').focus();
				$('#add_comment').removeAttr('disabled');
			}
		
        },
        loadMore: function(type){
            // SI ESTA OCUPADO NO HACEMOS NADA
            if(scrollContinue == false) return false;
			else scrollContinue = false;
			$('.load-more').attr({'disabled':'disabled'});  
            // LOADER
            $.ajax({
            	type: 'POST',
            	url: global_data.url + '/muro-stream.php?do=more&act=' + type,
            	data: 'pid=' + $('#info').val() + '&start=' + muro.stream.total,
            	success: function(h){
					// CARGAMOS AL DOM
					$('.shouts').append(h.substring(3));
					// VALIDAMOS
					var total_pubs = $('#total_pubs').attr('val');
					total_pubs = parseInt(total_pubs);
					// 
					var msg = 'No hay m&aacute;s mensajes para mostrar.'; 
					if(total_pubs == 0 || total_pubs < muro.stream.show) $('.load-more').html(msg).attr('onClick', 'return false');
					else muro.stream.total = muro.stream.total + parseInt(total_pubs);
					// REMOVER
					$('#total_pubs').remove();
            	},
                complete: function (){
                    scrollContinue = true;
					$('.load-more').removeAttr('disabled');
                }
            });
        },
        // LOADER
        loader: function(active){
            if(active == true) $('#comment_loading').show();
            else if(active == false) $('#comment_loading').hide();
        }
    },
    // LIKE
    like_this: function(id){
		if(muro.stream.status == 1) return false;
        else muro.stream.status = 1;
		$('#pub_' + id + ' .shbtn.shlike').toggleClass('ok');
		if($('#pub_' + id + ' .shbtn.shlike').hasClass('ok')) {
			var suma = parseInt($('#tlikes_' + id).html())+1;
			var resta = suma-1;
		} else {
			var suma = parseInt($('#tlikes_' + id).html())-1;
			var resta = suma+1;
		}
		$('#tlikes_' + id).html(parseInt(suma));
        // MANDAMOS
        $.ajax({
        	type: 'POST',
        	url: global_data.url + '/muro-likes.php',
            dataType: 'json',
        	data: 'id=' + id,
        	success: function(h){
        	   if(h['status'] == 'ok'){
				   	$('#pub_' + id + ' #error').hide();
        	   } else {
        	       $('#pub_' + id + ' #error').html(h['text'].substring(3)).show();
				   $('#tlikes_' + id).html(parseInt(resta));
				   $('#pub_' + id + ' .shbtn.shlike').toggleClass('ok');
        	   }
               $('#loading').slideUp(350); 
        	},
            complete: function (){
                // STATUS
                muro.stream.status = 0;
            }
        });
    },
    comentar: function(id){
		$('#add_comment').attr({'disabled':'disabled'});       
		if($('.box_comentario textarea').val() != '' || $('.box_comentario textarea').val().length > 0) {            
			muro.stream.loader(true);
			var data = $('.box_comentario textarea').val();
			$.ajax({
				type: 'POST',
				url: global_data.url + '/muro-stream.php?do=repost',
				data: 'data=' + encodeURIComponent(data) + '&pid=' + id,
				success: function(h){
					switch(h.charAt(0)){
						case '0': //Error
							$('.box_comentario #error').html(h.substring(3)).show();
							break;
						case '1': //OK
							$('#no-comments').remove();
							$('.box_comentario #error').hide();
							$('#nuevos').prepend(h.substring(3)).show();
							$('.box_comentario textarea').val('');
							$('#add_comment'). removeAttr('disabled');
							break;
					}
				},
				complete: function (){
					muro.stream.loader(false);
					$('#add_comment').removeAttr('disabled');
				}
			});
		} else {
			$('.box_comentario textarea').focus();
			$('#add_comment').removeAttr('disabled');
		}
    },
    // MOSTRAR VIDEO DEL MURO
    load_atta: function(type, ID, obj){
        switch(type){
            case 'foto':
                var content = '<center><img src="' + ID + '" style="max-width:' + this.maxWidth + 'px; max-height: 380px" /><center>'; //bzox
            break;
            case 'video':
				var content = '<div style="position: relative;width: 100%;padding-top: 56.25%"><iframe width="100%" height="100%" src="//www.youtube.com/embed/' + ID +'" frameborder="0" allowFullScreen style="position: absolute;left: 0;top: 0;"></iframe></div>';
            break;
        }
        // CARGAMOS
        $(obj).parent().html(content);
    }
}

$(document).ready(function() {
	$(".comment").click(function() {
		$('.comment').children('.buttons').hide();
		$('.comment').children('.bbody').hide();
		$(this).children('.buttons').show();
		$(this).children('.bbody').show();
	});
   news.total = $('#top_news > li').length;
   news.slider();
   $(window).scroll(function(){
      if (window.pageYOffset >= 1000) $('#scroll-up').fadeIn();
      else $('#scroll-up').fadeOut();
   });    
   $('#scroll-up').click(function(a){scrollTo(0,0);});
   $('img[src], a.flazyload').lazyload();
});