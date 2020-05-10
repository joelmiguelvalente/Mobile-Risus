// JavaScript Document
var registro = {
	banned_passwords: ["111111","11111111","112233","121212","123123","123456","1234567","12345678","131313","232323","654321","666666","696969","777777","7777777","8675309","987654","aaaaaa","abc123","abc123","abcdef","abgrtyu","access","access14","action","admin","albert","alexis","amanda","amateur","andrea","andrew","angela","angels","animal","anthony","apollo","apples","arsenal","arthur","asdfgh","asdfgh","ashley","asshole","august","austin","badboy","bailey","banana","barney","baseball","batman","beaver","beavis","bigcock","bigdaddy","bigdick","bigdog","bigtits","birdie","bitches","biteme","blazer","blonde","blondes","blowjob","blowme","bond007","bonnie","booboo","booger","boomer","boston","brandon","brandy","braves","brazil","bronco","broncos","bulldog","buster","butter","butthead","calvin","camaro","cameron","canada","captain","carlos","carter","casper","charles","charlie","cheese","chelsea","chester","chicago","chicken","cocacola","coffee","college","compaq","computer","cookie","cooper","corvette","cowboy","cowboys","crystal","cumming","cumshot","dakota","dallas","daniel","danielle","debbie","dennis","diablo","diamond","doctor","doggie","dolphin","dolphins","donald","dragon","dreams","driver","eagle1","eagles","edward","einstein","erotic","extreme","falcon","fender","ferrari", "fewfew", "firebird","fishing","florida","flower","flyers","football","forever","freddy","freedom","fucked","fucker","fucking","fuckme","fuckyou","gandalf","gateway","gators","gemini","george","giants","ginger","golden","golfer","gordon","gregory","guitar","gunner","hammer","hannah","hardcore","harley","heather","helpme","hentai","hockey","hooters","horney","hotdog","hunter","hunting","iceman","iloveyou","internet","iwantu","jackie","jackson","jaguar","jasmine","jasper","jennifer","jeremy","jessica","johnny","johnson","jordan","joseph","joshua","junior","justin","killer","knight","ladies","lakers","lauren","leather","legend","letmein","letmein","little","london","lovers","maddog","madison","maggie","magnum","marine","marlboro","martin","marvin","master","matrix","matthew","maverick","maxwell","melissa","member","mercedes","merlin","michael","michelle","mickey","midnight","miller","mistress","moderador","monica","monkey","monkey","monster","morgan","mother","mountain","muffin","murphy","mustang","naked","nascar","nathan","naughty","ncc1701","newyork","nicholas","nicole","nipple","nipples","oliver","orange","packers","panther","panties","parker","password","password","password1","password12","password123","patrick","peaches","peanut","pepper","phantom","phoenix","player","please","pookie","porsche","prince","princess","private","purple","pussies","qazwsx","qwerty","qwertyui","rabbit","rachel","racing","raiders","rainbow","ranger","rangers","rebecca","redskins","redsox","redwings","richard","robert","rocket","rosebud","runner","rush2112","russia","samantha","sammy","samson","sandra","saturn","scooby","scooter","scorpio","scorpion","secret","sexsex","shadow","shannon","shaved","sierra","silver","skippy","slayer","smokey","snoopy","soccer","sophie","spanky","sparky","spider","squirt","srinivas","startrek","starwars","steelers","steven","sticky","stupid","success","suckit","summer","sunshine","superman","surfer","swimming","sydney","taylor","tennis","teresa","tester","testing","theman","thomas","thunder","thx1138","tiffany","tigers","tigger","tomcat","topgun","toyota","travis","trouble","trustno1","tucker","turtle","twitter","united","vagina","victor","victoria","viking","voodoo","voyager","walter","warrior","welcome","whatever","william","willie","wilson","winner","winston","winter","wizard","xavier","xxxxxx","xxxxxxxx","yamaha","yankee","yankees","yellow","zxcvbn","zxcvbnm","zzzzzz"],
	datos: new Array(),
	verify: function() {
		var cont = false;
		// Nick
		var nick = $('#nick');
		var vnick = nick.val();
		if(vnick.length<4 || vnick.length>16) {
			nick.addClass('error').focus();
			return this.error('El nombre de usuario debe tener entre 4 y 16 caracteres');
		} else cont = true;
		if(!/[^0-9]/.test(vnick)) {
			nick.addClass('error').focus();
			return this.error('El nombre de usuario no puede contener solo numeros');
		} else cont = true;
		if(/[^a-zA-Z0-9_]/.test(vnick)) {
			nick.addClass('error').focus();
			return this.error('S&oacute;lo se permiten letras, n&uacute;meros y guiones(_) para el nombre de usuario');
		} else cont = true;
		if(cont) {
			nick.removeClass('error');
			this.error(false);
			this.datos['nick'] = vnick;
		}
		// Contrasena
		var pass = $('#password');
		var vpass = pass.val();
		if($.inArray(vpass.toLowerCase(),this.banned_passwords)!=-1) {
			pass.addClass('error').focus();
			return this.error('Ingresa una contrase&ntilde;a m&aacute;s segura');
		} else cont = true;
		if(vpass.toLowerCase() == vnick.toLowerCase()) {
			pass.addClass('error').focus();
			return this.error('La contrase&ntilde;a tiene que ser distinta que el nick');
		} else cont = true;
		if(vpass.length<5 || vpass.length>35) {
			pass.addClass('error').focus();
			return this.error('La contrase&ntilde;a debe tener entre 5 y 35 caracteres');
		} else cont = true;
		if(cont) {
			pass.removeClass('error');
			this.error(false);
			this.datos['password'] = vpass;
		}		
		// Email
		var email = $('#email');
		var vemail = email.val();
		if(empty(vemail)) {
			email.addClass('error').focus();
			return this.error(false);
		} else cont = true;
		if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.exec(vemail)) {
			email.addClass('error').focus();
			return this.error('El formato del correo es incorrecto.');
		} else cont = true;
		if(vemail.length>35) {
			email.addClass('error').focus();
			return this.error('El email es demasiado largo');
		} else cont = true;
		if(cont) {
			email.removeClass('error');
			this.error(false);
			this.datos['email'] = vemail;
		}
		// Sexo
		var sexo = $('#sexo');
		if(empty(sexo.val())) {
			return sexo.addClass('error').focus();
		} else {
			sexo.removeClass('error');
			this.datos['sexo'] = sexo.val();
		}
		// Recaptcha
		var captcha = $('.g-recaptcha');
		if(empty(captcha.val())) {
			return captcha.addClass('error').focus();
		} else {
			captcha.removeClass('error');
			this.datos['g-recaptcha-response'] = captcha.val();
		}
		// Todo bien
		registro.send();
	},
	send: function() {
		$('#registro input[type="submit"]').attr('disabled', 'disabled').addClass('disabled');
		var params = '';
		var amp = '';
		for(var campo in this.datos){
			params += amp + campo + '=' + encodeURIComponent(this.datos[campo]);
			amp = '&';
		}
		$.ajax({
			type: 'POST',
			url: global_data.url + '/registro-nuevo.php',
			data: params,
			success: function(h){
				r = h.split(':');
				// reload captcha
				if(r[0] != 1 || r[0] != 2) {
					h.substring(strpos(h, ':') + 2);
				}
				if(r[0] == '0') {
					registro.error(r[1], true);
				} else if(r[0] == 'recaptcha') {
					registro.error('El c&oacute;digo del captcha es incorrecto');					
				} else if(r[0] == '1' || r[0] == '2') {
					$('#registro').show().html(r[1]);
					$('.welcomeToMySite').append('<a href="'+global_data.web+'" style="display:block;" class="btn_blue">Ver web</a>');
					$('#load').hide();
				} else {
					registro.error(r[1], true);
				}
				$('#registro input[type="submit"]').removeAttr('disabled').removeClass('disabled');
			}
		})
	},
	error: function(msj, foco) {
		if(msj.length > 0) {
			$('#login_error').html(msj).show();
			if(foco) $('#login_error').focus();
			//window.scrollTo(0,0);
		} else {
			$('#login_error').html('').hide();
		}
	}
}