<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Culqi', 'fullculqi'); ?>
        <?php echo esc_html( MPCULQI_PLUGIN_VERSION ); ?>
    </h1>

    <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="admin.php?page=fullculqi_settings" class="nav-tab nav-tab-active">Settings</a>
        <a href="edit.php?post_type=culqi_charges" class="nav-tab">Charges</a>
        <a href="edit.php?post_type=culqi_orders" class="nav-tab">Orders</a>
        <a style="display: none" href="edit.php?post_type=culqi_customers" class="nav-tab">Customers</a>
        <a href="admin.php?page=fullculqi_webhooks" class="nav-tab">Webhooks</a>
    </nav>
    <?php
    require_once MPCULQI_DIR . 'admin/layouts/modal_merchants.php';
    require_once MPCULQI_DIR . 'admin/layouts/modal_login.php';
    ?>

    <form id="form-culqi-settings" method="post" action="options.php">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row"><?php esc_html_e('Culqi', 'fullculqi'); ?></th>
                <td>
                    <?php $settings = fullculqi_get_settings(); ?>
                    <div class="can-toggle demo-rebrand-1">
                        <input id="d" type="checkbox" name="fullculqi_options[enabled]"
                               value="yes" <?php checked( $settings['enabled'], 'yes', true ); ?>>
                        <label for="d">
                            <div class="can-toggle__switch" data-checked="Activo" data-unchecked="Inactivo"></div>
                        </label>
                    </div>

                </td>
            </tr>
            </tbody>
        </table>

        <?php
        // OLANDA FORM SETTINGS PAGE
        // This prints out all hidden setting fields
        settings_fields('fullculqi_group');
        do_settings_sections('fullculqi_page');

        do_action('fullculqi/settings/section');

        submit_button('', 'primary', 'culqiBtn');
        ?>
        <?php if(isset($_GET['settings-updated']) and $_GET['settings-updated']){ ?>
            <div id="contact-popup">
                <form class="contact-form" action="" id="contact-form"
                      method="post" enctype="multipart/form-data">
                    <div>
                        <div>
                            <img src="<?php echo esc_url( MPCULQI_URL . 'resources/assets/images/icon-check.png' ); ?>" />
                        </div>
                        <div>
                            <h1>¡Configuración registrada<br/> con éxito!</h1>
                        </div>
                        <div>
                            <p>La configuración de tu Culqi checkout se ha<br/> registrado satisfactoriamente</p>
                        </div>
                    </div>
                    <div>
                        <input onclick="const element = document.getElementById('contact-popup'); element.remove();" id="send" type="button" value="Ok">
                    </div>
                </form>
            </div>
            <style>
                #contact-popup {
                    position: absolute;
                    top: 0px;
                    left: -20px;
                    height: 100%;
                    width: 101%;
                    color: #676767;
                    z-index: 999;
                    background: rgba(0, 0, 0, 0.5);
                }

                .contact-form {
                    width: 370px;
                    margin: 0px;
                    background-color: white;
                    font-family: Arial;
                    position: relative;
                    left: 50%;
                    top: 35%;
                    margin-left: -210px;
                    margin-top: -255px;
                    box-shadow: 1px 1px 5px #444444;
                    padding: 20px 40px 40px 40px;
                }

                #contact-icon {
                    padding: 10px 5px 5px 12px;
                    width: 58px;
                    color: white;
                    box-shadow: 1px 1px 5px grey;
                    border-radius: 3px;
                    cursor: pointer;
                    margin: 60px auto;
                }

                .info {
                    color: #d30a0a;
                    letter-spacing: 2px;
                    padding-left: 5px;
                }

                #send {
                    background-color: #29A19B;
                    border: 1px solid #29A19B;
                    color: white;
                    width: 100%;
                    padding: 10px;
                    cursor: pointer;
                }

                #contact-popup h1 {
                    font-weight: normal;
                    text-align: center;
                    margin: 10px 0px 20px 0px;
                    font-size: 20px;
                    text-align: center;
                }
                #contact-popup p {
                    font-size: 14px;
                    text-align: center;
                }
                #contact-popup div {
                    text-align: center;
                }
            </style>
       <?php  } ?>
    </form>
</div>
