<?php get_header(); ?>

<div class="container py-5">
    <div class="row">
		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( '404', 'robo' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'robo' ); ?></p>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
	</div><!-- #row -->
</div><!-- #container -->
<?php get_footer(); ?>