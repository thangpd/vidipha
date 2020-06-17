<?php

get_header(); ?>

<?php do_action( 'flatsome_before_page' ); ?>

<div class="page-wrapper page-right-sidebar">
<div class="row">
<div class="large-3 col">
	<?php get_sidebar(); ?>
</div><!-- .sidebar -->
<div id="content" class="large-6 left col col-divided small-col-first" role="main">
	<div class="page-inner">
		<?php if(get_theme_mod('default_title', 0)){ ?>
			<header class="entry-header">
				<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
		<?php } ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>
			
			<?php if ( comments_open() || '0' != get_comments_number() ){
						comments_template(); } ?>

		<?php endwhile; // end of the loop. ?>
	</div><!-- .page-inner -->
</div><!-- .#content large-9 left -->


	<div class="post-sidebar large-3 col">
	<?php if ( is_active_sidebar( 'block-after-content' ) ) : ?>
                <?php dynamic_sidebar( 'block-after-content' ); ?>
<?php endif; ?>
	</div><!-- .post-sidebar -->
</div><!-- .row -->
</div><!-- .page-right-sidebar container -->

<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>