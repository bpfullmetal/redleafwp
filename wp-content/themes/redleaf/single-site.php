<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package redleaf
 */

get_header();

$loader_type = get_field('loading_type');
$virtual_tour_data = [
    'virtual_tour_desktop' => get_field('virtual_tour_desktop'),
    'virtual_tour_mobile' => get_field('virtual_tour_mobile')
];

if ( $loader_type == 'pano2vr' ) {
    $virtual_tour_data['xml'] = wp_get_attachment_url(get_field('pano_xml'));
}

wp_localize_script( 'red-leaf-virtual-tour', 'virtualTourData', $virtual_tour_data);

echo get_template_directory_uri() . '/images/tiles'; ?>
    <div id="loaderStep0" class="loader">
        <?php virtual_tour_loader(); ?>
    </div>
    <div id="loaderStep1"
            style="top: 0px; left: 0px; width: 100%; height: 100%;"
            class="loader loading-pano">
        <div class="loader-header">
            <div id="container" style="width:100%;height:100%;overflow:hidden;position: relative;">
            	<?php
                if ( $loader_type == 'video' ) {
                    $video_url = get_field('video_url'); ?>
                    <div class="video-background">
                        <video id="video-background" class="video-background__video" muted="true" autoplay="" loop=""><source src="<?php echo $video_url; ?>" type="video/mp4"></video>
                    </div>
                <?php 
                } ?>
        	</div>
            <noscript>
                <p><b>Please enable Javascript!</b></p>
            </noscript>
        </div>
        <div class="loader-wrapper" style="display: none;"></div>
    </div>

    <div id="loaderStep2" class="loader">
        <div class="loader-bg"></div>
        <div class="loader-wrapper">
            <div class="loader-container">
                <div id="play-button-wrapper">
                    <div id="play-button">
                        <div id="play-button-outer">
                            <div id="play-button-inner">
                            </div>
                        </div>
                        <svg width="602" height="602" viewBox="0 0 602 602" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="301" cy="301" r="296.5" stroke="white" stroke-width="9"/>
                            <path d="M194 442V172L477 306.024L194 442Z" stroke="white" stroke-width="9" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p class="uppercase text-white">Start Virtual Tour<p>
                </div>
            </div>
        </div>
    </div>

    <div id="resetIframeLoader" class="loader">
        <div class="loader-bg"></div>
        <div class="loader-wrapper">
            <?php virtual_tour_loader(); ?>
        </div>
    </div>

    <div id="instructions-container">
        <div class="instruction instruction-move">
            <div class="instruction-icons">
                <div class="instruction-icon">
                    <img src="<?php echo get_template_directory_uri() . '/images/Icon_Nav_Mouse-2xClick.png'; ?>"/>
                </div>
                <div class="instruction-icon">
                    <img src="<?php echo get_template_directory_uri() . '/images/Icon_Nav_Hand-2xClick.png'; ?>"/>
                </div>
            </div>
            <div class="instruction-text text-center">
                <p class="uppercase fw-bold text-white">Move Camera</p>
                <span class="text-white"><p>Double tap floor</p></span>
            </div>
        </div>
        <div class="instruction instruction-rotate">
            <div class="instruction-icons">
                <div class="instruction-icon">
                    <img src="<?php echo get_template_directory_uri() . '/images/Icon_Nav_Mouse-Swipe.png'; ?>"/>
                </div>
                <div class="instruction-icon">
                    <img src="<?php echo get_template_directory_uri() . '/images/Icon_Nav_Hand-Swipe.png'; ?>"/>
                </div>
            </div>
            <div class="instruction-text text-center">
                <p class="uppercase fw-bold text-white">Rotation</p>
                <span class="text-white"><p>Swipe on screen to control your view</p></span>
            </div>
        </div>
    </div>


    <div id="wrapperdiv">
        <div class="wrapper d-flex align-items-stretch">
            <div id="content">
                <div id="player">
                    <div id="player-content">
                        <iframe allow="camera;microphone" style="visibility: hidden;" id="iframe_1"
                                src=""
                                width="100%" height="100%" allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <!---Start footer-->
                <a style="visibility: hidden;" id="ck-fullscreen" class="full-screen-btn" onclick="goToFullScreen()">
                    <span class="footer-icon fullscreen-icon"></span>
                </a>
                <!--End Footer-->
            </div>
        </div>
        <a id="help-button" class="text-white fw-bold" href="#help">?</a>
    </div>

    <div class="modal fade" id="inquire-modal" tabindex="-1" role="dialog" aria-labelledby="inquire-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-center uppercase" id="inquire-modal-label">Contact Us</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <hr>
                </div>
                <div class="modal-body">
                    <div class="modal-body-inner">
                        <div class="modal-contact">
                            <div class="modal-contact-name">
                                <h3>STEPHEN C. WINTER</h3>
                                <h4>Senior Vice President Related Companies</h4>
                            </div>
                            <div class="modal-contact-info">
                                <p>Direct: <a href="tel:6465822273">(646) 582-2273</a></p>
                                <p>Mobile: <a href="tel:3474089002">(347) 408-9002</p>
                                <p><a href="mailto:stephen.winter@related.com">stephen.winter@related.com</a></p>
                            </div>
                        </div>
                        <div class="modal-contact">
                            <div class="modal-contact-name">
                                <h3>HENRY ALLMAN</h3>
                                <h4>Senior Associate, Commercial Leasing Related Companies</h4>
                            </div>
                            <div class="modal-contact-info">
                                <p>Direct: <a href="tel:2124017666">(212) 401-7666</a></p>
                                <p>Mobile: <a href="tel:9178469273">(917) 846-9273</a></p>
                                <p><a href="mailto:hallman@related.com">hallman@related.com</a></p>
                            </div>
                        </div>
                        <div class="modal-contact">
                            <div class="modal-contact-name">
                                <h3>ELLIOT KARP</h3>
                                <h4>Senior Associate, Commercial Leasing Related Companies</h4>
                            </div>
                            <div class="modal-contact-info">
                                <p>Direct: <a href="tel:2125000822">(212) 500-0822</a></p>
                                <p>Mobile: <a href="tel:9085778537">(908) 577-8537</a></p>
                                <p><a href="mailto:ekarp@related.com">ekarp@related.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
?>