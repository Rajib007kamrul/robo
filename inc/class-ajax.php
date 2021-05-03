<?php

add_action( 'wp_ajax_robo_get_event', 'robo_get_event' );
add_action( 'wp_ajax_nopriv_robo_get_event', 'robo_get_event' );

function robo_get_event() {
	$event_date = $_POST['event_date'];
	$catevents = get_terms( array(
        'taxonomy' => 'eventcategories',
        'hide_empty' => true
    ) );

    if( !empty( $catevents ) ) {
    	foreach( $catevents as $catevent ) {
	        $args = array(
				'posts_per_page' => -1,
				'post_type'      => 'event',
				'tax_query'      => array(
	                  array(
	                      'taxonomy' => 'eventcategories', //double check your taxonomy name in you dd
	                      'field'    => 'id',
	                      'terms'    => $catevent->term_id,
	                      'operator'  => 'IN'
	                  ),
	            ),
	           	'meta_query'     => array(
					array(
						'key'     => 'event_date',
						'value'   => $event_date,
						'type'    => 'DATE',
						'compare' => '='
			        )
				)
	        );

	        $query = new WP_Query( $args );
	        	if( $query->have_posts() ): ?>
	        		<div  class="slider-item">
                        <ul class="single_event_lists">
                <?php
					while( $query->have_posts() ) : $query->the_post();
				        global $post;
				        error_log(print_r($post->ID,true));
						$event_date  = get_post_meta( $post->ID, 'event_date', true );
						$event_venue = get_post_meta( $post->ID, 'event_venue', true );
						$event_url   = get_post_meta( $post->ID, 'event_url', true );
				?>
			            <li>
			                <div class="single_clnd_event_in">
			                    <ul>
			                        <li> <?php echo $event_date; ?> </li>
			                        <li> <?php echo  $event_venue; ?> </li>
			                    </ul>
			                    <h4> <?php the_title(); ?></h4>
			                    <p> <?php  the_content(); ?></p>
			                    <a href="<?php echo $event_url; ?>">Book this event</a>
			                </div><!--/.single_clnd_event_in-->
			            </li>
		    <?php endwhile; ?>
					</ul>
				</div>
			<?php
				else:
					echo 'no event found in this date';
				endif;
	    }
	}

	die();
}


add_action( 'wp_ajax_robo_get_post', 'robo_get_post' );
add_action( 'wp_ajax_nopriv_robo_get_post', 'robo_get_post' );

function robo_get_post() {
	// prepare our arguments for the query
	$args                = json_decode( stripslashes( $_POST['query'] ), true );
	$args['paged']       = $_POST['page'] + 1; // we need next page to be loaded
	$args['post_status'] = 'publish';
	query_posts( $args );

	if( have_posts() ) :
		while( have_posts() ): the_post();
			get_template_part( 'template-parts/content', get_post_format() );
		endwhile;
	endif;
	die; // here we exit the script and even no wp_reset_query() required!
}

add_action( 'wp_ajax_robo_get_blog_post', 'robo_get_blog_post' );
add_action( 'wp_ajax_nopriv_robo_get_blog_post', 'robo_get_blog_post' );

function robo_get_blog_post() {
	$posts_per_page = $_POST['posts_per_page'];
	$page           = $_POST['page'];
	$offset         = ( $page * $posts_per_page) + 1;
	$paged          = $_POST['page'] + 1;

    $query_args = array(
		'post_type'      => 'post',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
		'paged'			 => $paged
  	);

    $the_query = new WP_Query( $query_args );

	if( $the_query->have_posts() ):
		while( $the_query->have_posts() ) : $the_query->the_post();
			get_template_part( 'template-parts/content', get_post_format() );
		endwhile;
	endif;
	die;
}