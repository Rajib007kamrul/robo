<?php

add_filter( 'show_admin_bar', 'show_admin' );

function show_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
	    return false;
	}

	return true;
}

if ( ! function_exists( 'robo_setup' ) ) {

	function robo_setup() {

		load_theme_textdomain( 'robo', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_image_size( 'robo_blog_thumbnail', 376, 190 );
		add_image_size( 'robo_compettion_thumbnail', 470, 245 );
		add_image_size( 'robo_blog_full', 900, 300 );
		add_editor_style();
		add_theme_support( 'post-thumbnails' );
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'robo' ),
			'footer' => esc_html__( 'footer Menu', 'robo' ),
		) );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		add_theme_support( 'custom-logo', array(
		   'flex-width' => false,
		   'height'     => 80,
	   	   'width'      => 250,
		) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'tar_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		add_role( 'student', 'Student', get_role( 'subscriber' )->capabilities );
		add_role( 'teacher', 'Teacher', get_role( 'subscriber' )->capabilities );

		add_image_size( 'feature_blog', 684, 355, false );
		add_image_size( 'blog_post', 380, 150, false );
		add_image_size( 'feature_competition', 684, 355, false );
		add_image_size( 'competition_post', 684, 355, false );
		add_image_size( 'competition_single_post', 684, 355, false );

		theme_activated();
	}
}

add_action( 'after_setup_theme', 'robo_setup' );

function theme_activated() {
    // Information needed for creating the plugin's pages
    $page_definitions = array(
        'login' => array(
            'title' => __( 'Log In', 'personalize-login' ),
            'content' => '[robo_login_form]'
        ),
        'registration' => array(
            'title' => __( 'Registration', 'personalize-login' ),
            'content' => '[robo_register_form]'
        ),
	    'password-lost' => array(
	        'title' => __( 'Forgot Your Password?', 'personalize-login' ),
	        'content' => '[robo_lostpass_form]'
	    ),
	    'password-reset' => array(
	        'title' => __( 'Pick a New Password', 'personalize-login' ),
	        'content' => '[robo_reset_form]'
	    )
    );

    foreach ( $page_definitions as $slug => $page ) {
        // Check that the page doesn't exist already
        $query = new WP_Query( 'pagename=' . $slug );
        if ( ! $query->have_posts() ) {
            // Add the page using the data from the array above
            wp_insert_post(
                array(
                    'post_content'   => $page['content'],
                    'post_name'      => $slug,
                    'post_title'     => $page['title'],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                )
            );
        }
    }
}

if ( ! function_exists( 'robo_widgets_init' ) ) {

	function robo_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'tar' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Sidebar Widget', 'tar'),
			'before_widget' => '<div id="%1$s" class="card my-4 %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="card-header">',
			'after_title'   => '</h2>',
		) );
	}
}

add_action( 'widgets_init', 'robo_widgets_init' );

if ( ! function_exists( 'robo_scripts' ) ) {

	function robo_scripts() {
		global $wp_query;
		wp_enqueue_style( 'robo_bootstrap_css', get_template_directory_uri() .'/assets/css/vendor/bootstrap.min.css' );
	    wp_enqueue_style( 'robo_sal_css', get_template_directory_uri() .'/assets/css/vendor/sal.css' );
	    wp_enqueue_style( 'robo_carousel_css', get_template_directory_uri() .'/assets/css/vendor/owl.carousel-2.3.4.min.css' );
	    wp_enqueue_style( 'robo_main_css', get_template_directory_uri() .'/assets/css/main.css' );
		wp_enqueue_style( 'robo_all_css',  get_template_directory_uri() .'/assets/css/all.min.css' );
		wp_enqueue_style( 'robo_calendar_css',  'https://unpkg.com/simple-jscalendar@1.4.4/source/jsCalendar.min.css' );
		wp_enqueue_style( 'robo_slick_css',  get_template_directory_uri() .'/assets/css/slick-slider.css' );
		wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_script( 'robo_style_css', get_template_directory_uri() . '/style.css');

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'robo_bootstrap_js', get_template_directory_uri() . '/assets/js/vendor/bootstrap.bundle.min.js');
		wp_enqueue_script( 'robo_easing_js', get_template_directory_uri() . '/assets/js/vendor/jquery.easing.min.js');
		wp_enqueue_script( 'robo_carousel_js', get_template_directory_uri() . '/assets/js/vendor/owl.carousel-2.3.4.min.js');
		wp_enqueue_script( 'robo_sal_js', get_template_directory_uri() . '/assets/js/vendor/sal.js');
		wp_enqueue_script( 'robo_slick_js', get_template_directory_uri() . '/assets/js/vendor/slick-min.js');
		wp_enqueue_script( 'robo_calendar_js', 'https://unpkg.com/simple-jscalendar@1.4.4/source/jsCalendar.min.js', ['jquery'] );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'robo_main_js', get_template_directory_uri() . '/assets/js/main.js');

		wp_localize_script( 'robo_main_js', 'robo',
			array(
				'ajaxurl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( "robo-nonce" ),
				'posts'          => json_encode( $wp_query->query_vars ), // everything about your loop is here
				'current_page'   => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
				'max_page'       => $wp_query->max_num_pages,
				'posts_per_page' => !empty( get_option( 'posts_per_page ' ) ) ? get_option( 'posts_per_page ' ) : 3
			)
		);
	}
}

add_action( 'wp_enqueue_scripts', 'robo_scripts' );

add_filter( 'script_loader_tag', 'robo_id_to_script', 10, 3 );

function robo_id_to_script( $tag, $handle, $src ) {
    if ( 'robo_calendar_js' === $handle ) {
        $tag = '<script type="text/javascript" src="' . esc_url( $src ) . '" integrity=""> </script>';
    }

    return $tag;
}

add_filter( 'style_loader_tag',  'robo_id_to_style', 10, 4 );

function robo_id_to_style( $html, $handle, $href, $media ){
    if ( 'robo_calendar_css' === $handle ) {
    	$html = '<link rel="stylesheet" id="robo_calendar_css-css" href="'. esc_url( $href ) .'" integrity="" />';
    }
    return $html;
}

if ( ! function_exists( 'robo_customizer_css' ) ) {

	function robo_customizer_css() {

	}
}

add_action( 'wp_head', 'robo_customizer_css' );

function robo_custom_excerpt_length( $length ) {
    return 20;
}
function new_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

add_filter( 'excerpt_length', 'robo_custom_excerpt_length', 999 );

add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );

function add_loginout_link( $items, $args ) {
    if (is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<li class="nav-item"><a class="btn btn-yellow mt-3  ml-lg-3" href="'. wp_logout_url(get_permalink()) .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in() && $args->theme_location == 'primary') {
        $items .= '<li class="nav-item"><a class="btn btn-yellow mt-3  ml-lg-3" href="'. esc_url( wp_login_url( get_permalink() ) ) .'">Log In</a></li>';
        $items .= '<li class="nav-item"><a class="btn btn-yellow mt-3  ml-lg-3" href="'. esc_url( wp_registration_url() ) .'"> 	Register </a></li>';
        // $items .= '<li class="nav-item"><a class="btn btn-yellow mt-3  ml-lg-3" href="'. site_url('wp-login.php') .'">Log In</a></li>';
    }
    return $items;
}

require get_template_directory() . '/inc/class-ajax.php';
require get_template_directory() . '/inc/class-bootstrap-walker-menu.php';
require get_template_directory() . '/inc/class-robo-walker-comment.php';
require get_template_directory() . '/inc/class-user-login.php';
require get_template_directory() . '/inc/class-user-registration.php';
require get_template_directory() . '/inc/class-user-profile.php';
require get_template_directory() . '/inc/custom_post.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/roboshortcode.php';
require get_template_directory() . '/inc/user-taxonomy.php';

