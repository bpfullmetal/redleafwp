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
$loader_text = get_field('loading_text');
$loader_subtext = get_field('loading_subtext');
$virtual_tour_data = [
    'virtual_tour_desktop' => get_field('virtual_tour_desktop'),
    'virtual_tour_mobile' => get_field('virtual_tour_mobile')
];

if ( $loader_type == 'pano2vr' ) {
    $virtual_tour_data['xml'] = wp_get_attachment_url(get_field('pano_xml'));
}

$display_modal = get_field('display_info_modal');
$display_header = get_field('display_header');

wp_localize_script( 'red-leaf-virtual-tour', 'virtualTourData', $virtual_tour_data);

echo get_template_directory_uri() . '/images/tiles'; ?>
    <?php 
    if ( $display_header ) { ?>
        <div id="header">
            <div id="header-logo">
                <svg width="307" height="35" viewBox="0 0 307 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.7206 1.3144H8.20355L2.95876 18.4385C4.58983 16.5009 7.00171 15.3931 9.53211 15.4135C11.8131 15.3849 14.0042 16.3006 15.5781 17.9521C17.1928 19.5709 18.0757 21.7783 18.0267 24.0635C18.0676 26.3241 17.1356 28.4947 15.4677 30.0236C13.7794 31.6874 11.4943 32.599 9.12332 32.5622C6.86272 32.5704 4.69614 31.6465 3.13866 30.0032C2.54182 29.4063 2.03085 28.7318 1.61798 27.996L-0.135742 28.8218C0.367067 29.7538 1.00888 30.6 1.76922 31.3399C3.67827 33.3593 6.34765 34.4876 9.12741 34.4467C12.0175 34.4794 14.8014 33.3634 16.8657 31.3399C18.9015 29.4717 20.038 26.8228 19.9971 24.0594C20.0666 21.3082 18.9996 18.647 17.0415 16.7094C15.063 14.6859 12.3446 13.5658 9.51574 13.6026C8.42019 13.6108 7.33282 13.807 6.30267 14.1749L9.6629 3.1376H20.7206V1.3144ZM42.3741 4.19227C40.2444 2.03387 37.3256 0.844295 34.2965 0.897438C31.2878 0.864735 28.3936 2.04613 26.2638 4.17592C24.0645 6.21168 22.834 9.08956 22.879 12.086C22.8136 15.1355 23.9869 18.0788 26.1371 20.2454C28.2423 22.4406 31.1734 23.6506 34.2147 23.5852C37.2643 23.6547 40.2117 22.4733 42.3741 20.319C44.5898 18.2137 45.808 15.2745 45.7344 12.2209C45.7875 9.19175 44.5693 6.28118 42.3741 4.19227ZM41.0006 19.019C39.251 20.8381 36.8187 21.8478 34.2965 21.8029C31.7947 21.8397 29.3869 20.8463 27.6373 19.0558C25.8223 17.3307 24.8085 14.927 24.8453 12.4253C24.8085 9.87443 25.7773 7.41352 27.5515 5.58215C29.2602 3.70172 31.6966 2.65522 34.2311 2.70837C36.7737 2.65931 39.2183 3.6772 40.9761 5.51675C42.7993 7.2868 43.809 9.73135 43.7681 12.274C43.8049 14.8044 42.8034 17.2408 40.9965 19.0108M76.9985 1.3144H75.0322V10.32H62.7686V1.3144H60.8023V23.0905H62.7686V12.1391H75.0322V23.0905H76.9985V1.3144ZM100.197 1.3144H98.231V14.8249C98.231 15.5034 98.2105 16.1207 98.1696 16.6848C98.141 17.7927 97.7813 18.8719 97.1354 19.7712C94.793 22.3179 90.8278 22.4896 88.277 20.1473C87.533 19.3665 87.0588 18.365 86.9239 17.298C86.8258 16.4764 86.7808 15.6506 86.7972 14.8249V1.3144H84.8309V15.3522C84.8309 17.9439 85.4236 19.8775 86.605 21.1447C89.8712 24.3864 95.1364 24.3864 98.4026 21.1447C99.6004 19.8611 100.201 17.9316 100.201 15.3522L100.197 1.3144ZM121.217 4.69917C119.132 2.44266 116.218 1.31849 112.465 1.31849H108.026V23.0946H112.44C116.144 23.0946 119.055 21.9664 121.172 19.7099C123.089 17.6864 124.132 14.9925 124.066 12.2045C124.132 9.42476 123.106 6.73085 121.217 4.69917ZM119.329 18.8473C117.444 20.5111 114.991 21.3818 112.477 21.2755H109.988V3.13351H112.481C115.069 2.99861 117.595 3.93064 119.48 5.70887C121.213 7.42169 122.161 9.77632 122.1 12.2127C122.198 14.7227 121.189 17.1509 119.333 18.8432M137.503 11.1376L135.472 10.2096C133.117 9.12634 131.94 7.88771 131.944 6.49374C131.919 5.47177 132.332 4.4866 133.084 3.79166C133.828 3.08037 134.826 2.69202 135.856 2.71246C137.561 2.76969 139.102 3.73851 139.895 5.25103L141.415 4.17183C140.369 2.10336 138.215 0.823852 135.897 0.893346C134.364 0.848379 132.868 1.38389 131.711 2.3936C130.558 3.41148 129.924 4.89538 129.977 6.43243C129.937 7.89589 130.578 9.29395 131.711 10.2178C132.573 10.8637 133.509 11.4033 134.503 11.8243L136.322 12.691C137.295 13.1243 138.215 13.6639 139.069 14.2975C139.907 14.9557 140.397 15.9613 140.402 17.0282C140.426 18.3077 139.895 19.5341 138.942 20.3885C138.01 21.2837 136.767 21.7743 135.476 21.762C133.064 21.762 131.437 20.4212 130.595 17.7436L128.776 18.3772C129.834 21.8478 132.046 23.5811 135.414 23.577C137.233 23.6097 138.991 22.9352 140.32 21.6925C141.669 20.4948 142.417 18.7656 142.372 16.9628C142.364 14.5591 140.741 12.6174 137.503 11.1376ZM166.413 3.83253C161.744 -0.38616 154.537 -0.0223365 150.319 4.64603C150.278 4.69099 150.237 4.73596 150.196 4.78093C148.344 6.75946 147.314 9.36752 147.318 12.0737C147.228 15.3358 148.614 18.4631 151.083 20.5929C155.486 24.6072 162.235 24.5622 166.58 20.4866C168.935 18.3895 170.264 15.3726 170.219 12.2209C170.268 8.99962 168.87 5.92144 166.413 3.83253ZM165.211 19.2275C163.453 20.8913 161.119 21.8151 158.699 21.8029C156.307 21.811 154.01 20.8708 152.314 19.1866C150.327 17.3716 149.223 14.784 149.289 12.0901C149.268 9.82946 150.106 7.64653 151.631 5.98276C155.012 2.04613 160.943 1.59646 164.88 4.97714C165.002 5.08343 165.125 5.1938 165.244 5.30826C167.198 7.07423 168.293 9.60054 168.244 12.2372C168.31 14.8984 167.198 17.4534 165.211 19.2275ZM196.536 1.3144H194.57V19.2439L176.771 0.599018V23.0946H178.738V5.35731L196.536 23.9613V1.3144ZM226.558 1.3144L220.573 11.6118L214.699 1.3144H212.418L219.584 13.7661V23.0905H221.55V13.7661L228.863 1.3144H226.558ZM239.733 0.321045L230.069 23.0905H232.228L234.958 16.4723H244.365L247.05 23.0905H249.229L239.733 0.321045ZM235.735 14.6532L239.708 5.03846L243.6 14.6572L235.735 14.6532ZM259.253 13.0916C260.692 13.0712 262.081 12.5765 263.21 11.6772C264.522 10.5653 265.245 8.91377 265.176 7.19686C265.176 3.28067 262.731 1.31849 257.838 1.31849H254.498V23.0905H256.465V13.1733H257.098L263.884 23.0905H266.272L259.253 13.0916ZM258.28 11.3788H256.461V3.13351H258.088C261.497 3.13351 263.206 4.49477 263.206 7.21322C263.206 9.98889 261.566 11.3788 258.28 11.3788ZM285.44 4.69917C283.355 2.44266 280.44 1.31849 276.687 1.31849H272.244V23.0946H276.663C280.367 23.0946 283.277 21.9664 285.395 19.7099C287.312 17.6864 288.35 14.9925 288.289 12.2045C288.358 9.42476 287.336 6.73085 285.44 4.69917ZM283.555 18.8473C281.671 20.5111 279.218 21.3818 276.704 21.2755H274.21V3.13351H276.704C279.291 2.99861 281.822 3.92655 283.706 5.70887C285.44 7.42169 286.392 9.77632 286.331 12.2127C286.429 14.7227 285.415 17.1509 283.559 18.8432M301.726 11.1376L299.698 10.2096C297.348 9.12634 296.17 7.88771 296.17 6.49374C296.146 5.47177 296.563 4.4866 297.311 3.79166C298.055 3.08037 299.052 2.69202 300.078 2.71246C301.783 2.76969 303.324 3.73851 304.117 5.25103L305.642 4.17183C304.595 2.10336 302.441 0.823852 300.123 0.893346C298.59 0.848379 297.094 1.38389 295.937 2.3936C294.785 3.41148 294.147 4.89538 294.204 6.43243C294.163 7.89589 294.805 9.29395 295.937 10.2178C296.8 10.8637 297.736 11.4033 298.725 11.8243L300.544 12.691C301.517 13.1243 302.437 13.6639 303.291 14.2975C304.129 14.9557 304.62 15.9613 304.624 17.0282C304.649 18.3077 304.117 19.5341 303.165 20.3885C302.233 21.2837 300.99 21.7743 299.698 21.762C297.286 21.762 295.659 20.4212 294.813 17.7436L292.994 18.3772C294.053 21.8478 296.264 23.5811 299.633 23.577C301.452 23.6097 303.21 22.9352 304.538 21.6925C305.887 20.4948 306.639 18.7656 306.59 16.9628C306.59 14.5551 304.967 12.6133 301.726 11.1335" fill="#A6958C"/>
                </svg>
            </div>
            <div id="header-label">
                <span>64th Floor Pre-Built Virtual Tour</span>
            </div>
            <div id="header-inquire">
                <button id="inquire-button" data-toggle="modal" data-target="#inquire-modal" class="btn">Inquire</button>
            </div>
        </div>
    <?php 
    } ?>
    <div id="loaderStep0" class="loader">
        <?php virtual_tour_loader($loader_type, $loader_text, $loader_subtext); ?>
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
                        <video id="video-background" class="video-background__video" preload muted autoplay loop playsinline><source src="<?php echo $video_url; ?>" type="video/mp4"></video>
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
            <?php virtual_tour_loader(null, 'Resizing video Tour', ''); ?>
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
        <button id="close-instructions"><img src="<?php echo get_template_directory_uri(); ?>/images/close-x-white.png" alt="close instructions"></button>
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
    <?php 
    if ( $display_modal ) { ?>
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
    }
get_footer();
?>