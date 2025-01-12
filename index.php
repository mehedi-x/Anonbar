<?php get_header(); ?>

<!-- Feature posts -->
<div class="block_posts"> 
<h2>Featured post</h2>
<ul class="rpul">
<?php $cat_id = 2;
$latest_cat_post = new WP_Query( array('posts_per_page' => 1, 'category__in' => array($cat_id)));
if( $latest_cat_post->have_posts() ) : while( $latest_cat_post->have_posts() ) : $latest_cat_post->the_post();  ?>

<li><?php
// Feature post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <p><?php the_time( get_option( 'date_format' ) ) ?>  <a href="<?php the_permalink(); ?>#comments"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a>  <font color="black"><?php echo
getPostViews (get_the_ID ()); ?></font></p>
</li>

<?php endwhile; endif; ?> 

</ul>
</div>




<!-- Hot posts -->
<div class="block_posts"> 
<h2>Hot post</h2>
<ul class="rpul">
<?php $cat_id = 3;
$latest_cat_post = new WP_Query( array('posts_per_page' => 4, 'category__in' => array($cat_id)));
if( $latest_cat_post->have_posts() ) : while( $latest_cat_post->have_posts() ) : $latest_cat_post->the_post();  ?>

<li><?php
// Feature post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <p><?php the_time( get_option( 'date_format' ) ) ?>   <a href="<?php the_permalink(); ?>#comments"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a> <font color="black"><?php echo
getPostViews (get_the_ID ()); ?></font></p>
</li>

<?php endwhile; endif; ?> 

</ul>
</div>




<!-- Recent posts -->
<div class="block_posts">
<h2>Recent Posts</h2>
<ul class="rpul">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<li><?php
// Recent post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>

<p><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?>  <a href="<?php the_permalink(); ?>#comments"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a></p> 
</li>

 <?php endwhile; else: ?>
<?php endif; ?>

</ul>
</div>


<?php wp_pagenavi(); ?>

<div class="ad_block"><center><a target="_blank" href="http://wap4dollar.com/ad/serve.php?id=98ft0j2l3b"><img src="http://trickbd.com/wp-content/uploads/2017/02/17/58a7091440de0.png" border="0" width="320" height="250" /></a></center></div>

<?php get_sidebar(); ?> 

<?php get_footer(); ?>
