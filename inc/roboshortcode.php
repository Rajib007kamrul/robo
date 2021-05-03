<?php
add_shortcode( 'course', 'robo_course_shortcode' );

function robo_course_shortcode( $atts, $content = "" ) {
	extract( shortcode_atts( [
        'cat' => '',
    ], $atts ) );
    ob_start();
    ?>

    <?php
    // get_template_part( 'template-parts/content', 'course' );
    include(locate_template('template-parts/content-course.php'));
	return ob_get_clean();
}


add_shortcode( 'curriculum', 'robo_curriculum_shortcode' );

function robo_curriculum_shortcode( $atts, $content = "" ) {
	extract( shortcode_atts( [], $atts ) );
    ob_start();
    	get_template_part( 'template-parts/content', 'curriculum' );
	return ob_get_clean();
}

add_shortcode( 'competition', 'robo_competition_shortcode' );

function robo_competition_shortcode( $atts, $content = "" ) {
	extract( shortcode_atts( [], $atts ) );
    ob_start();

	$query_args = array(
		'post_type'      => 'competition',
		'posts_per_page' =>  -1,
	);

	$the_query = new WP_Query( $query_args );

	if( $the_query->have_posts() ):
        while( $the_query->have_posts() ) : $the_query->the_post();
        	get_template_part( 'template-parts/content', 'competitions' );
        endwhile;
    else:
        get_template_part( 'template-parts/content', 'none' );
    endif;

	return ob_get_clean();
}

add_shortcode( 'blog', 'robo_blog_shortcode' );

function robo_blog_shortcode( $atts, $content = "" ) {
	extract( shortcode_atts( [], $atts ) );
    ob_start();
    global $wp_query;
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$query_args = array(
		'post_type'      => 'post',
		'posts_per_page' =>  !empty( get_option( 'posts_per_page ' ) ) ? get_option( 'posts_per_page ' ) : 3
	);

	$the_query = new WP_Query( $query_args );
	if( $the_query->have_posts() ):
        echo "<div class=\"featured-blog py-5\">";
        echo "<div class=\"row \" id=\"blog_posts\">";
        while( $the_query->have_posts() ) : $the_query->the_post();
        	get_template_part( 'template-parts/content', get_post_format() );
        endwhile;
        ?>
        </div>
        <?php
         if (  $the_query->max_num_pages > 1 ) : ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <a class="btn btn-yellow btn-xl text-uppercase js-scroll-trigger" data-max_page=" <?php echo $the_query->max_num_pages; ?> " id="load_more_blog" href="#">Load more  </a>
            </div>
        </div>
    </div>
        <?php endif;
    else:
        get_template_part( 'template-parts/content', 'none' );
    endif;

	return ob_get_clean();
}

add_shortcode( 'robo_feature_competition', 'robo_feature_competition' );

function robo_feature_competition( $atts, $content = "" ) {
    extract( shortcode_atts( [], $atts ) );
    ob_start();
    get_template_part( 'template-parts/feature-competition' );
    return ob_get_clean();
}

add_shortcode( 'robo_previous_competition', 'robo_previous_competition' );

function robo_previous_competition( $atts, $content = "" ) {
    extract( shortcode_atts( [], $atts ) );
    ob_start();
    get_template_part( 'template-parts/previous-competition' );
    return ob_get_clean();
}

add_shortcode( 'robo_feature_post', 'robo_feature_post' );

function robo_feature_post( $atts, $content = "" ) {
    extract( shortcode_atts( [], $atts ) );
    ob_start();
    get_template_part( 'template-parts/feature-post' );
    return ob_get_clean();
}

add_shortcode( 'robo_event', 'robo_event' );

function robo_event( $atts, $content = "" ) {
    extract( shortcode_atts( [], $atts ) );
    ob_start();
    get_template_part( 'template-parts/content-event' );
    return ob_get_clean();
}
