<?php get_header(); ?>

<div class="block_posts">
<h2><div class="breadcumbs">
<div id="crumbs"><a href="/">Home</a> › <span class="current">Archive by category '<?php single_cat_title(); ?>'</span></div></div></h2>

<ul class="rpul">
	
		<?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

<li><?php
// Archive post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
 <p><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?> <a href="<?php the_permalink(); ?>#comments"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a></p>
</li>

<?php endwhile; ?> 
<?php endif; ?>
</ul>
</div>

<?php if ($wp_query->max_num_pages > 1){ echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>