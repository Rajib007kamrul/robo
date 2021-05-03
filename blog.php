<?php /* Template Name: Blog Template */  get_header(); ?>

    <div class="featured-blog py-5">
        <div class="container">
            <?php
              $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
              $query_args = array(
                  'post_type' => 'post',
                  'posts_per_page' => -1,
              );

              $the_query = new WP_Query( $query_args );

              if( $the_query->have_posts() ):
                echo "<div class=\"row py-5\">";
                  while( $the_query->have_posts() ) : $the_query->the_post();
                      get_template_part( 'template-parts/content', get_post_format() );
                  endwhile;
                echo "</div>";
                else:

                endif;
            ?>
        </div>
    </div>
<?php get_footer(); ?>