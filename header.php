<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php
      $logo = get_theme_mod( 'logo_upload' );
      if ( empty( $logo ) ) {
        $logo = get_template_directory_uri() . '/assets/img/logo.png';
      }
      global $wp_query;
      $sticky = get_post_meta( $wp_query->post->ID, $key = 'robo_page_sticky', true );
      if ( $sticky == 1) {
        $classname = 'fixed-top';
      } else {
        $classname = '';
      }
    ?>
   <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark <?php echo $classname; ?>" id="mainNav">
    <div class="container">
      <a class="navbar-brand js-scroll-trigger" href="<?php echo home_url('/'); ?>"> <img src="<?php echo $logo; ?>" alt=""></a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">

        <i class="fas fa-bars"></i>
      </button>

      <?php
          $defaults = array(
            'theme_location'  => 'primary',
            'container_id'    => 'navbarResponsive',
            'container'       => 'div',
            'container_class' =>'navbar-collapse collapse',
            'items_wrap'      => '<ul id="menu" class="navbar-nav text-uppercase ml-auto pb-4 pb-lg-0 mt-2 mt-lg-0 pt-2 pt-lg-0 %2$s">%3$s</ul>',
            'walker'         => new WP_Bootstrap_Navwalker(),
          );

          wp_nav_menu( $defaults );
      ?>

    </div>
  </nav>