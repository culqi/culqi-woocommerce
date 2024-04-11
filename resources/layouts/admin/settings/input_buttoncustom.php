<style>
    .customcheckout .form__group__select::before {
        content: url( "<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/arrow_down.svg' ); ?>" );
    }
    .customcheckout .form__group-input .brand {
        background-image: url( "<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/visa.png' ); ?>" );
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

</style>
<div class="customcheckout">
    <div>
        <input value="Personalizar" type="button" class="btn btn-save" id="open-modal" />
    </div>
    <div class="overlay" id="overlay">
        <div class="container containerpopup">
            <div class="custom-checkout">
                <div class="custom-checkout__header">
                    <div class="custom-checkout__header-title">Personalización</div>
                    <div class="custom-checkout__header-close" id="btn-close">
                    <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/close-black.svg' ); ?>" alt="close">
                    </div>
                </div>
                <div class="custom-checkout__body">
                    <div class="content">
                        <div class="preview">
                            <div class="preview-checkout">
                                <div class="preview-checkout__banner" id="palette-left">
                                    <div class="banner-logo">
                                    <img id="logo" src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/brand.svg' ); ?>" alt="logo">
                                    <img id="logo-default" style="display:none;" src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/brand.svg' ); ?>" alt="logo">
                                    </div>
                                    <div class="banner-title">
                                        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                                    </div>
                                </div>
                                <div class="preview-checkout__amount">
                                    <div class="preview-checkout__amount-contain">
                                        <span id="palette-right" name="color">S/ 350.00</span>
                                        <svg style="width: 20px!important; height: 20px!important" width="22" height="22" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" id="palette-right" name="svg" clip-rule="evenodd" d="M2.39404 14.3924C2.39404 9.80605 6.11198 6.0881 10.6983 6.0881H31.6775C36.2638 6.0881 39.9817 9.80605 39.9817 14.3924V23.1337C39.9817 23.8578 39.3947 24.4449 38.6705 24.4449C37.9464 24.4449 37.3593 23.8578 37.3593 23.1337V14.3924C37.3593 11.2544 34.8155 8.7105 31.6775 8.7105H10.6983C7.56029 8.7105 5.01644 11.2544 5.01644 14.3924V28.3785C5.01644 31.5165 7.56029 34.0603 10.6983 34.0603H22.9361C23.6603 34.0603 24.2473 34.6474 24.2473 35.3715C24.2473 36.0957 23.6603 36.6827 22.9361 36.6827H10.6983C6.11198 36.6827 2.39404 32.9648 2.39404 28.3785V14.3924Z" fill="#00A19B" />
                                            <path id="palette-right" name="svg" d="M12.4462 14.3924C12.4462 13.4268 11.6635 12.6441 10.698 12.6441C9.73243 12.6441 8.94971 13.4268 8.94971 14.3924C8.94971 15.3579 9.73243 16.1406 10.698 16.1406C11.6635 16.1406 12.4462 15.3579 12.4462 14.3924Z" fill="#00A19B" />
                                            <path fill-rule="evenodd" id="palette-right" name="svg" clip-rule="evenodd" d="M21.1876 23.5707C19.9807 23.5707 19.0023 22.5923 19.0023 21.3854C19.0023 20.1785 19.9807 19.2001 21.1876 19.2001C22.3945 19.2001 23.3729 20.1785 23.3729 21.3854C23.3729 22.5923 22.3945 23.5707 21.1876 23.5707ZM16.3799 21.3854C16.3799 24.0407 18.5324 26.1931 21.1876 26.1931C23.8428 26.1931 25.9953 24.0407 25.9953 21.3854C25.9953 18.7302 23.8428 16.5777 21.1876 16.5777C18.5324 16.5777 16.3799 18.7302 16.3799 21.3854Z" fill="#00A19B" />
                                            <path fill-rule="evenodd" id="palette-right" name="svg" clip-rule="evenodd" d="M39.5339 27.3917C40.0789 27.8686 40.1341 28.6969 39.6572 29.2419L34.6399 34.976C33.5617 36.2082 31.7047 36.3732 30.4262 35.3504L27.3618 32.8989C26.7963 32.4465 26.7046 31.6214 27.157 31.0559C27.6094 30.4904 28.4345 30.3988 29 30.8511L32.0644 33.3026C32.247 33.4488 32.5123 33.4252 32.6663 33.2492L37.6837 27.515C38.1605 26.9701 38.9889 26.9148 39.5339 27.3917Z" fill="#00A19B" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="preview-checkout__container">
                                    <div class="preview-checkout__container-menu">
                                        <ul>
                                            <li id="palette-right" name="color">
                                                <span class="barra" id="palette-right" name="bg"></span>
                                                <svg style="width: 20px!important; height: 20px!important" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_478_2813)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" id="palette-right" name="svg" d="M3.94475 4.5H16.0552C17.0276 4.5 17.9116 5.38 18 6.436V13.564C18 14.62 17.116 15.5 16.0552 15.5H3.94475C2.88398 15.5 2 14.62 2 13.564V6.436C2 5.38 2.88398 4.5 3.94475 4.5ZM16.0552 5.644H3.94475C3.50276 5.644 3.14917 5.996 3.14917 6.436V6.964H16.8508V6.436C16.8508 5.996 16.4972 5.644 16.0552 5.644ZM3.94475 14.268H16.0552C16.4088 14.268 16.7624 13.916 16.7624 13.564V8.108H3.14917V13.476C3.14917 13.916 3.50276 14.268 3.94475 14.268ZM4.20989 11.892H6.68503V13.036H4.20989V11.892Z" fill="#00A19B"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_478_2813">
                                                            <rect width="16" height="11" fill="white" transform="translate(2 4.5)"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                Tarjeta débito/crédito
                                            </li>
                                            <li>
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/yape.svg' ); ?>" alt="icon">
                                                Yape
                                            </li>
                                            <li>
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/banca-movil.svg' ); ?>" alt="icon">

                                                Billeteras móviles
                                            </li>
                                            <li>
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/online-banking.svg' ); ?>" alt="icon">
                                                Banca móvil o internet
                                            </li>
                                            <li>
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/agents.svg' ); ?>" alt="icon">
                                                Agentes y bodegas
                                            </li>
                                            <li>
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/bcp.svg' ); ?>" alt="icon">
                                                Cuotéalo BCP
                                            </li>
                                        </ul>
                                        <div class="preview-checkout__container-menu-image">
                                            <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/culqi-black.svg'); ?>" alt="Culqi logo"/>
                                        </div>
                                    </div>
                                    <div class="preview-checkout__container-content">
                                        <span class="form-info">
                                            <svg width="15" height="13" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="14.0867" cy="8.22975" r="1.16551" fill="#00A19B" />
                                                <path fill-rule="evenodd" id="palette-right" name="svg" clip-rule="evenodd" d="M14.0868 3.27637C8.13268 3.27637 3.30588 8.10317 3.30588 14.0573C3.30588 20.0115 8.13268 24.8383 14.0868 24.8383C20.041 24.8383 24.8678 20.0115 24.8678 14.0573C24.8678 8.10317 20.041 3.27637 14.0868 3.27637ZM1.55762 14.0573C1.55762 7.13763 7.16714 1.52811 14.0868 1.52811C21.0065 1.52811 26.6161 7.13763 26.6161 14.0573C26.6161 20.977 21.0065 26.5866 14.0868 26.5866C7.16714 26.5866 1.55762 20.977 1.55762 14.0573ZM12.0472 11.7263C12.0472 11.2435 12.4386 10.8522 12.9213 10.8522H14.0868C14.5696 10.8522 14.961 11.2435 14.961 11.7263V19.8849C14.961 20.3676 14.5696 20.759 14.0868 20.759C13.6041 20.759 13.2127 20.3676 13.2127 19.8849V12.6004H12.9213C12.4386 12.6004 12.0472 12.2091 12.0472 11.7263Z" fill="#00A19B" />
                                            </svg>
                                            <p>Recuerda activar tu tarjeta para <b>compras por internet.</b></p>
                                        </span>
                                        <form class="form">
                                            <div class="form__row empty dobble" style="margin-top: 15px">
                                                <div class="form__row__col">
                                                    <div class="form__group">
                                                        <label class="" for="cardNumber">
                                                            Número de Tarjeta
                                                        </label>
                                                        <div class="form__group-input">
                                                            <input name="cardNumber" value="1234 1234 1234 1234" type="tel" class="valid">
                                                            <div class="brand"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row middle" style="float: left; width: 50%;">
                                                <div class="form__row__col left">
                                                    <div class="form__group">
                                                        <label class="" for="cardExp">
                                                            Vencimiento
                                                        </label>
                                                        <div class="form__group-input">
                                                            <input name="cardExp" placeholder="MM/AAAA" type="tel" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row middle" style="float: left; width: 50%;">
                                                <div class="form__row__col right">
                                                    <div class="form__group">
                                                        <label class="" for="cardCVV">
                                                            Codigo de seguridad
                                                        </label>
                                                        <div class="form__group-input">
                                                            <input name="cardCVV" placeholder="CVV" type="tel" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row dobble">
                                                <div class="form__row__col">
                                                    <div class="form__group">
                                                        <label class="" for="select">
                                                            Número de Cuotas
                                                        </label>
                                                        <div class="form__group__select">
                                                            <select name="select" id="select" disabled="disabled">
                                                                <option value="">
                                                                    Sin cuotas
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row dobble">
                                                <div class="form__row__col">
                                                    <div class="form__group">
                                                        <label class="" for="cardEmail">
                                                            Correo Electrónico
                                                        </label>
                                                        <div class="form__group-input">
                                                            <input name="cardEmail" placeholder="correo@electronico.com" type="email" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row dobble">
                                                <div class="form__row__col">
                                                    <div class="form__group" style="margin-bottom: 0;">
                                                        <button class="btn btn-green opacity" id="palette-right" name="bg">
                                                            Pagar S/ 350.00
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="personalize" id="personalize">
                            <div class="personalize-logotipo">
                                <span class="title">Logotipo</span>
                                <div class="personalize-logotipo__content">
                                    <div class="iso-logo">
                                        <div class="iso-logo__item">
                                            <div class="check">
                                                <svg width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 3.55361L3.53289 6L8 1" stroke="white" stroke-width="1.4" stroke-linecap="round" />
                                                </svg>

                                            </div>
                                            <div class="image">
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/brand.svg' ); ?>" alt="logo">
                                            </div>
                                        </div>
                                        <div class="iso-logo__item">
                                            <div class="check">
                                                <svg width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 3.55361L3.53289 6L8 1" stroke="white" stroke-width="1.4" stroke-linecap="round" />
                                                </svg>

                                            </div>
                                            <div class="image">
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/brand-culqi.svg' ); ?>" alt="logo">
                                            </div>
                                        </div>
                                        <div class="iso-logo__item">
                                            <div class="errorlogo">
                                                <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1L6 6" stroke="white" stroke-width="1.4" stroke-linecap="round" />
                                                    <path d="M6 1L1 6" stroke="white" stroke-width="1.4" stroke-linecap="round" />
                                                </svg>
                                            </div>
                                            <div class="image">
                                                <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/bran-culqi-bg.svg' ); ?>" alt="logo">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="detail-logo">
                                        <li class="detail-logo__list">
                                            <svg width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 3.55361L3.53289 6L8 1" stroke="#5BBC43" stroke-width="1.4" stroke-linecap="round" />
                                            </svg>

                                            Isotipo
                                        </li>
                                        <li class="detail-logo__list">
                                            <svg width="9" height="8" viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 3.55361L3.53289 6L8 1" stroke="#5BBC43" stroke-width="1.4" stroke-linecap="round" />
                                            </svg>

                                            En fondo blanco
                                        </li>
                                        <li class="detail-logo__list">
                                            <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 1L6 6" stroke="#D20808" stroke-width="1.4" stroke-linecap="round" />
                                                <path d="M6 1L1 6" stroke="#D20808" stroke-width="1.4" stroke-linecap="round" />
                                            </svg>

                                            Evita fondos de color
                                        </li>

                                    </div>
                                </div>
                            </div>
                            <div class="personalize-url">
                                <span class="title">URL</span>
                                <div class="personalize-url__content">
                                    <div class="form__row empty dobble">
                                        <div class="form__row__col">
                                            <div class="form__group" style="margin-bottom: 0;">
                                                <div class="form__group-input">
                                                    <input name="url" id="logo-url" placeholder="https://culqi.image.jpg.com" value="<?php echo esc_html( $logo_url ); ?>" type="url">
                                                </div>
                                                <label id="label-text" for="url">
                                                    Copia la URL de tu logotipo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="personalize-color">
                                <span class="title">Color</span>
                                <div class="personalize-color__content">
                                    <p class="paragraph">Personaliza los colores de tu checkout. Elige los colores que más
                                        se acomoden a tu marca.</p>
                                    <div class="personalize-color__content-item">
                                        <div class="subtitle">Predeterminado</div>
                                        <div class="color-palette">
                                            <div class="color-palette__item">
                                                <input type="radio" checked name="color-palette" class="colorPreviewDefault" id="141414-00a19b">
                                                <label for="141414-00a19b">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="personalize-color__content-item">
                                        <div class="subtitle">Colores clásicos</div>
                                        <div class="color-palette">
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="0E456F-2B88D8">
                                                <label for="0E456F-2B88D8">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="7049A8-9373BF">
                                                <label for="7049A8-9373BF">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="3A6505-85B44C">
                                                <label for="3A6505-85B44C">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="EB6F25-E98B52">
                                                <label for="EB6F25-E98B52">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="0280B5-00B1F5">
                                                <label for="0280B5-00B1F5">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="B40593-D32CB5">
                                                <label for="B40593-D32CB5">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="9C3F10-CA5011">
                                                <label for="9C3F10-CA5011">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="F6911B-FFB600">
                                                <label for="F6911B-FFB600">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="6C0101-9D0606">
                                                <label for="6C0101-9D0606">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="046763-00A19B">
                                                <label for="046763-00A19B">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="63464F-894B5D">
                                                <label for="63464F-894B5D">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="color-palette__item">
                                                <input type="radio" name="color-palette" id="C50011-E85965">
                                                <label for="C50011-E85965">
                                                    <div class="color-container">
                                                        <div class="color-container__left"></div>
                                                        <div class="color-container__right"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="personalize-color__content-item">
                                        <span class="action-visible">
                                            <svg width="9" height="5" viewBox="0 0 9 5" fill="none" class="action-svg" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_478_2514)">
                                                    <path d="M1.61794 4.64227L0.913939 3.94377L4.49994 0.357773L8.08594 3.93827L7.38744 4.64227L4.49994 1.75477L1.61794 4.64227Z" fill="#3CB4E5" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_478_2514">
                                                        <rect width="7.172" height="4.2845" fill="white" transform="translate(8.08594 4.64227) rotate(-180)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <span class="action-visible-text">Ver más combinaciones</span></span>
                                        <div class="action-container">
                                            <div class="subtitle">Colores complementarios</div>
                                            <div class="color-palette">
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="0976C9-F6911B">
                                                    <label for="0976C9-F6911B">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="8865B9-F48572">
                                                    <label for="8865B9-F48572">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="52463A-00A19B">
                                                    <label for="52463A-00A19B">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="EB6F25-00A9A4">
                                                    <label for="EB6F25-00A9A4">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="134A29-024064">
                                                    <label for="134A29-024064">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="0976C9-D32CB5">
                                                    <label for="0976C9-D32CB5">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="CA5011-F6911B">
                                                    <label for="CA5011-F6911B">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="color-palette__item">
                                                    <input type="radio" name="color-palette" id="141414-D20808">
                                                    <label for="141414-D20808">
                                                        <div class="color-container">
                                                            <div class="color-container__left"></div>
                                                            <div class="color-container__right"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="custom-checkout__footer">
                    <button class="btn btn-without-line" id="btn-close" type="button">Cancelar</button>
                    <input class="btn btn-save disabled" id="btn-save" disabled value="Guardar" />
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#<?php echo esc_html( str_replace( '#', '', $color_palette ) ); ?>').click();
        if (document.querySelector('#logo-url').value != '') {
            document.querySelector('#logo').src = document.querySelector('#logo-url').value;
        }
    });
</script>
<script type="text/javascript" defer>
    jQuery(document).ready(function() {
        previewCustomFunction('<?php echo esc_html( str_replace('#', '', $color_palette) );?>', '<?php echo esc_html( str_replace('#', '', $logo_url) );?>')
    });
</script>
