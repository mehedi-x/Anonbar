<?php get_header(); ?>


<div class="breadcumbs_single">
<div id="crumbs"><a href="/">Home</a> &rsaquo; <span class="current"><?php the_title(); ?></span></div></div>

<div class="block_single">

<h1><?php the_title(); ?></h1>


	<?php
		if ( isset( $_GET['postcomment'] ) ) :
			if ( have_posts() ) : while ( have_posts() ): the_post();
				comments_template( '/postcomment.php' );
			endwhile; endif;

		else :
	?>

		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

	
			
<div class="post_paragraph">
			<?php the_content(); ?>
		
		</div>

		

		<?php endwhile; ?>
		<?php endif;?>
	<?php endif; ?>

</div>
	
<?php get_sidebar(); ?>


<?php get_footer(); ?>