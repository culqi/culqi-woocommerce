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
}, false);
Culqi3DS.publicKey = fullculqi_vars.public_key;

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
		executeUIActions: function () {
			FullCulqi.setCheckout();
		},

		/**
		 * Element bindings.
		 *
		 * @since 2.0.0
		 */
		bindUIActions: function () {
			//var device = await Culqi3DS.generateDevice();
			const device_aux = Promise.resolve(Culqi3DS.generateDevice());
			device_aux.then(value => {
				window.device = value;
				FullCulqi.openModal();
			}).catch(err => {
				console.log(err);
			});
		},
		/**
		 * Check if the browser is Safari
		 * @return bool
		 */
		isSafari: function () {
			return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
		},
		/**
		 * Configuration of Checkout
		 */
		setCheckout: function () {
			const publicKey = FullCulqi.setPublicKey();
			const config = FullCulqi.setConfigCheckout();
			Culqi = new CulqiCheckout(publicKey, config);
			Culqi.culqi = culqi
		},
		setConfigCheckout: function () {
			const settings = FullCulqi.setSetting();
			const client = FullCulqi.setClient();
			const options = FullCulqi.setOptions();
			const appearance = FullCulqi.setAppearance();


			const config = {
				settings,
				client,
				options,
				appearance
			};

			return config


		},
		setPublicKey: function () {
			return fullculqi_vars.public_key
		},
		setSetting: function () {
			const setting = {
				title: fullculqi_vars.commerce,
				currency: fullculqi_vars.currency,
				amount: fullculqi_vars.total,
				culqiclient: 'woocommerce',
				culqiclientversion: fullculqi_vars.version_wc,
				culqipluginversion: fullculqi_vars.version_plugin
			}

			// Agregar el orden si existe
			if (fullculqi_vars.multi_order != '') {
				setting.order = fullculqi_vars.multi_order;
			}

			// Agregar llave publica si existe
			if (fullculqi_vars.rsa_id && fullculqi_vars.rsa_pk) {
				setting.xculqirsaid = fullculqi_vars.rsa_id;
				setting.rsapublickey = fullculqi_vars.rsa_pk;
			}

			return setting;
		},
		setClient: function () {
			const client = {
				email: fullculqi_vars.culqi_customer_email

			};

			return client;
		},
		setPaymentMethods: function () {
			const paymentMethods = {
				tarjeta: fullculqi_vars.methods.tarjeta,
				yape: fullculqi_vars.methods.yape,
				billetera: fullculqi_vars.methods.billetera,
				bancaMovil: fullculqi_vars.methods.bancaMovil,
				agente: fullculqi_vars.methods.agente,
				cuotealo: fullculqi_vars.methods.cuetealo
			}

			return paymentMethods;
		},

		setOptions: function () {
			const paymentMethods = FullCulqi.setPaymentMethods();

			const options = {
				lang: fullculqi_vars.lang,
				installments: true,
				modal: true,
				paymentMethods,
				paymentMethodsSort: Object.keys(paymentMethods)
			}
			return options;
		},
		setAppearance: function () {
			const appearence = {
				theme: "default",
				hiddenCulqiLogo: false,
				hiddenBannerContent: false,
				hiddenBanner: false,
				hiddenToolBarAmount: false,
				menuType: "sidebar", // sidebar / sliderTop / select
				logo: fullculqi_vars.url_logo
			}


			if (fullculqi_vars.color_palette.length > 0) {
				const defaultStyle = {};
				const colors = fullculqi_vars.color_palette.split('-');
				defaultStyle.bannerColor = colors[0];
				defaultStyle.buttonBackground = colors[1];
				defaultStyle.menuColor = colors[1];
				defaultStyle.linksColor = colors[1];
				defaultStyle.priceColor = colors[1];

				appearence.defaultStyle = defaultStyle;
			}
			return appearence;
		},
		/**
		 * Time to open modal
		 * @return mixed
		 */
		timeModal: function () {
			if (!FullCulqi.isSafari() && fullculqi_vars.time_modal > 0) {
				setTimeout(function () {
					FullCulqi.openModal();
				}, fullculqi_vars.time_modal);
			}
		},
		/**
		 * Open Modal
		 * @return mixed
		 */
		openModal: function () {
			//console.log(Culqi.open());
			Culqi.open();
			$('#culqi_notify').empty();
			jQuery('#place_order').removeAttr("disabled");
			jQuery('#loadingloginculqi').remove();
		},
		/**
		 * waitMe to Ajax
		 * @return mixed
		 */
		waitMe: function () {
			$(document).ajaxStart(function () {
				$('#fullculqi_notify').removeClass('woocommerce-error').empty();

				$('#page').waitMe({
					effect: 'pulse',
					text: fullculqi_vars.loading_text,
					bg: 'rgba(0,0,0,0.7)',
					color: '#FFFFFF',
					maxSize: '',
					waitTime: -1,
					textPos: 'vertical',
					fontSize: '',
					source: '',
					onClose: function () { },
				});
			});

			$(document).ajaxComplete(function () {
				$('#page').waitMe('hide');
			});
		},
		/*
		* Generate Token
		 * @return mixed
		 */
		generateToken: function () {
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
		payProcess: function () {
			console.log('payProcess:::');
			if (Culqi.error) {
				$('#fullculqi_notify').addClass('woocommerce-error').html(Culqi.error.merchant_message);
				scrollToCulqiError();
			} else {
				console.log('device:::::::' + device);
				let data;
				var enviroment = fullculqi_vars.enviroment.split('|');
				if (Culqi.order) {
					data = {
						action: 'order',
						id: Culqi.order.id,
						cip_code: Culqi.order.payment_code,
						order_id: Culqi.order.metadata.order_id,
						order_number: Culqi.order.order_number,
						wpnonce: fullculqi_vars.wpnonce,
						enviroment: enviroment[0],
						device: device
					};

				} else if (Culqi.token) {
					Culqi.close();
					jQuery('body').append('<div id="loadingloginculqi" style="position: fixed; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999999; top: 0; text-align: center; justify-content: center; align-content: center; flex-direction: column; color: white; font-size: 14px; display:table-cell; vertical-align:middle;"><div style="position: absolute; width: 100%; top: 50%">Cargando <img style="display: inline-block" width="14" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" /></div></div>');
					data = {
						action: 'charge',
						token_id: Culqi.token.id,
						order_id: fullculqi_vars.order_id,
						country_code: Culqi.token.client.ip_country_code,
						installments: (Culqi.token.metadata == undefined || Culqi.token.metadata.installments == undefined) ? 0 : Culqi.token.metadata.installments,
						wpnonce: fullculqi_vars.wpnonce,
						enviroment: enviroment[0],
						device: device,
						email: Culqi.token.email
					};
				}
				FullCulqi.loadAjax(data);
			}
		},

		payProcess3DS: function (parameters3DS) {
			console.log('payProcess3DS:::');

			if (Culqi.error && Culqi.error.merchant_message) {
				$('#fullculqi_notify').addClass('woocommerce-error').html(Culqi.error.merchant_message);
				scrollToCulqiError();
			} else {
				console.log('device:::::::' + device);
				jQuery('body').append('<div id="loadingloginculqi" style="position: fixed; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999999; top: 0; text-align: center; justify-content: center; align-content: center; flex-direction: column; color: white; font-size: 14px; display:table-cell; vertical-align:middle;"><div style="position: absolute; width: 100%; top: 50%">Cargando <img style="display: inline-block" width="14" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" /></div></div>');
				let data;
				var enviroment = fullculqi_vars.enviroment.split('|');
				if (Culqi.order) {
					data = {
						action: 'order',
						id: Culqi.order.id,
						cip_code: Culqi.order.payment_code,
						order_id: Culqi.order.order_number,
						wpnonce: fullculqi_vars.wpnonce,
						enviroment: enviroment[0],
						device: device,
						parameters3DS: parameters3DS
					};

				} else if (Culqi.token) {
					data = {
						action: 'charge',
						token_id: Culqi.token.id,
						order_id: fullculqi_vars.order_id,
						country_code: Culqi.token.client.ip_country_code,
						installments: (Culqi.token.metadata == undefined || Culqi.token.metadata.installments == undefined) ? 0 : Culqi.token.metadata.installments,
						wpnonce: fullculqi_vars.wpnonce,
						enviroment: enviroment[0],
						device: device,
						parameters3DS: parameters3DS,
						email: Culqi.token.email
					};
				}
				FullCulqi.loadAjax3DS(data);
			}
		},

		/**
		 * Load to Ajax
		 * @param  objet post_data
		 * @return mixed
		 */
		loadAjax: function (post_data) {
			$.ajax({
				url: fullculqi_vars.url_actions,
				type: 'POST',
				dataType: 'json',
				data: post_data,

				success: function (response) {
					$(document.body).trigger('fullculqi.checkout.success', [post_data, response]);
					if (response.success) {
						var enviroment = fullculqi_vars.enviroment.split('|');
						$('#fullculqi_notify').empty();
						if (Culqi.token == null) {
							var interval = setInterval(function () {
								if (!Culqi.isOpen) {
									location.href = fullculqi_vars.url_success;
									clearInterval(interval);
								}
							}, 1000);
						} else {
							if (response.data.charge.data.card_brand.toUpperCase() == "MASTERCARD") {
								jQuery("body").append('<div id= "d_mc-sonic"><mc-sonic id="mc-sonic"  clear-background></mc-sonic></div>');
								jQuery('#loadingloginculqi').remove();
								FullCulqi.playSonic(fullculqi_vars.url_success);
							} else if (response.data.charge.data.card_brand.toUpperCase() == "VISA") {  
								const url_plugin = visa_lib.plugin_url; 
								jQuery("body").append('<link id="visa-css" rel="stylesheet" href="' + url_plugin + 'css/visa.css?_=' + new Date().getTime() + '">');
								jQuery("body").children().first().before('<div id="visa-branding"><div class="flag-container"><div class="flag-mask-top flag-mask"></div><div class="constrained-flag-mask constrained-top-flag-mask"></div><div class="flag-slider flag-slider-top"><img class="top-flag flag"></div><div class="top-flag-fade-mask flag-fade-mask"></div></div><div class="visa-container"><div class="visa-wrapper"><img class="visa-logo"><div class="wipers-container"><div class="wiper-left wiper"></div><div class="wiper-middle wiper"></div><div class="wiper-right wiper"></div></div><div class="checkmark-container"><img class="checkmark-circle"><div class="rotate-container"><img class="checkmark"><div class="checkmark-mask"></div></div></div></div></div><div class="flag-container"><div class="flag-mask-bottom flag-mask"></div><div class="constrained-flag-mask constrained-bottom-flag-mask"></div><div class="flag-slider flag-slider-bottom"><img class="bottom-flag flag"></div><div class="bottom-flag-fade-mask flag-fade-mask"></div></div></div>');
								jQuery("body").append('<script id="visa-js" src="' + url_plugin + 'js/visa-sonic-min.js?_=' + new Date().getTime() + '"></script>');
								FullCulqi.stopVisa(fullculqi_vars.url_success);
							}
							else {
								location.href = fullculqi_vars.url_success;
							}
						}

					} else {
						console.log(response);
						Culqi.close();
						if (response.data.message != '' && response.data.message != null) {
							jQuery('#loadingloginculqi').remove();
							console.log(response.data.message);
							if (response.data.message == 'REVIEW') {
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
							} else {
								$('#fullculqi_notify').addClass('woocommerce-error').html(response.data.message);
								scrollToCulqiError();
							}

						} else {
							jQuery('#loadingloginculqi').remove();
							$('#fullculqi_notify').addClass('woocommerce-error').html('Ha ocurrido un error procesando el pago. Por favor intente nuevamente o comuníquese con su entidad bancaria.');
							scrollToCulqiError();
						}

					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('jqXHR:::', jqXHR);
					console.log('textStatus:::', textStatus);
					console.log('errorThrown:::', errorThrown);
					$('#fullculqi_notify').empty();
					$('#fullculqi_notify').addClass('woocommerce-error').html(fullculqi_vars.msg_error);
					$(document.body).trigger('fullculqi.checkout.error', [post_data, jqXHR, textStatus, errorThrown]);
					Culqi.close();
					scrollToCulqiError();
				}
			});
		},
		loadAjax3DS: function (post_data) {
			console.log('enviando al cargo con 3DS');
			console.log(post_data);
			$.ajax({
				url: fullculqi_vars.url_actions,
				type: 'POST',
				dataType: 'json',
				data: post_data,

				success: function (response) {
					if (response.success) {
						$(document.body).trigger('fullculqi.checkout.success', [post_data, response]);
						var enviroment = fullculqi_vars.enviroment.split('|');
						$('#fullculqi_notify').empty();
						if (Culqi.token == null) {
							var interval = setInterval(function () {
								if (!Culqi.isOpen) {
									location.href = fullculqi_vars.url_success;
									clearInterval(interval);
								}
							}, 1000);
						} else {
							if (response.data.charge.data.card_brand.toUpperCase() == "MASTERCARD") {
								jQuery("body").append('<div id= "d_mc-sonic"><mc-sonic id="mc-sonic"  clear-background></mc-sonic></div>');
								jQuery('#loadingloginculqi').remove();
								FullCulqi.playSonic(fullculqi_vars.url_success);
							} else if (response.data.charge.data.card_brand.toUpperCase() == "VISA") {
								const url_plugin = visa_lib.plugin_url; 
								jQuery("body").append('<link id="visa-css" rel="stylesheet" href="' + url_plugin + 'css/visa.css?_=' + new Date().getTime() + '">');
								jQuery("body").children().first().before('<div id="visa-branding"><div class="flag-container"><div class="flag-mask-top flag-mask"></div><div class="constrained-flag-mask constrained-top-flag-mask"></div><div class="flag-slider flag-slider-top"><img class="top-flag flag"></div><div class="top-flag-fade-mask flag-fade-mask"></div></div><div class="visa-container"><div class="visa-wrapper"><img class="visa-logo"><div class="wipers-container"><div class="wiper-left wiper"></div><div class="wiper-middle wiper"></div><div class="wiper-right wiper"></div></div><div class="checkmark-container"><img class="checkmark-circle"><div class="rotate-container"><img class="checkmark"><div class="checkmark-mask"></div></div></div></div></div><div class="flag-container"><div class="flag-mask-bottom flag-mask"></div><div class="constrained-flag-mask constrained-bottom-flag-mask"></div><div class="flag-slider flag-slider-bottom"><img class="bottom-flag flag"></div><div class="bottom-flag-fade-mask flag-fade-mask"></div></div></div>');
								jQuery("body").append('<script id="visa-js" src="' + url_plugin + 'js/visa-sonic-min.js?_=' + new Date().getTime() + '"></script>');
								FullCulqi.stopVisa(fullculqi_vars.url_success);
							} else {
								location.href = fullculqi_vars.url_success;
							}
						}
					} else {
						jQuery('#loadingloginculqi').remove();
						if (response.data && response.data.message) {
							$('#fullculqi_notify').addClass('woocommerce-error').html(response.data.message);
							scrollToCulqiError();
						} else {
							$('#fullculqi_notify').addClass('woocommerce-error').html('Ha ocurrido un error procesando el pago. Por favor intente nuevamente o comuníquese con su entidad bancaria.');
							scrollToCulqiError();
						}
					}


				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('jqXHR:::', jqXHR);
					console.log('textStatus:::', textStatus);
					console.log('errorThrown:::', errorThrown);
					$('#fullculqi_notify').empty();
					$('#fullculqi_notify').addClass('woocommerce-error').html(fullculqi_vars.msg_error);
					$(document.body).trigger('fullculqi.checkout.error', [post_data, jqXHR, textStatus, errorThrown]);
					Culqi.close();
					scrollToCulqiError();
				}
			});
		},

		stopVisa: function (succes_url) { 
			jQuery('#loadingloginculqi').remove(); 
			document.addEventListener('sonicCompletion', FullCulqi.onCompletion(succes_url));
			
			setTimeout(function() { 
				jQuery("#visa-css").remove();
				jQuery("#visa-branding").remove();
				jQuery("#visa-js").remove();
			}, 1500);
			
		},

		playSonic: function (succes_url) {
			const mc_component = document.getElementById("mc-sonic");
			console.log(mc_component);
			document.addEventListener('sonicCompletion', FullCulqi.onCompletion(succes_url));
			mc_component.play();
		},

		onCompletion: function (succes_url) {
			console.log("Sonic success");
			setTimeout(() => {
				location.href = succes_url;
			}, 2000);
		}
	};

	FullCulqi.init();
	window.fullculqi = FullCulqi;
	window.load = FullCulqi.load();
	window.culqi = culqi;
})(jQuery);


function culqi() {
	setTimeout(function () {
		window.fullculqi.payProcess();
	}, 10);
}

function scrollToCulqiError() {
	var scrollPos = jQuery("#fullculqi_notify").offset().top - 50;
	jQuery(window).scrollTop(scrollPos);
}