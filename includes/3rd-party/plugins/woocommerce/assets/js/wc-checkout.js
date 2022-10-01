/**
 * @license
 * three.js - JavaScript 3D library
 * Copyright 2016 The three.js Authors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
Culqi3DS.options = {
	closeModalAction: () => window.location.reload(true), // ACTION CUANDO SE CIERRA EL MODAL
};
window.addEventListener("message", async function (event) {

	if (event.origin === window.location.origin) {
		const { parameters3DS, error } = event.data;

		if (parameters3DS) {
			window.fullculqi.payProcess3DS(parameters3DS);
		}

		if (error) {

		}
	}
}, false );
Culqi3DS.publicKey = fullculqi_vars.public_key;
var device = await Culqi3DS.generateDevice();

(function ($) {


	const FullCulqi = {

		/**
		 * Start the engine.
		 *
		 * @since 2.0.0
		 */



		init: function () {

			$(document).ready(FullCulqi.ready);

			$(window).on('load', FullCulqi.load);

		},
		/**
		 * Document ready.
		 *
		 * @since 2.0.0
		 */
		ready: function () {
			// Execute
			FullCulqi.executeUIActions();
		},
		/**
		 * Page load.
		 *
		 * @since 2.0.0
		 */
		load: function () {
			FullCulqi.bindUIActions();
		},

		/**
		 * Execute when the page is loaded
		 * @return mixed
		 */
		executeUIActions: function() {

			FullCulqi.setSettings();
			FullCulqi.setOptions();
			FullCulqi.timeModal();
		},

		/**
		 * Element bindings.
		 *
		 * @since 2.0.0
		 */
		bindUIActions: function () {
			
			$('#fullculqi_button').on('click', function (e) {
				e.preventDefault();
				FullCulqi.openModal();
			});
		},
		/**
		 * Check if the browser is Safari
		 * @return bool
		 */
		isSafari: function() {
			return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
		},
		/**
		 * Set Culqi Settings
		 * @return mixed
		 */
		setSettings: function() {
			Culqi.publicKey = fullculqi_vars.public_key;
			let args_settings = {
				title: fullculqi_vars.commerce,
				currency: fullculqi_vars.currency,
				//description: fullculqi_vars.description,
				amount: fullculqi_vars.total,
				culqiclient: 'woocommerce',
				culqiclientversion: fullculqi_vars.version_wc
			};
			console.log(fullculqi_vars.multi_order+':: el orderid');
			if( fullculqi_vars.multi_order != '' ) {
				args_settings.order = fullculqi_vars.multi_order;
			}
			console.log(args_settings);
			Culqi.settings( args_settings );
		},
		/**
		 * Set Culqi Options 
		 * @return mixed
		 */
		setOptions: function() {

			let args_options = {};
			args_options.lang = fullculqi_vars.lang;

			args_options.paymentMethods = {
				tarjeta: fullculqi_vars.methods.tarjeta,
				yape: fullculqi_vars.methods.yape,
				billetera: fullculqi_vars.methods.billetera,
				bancaMovil: fullculqi_vars.methods.bancaMovil,
				agente: fullculqi_vars.methods.agente,
				cuotealo: fullculqi_vars.methods.cuetealo
			};

			args_options.style = {
				bannerColor: '#715091', // hexadecimal
				imageBanner: '', 
				buttonBackground: '#25a69f', // hexadecimal
				menuColor: '#715091', // hexadecimal
				linksColor: '#00A19B', // hexadecimal
				buttontext: '#ffffff', // hexadecimal
				priceColor: '', // hexadecimal
			}

			args_options.installments = true;
				args_options.style.logo = fullculqi_vars.url_logo;
			if(fullculqi_vars.color_palette.length > 0 ){
				var colors = fullculqi_vars.color_palette.split('-');
				args_options.style.bannerColor=colors[0];
				args_options.style.linksColor=colors[1];
				args_options.style.menuColor=colors[1];
				args_options.style.priceColor=colors[1];
				args_options.style.buttonBackground=colors[1];
			}
			console.log(args_options);
			if( Object.keys( args_options ).length > 0 ) {
				Culqi.options( args_options );
			}
		},
		/**
		 * Time to open modal
		 * @return mixed
		 */
		timeModal: function() {
			if( ! FullCulqi.isSafari() && fullculqi_vars.time_modal > 0 ) {
				setTimeout(function() {
					FullCulqi.openModal();
				}, fullculqi_vars.time_modal);
			}
		},
		/**
		 * Open Modal
		 * @return mixed
		 */
		openModal: function() {
			//console.log(Culqi.open());
			Culqi.open();
			$('#culqi_notify').empty();
		},
		/**
		 * waitMe to Ajax
		 * @return mixed
		 */
		waitMe: function() {
			$( document ).ajaxStart( function() {
				$('#fullculqi_notify').removeClass('woocommerce-error').empty();
				
				$('#page').waitMe({
					effect		: 'pulse',
					text 		: fullculqi_vars.loading_text,
					bg			: 'rgba(0,0,0,0.7)',
					color		: '#FFFFFF',
					maxSize		: '',
					waitTime	: -1,
					textPos		: 'vertical',
					fontSize	: '',
					source		: '',
					onClose : function() {},
				});
			});

			$( document ).ajaxComplete( function() {
				$('#page').waitMe('hide');
			});
		},
		/*
		* Generate Token
		 * @return mixed
		 */
		generateToken: function() {
			console.log('generateToken:::');
			console.log('Culqi:::', Culqi);
			if (Culqi.token) { 
				var token = Culqi.token.id;
			} else {
				console.log(Culqi.error);
			}
		},
		/**
		 * Pay Process
		 * @return mixed
		 */
		payProcess: function() {
			if( Culqi.error ) {
				$('#fullculqi_notify').addClass('woocommerce-error').html( Culqi.error.merchant_message );
			} else {
				console.log('device:::::::'+device);
				let data;
				var enviroment = fullculqi_vars.enviroment.split('|');
				if( Culqi.order ) {
					data = {
						action 		: 'order',
						id 			: Culqi.order.id,
						cip_code	: Culqi.order.payment_code,
						order_id	: Culqi.order.metadata.order_id,
						wpnonce		: fullculqi_vars.wpnonce,
						enviroment		: enviroment[0],
						device			: device
					};

				} else if( Culqi.token ) {
					Culqi.close();
					jQuery('body').append('<div id="loadingloginculqi" style="position: fixed; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999999; top: 0; text-align: center; justify-content: center; align-content: center; flex-direction: column; color: white; font-size: 14px; display:table-cell; vertical-align:middle;"><div style="position: absolute; width: 100%; top: 50%">Cargando <img style="display: inline-block" width="14" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" /></div></div>');
					data = {
						action 			: 'charge',
						token_id		: Culqi.token.id,
						order_id 		: fullculqi_vars.order_id,
						country_code	: Culqi.token.client.ip_country_code,
						installments	: Culqi.token.metadata.installments,
						wpnonce			: fullculqi_vars.wpnonce,
						enviroment		: enviroment[0],
						device			: device,
						email			: Culqi.token.email
					};
				}
				FullCulqi.loadAjax( data );
			}
		},

		payProcess3DS: function(parameters3DS) {
			if( Culqi.error ) {
				$('#fullculqi_notify').addClass('woocommerce-error').html( Culqi.error.merchant_message );
			} else {
				console.log('device:::::::'+device);
				jQuery('body').append('<div id="loadingloginculqi" style="position: fixed; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999999; top: 0; text-align: center; justify-content: center; align-content: center; flex-direction: column; color: white; font-size: 14px; display:table-cell; vertical-align:middle;"><div style="position: absolute; width: 100%; top: 50%">Cargando <img style="display: inline-block" width="14" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" /></div></div>');
				let data;
				var enviroment = fullculqi_vars.enviroment.split('|');
				if( Culqi.order ) {
					data = {
						action 		: 'order',
						id 			: Culqi.order.id,
						cip_code	: Culqi.order.payment_code,
						order_id	: Culqi.order.order_number,
						wpnonce		: fullculqi_vars.wpnonce,
						enviroment		: enviroment[0],
						device			: device,
						parameters3DS	: parameters3DS
					};

				} else if( Culqi.token ) {
					data = {
						action 			: 'charge',
						token_id		: Culqi.token.id,
						order_id 		: fullculqi_vars.order_id,
						country_code	: Culqi.token.client.ip_country_code,
						installments	: Culqi.token.metadata.installments,
						wpnonce			: fullculqi_vars.wpnonce,
						enviroment		: enviroment[0],
						device			: device,
						parameters3DS	: parameters3DS,
						email			: Culqi.token.email
					};
				}
				FullCulqi.loadAjax3DS( data );
			}
		},

		/**
		 * Load to Ajax
		 * @param  objet post_data
		 * @return mixed
		 */
		loadAjax: function( post_data ) {
			$.ajax({
				url 		: fullculqi_vars.url_actions,
				type 		: 'POST',
				dataType	: 'json',
				data 		: post_data,

				success: function( response ) {
					$( document.body ).trigger('fullculqi.checkout.success', [ post_data, response ]);
					console.log(response);
					if( response.success ) {
						var enviroment = fullculqi_vars.enviroment.split('|');
						$('#fullculqi_notify').empty();
						if(Culqi.token==null){
							var interval = setInterval(function(){
								if(!Culqi.isOpen){
									location.href = fullculqi_vars.url_success;
									clearInterval(interval);
								}
							}, 1000);
						}else{
							console.log(response);
							location.href = fullculqi_vars.url_success;
						}

					} else {
						console.log(response);
						Culqi.close();
						if(response.data.message!='' && response.data.message!=null){
							jQuery('#loadingloginculqi').remove();
							console.log(response.data.message);
							if(response.data.message=='REVIEW'){
								console.log('abriendo 3DS');
								Culqi3DS.settings = {
									charge: {
										currency: fullculqi_vars.currency,
										totalAmount: fullculqi_vars.total,
										returnUrl: fullculqi_vars.url //URL DEL CHECKOUT DEL COMERCIO
								},
									card: {
										email: Culqi.token.email,
									}
								};
								Culqi3DS.initAuthentication(Culqi.token.id);
							}else{
								$('#fullculqi_notify').addClass('woocommerce-error').html( response.data.message);
							}

						}else{
							$('#fullculqi_notify').addClass('woocommerce-error').html( 'Ha ocurrido un error procesando el pago. Por favor intente nuevamente o comuníquese con su entidad bancaria.');
						}

					}			
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('jqXHR:::', jqXHR);
					console.log('textStatus:::',textStatus);
					console.log('errorThrown:::',errorThrown);
					$('#fullculqi_notify').empty();
					$('#fullculqi_notify').addClass('woocommerce-error').html( fullculqi_vars.msg_error );
					$( document.body ).trigger('fullculqi.checkout.error', [ post_data, jqXHR, textStatus, errorThrown ] );
					Culqi.close();
				}
			});
		},
		loadAjax3DS: function( post_data ) {
			console.log('enviando al cargo con 3DS');
			console.log(post_data);
			$.ajax({
				url 		: fullculqi_vars.url_actions,
				type 		: 'POST',
				dataType	: 'json',
				data 		: post_data,

				success: function( response ) {

					$( document.body ).trigger('fullculqi.checkout.success', [ post_data, response ]);

						var enviroment = fullculqi_vars.enviroment.split('|');
						$('#fullculqi_notify').empty();
						if(Culqi.token==null){
							var interval = setInterval(function(){
								if(!Culqi.isOpen){
									location.href = fullculqi_vars.url_success;
									clearInterval(interval);
								}
							}, 1000);
						}else{
							console.log(response);
							//alert('stop');
							location.href = fullculqi_vars.url_success;
						}


				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('jqXHR:::', jqXHR);
					console.log('textStatus:::',textStatus);
					console.log('errorThrown:::',errorThrown);
					$('#fullculqi_notify').empty();
					$('#fullculqi_notify').addClass('woocommerce-error').html( fullculqi_vars.msg_error );
					$( document.body ).trigger('fullculqi.checkout.error', [ post_data, jqXHR, textStatus, errorThrown ] );
					Culqi.close();
				}
			});
		}
	};

	FullCulqi.init();
	console.log('device:::'+device);
	window.fullculqi = FullCulqi;
	window.load = FullCulqi.load();
	window.culqi = culqi;
})(jQuery);


function culqi() {
	window.fullculqi.payProcess();
}