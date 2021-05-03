<?php get_header(); ?>

<div class="featured-blog py-5">
    <div class="container">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
			<?php
				echo "<div class=\"row py-5\">";
				while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', get_post_format() );
				endwhile;
				echo "</div>";
				the_posts_navigation();

				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
		?>
	</div><!-- #main -->
</div><!-- #primary -->
<?php get_footer(); ?>