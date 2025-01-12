<?php //start for Image bbcode
function azc_bbcode_pic($atts, $content = null) {
	if (empty($atts)){
		$downloadwe = get_template_directory_uri()."/img/placeholder.png";
		$return = "<img src=".$downloadwe." />";
	}else{
		$attribs = implode('',$atts);
		$images = str_replace("'", ' ', str_replace('"', ' ', substr ( $attribs, 1)));
		if(ctype_xdigit($images)) {
			$images_str = wp_get_attachment_url( $images );
			$images_tit = get_the_title( $images );
		}
		$return = "<img src=".$images_str." alt=".$images_tit." />";
	}
	return $return;
}
add_shortcode( 'img', 'azc_bbcode_pic' );
add_shortcode( 'IMG', 'azc_bbcode_pic' );
 /* Title Home and other page */
    function custom_excerpt_length( $length ) {
        return 40;
    }
    add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/* Disable WordPress Admin Bar for all users but admins. */show_admin_bar(false);

/* Seo Friendly Title
*/ 
function sbtheme_wp_title( $title ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	$site_description = get_bloginfo( 'description' );

	$filtered_title = $title . get_bloginfo( 'name' );
	$filtered_title .= ( ! empty( $site_description ) && ( is_home() || is_front_page() ) ) ? ' || ' . $site_description: '';
	$filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' - ' . sprintf( __( 'Page %s', 'sbtheme' ), max( $paged, $page ) ) : '';

	return $filtered_title;
}
add_filter( 'wp_title', 'sbtheme_wp_title' );

/*
Enable support for Post Thumbnails on posts and pages */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 500, 500, true );


/* Custom login logo */
function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image: url('.get_bloginfo('template_directory').'/images/login-logo.png) !important; background-size: 80% auto !important; width: 80% !important; }
	</style>'; 
}
add_action('login_head', 'custom_login_logo');

function loginpage_custom_link() {
	return '/';
}

require_once ( get_template_directory() . '/framework/functions/function-mobiles.php'     );


/* Round */
function round_num($num, $to_nearest) {
   return floor($num/$to_nearest)*$to_nearest;
}

/* Pagination */
function pagenavi($before = '', $after = '') {  
    global $wpdb, $wp_query;
    $pagenavi_options = array();
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = ('First');
    $pagenavi_options['last_text'] = ('Last');
    $pagenavi_options['next_text'] = '&raquo;';
    $pagenavi_options['prev_text'] = '&laquo;';
    $pagenavi_options['dotright_text'] = '...';
    $pagenavi_options['dotleft_text'] = '...';
    $pagenavi_options['num_pages'] = 5; 
    $pagenavi_options['always_show'] = 0;
    $pagenavi_options['num_larger_page_numbers'] = 0;
    $pagenavi_options['larger_page_numbers_multiple'] = 5;    
    
	if (!is_single()) {
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
       
	    if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
        $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
        if($start_page <= 0) {
            $start_page = 1;
        }
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }
        $larger_per_page = $larger_page_to_show*$larger_page_multiple;
        $larger_start_page_start = (round_num($start_page, 10) + $larger_page_multiple) - $larger_per_page;
        $larger_start_page_end = round_num($start_page, 10) + $larger_page_multiple;
        $larger_end_page_start = round_num($end_page, 10) + $larger_page_multiple;
        $larger_end_page_end = round_num($end_page, 10) + ($larger_per_page);
        if($larger_start_page_end - $larger_page_multiple == $start_page) {
            $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
            $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
        }   
        if($larger_start_page_start <= 0) {
            $larger_start_page_start = $larger_page_multiple;
        }
        if($larger_start_page_end > $max_page) {
            $larger_start_page_end = $max_page;
        }
        if($larger_end_page_end > $max_page) {
            $larger_end_page_end = $max_page;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
            $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
            $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
            echo $before.'<div id="pagination-links">'."\n";
            if(!empty($pages_text)) {
                echo '<span class="pages">'.$pages_text.'</span>';
            }
			previous_posts_link($pagenavi_options['prev_text']);
             
            if ($start_page >= 2 && $pages_to_show < $max_page) {
                $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
                echo '<a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'">1</a>';
                if(!empty($pagenavi_options['dotleft_text'])) {
                    echo '<span class="expand">'.$pagenavi_options['dotleft_text'].'</span>';
                }
            }
            if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
                for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            for($i = $start_page; $i  <= $end_page; $i++) {                      
                if($i == $paged) {
                    $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                    echo '<span class="current">'.$current_page_text.'</span>';
                } else {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            if ($end_page < $max_page) {
                if(!empty($pagenavi_options['dotright_text'])) {
                    echo '<span class="expand">'.$pagenavi_options['dotright_text'].'</span>';
                }
                $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
                echo '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$max_page.'</a>';
            }
            next_posts_link($pagenavi_options['next_text'], $max_page);
            if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
                for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            echo '</div>'.$after."\n";
        }
    }
}

/* Homepage redirect */
add_filter('login_headerurl','loginpage_custom_link'); 

add_action('login_form', 'redirect_after_login');
function redirect_after_login() {
global $redirect_to;
if (!isset($_GET['redirect_to'])) {
$redirect_to = get_option('siteurl');
}
}

/* Post view conter*/
function getPostViews ($postID ){
$count_key = 'post_views_count';
$count = get_post_meta ($postID, $count_key , true );
if($count =='' ){
delete_post_meta ($postID , $count_key );
add_post_meta ( $postID , $count_key , '0' );
return "no views" ;
}
return $count . ' views';
}
function setPostViews ( $postID ) {
$count_key = 'post_views_count';
$count = get_post_meta ($postID, $count_key , true );
if($count =='' ){
$count = 0;
delete_post_meta ($postID , $count_key );
add_post_meta ( $postID , $count_key , '0' );
}else{
$count = $count + 1;
update_post_meta ( $postID , $count_key , $count );
}
}
// Remove issues with prefetching adding extra views
remove_action ( 'wp_head' , 'adjacent_posts_rel_link_wp_head' , 10 , 0);
?>