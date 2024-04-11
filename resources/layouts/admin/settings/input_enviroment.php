<input type="radio" name="fullculqi_options[enviroment]" 
    value="<?php echo esc_url( MPCULQI_URLAPI_ORDERCHARGES_INTEG ) . '|' .
                    esc_url( MPCULQI_URLAPI_CHECKOUT_INTEG ) . '|' .
                    esc_url( MPCULQI_URLAPI_INTEG_3DS ) . '|integ'; ?>"
    <?php echo ( $enviroment === esc_url( MPCULQI_URLAPI_ORDERCHARGES_INTEG ) . '|' .
                    esc_url( MPCULQI_URLAPI_CHECKOUT_INTEG ) . '|' .
                    esc_url( MPCULQI_URLAPI_INTEG_3DS ) . '|integ'
                || $enviroment === ''
                || is_null( $enviroment ) ) ? 'checked="true"' : ''; ?>
    id="integracion"
    data-urllogin="<?php echo esc_url( MPCULQI_URLAPI_LOGIN_INTEG ); ?>"
    data-urlmerchant="<?php echo esc_url( MPCULQI_URLAPI_MERCHANT_INTEG ); ?>"
    data-urlmerchantsingle="<?php echo esc_url( MPCULQI_URLAPI_MERCHANTSINGLE_INTEG ); ?>"
    data-urlwebhook="<?php echo esc_url( MPCULQI_URLAPI_WEBHOOK_INTEG ); ?>" />
    <label for="integracion">Integración</label>
<input style="margin-left: 25px" type="radio" name="fullculqi_options[enviroment]"
    value="<?php echo esc_url( MPCULQI_URLAPI_ORDERCHARGES_PROD ) . '|' .
                    esc_url( MPCULQI_URLAPI_CHECKOUT_PROD ) . '|' .
                    esc_url( MPCULQI_URLAPI_PROD_3DS ) . '|prod'; ?>"
    <?php echo ( $enviroment === esc_url( MPCULQI_URLAPI_ORDERCHARGES_PROD ) . '|' .
                esc_url( MPCULQI_URLAPI_CHECKOUT_PROD ) . '|' .
                esc_url( MPCULQI_URLAPI_PROD_3DS ) . '|prod' ) ? 'checked="true"' : ''; ?>
    id="produccion"
    data-urllogin="<?php echo esc_url( MPCULQI_URLAPI_LOGIN_PROD ); ?>"
    data-urlmerchant="<?php echo esc_url( MPCULQI_URLAPI_MERCHANT_PROD ); ?>"
    data-urlmerchantsingle="<?php echo esc_url( MPCULQI_URLAPI_MERCHANTSINGLE_PROD ); ?>"
    data-urlwebhook="<?php echo esc_url( MPCULQI_URLAPI_WEBHOOK_PROD ); ?>" />
    <label for="produccion"> Producción</label>
</td>
</tr>
<tr>
<td colspan="2" style="padding-left: 0">

<div class="logincontainer">

    <span id="spanbuttonlogin" style="color: #1d2327"><b>¡Ahorra tiempo configurando tu Culqi checkout! Inicia sesión con tu cuenta de CulqiPanel</b></span>
    <button id="woocommerce_culqi_login_button" type="button" class="btnlogin" data-toggle="modal"
            data-target="#modalLogin" onclick="jQuery('#errorlogincpanelculqi').html('');">
        <div class=''>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.99463 8.66086C6.58697 8.66086 5.44795 7.52184 5.44795 6.11418C5.44795 4.70653 6.59772 3.55676 8.00537 3.55676C9.41303 3.55676 10.5628 4.70653 10.5628 6.11418C10.5628 7.52184 9.40228 8.66086 7.99463 8.66086ZM7.99463 4.95367C7.36065 4.95367 6.83412 5.46946 6.83412 6.11418C6.83412 6.75891 7.3499 7.27469 7.99463 7.27469C8.63936 7.27469 9.15514 6.75891 9.15514 6.11418C9.15514 5.46946 8.63936 4.95367 7.99463 4.95367Z"
                      fill="#3E4B61"/>
                <path d="M7.99463 0C3.58899 0 0 3.58899 0 7.99463C0 9.75688 0.580255 11.3902 1.54735 12.7119C1.83747 13.1095 2.17058 13.4856 2.53593 13.8294C3.96508 15.1726 5.88852 16 7.99463 16C10.1007 16 12.0242 15.1726 13.4533 13.8294C13.8187 13.4856 14.1518 13.1202 14.4419 12.7119C15.409 11.3902 15.9893 9.75688 15.9893 7.99463C16 3.58899 12.411 0 7.99463 0ZM7.99463 14.6568C6.14641 14.6568 4.47011 13.8939 3.25588 12.6689C4.36266 11.272 6.07119 10.3801 7.99463 10.3801C9.90732 10.3801 11.6159 11.272 12.7334 12.6689C11.5299 13.8939 9.85359 14.6568 7.99463 14.6568ZM13.6145 11.5621C12.2498 9.98254 10.2404 8.98321 7.99463 8.98321C5.74883 8.98321 3.73942 9.98254 2.37475 11.5514C1.71927 10.5306 1.34318 9.30557 1.34318 7.99463C1.34318 4.31968 4.33042 1.33244 8.00537 1.33244C11.6803 1.33244 14.6676 4.31968 14.6676 7.99463C14.6568 9.30557 14.27 10.5306 13.6145 11.5621Z"
                      fill="#3E4B61"/>
            </svg>
        </div>
        <span>Iniciar Sesión</span>
    </button>
</div>
