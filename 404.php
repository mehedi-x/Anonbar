<?php get_header(); ?>

<div class="block_404">
<h2>Not Found</h2>
<h1>Error 404</h1>
<p>Sorry the content you are looking for, it's unavailable.</p>
</div>

<div class="block_posts">
<h2>You may check your latest posts..</h2>
<ul class="rpul">

<?php query_posts('posts_per_page=10'); if (have_posts()) : while (have_posts()) : the_post(); ?>

<li><?php
// 404 post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>

<p><?php the_time( get_option( 'date_format' ) ) ?> - <?php the_time( get_option( 'time_format' ) ) ?></p>
</li>

 <?php endwhile; else: ?>
<?php endif; ?>

</ul>
</div>

        <?php get_sidebar(); ?>

        <?php get_footer(); ?>        