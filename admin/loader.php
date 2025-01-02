<?php 

add_action('wp_enqueue_scripts', 'culqi_woocommerce_loader_html');
function culqi_woocommerce_loader_html() {
    if (is_checkout()) {
        $image_url = plugin_dir_url(__DIR__) . 'assets/images/culqi_brand.svg';
        echo '<div class="woocommerce-loader">
                <style> 
                    @keyframes animate-svg-stroke { 
                    0% {\
                        stroke-dashoffset: 230px;
                        stroke-dasharray: 244.16209411621094px;
                        stroke-linecap: butt;
                    }
                    50% {
                        stroke-dashoffset: -244.16209411621094px;
                        stroke-dasharray: 244.16209411621094px;
                        stroke-linecap: butt;
                    }
                    100% {
                        stroke-dashoffset: 230px;
                        stroke-dasharray: 244.16209411621094px;
                        stroke-linecap: butt;
                    }
                }
                .svg-elem-2 {
                    animation: animate-svg-stroke 8s ease-in-out infinite;
                    stroke: #FF7800;
                    opacity: 1;
                }
                @keyframes waveAnimation {
                    0% { background-position: 50% 0%; }
                    50% { background-position: 50% 100%; }
                    100% { background-position: 50% 0%; }
                }
                </style>
                <svg width="78" height="76" viewBox="0 0 78 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Forma estática en blanco con opacidad del 10% -->
                    <path opacity="0.1" d="M65.0414 63.9891C65.0414 63.9891 49.0412 47.987 39.0414 37.9891C35.7044 34.6528 32.7488 32.6628 28.0414 32.9891C20.5222 33.5105 15.4647 41.4038 18.0412 48.487C20.041 53.9848 27.665 59.7738 37.0393 59.987C50.2935 60.2884 60.9787 50.7349 61.5414 37.4891C62.105 24.2204 51.7926 13.3175 38.5371 12.5003C25.0205 11.6671 14.7491 21.9632 14.0412 35.487C13.3392 48.8963 21.6659 58.6253 35.0371 59.856" stroke="white" stroke-width="7" class="svg-elem-1"></path>

                    <!-- Forma animada en naranja con el final del trazo redondeado -->
                    <path d="M65.0409 63.9891C65.0409 63.9891 49.0408 47.987 39.041 37.9891C35.704 34.6528 32.7484 32.6628 28.041 32.9891C20.5218 33.5105 15.4643 41.4038 18.0408 48.487C20.0406 53.9848 27.6646 59.7738 37.0389 59.987C50.2931 60.2884 60.9783 50.7349 61.541 37.4891C62.1046 24.2204 51.7922 13.3175 38.5367 12.5003C25.0201 11.6671 14.7487 21.9632 14.0408 35.487C12.5 54 31.9997 67 50.9991 56" stroke="#FF7800" stroke-width="7" stroke-linecap="round" stroke-dasharray="0, 99.5" class="svg-elem-2"></path>
                </svg>
                <div class="loader-text">
                    <span>Procesando...</span>
                </div>
              </div>';
    }
}