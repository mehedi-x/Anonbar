<?php get_header(); ?>

<?php if ( is_user_logged_in() ) {

    $current_user = wp_get_current_user();
	echo '<a class="btn btn-default" style="margin: 0px 5px;" href="/wp-admin/profile.php">Edit My Profile</a>';} ?>

<div class="author_block">
<!– This sets the $curauth variable –>
<?php
if(isset($_GET['author_name'])) :
$curauth = get_userdatabylogin($author_name);
else :
$curauth = get_userdata(intval($author));
endif;
?>
<h2>24TuneBD.Ga Site Profile of <?php the_author(); ?></h2>

<table width="100%">
<tr>
<td width="75px"><?php echo get_avatar( get_the_author_meta('email'), '75' ); ?></td>
<td>
<h3><?php the_author(); ?></h3>
<div class="user_role"><?php echo $curauth->user_role; ?><?php global $post;
if ( user_can( $post->post_author, 'administrator' ) ) {
echo 'Administrator';
} 
elseif ( user_can( $post->post_author, 'editor' ) ) {
echo 'Admin';
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
<p><?php echo $curauth->user_description; ?></p>
</td>
</tr>
</table>

<div class="author_info">
<p><span>Registered:</span> <?php echo $curauth->user_registered; ?></p>

<p><span>Website:</span> <?php echo $curauth->user_url; ?></p>

<p><span>User Posts:</span> <?php the_author_posts(); ?></p>

<p><span>User ID:</span> <?php the_author_id(); ?></p>
</div></div>

<div class="block_posts">
<h2><div class="breadcumbs"><div id="crumbs"><a href="/">Home</a> &rsaquo; <span class="current">Articles posted by <?php the_author(); ?></span></div></div></h2>

<ul class="rpul">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<li><?php
// Author post thumbnail.

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'thumbnail' );
}
else {
	echo '<img width="60" height="60" src="' . get_bloginfo( 'template_url' ) . '/images/default.jpg" class="attachment-thumbnail wp-post-image" alt="Default image" />';
} ?>

<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a> <p><?php the_time( get_option( 'date_format' ) ) ?> - <?php the_time( get_option( 'time_format' ) ) ?> <a href="<?php the_permalink(); ?>#comments"><?php comments_number( 'No Comments', '1 Comment', '% Comments' ); ?></a></p>
</li>

<?php endwhile; else: ?>

<p><?php _e('No posts by this author.'); ?></p>

<?php endif; ?>

</ul>
</div>

<?php if ($wp_query->max_num_pages > 1){ echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>