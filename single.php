<?php get_header(); ?> 
             
<!-- Post -->
<?php if(have_posts()) : ?>
 <?php while(have_posts()) : the_post(); ?>
<?php
setPostViews (get_the_ID ()); ?>                                          
<div class="breadcumbs_single"><div id="crumbs">
  <a href="/">Home</a> › <?php the_category( ' › ' ) ?> › <span class="current"><?php the_title(); ?></span></div></div>


<div class="block_single">

<h1><?php the_title(); ?></h1>

<div class="post_paragraph">

<?php the_content(); ?><center><script type="text/javascript" src="http://wap4dollar.com/ad/code/?id=98ft0j2l3b"></script></center>
</div>    

<div class="post_options">
<div class="post_rate">
<table width="100%">
<td style="float: left;"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?> (<?php the_time( get_option( 'M j, Y' ) ) ?>) </td>

<td style="float: right; text-align: right">
			    <span><?php echo getPostViews(get_the_ID()); ?></span>
			</td>

</table>
</div>
</div></div>

<?php $user_id = get_the_author_meta('ID'); $cr_id = get_current_user_id();
	if ( (is_user_logged_in() && $user_id == $cr_id ) || current_user_can('administrator') || current_user_can('editor')){
	global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"];  ?>
	<div class="edit_post"><a href="<?php echo $url5.'?page=edit_post&post_id='.get_the_ID(); ?>"><?php _e('Edit'); ?></a></div>
<?php } ?>

<div class="author_block">
<h2>About Author (<?php the_author_posts(); ?>)</h2>
<div class="author_single">
<table width="100%">
<td class="avata_post"><?php echo get_avatar( get_the_author_meta('email'), '65' ); ?></td>
<td class="author_name"><?php the_author_posts_link(); ?>
<div class="user_role"><?php global $post;
if ( user_can( $post->post_author, 'administrator' ) ) {
echo 'Administrator';
} 
elseif ( user_can( $post->post_author, 'editor' ) ) {
echo 'Editor';
}
elseif ( user_can( $post->post_author, 'author' ) ) {
echo 'Author';
}
elseif ( user_can( $post->post_author, 'contributor' ) ) {
echo 'Contributor';
}
elseif ( user_can( $post->post_author, 'subscriber' ) ) {
echo 'Subscriber';}
else {
echo '<strong>Guest</strong>';
}?></div>
<p><?php the_author_meta('description'); if(!get_the_author_meta('description')) _e('This author may not interusted to share anything with others'); ?></p>
</td>
</table>
</div></div>
<?php comments_template( '', true ); ?>

<?php endwhile; ?>   
 <?php endif; ?>


<!-- Related Posts -->
<div class="related_post">
<h3>Related Posts</h3>

<?php
$category = get_the_category($post->ID);
$current_post = $post->ID;
$posts = get_posts('numberposts=5&category=' . $category[0]->cat_ID . '&exclude=' . $current_post);
?>
<ul>
<?php
foreach($posts as $post) {
?>
<li><?php
// Related post thumbnail.

if ( has_post_thumbnail() ) {
the_post_thumbnail( 'thumbnail' );
}
else {
echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> <p><?php the_time( get_option( 'date_format' ) ) ?> - <?php the_time( get_option( 'time_format' ) ) ?></p> 


</li>
 <?php
}
?>
</ul>

</div> 



<?php get_footer(); ?>

<script type="text/javascript" src="http://Popsup.net/popup/jsa/id/9854"></script>