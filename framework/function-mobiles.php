<?php 
$my_theme = wp_get_theme();
$name = esc_html( $my_theme->get( 'Author' ) );
$author_url = esc_html( $my_theme->get( 'AuthorURI' ) );
if( $name == 'Hafizul Islam' && $author_url == 'http://facebook.com/hafizulofficial' ){
	
add_action( 'after_setup_theme', 'tie_setupss' );
function tie_setupss() {
    register_nav_menus( array(
	    'footer-left'	=> __( 'Footer Left (M)' ),
		'footer-right'	=> __( 'Footer Right (M)' )
	) );
	
	add_filter( 'wp_nav_menu_items', 'wti_loginout_footer_menu_link', 10, 2 );
	function wti_loginout_footer_menu_link( $items, $args ) {
		if ($args->theme_location == 'footer-right') {
			if (is_user_logged_in()) {
				global $current_user;
				$items .= '<li><a href="'. wp_logout_url() .'">Logout ('.$current_user->user_login.')</a></li>';
			}
		}
		return $items;
	}

	$pageindex = get_page_by_title( 'Mobile Dashboard' );
	if($pageindex==""){
		$my_post1 = array(
		'post_title'     => 'Mobile Dashboard',
		'page_template'  => 'template-mobile-dashboard.php',
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'post_author'    => 1,
		'post_category'  => '',
		'comment_status' => 'closed',
		'ping_status'    => 'closed'
		);
		$pageindex=wp_insert_post( $my_post1 );
	}
	$permalink = get_permalink( $pageindex);	
	$update_val = array('plink_dash' => $permalink);
	update_option('ln_options1', $update_val);
}


function post_exist_check($post_id){
	global $wpdb;
       	$post_id = $wpdb->get_col("SELECT `ID` FROM $wpdb->posts WHERE `ID` = '$post_id' AND `post_type` = 'post' AND `post_status` != 'trash'"); 
    return $post_id[0]; 
}

function PostCount($user_id, $status){
	global $wpdb;
	$tableName =  $wpdb->prefix . 'posts';
	$query = "SELECT COUNT(*) FROM $tableName WHERE post_status = '$status' AND post_author = '$user_id' AND post_type='post'";
	$post_count = $wpdb->get_var($query);
	return $post_count;
}


function remove_unnecessary_slug($str){	
	$str = preg_replace('/[^a-zA-Z0-9]/i',' ', $str);
	$str = trim($str);
	$str = preg_replace('/\s+/', ' ', $str);
	$str = preg_replace('/\s+/', ' ', $str);
	$str = ucfirst(strtolower($str));
	return $str;
}

function get_icon_for_attachment($post_id){
  $base = get_template_directory_uri() . "/images/media/";
  $type = get_post_mime_type($post_id);
  switch ($type) {
    case 'image/jpg':
    case 'image/jpeg':
    case 'image/png':
    case 'image/gif':
        return $base . "interactive.png"; break;
    case 'video/mpeg':
    case 'video/mp4': 
    case 'video/quicktime':
        return $base . "video.png"; break;
	case 'audio/mpeg':
    case 'audio/mp4': 
    case 'audio/quicktime':
        return $base . "audio.png"; break;
    case 'text/csv':
    case 'text/plain': 
    case 'text/xml':
        return $base . "text.png"; break; 
	case 'application/zip':
    case 'application/rar':  
        return $base . "archive.png"; break;
	case 'application/msword': 
        return $base . "document.png"; break;
    default:
        return $base . "default.png";
  }
}

//start for Feature Posts
function remove_footer_admin () {
	echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | Designed by <a href="http://facebook.com/hafizulofficial" target="_blank">Hafizul Islam</a></p>';
}
add_filter('admin_footer_text', 'remove_footer_admin');


function get_image_id($image_url) {
	global $wpdb;
       	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    return $attachment[0]; 
}


function bak_bbcode_bold($atts, $content = null) {
	return "<b>".do_shortcode($content)."</b>";
}
add_shortcode( 'b', 'bak_bbcode_bold' );
add_shortcode( 'B', 'bak_bbcode_bold' );

function bak_bbcode_dd($atts, $content = null) {
	return "<dd>".do_shortcode($content)."</dd>";
}
add_shortcode( 'dd', 'bak_bbcode_dd' );
add_shortcode( 'Dd', 'bak_bbcode_dd' );

function bak_bbcode_justify($atts, $content = null) {
	return "<p align='justify'>".do_shortcode($content)."</p>";
}
add_shortcode( 'justify', 'bak_bbcode_justify' );
add_shortcode( 'Justify', 'bak_bbcode_justify' );

function bak_bbcode_line_break($atts, $content = null) {
	return "<br>".do_shortcode($content)."</>";
}
add_shortcode( 'br', 'bak_bbcode_line_break' );
add_shortcode( 'Br', 'bak_bbcode_line_break' );

function bak_bbcode_left($atts, $content = null) {
	return "<left>".do_shortcode($content)."</left>";
}
add_shortcode( 'left', 'bak_bbcode_left' );
add_shortcode( 'left', 'bak_bbcode_left' );

function bak_bbcode_right($atts, $content = null) {
	return "<right>".do_shortcode($content)."</right>";
}
add_shortcode( 'right', 'bak_bbcode_right' );
add_shortcode( 'Right', 'bak_bbcode_right' );

function bak_bbcode_italic($atts, $content = null) {
	return "<span class='bak_bbc_italic'>".do_shortcode($content)."</span>";
}
add_shortcode( 'i', 'bak_bbcode_italic' );
add_shortcode( 'I', 'bak_bbcode_italic' );

function bak_bbcode_sub($atts, $content = null) {
	return "<sub>".do_shortcode($content)."</sub>";
}
add_shortcode( 'sub', 'bak_bbcode_sub' );
add_shortcode( 'Sub', 'bak_bbcode_sub' );

function bak_bbcode_p($atts, $content = null) {
	return "<p class='custom_para'>".do_shortcode($content)."</p>";
}
add_shortcode( 'p', 'bak_bbcode_p' );
add_shortcode( 'P', 'bak_bbcode_p' );

function bak_bbcode_h1($atts, $content = null) {
	return "<h1 class='custom_para_h1'>".do_shortcode($content)."</h1>";
}
add_shortcode( 'h1', 'bak_bbcode_h1' );
add_shortcode( 'H1', 'bak_bbcode_h1' );

function bak_bbcode_h2($atts, $content = null) {
	return "<h2 class='custom_para_h2'>".do_shortcode($content)."</h2>";
}
add_shortcode( 'h2', 'bak_bbcode_h2' );
add_shortcode( 'H2', 'bak_bbcode_h2' );

function bak_bbcode_h3($atts, $content = null) {
	return "<h3 class='custom_para_h3'>".do_shortcode($content)."</h3>";
}
add_shortcode( 'h3', 'bak_bbcode_h3' );
add_shortcode( 'H3', 'bak_bbcode_h3' );

function bak_bbcode_h4($atts, $content = null) {
	return "<h4 class='custom_para_h4'>".do_shortcode($content)."</h4>";
}
add_shortcode( 'h4', 'bak_bbcode_h4' );
add_shortcode( 'H4', 'bak_bbcode_h4' );

function bak_bbcode_h5($atts, $content = null) {
	return "<h5 class='custom_para_h5'>".do_shortcode($content)."</h5>";
}
add_shortcode( 'h5', 'bak_bbcode_h5' );
add_shortcode( 'H5', 'bak_bbcode_h5' );

function bak_bbcode_h6($atts, $content = null) {
	return "<h6 class='custom_para_h6'>".do_shortcode($content)."</h6>";
}
add_shortcode( 'h6', 'bak_bbcode_h6' );
add_shortcode( 'H6', 'bak_bbcode_h6' );

function bak_bbcode_underline($atts, $content = null) {
	return "<span class='bak_bbc_underline'>".do_shortcode($content)."</span>";
}
add_shortcode( 'u', 'bak_bbcode_underline' );
add_shortcode( 'U', 'bak_bbcode_underline' );

function bak_bbcode_ol($atts, $content = null) {
	return "<ol class='bak_bbc_ol'>".do_shortcode($content)."</ol>";
}
add_shortcode( 'ol', 'bak_bbcode_ol' );
add_shortcode( 'OL', 'bak_bbcode_ol' );

function bak_bbcode_select($atts, $content = null) {
	return "<select name='Options'>".do_shortcode($content)."</select>";
}
add_shortcode( 'select', 'bak_bbcode_select' );
add_shortcode( 'Select', 'bak_bbcode_select' );

function bak_bbcode_option($atts, $content = null) {
	return "<option value=''>".do_shortcode($content)."</option  >";
}
add_shortcode( 'option', 'bak_bbcode_option' );
add_shortcode( 'option', 'bak_bbcode_option' );

function bak_bbcode_ul($atts, $content = null) {
	return "<ul class='bak_bbc_ul'>".do_shortcode($content)."</ul>";
}
add_shortcode( 'ul', 'bak_bbcode_ul' );
add_shortcode( 'UL', 'bak_bbcode_ul' );

function bak_bbcode_li($atts, $content = null) {
	return "<li class='bak_bbc_li'>".do_shortcode($content)."</li>";
}
add_shortcode( 'li', 'bak_bbcode_li' );
add_shortcode( 'LI', 'bak_bbcode_li' );

function bak_bbcode_table($atts, $content = null) {
	return "<table class='bak_bbc_table'>".do_shortcode($content)."</table>";
}
add_shortcode( 'table', 'bak_bbcode_table' );
add_shortcode( 'TABLE', 'bak_bbcode_table' );

function bak_bbcode_tr($atts, $content = null) {
	return "<tr class='bak_bbc_tr'>".do_shortcode($content)."</tr>";
}
add_shortcode( 'tr', 'bak_bbcode_tr' );
add_shortcode( 'TR', 'bak_bbcode_tr' );

function bak_bbcode_th($atts, $content = null) {
	return "<th class='bak_bbc_th'>".do_shortcode($content)."</th>";
}
add_shortcode( 'th', 'bak_bbcode_th' );
add_shortcode( 'TH', 'bak_bbcode_th' );

function bak_bbcode_td($atts, $content = null) {
	return "<td class='bak_bbc_td'>".do_shortcode($content)."</td>";
}
add_shortcode( 'td', 'bak_bbcode_td' );
add_shortcode( 'TD', 'bak_bbcode_td' );

function bak_bbcode_strike($atts, $content = null) {
	return "<span class='bak_bbc_strike'>".do_shortcode($content)."</span>";
}
add_shortcode( 'strike', 'bak_bbcode_strike' );
add_shortcode( 'STRIKE', 'bak_bbcode_strike' );
add_shortcode( 's', 'bak_bbcode_strike' );
add_shortcode( 'S', 'bak_bbcode_strike' );

//start for Link bbcode
function bak_bbcode_url($atts, $content = null) {
	if (empty($atts)){
		$return = "<a class='bak_bbc_url' href='$content'>".$content."</a>";
	}else{
		$attribs = implode('',$atts);
		$url = substr ( $attribs, 1);
		$url = str_replace("'", ' ', str_replace('"', ' ', $url));
		
		$return = "<a href='$url' class='bak_bbc_url'>".$content."</a>";
	}
	return $return;
}
add_shortcode( 'url', 'bak_bbcode_url' );
add_shortcode( 'URL', 'bak_bbcode_url' );
add_shortcode( 'link', 'bak_bbcode_url' );
add_shortcode( 'LINK', 'bak_bbcode_url' );
add_shortcode( 'a', 'bak_bbcode_url' );


//start for Color bbcode
function bak_bbcode_color($atts, $content = null) {
	if (empty($atts)){
		$color = 'black';
	}else{
		$attribs = implode('',$atts);
		$color = str_replace("'", ' ', str_replace('"', ' ', substr ( $attribs, 1)));
		if(ctype_xdigit($color)) {
			$color = '#'.$color;
		}
	}
	return "<span style='color: $color; '>".do_shortcode($content)."</span>";
}
add_shortcode( 'color', 'bak_bbcode_color' );
add_shortcode( 'COLOR', 'bak_bbcode_color' );
add_shortcode( 'colour', 'bak_bbcode_color' );
add_shortcode( 'COLOUR', 'bak_bbcode_color' );

}else{
	
	echo 'Please Contact the theme Author: http://facebook.com/hafizulofficial';
}

 ?>
