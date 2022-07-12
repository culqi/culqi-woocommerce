<div class="customcheckout">
    <div>
        <input value="Personalizar" type="button" class="btn btn-save" id="open-modal" />
    </div>
    <div class="overlay" id="overlay">
        <div class="container containerpopup" >
            <div class="custom-checkout">
                <div class="custom-checkout__header">
                    <div class="custom-checkout__header-title">Personalización</div>
                    <div class="custom-checkout__header-close" id="btn-close">
                        <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/close-black.svg"
                             alt="close">
                    </div>
                </div>
                <div class="custom-checkout__body">
                    <div class="content">
                        <div class="preview">
                            <div class="preview-checkout">
                                <div class="preview-checkout__banner" id="palette-left">
                                    <div class="banner-logo">
                                        <img id="logo"
                                             src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/brand.svg"
                                             alt="logo">
                                    </div>
                                    <div class="banner-title">
                                        <?php echo get_bloginfo( 'name' ); ?>
                                    </div>
                                </div>
                                <div class="preview-checkout__amount">
                                    <div class="preview-checkout__amount-contain">
                                        <span id="palette-right" name="color">S/ 350.00</span>
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path id="palette-right" name="svg"
                                                  d="M18.3335 1.66663H1.66683C1.44582 1.66663 1.23385 1.75442 1.07757 1.9107C0.921293 2.06698 0.833496 2.27895 0.833496 2.49996V9.16663C0.833496 9.38764 0.921293 9.5996 1.07757 9.75588C1.23385 9.91216 1.44582 9.99996 1.66683 9.99996H4.16683V17.5C4.16683 17.721 4.25463 17.9329 4.41091 18.0892C4.56719 18.2455 4.77915 18.3333 5.00016 18.3333H15.0002C15.2212 18.3333 15.4331 18.2455 15.5894 18.0892C15.7457 17.9329 15.8335 17.721 15.8335 17.5V9.99996H18.3335C18.5545 9.99996 18.7665 9.91216 18.9228 9.75588C19.079 9.5996 19.1668 9.38764 19.1668 9.16663V2.49996C19.1668 2.27895 19.079 2.06698 18.9228 1.9107C18.7665 1.75442 18.5545 1.66663 18.3335 1.66663ZM5.8335 16.6666V15C6.27552 15 6.69945 15.1756 7.01201 15.4881C7.32457 15.8007 7.50016 16.2246 7.50016 16.6666H5.8335ZM14.1668 16.6666H12.5002C12.5002 16.2246 12.6758 15.8007 12.9883 15.4881C13.3009 15.1756 13.7248 15 14.1668 15V16.6666ZM14.1668 13.3333C13.2828 13.3333 12.4349 13.6845 11.8098 14.3096C11.1847 14.9347 10.8335 15.7826 10.8335 16.6666H9.16683C9.16683 15.7826 8.81564 14.9347 8.19052 14.3096C7.5654 13.6845 6.71755 13.3333 5.8335 13.3333V6.66663H14.1668V13.3333ZM17.5002 8.33329H15.8335V5.83329C15.8335 5.61228 15.7457 5.40032 15.5894 5.24404C15.4331 5.08776 15.2212 4.99996 15.0002 4.99996H5.00016C4.77915 4.99996 4.56719 5.08776 4.41091 5.24404C4.25463 5.40032 4.16683 5.61228 4.16683 5.83329V8.33329H2.50016V3.33329H17.5002V8.33329ZM10.0002 12.5C10.4946 12.5 10.978 12.3533 11.3891 12.0786C11.8002 11.8039 12.1206 11.4135 12.3099 10.9567C12.4991 10.4999 12.5486 9.99719 12.4521 9.51223C12.3557 9.02728 12.1176 8.58182 11.7679 8.23219C11.4183 7.88256 10.9728 7.64446 10.4879 7.548C10.0029 7.45153 9.50027 7.50104 9.04345 7.69026C8.58664 7.87948 8.19619 8.19991 7.92149 8.61103C7.64678 9.02216 7.50016 9.50551 7.50016 9.99996C7.50016 10.663 7.76355 11.2989 8.23239 11.7677C8.70124 12.2366 9.33712 12.5 10.0002 12.5ZM10.0002 9.16663C10.165 9.16663 10.3261 9.2155 10.4631 9.30707C10.6002 9.39864 10.707 9.52878 10.7701 9.68106C10.8331 9.83333 10.8496 10.0009 10.8175 10.1625C10.7853 10.3242 10.706 10.4727 10.5894 10.5892C10.4729 10.7058 10.3244 10.7851 10.1627 10.8173C10.0011 10.8494 9.83353 10.8329 9.68126 10.7699C9.52899 10.7068 9.39884 10.6 9.30727 10.4629C9.2157 10.3259 9.16683 10.1648 9.16683 9.99996C9.16683 9.77895 9.25463 9.56698 9.41091 9.4107C9.56719 9.25442 9.77915 9.16663 10.0002 9.16663Z"
                                                  fill="#00A19B"/>
                                        </svg>

                                    </div>
                                </div>
                                <div class="preview-checkout__container">
                                    <div class="preview-checkout__container-menu">
                                        <ul>
                                            <li id="palette-right" name="color" style="color: rgb(0, 161, 155);">
                                            <span class="barra" id="palette-right" name="bg"
                                                  style="background: rgb(0, 161, 155);"></span>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_478_2813)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" id="palette-right"
                                                              name="svg"
                                                              d="M3.94475 4.5H16.0552C17.0276 4.5 17.9116 5.38 18 6.436V13.564C18 14.62 17.116 15.5 16.0552 15.5H3.94475C2.88398 15.5 2 14.62 2 13.564V6.436C2 5.38 2.88398 4.5 3.94475 4.5ZM16.0552 5.644H3.94475C3.50276 5.644 3.14917 5.996 3.14917 6.436V6.964H16.8508V6.436C16.8508 5.996 16.4972 5.644 16.0552 5.644ZM3.94475 14.268H16.0552C16.4088 14.268 16.7624 13.916 16.7624 13.564V8.108H3.14917V13.476C3.14917 13.916 3.50276 14.268 3.94475 14.268ZM4.20989 11.892H6.68503V13.036H4.20989V11.892Z"
                                                              fill="#00A19B"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_478_2813">
                                                            <rect width="16" height="11" fill="white"
                                                                  transform="translate(2 4.5)"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                Tarjeta débito / crédito
                                            </li>
                                            <li>
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/online-banking.svg"
                                                     alt="icon">
                                                Banca móvil o internet
                                            </li>
                                            <li>
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/agents.svg"
                                                     alt="icon">
                                                Agentes y bodegas
                                            </li>
                                            <li>
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/banca-movil.svg"
                                                     alt="icon">

                                                Billeteras móviles
                                            </li>
                                            <li>
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/calendar.svg"
                                                     alt="icon">
                                                Cuotéalo BCP
                                            </li>
                                        </ul>
                                        <div class="preview-checkout__container-menu-image">
                                            <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/logos/culqi-black.svg"
                                                 alt="Culqi logo"/>
                                        </div>
                                    </div>
                                    <div class="preview-checkout__container-content">
                    <span class="form-info">
                      <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/icons/info.svg"
                           alt="culqi icon"/>
                      <p>Recuerda activar tu tarjeta para <b>compras por internet.</b></p>
                    </span>
                                        <form class="form">
                                            <div class="form__row empty dobble">
                                                <div class="form__row__col">
                                                    <div class="form__group">
                                                        <label class="" for="cardNumber">
                                                            Número de Tarjeta
                                                        </label>
                                                        <div class="form__group-input">
                                                            <input name="cardNumber" value="1234 1234 1234 1234" type="tel"
                                                                   class="empty">
                                                            <div class="brand"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row middle">
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
                                            <div class="form__row middle">
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
                                                                    0
                                                                </option>
                                                            </select></div>
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
                                                            <input name="cardEmail" value="correo@electronico.com"
                                                                   type="email" class="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form__row dobble">
                                                <div class="form__row__col">
                                                    <div class="form__group" style="margin-bottom: 0;">
                                                        <button class="btn btn-green" id="palette-right" name="bg"
                                                                style="background-color:#00A19B; color: #FFFFFF;">
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
                                                <svg width="9" height="8" viewBox="0 0 9 8" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 3.55361L3.53289 6L8 1" stroke="white" stroke-width="1.4"
                                                          stroke-linecap="round"/>
                                                </svg>

                                            </div>
                                            <div class="image">
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/brand.svg"
                                                     alt="logo">
                                            </div>
                                        </div>
                                        <div class="iso-logo__item">
                                            <div class="check">
                                                <svg width="9" height="8" viewBox="0 0 9 8" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 3.55361L3.53289 6L8 1" stroke="white" stroke-width="1.4"
                                                          stroke-linecap="round"/>
                                                </svg>

                                            </div>
                                            <div class="image">
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/brand-culqi.svg"
                                                     alt="logo">
                                            </div>
                                        </div>
                                        <div class="iso-logo__item">
                                            <div class="errorlogo">
                                                <svg width="7" height="7" viewBox="0 0 7 7" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 1L6 6" stroke="white" stroke-width="1.4"
                                                          stroke-linecap="round"/>
                                                    <path d="M6 1L1 6" stroke="white" stroke-width="1.4"
                                                          stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div class="image">
                                                <img src="https://culqi-static-files.s3.amazonaws.com/v3/v3-checkout/bran-culqi-bg.svg"
                                                     alt="logo">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="detail-logo">
                                        <li class="detail-logo__list">
                                            <svg width="9" height="8" viewBox="0 0 9 8" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 3.55361L3.53289 6L8 1" stroke="#5BBC43" stroke-width="1.4"
                                                      stroke-linecap="round"/>
                                            </svg>

                                            Isotipo
                                        </li>
                                        <li class="detail-logo__list">
                                            <svg width="9" height="8" viewBox="0 0 9 8" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 3.55361L3.53289 6L8 1" stroke="#5BBC43" stroke-width="1.4"
                                                      stroke-linecap="round"/>
                                            </svg>

                                            En fondo blanco
                                        </li>
                                        <li class="detail-logo__list">
                                            <svg width="7" height="7" viewBox="0 0 7 7" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 1L6 6" stroke="#D20808" stroke-width="1.4"
                                                      stroke-linecap="round"/>
                                                <path d="M6 1L1 6" stroke="#D20808" stroke-width="1.4"
                                                      stroke-linecap="round"/>
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
                                                    <input name="url" id="logo-url"
                                                           placeholder="https://culqi.image.jpg.com" value="<?php echo $logo_url ?>" type="url">
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
                                                <input type="radio" checked name="color-palette" id="141414-00a19b">
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
                      <svg width="9" height="5" viewBox="0 0 9 5" fill="none" class="action-svg"
                           xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_478_2514)">
                          <path d="M1.61794 4.64227L0.913939 3.94377L4.49994 0.357773L8.08594 3.93827L7.38744 4.64227L4.49994 1.75477L1.61794 4.64227Z"
                                fill="#3CB4E5"/>
                        </g>
                        <defs>
                          <clipPath id="clip0_478_2514">
                            <rect width="7.172" height="4.2845" fill="white"
                                  transform="translate(8.08594 4.64227) rotate(-180)"/>
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
    jQuery(document).ready(function(){
        jQuery('#<?php echo str_replace('#', '', $color_palette) ?>').click();
        if(document.querySelector('#logo-url').value!=''){
            document.querySelector('#logo').src=document.querySelector('#logo-url').value;
        }

    });
</script>
