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
jQuery(document).ready(function () {
    jQuery('#form-culqi-settings').submit(function (e) {
        e.preventDefault();
        jQuery('#errorpubkey').html('');
        jQuery('#errorseckey').html('');
        jQuery('#errortimeexp').html('');
        var llavepublica = jQuery('#fullculqi_pubkey').val().split('_');
        var llaveprivada = jQuery('#fullculqi_seckey').val().split('_');
        var isValid = true;
        if(jQuery('#integracion').is(':checked')){
            if(!(llavepublica.length==3 && llavepublica[1]=='test')){
                jQuery('#errorpubkey').html('La llave pública no pertenece al ambiente de integración');
                isValid = false;
            }
            if(!(llaveprivada.length==3 && llaveprivada[1]=='test')){
                jQuery('#errorseckey').html('La llave privada no pertenece al ambiente de integración');
                isValid = false;
            }
        }
        if(jQuery('#produccion').is(':checked')){
            if(!(llavepublica.length==3 && llavepublica[1]=='live')){
                jQuery('#errorpubkey').html('La llave pública no pertenece al ambiente de producción');
                isValid = false;
            }
            if(!(llaveprivada.length==3 && llaveprivada[1]=='live')){
                jQuery('#errorseckey').html('La llave privada no pertenece al ambiente de producción');
                isValid = false;
            }
        }

        if(!(jQuery('#fullculqi_timexp').val()=='' || (jQuery('#fullculqi_timexp').val()>0 && jQuery('#fullculqi_timexp').val().length <= 10 && jQuery('#fullculqi_timexp').val().length > 0))){
            jQuery('#errortimeexp').html('El tiempo de expiración debe ser un valor numérico, mayor a 0 y no mayor a 10 dígitos.');
            isValid = false;
        }

        if(!(jQuery('#fullculqi_methods_tarjeta').is(':checked') || jQuery('#fullculqi_methods_yape').is(':checked') || jQuery('#fullculqi_methods_bancaMovil').is(':checked') || jQuery('#fullculqi_methods_agents').is(':checked') || jQuery('#fullculqi_methods_wallets').is(':checked') || jQuery('#fullculqi_methods_quotedbcp').is(':checked'))){
            jQuery('#errorpaymentmethods').html('Debe seleccionar por lo menos 1 método de pago.');
            isValid = false;
        }
        
        if (jQuery('#fullculqi_rsa_id').val() === '' && jQuery('#fullculqi_rsa_pk').val()){
            jQuery('#errorrsa_id').html('Ingrese el RSA Id.');
            isValid = false;
        }
        
        if (jQuery('#fullculqi_rsa_id').val() && jQuery('#fullculqi_rsa_pk').val() === '' ){
            jQuery('#errorrsa_pk').html('Ingrese el RSA Publickey.');
            isValid = false;
        }

        if (!isValid){
            return false;
        }

        if (jQuery('#fullculqi_tokenlogin').val().length>0) {
            var url_webhook = '';
            var env = '';
            if (jQuery('#integracion').is(':checked')) {
                url_webhook = jQuery('#integracion').data('urlwebhook');
                env = 'test';
            }
            if (jQuery('#produccion').is(':checked')) {
                url_webhook = jQuery('#produccion').data('urlwebhook');
                env = 'live';
            }
            const settings = {
                url: url_webhook,
                crossDomain: true,
                dataType: 'json',
                contentType: 'application/json',
                type: "GET",
                timeout: 0,
                headers: {
                    'Authorization': 'Bearer ' + jQuery('#fullculqi_tokenlogin').val(),
                    //'Access-Control-Allow-Origin': '*',
                    "Content-Type": "application/json",
                    "Accept": "*/*",
                    "x-culqi-env": env
                },
                data: {
                    "merchant": jQuery('#fullculqi_pubkey').val(),
                    "version": 2
                }
            };
            jQuery.ajax(settings).done(function (response) {
                var valid = 1;
                for(let i = 0; i < response.data.length; i++) {
                    if(response.data[i].url==jQuery('#fullculqi_notpay').val() && response.data[i].eventType=="order.status.changed"){
                        valid=0;
                    }
                }
                if(valid==1){
                    const settings = {
                        url: url_webhook,
                        crossDomain: true,
                        dataType: 'json',
                        contentType: 'application/json',
                        type: "POST",
                        timeout: 0,
                        headers: {
                            'Authorization': 'Bearer ' + jQuery('#fullculqi_tokenlogin').val(),
                            "Content-Type": "application/json",
                            "Accept": "*/*",
                            "x-culqi-env": env
                        },
                        data: JSON.stringify({
                            "merchant": jQuery('#fullculqi_pubkey').val(),
                            "eventId": "order.status.changed",
                            "url": jQuery('#fullculqi_notpay').val(),
                            "version": 2,
                            "loginActive": true,
                            "username": jQuery('#fullculqi_username').val(),
                            "password": jQuery('#fullculqi_password').val()
                        }),
                    };
                    jQuery.ajax(settings).done(function (response) {
                        console.log(response);
                        e.currentTarget.submit();
                    });
                }else{
                    e.currentTarget.submit();
                }
            });
        }else{
            e.currentTarget.submit();
        }
    });
    jQuery("#modal_login_form_culqi").submit(function (e) {
        jQuery('div#wpwrap').append('<div id="loadingloginculqi" style="position: fixed; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999999; top: 0; text-align: center; justify-content: center; align-content: center; flex-direction: column; color: white; font-size: 14px; display:table-cell; vertical-align:middle;"><div style="position: absolute; width: 100%; top: 50%">Cargando <img width="14" src="https://icon-library.com/images/loading-icon-transparent-background/loading-icon-transparent-background-12.jpg" /></div></div>');
        e.preventDefault(); 
        const data = jQuery(this).serializeArray();
        const databody = data.reduce((acc, curVal) => {
            return {...acc, [curVal.name]: curVal.value};
        }, {});

        fullculqi_login(databody);
    });

    function fullculqi_login(data) {
        var url_login = '';
        var url_merchant = '';
        var env = '';
        if (jQuery('#integracion').is(':checked')) {
            url_login = jQuery('#integracion').data('urllogin');
            url_merchant = jQuery('#integracion').data('urlmerchant');
            env = 'test';
        }
        if (jQuery('#produccion').is(':checked')) {
            url_login = jQuery('#produccion').data('urllogin');
            url_merchant = jQuery('#produccion').data('urlmerchant');
            env = 'live';
        }
        const settings = {
            url: url_login,
            method: "POST",
            timeout: 0,
            headers: {
                "Content-Type": "application/json",
                "x-culqi-env": env,
            },
            data: JSON.stringify(data),
        };
        jQuery.ajax(settings).done(function (response) {
            console.log(response);
            if(typeof(response.message) != "undefined" && response.message !== null){
                jQuery('#loadingloginculqi').remove();
                jQuery('#errorlogincpanelculqi').html(response.message);
            }else{
                jQuery("#modalLogin").modal("hide");
                window.culqi_token = response.data;
                culqiWoGetMerchants(url_merchant, env);
            }
        });
    }

    const culqiWoGetMerchants = (url_merchant, env) => {
        const settings = {
            url: fullculqi_merchants.url_merchants,
            dataType: "json",
            type: "get",
            timeout: 0,
            data: {
                action: "culqi_merchants",
                token: window.culqi_token,
                url_merchant: url_merchant,
                env : env,
                nonce : fullculqi_merchants.nonce,
            },
        };

        jQuery.ajax(settings).done(function (response) {
            renderMerchants(response.data);
            jQuery("#modalLogin").modal("hide");
            jQuery("#modalList").modal("show");

        });
    };

    const getMerchant = (id) => {
        var url_merchantsingle = '';
        var env = '';
        if (jQuery('#integracion').is(':checked')) {
            url_merchantsingle = jQuery('#integracion').data('urlmerchantsingle');
            env = 'test';
        }
        if (jQuery('#produccion').is(':checked')) {
            url_merchantsingle = jQuery('#produccion').data('urlmerchantsingle');
            env = 'live';
        }
        const settings = {
            url: fullculqi_merchants.url_merchants,
            dataType: "json",
            type: "get",
            timeout: 0,
            data: {
                action: "culqi_merchant",
                token: window.culqi_token,
                public_key: id,
                url_merchant: url_merchantsingle,
                env: env,
                nonce : fullculqi_merchants.nonce,
            },
        };

        jQuery.ajax(settings).done(function (response) {
            window.culqi_settings["private_key"] = response.data;
            renderSettings();
            jQuery("#modalList").modal("hide");
        });
    };

    const renderSettings = () => {
        if (jQuery("#commerce").length) {
            jQuery("#commerce").val(window.culqi_settings["merchant_name"]);
        } else {
            jQuery("#fullculqi_commerce").val(window.culqi_settings["merchant_name"]);
        }
        if (jQuery("#public_key").length) {
            jQuery("#public_key").val(window.culqi_settings["public_key"]);
        } else {
            jQuery("#fullculqi_pubkey").val(window.culqi_settings["public_key"]);
        }
        if (jQuery("#secret_key").length) {
            jQuery("#secret_key").val(window.culqi_settings["private_key"]);
        } else {
            jQuery("#fullculqi_seckey").val(window.culqi_settings["private_key"]);
        }
        jQuery("#fullculqi_tokenlogin").val(window.culqi_token);
        window.culqi_settings = null;
    };

    const renderMerchants = (merchants) => {
        let html = "";
        merchants.forEach((merchant) => {
            html += `
        <li>
          <div class="items">
            <div class="merchant_item" data-name="${merchant.nombre_comercial}" data-key='${merchant.codigo_comercio}'>
              <div class="merchant_logo">
                <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_135_620)">
                  <path d="M11.6701 18.3208V19.625H2.46777V8.49707C2.8659 8.69858 3.3209 8.81613 3.79296 8.83852V18.3264H11.6701V18.3208Z" fill="white"/>
                  <path d="M23.244 0.38623V5.56957C23.244 6.50436 22.8459 7.34959 22.2032 7.94293V19.625H12.9952V18.3208H20.8837V8.6986C20.5823 8.78816 20.2524 8.83854 19.9168 8.83854C18.8248 8.83854 17.858 8.31797 17.2551 7.51752C16.6522 8.31797 15.6853 8.83854 14.5933 8.83854C13.5013 8.83854 12.5288 8.31797 11.9259 7.51752C11.414 8.18922 10.6462 8.67061 9.75328 8.79936C9.59403 8.82175 9.4291 8.83854 9.26416 8.83854V7.53431C9.36654 7.53431 9.46322 7.52871 9.55991 7.51192C10.5268 7.37198 11.2605 6.53794 11.2605 5.56957V3.35294H12.5857V5.56957C12.5857 6.65549 13.4786 7.53431 14.582 7.53431C15.6797 7.53431 16.5783 6.65549 16.5783 5.56957V3.35294H17.9035V5.56957C17.9035 6.65549 18.8021 7.53431 19.8998 7.53431C21.0032 7.53431 21.8961 6.65549 21.8961 5.56957V0.38623H23.244Z" fill="white"/>
                  <path d="M12.9952 14.4416V19.625H11.67V14.4416C11.67 13.3053 10.7316 12.3761 9.57134 12.3761C8.41677 12.3761 7.47265 13.2997 7.47265 14.4416V18.3207H6.14746V14.4416C6.14746 12.5832 7.6774 11.0719 9.56565 11.0719C11.4596 11.0719 12.9952 12.5832 12.9952 14.4416Z" fill="white"/>
                  <path d="M19.4337 16.0034H14.4287V11.0775H19.4337V16.0034ZM15.7539 14.6991H18.1085V12.3817H15.7539V14.6991Z" fill="white"/>
                  <path d="M1.95019 1.68924V5.56835C1.95019 6.65427 2.84312 7.53309 3.9465 7.53309C5.04419 7.53309 5.94281 6.65427 5.94281 5.56835V3.35172H7.268V5.56835C7.268 6.65427 8.16662 7.53309 9.26431 7.53309V8.83732C8.17231 8.83732 7.20544 8.31675 6.60256 7.5163C5.99969 8.31675 5.03281 8.83732 3.94081 8.83732C3.88962 8.83732 3.83275 8.83732 3.78156 8.82613C3.30381 8.80933 2.8545 8.68619 2.45637 8.48468C1.37575 7.9585 0.625 6.85019 0.625 5.56835V0.38501H21.9133V1.68924H1.95019Z" fill="white"/>
                  <path d="M23.2388 0.375H21.9136V0.386195H23.2388V0.375Z" fill="white"/>
                  </g>
                  <defs>
                  <clipPath id="clip0_135_620">
                  <rect width="22.75" height="19.25" fill="white" transform="translate(0.625 0.375)"/>
                  </clipPath>
                  </defs>
                </svg>
              </div>
              <div class="merchant_name">
              ${merchant.nombre_comercial}
              </div>
              <div class="merchant_arrow">
                <svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.95079 5.23163L1.71079 0.341176C1.61783 0.233069 1.50723 0.147262 1.38537 0.0887049C1.26351 0.0301478 1.1328 0 1.00079 0C0.868781 0 0.738075 0.0301478 0.616216 0.0887049C0.494356 0.147262 0.383755 0.233069 0.290792 0.341176C0.104542 0.557282 0 0.849616 0 1.15433C0 1.45904 0.104542 1.75138 0.290792 1.96748L3.83079 6.05055L0.290792 10.1336C0.104542 10.3497 0 10.6421 0 10.9468C0 11.2515 0.104542 11.5438 0.290792 11.7599C0.384233 11.8668 0.495049 11.9514 0.616886 12.0088C0.738724 12.0662 0.869186 12.0953 1.00079 12.0944C1.1324 12.0953 1.26286 12.0662 1.3847 12.0088C1.50654 11.9514 1.61735 11.8668 1.71079 11.7599L5.95079 6.86947C6.04452 6.76225 6.11891 6.63468 6.16968 6.49413C6.22045 6.35357 6.24659 6.20282 6.24659 6.05055C6.24659 5.89829 6.22045 5.74753 6.16968 5.60698C6.11891 5.46642 6.04452 5.33886 5.95079 5.23163Z" fill="#B1B8C1"/>
                </svg>
              </div>
            </div>
          </div>
        </li>`;
        });
        jQuery("#list-merchants").html(html);
        jQuery("#modalLogin").modal("hide");
        setTimeout(function (){
            jQuery('#loadingloginculqi').remove();
        }, 3000)

        jQuery(".merchant_item").click(function () {
            const key = jQuery(this).attr("data-key");
            const name = jQuery(this).attr("data-name");
            window.culqi_settings = {
                public_key: key,
                merchant_name: name,
            };
            getMerchant(key);
        });
    };
});