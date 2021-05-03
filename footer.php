    <footer class="site-footer pb-0 pb-lg-4">
            <div class="container">
                <div class="row border-bottom pb-4 ">
                    <div class="col-md-3 p-2 p-lg-0">
                        <a href="<?php echo home_url('/'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="row py-3">
                    <div class="col-md-9 p-2 p-lg-0">
                        <?php
                              $defaults = array(
                                'theme_location'  => 'footer',
                                'container_id'=>'footer-nav-menu',
                                'container' => 'div',
                                'container_class' =>'footer-menu',
                                'items_wrap'      => '<ul id="menu" class="%2$s">%3$s</ul>',
                              );

                              wp_nav_menu( $defaults );
                        ?>
                    </div>
                    <div class="col-md-3 p-2 p-lg-0">
                        <div class="social-icons">
                            <ul>
                                <li><a href=" <?php echo esc_url( get_theme_mod('fb_section') );?> ">  <i class="fab fa-facebook"></i></a></li>
                                <li><a href=" <?php echo esc_url( get_theme_mod('linkedin_section') );?> "><i class="fab fa-linkedin"></i></a></li>
                                <li><a href=" <?php echo esc_url( get_theme_mod('twitter_section') );?> "><i class="fab fa-twitter"></i></a></li>
                                <li><a href=" <?php echo esc_url( get_theme_mod('youtube_section') );?> "><i class="fab fa-youtube"></i></a></li>
                                <li><a href=" <?php echo esc_url( get_theme_mod('instragram_section') );?> "><i class="fab fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>