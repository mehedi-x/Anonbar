<?php
/*
Template Name: Mobile Dashboard
*/
?>
<?php if ( !is_user_logged_in() ){
	wp_redirect( wp_login_url(get_permalink()));
	exit;
}else{ 

//Start Sub Page
if ($_GET['page'] == "thumbnail"){ 
    
	//Set Post Thumbnail
	if ( $_REQUEST['page'] == 'thumbnail' && isset( $_POST['submit'] ) ){
        $return_id    = $_POST['return_id'];
		$past_thumb   = get_post_thumbnail_id($return_id); 
		
	    if( isset( $_POST['my_image_upload_nonce'], $return_id ) && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' ) ){
	    	foreach( $_FILES as $file => $array ){
		    	if( isset($_FILES['my_image_upload']) && ($_FILES['my_image_upload']['size'] > 0) ){
		    		$arr_file_type  =  wp_check_filetype(basename($_FILES['my_image_upload']['name']));
			    	$detect_type    =  $arr_file_type['type'];
					$allowed_type   =  array('image/jpg','image/jpeg','image/gif','image/png');
					$img_info       =  getimagesize($_FILES['my_image_upload']['tmp_name']);
					$img_width      =  $img_info[0];
					$img_height     =  $img_info[1];
					$img_cnt        =  count($_FILES['my_image_upload']['name']);
					
					if( $img_cnt > 1 ){
			    		$err .= "Please select only one image";
			      	}elseif( ! in_array($detect_type, $allowed_type) ){
						$err .= "Please select a JPG, JPEG, GIF, or PNG file.";
					}
				}
			}
	    }
		
		if( $return_id ){
			if (!function_exists('wp_generate_attachment_metadata')){
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }
			
			if( isset($_FILES['my_image_upload']) && ($_FILES['my_image_upload']['size'] > 0) && $err == "" ){
				$attach_id = media_handle_upload( $file, $return_id );	
			}elseif( isset($_POST['rdb']) ){
				$attach_id = $_POST['rdb'];	
			}elseif( $past_thumb ){
				$attach_id = $past_thumb;	
			}else{
				$err .= "Please select a thumbnail.";
			}
		}
		
		if ( $err == "" ){
	     	// Upload file in Media directory
	        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
			
			//Post save as draft and redirect url
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			$redirect_ul = $url5.'?action=confirm_thumb&return_id='.$return_id.'&thumb_id='.$attach_id;
			wp_redirect( $redirect_ul );
		}
    } 
	
    // Short Urk for view Post
	$post_short	= esc_url(wp_get_shortlink( $return_id ));
	
	// Attachment ID
	$curnt_user = get_current_user_id();
	$args = array(
    	   	'post_type' => 'attachment',
			'post_status' => array('publish', 'draft', 'inherit'),
			'numberposts' => -1,
			'posts_per_page' => 20,
			'orderby' => 'menu_order ID',
			'order' => 'DESC',
			'post_mime_type' => 'image',
			'author' => $curnt_user,
		    'paged' => get_query_var('paged')
		); 
	$wp_query = new WP_Query($args);
	
	global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"]; 
	get_header(); ?>
    
	
	<div class="notify success">Your post was submitted.</div>
<div class="notify">
<ul>
Please select thumbnail to publish post.
<li>You can upload new photo to set thumbnail.</li>
<li>From bellow media list you can also just select a a photo.</li>
<li>Your thumbnail must be related to Post.</li>
	</ul>
	</div>
	
	<div class="block_single">
    	<h1>Upload new</h1>
	
    <div id="m_sec"> <?php if ( $err != "" ){
		echo "<div id='error_rep'>".$err."</div>";
	} ?>
	
	<?php // get post link from post
	    $return_id = isset( $_REQUEST['return_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['return_id'] )
	    : $_REQUEST['return_id'] ) : '';
	    $return_id = urldecode( $return_id );
		// get thumbnail 
		$past_thumb = get_post_thumbnail_id( $return_id ); 
	?>
		
    <form action="" id="primary_form" method="post" enctype="multipart/form-data">
	    <input id="my_image_upload" name="my_image_upload" type="file" multiple="false" multiple accept="image/*"/>
		
		<input type="hidden" name="page" value="thumbnail"/>	
		<input type="hidden" id="return_id" name="return_id" value="<?php echo $return_id; ?>"/>
		<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
		<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Submit', 'tie' ) ?>"/>
	</form>
	</div></div>

	
	<div id="thumb_bujj">
	
	<div class="page-head">
    	<h1>Select thumbnail</h1>
	</div>	
	
    <?php if( have_posts() ):
        echo '<div id="thumbanail-list"><ul>';
	while( have_posts() ): the_post();
	    $image_url = wp_get_attachment_url( $wp_query->ID );
		$image_id = get_image_id($image_url);
		$name = basename(get_attached_file($image_id));
		global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 		?>
		 
    	<li><div class="thumb_div">
	     	<a href="<?php echo $url5.'?action=confirm_thumb&return_id='.$return_id.'&thumb_id='.$image_id.''; ?>">
	    		<?php echo wp_get_attachment_image( $image_id, 'figcaption' ); ?>
			</a>
		</div></li>
			
	<?php endwhile; echo '</ul></div>';
	  	if ($wp_query->max_num_pages > 1){ echo '<div id="media-pag">'; echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; echo "</div>";  } ?>
	<?php else: ?>
	    <p><?php _e("You doesn't have any thumbnail."); ?></p>
	<?php endif; wp_reset_query(); ?>
		
    </div>
	
<?php }elseif ($_GET['page'] == "edit_thumb"){ 
    
	//Set Post Thumbnail
	if ( $_REQUEST['page'] == 'thumbnail' && isset( $_POST['submit'] ) ){
        $return_id    = $_POST['return_id'];
		$past_thumb   = get_post_thumbnail_id($return_id); 
		
	    if( isset( $_POST['my_image_upload_nonce'], $return_id ) && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' ) ){
	    	foreach( $_FILES as $file => $array ){
		    	if( isset($_FILES['my_image_upload']) && ($_FILES['my_image_upload']['size'] > 0) ){
		    		$arr_file_type  =  wp_check_filetype(basename($_FILES['my_image_upload']['name']));
			    	$detect_type    =  $arr_file_type['type'];
					$allowed_type   =  array('image/jpg','image/jpeg','image/gif','image/png');
					$img_info       =  getimagesize($_FILES['my_image_upload']['tmp_name']);
					$img_width      =  $img_info[0];
					$img_height     =  $img_info[1];
					$img_cnt        =  count($_FILES['my_image_upload']['name']);
					
					if( $img_cnt > 1 ){
			    		$err .= "Please select only one image";
			      	}elseif( ! in_array($detect_type, $allowed_type) ){
						$err .= "Please select a JPG, JPEG, GIF, or PNG file.";
					}
				}
			}
	    }
		
		if( $return_id ){
			if (!function_exists('wp_generate_attachment_metadata')){
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }
			
			if( isset($_FILES['my_image_upload']) && ($_FILES['my_image_upload']['size'] > 0) && $err == "" ){
				$attach_id = media_handle_upload( $file, $return_id );	
			}elseif( isset($_POST['rdb']) ){
				$attach_id = $_POST['rdb'];	
			}elseif( $past_thumb ){
				$attach_id = $past_thumb;	
			}else{
				$err .= "Please select a thumbnail.";
			}
		}
		
		if ( $err == "" ){
	     	// Upload file in Media directory
	        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
			
			//Post save as draft and redirect url
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			$redirect_ul = $url5.'?page=edit_post&post_id='.$return_id.'&thumb_id='.$attach_id;
			wp_redirect( $redirect_ul );
		}
    } 
	
    // Short Urk for view Post
	$post_short	= esc_url(wp_get_shortlink( $return_id ));
	
	// Attachment ID
	$curnt_user = get_current_user_id();
	$args = array(
    	   	'post_type' => 'attachment',
			'post_status' => array('publish', 'draft', 'inherit'),
			'numberposts' => -1,
			'posts_per_page' => 20,
			'orderby' => 'menu_order ID',
			'order' => 'DESC',
			'post_mime_type' => 'image',
			'author' => $curnt_user,
		    'paged' => get_query_var('paged')
		); 
	$wp_query = new WP_Query($args);
	
	global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"]; 
	get_header(); ?>
    
	<div id="media_bujj">
	
	<div class="notiff">
	<ul>
		<li>Please select thumbnail to publish post.</li>
		<li>You can upload new photo to set thumbnail.</li>
		<li>From bellow media list you can also just select a a photo.</li>
		<li>Your thumbnail must be related to Post.</li>
	</ul>
	</div>
	
	<div class="page-head">
    	<h1>Upload new</h1>
	</div>	
	
    <div id="m_sec"> <?php if ( $err != "" ){
		echo "<div id='error_rep'>".$err."</div>";
	} ?>
	
	<?php // get post link from post
	    $return_id = isset( $_REQUEST['return_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['return_id'] )
	    : $_REQUEST['return_id'] ) : '';
	    $return_id = urldecode( $return_id );
		// get thumbnail 
		$past_thumb = get_post_thumbnail_id( $return_id ); 
	?>
		
    <form action="" id="primary_form" method="post" enctype="multipart/form-data">
	    <input id="my_image_upload" name="my_image_upload" type="file" multiple="false" multiple accept="image/*"/>
		
		<input type="hidden" name="page" value="thumbnail"/>	
		<input type="hidden" id="return_id" name="return_id" value="<?php echo $return_id; ?>"/>
		<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
		<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Submit', 'tie' ) ?>"/>
	</form>	
	</div>
	</div>
	
	<div id="thumb_bujj">
	
	<div class="page-head">
    	<h1>Select thumbnail</h1>
	</div>	
	
    <?php if( have_posts() ):
        echo '<div id="thumbanail-list"><ul>';
	while( have_posts() ): the_post();
	    $image_url = wp_get_attachment_url( $wp_query->ID );
		$image_id = get_image_id($image_url);
		$name = basename(get_attached_file($image_id));
		global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 		?>
		 
    	<li><div class="thumb_div">
	     	<a href="<?php echo $url5.'?page=edit_post&post_id='.$return_id.'&thumb_id='.$image_id; ?>">
	    		<?php echo wp_get_attachment_image( $image_id, 'figcaption' ); ?>
			</a>
		</div></li>
			
	<?php endwhile; echo '</ul></div>';
	  	if ($wp_query->max_num_pages > 1){ echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; } ?>
	<?php else: ?>
	    <p><?php _e("You doesn't have any thumbnail."); ?></p>
	<?php endif; wp_reset_query(); ?>
		
    </div>
	
<?php }elseif ($_GET['page'] == "bb_code"){ get_header(); ?>
    
<div id="media_bujj">
<?php global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"]; 
	$new_post = $url5.'?page=new_post';
	$media = $url5.'?page=media';
	$bbcode = $url5.'?page=bb_code';
?>
	
<div class="page-head"><h1><a href="<?php echo $new_post; ?>">Add Post</a> | BB Codes</h1></div>	
<div id="m_sec"> 
<div class=ntc>

*<b>পোষ্টে লিংক দিতে [*url= your link] Download Now[\url]</b><br><br><br>

* <b>পোষ্টে কোড দিতে [*code]your code[/code]</b><br><br><br>

  *<b> লিখাকে গাড় করতে [*b]your text[/b]</b><br><br><br>

* <b>লিখাকে কালার করতে [*color=color name]your text[/color]</b><br><br><br>
<b>(*)রিমুভ করে নিবেন</div>



</div>
</div>
	
<?php }elseif($_GET['page'] == "media"){ 

    //Start for New Upload
if( isset($_POST['my_image_upload_nonce']) && wp_verify_nonce($_POST['my_image_upload_nonce'], 'upload_attachment') ){
		
	if ($_FILES){

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );


        $files = $_FILES['upload_attachment'];
        $count = 0;
        $galleryImages = array();
		$media_rirle = $_POST['posttitle'];

        foreach ($files['name'] as $count => $value){

            if ($files['name'][$count]) {

                $file = array(
                    'name'     => $files['name'][$count],
                    'type'     => $files['type'][$count],
                    'tmp_name' => $files['tmp_name'][$count],
                    'error'    => $files['error'][$count],
                    'size'     => $files['size'][$count]
                );

                $upload_overrides = array( 'test_form' => false );
                $upload = wp_handle_upload($file, $upload_overrides);


                // $filename should be the path to a file in the upload directory.
                $filename = $upload['file'];

                // The ID of the post this attachment is for.
                $parent_post_id = $post_id;

                // Check the type of tile. We'll use this as the 'post_mime_type'.
                $filetype = wp_check_filetype( basename( $filename ), null );

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();
				
				$cat = array();
				$eror = "";
				$user_id 		= $current_user->user_id;
				$md_title 	= $_POST['md_title'];
				
				if( $md_title == "" ){
	             	$eror .= __('Please fill in Post Title field') . "<br />";
				}
				
				if ( $eror == "" ){

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => $md_title,
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once( ABSPATH . 'wp-admin/includes/image.php' );

                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
                wp_update_attachment_metadata( $attach_id, $attach_data );
				
				global $wpdb;
				$options1 = get_option('ln_options1'); 
				$url5 = $options1["plink_dash"]; 
				
				$redirect_edit = $url5.'?page=media';
				header('Location:'.$redirect_edit);
				}
			}
	    }
	}
}  get_header(); ?>

<?php global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"]; 
	$media = $url5.'?page=media';
	$new_posts = $url5.'?page=new_post';
?>
	<div class="block_single"><h1><a href="<?php echo $url5; ?>">Posts</a> | Add Screenshot</h1>

<div class="media_upload pad4">
<div class="upload-form">
<?php if ($eror != "") {
echo "<div id='eror'><p>".$eror."</p></div>";
} ?>

<form action="" id="post_form" method="post" enctype="multipart/form-data" > <div class="field">
<label for="md_title"><?php _e('<strong>Title</strong>'); ?></label>
<input style="width: 80%;" id="md_title" name="md_title" title="Search" value="<?php echo bloginfo('name'); ?>" onfocus="if (this.value == '<?php echo bloginfo('name'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo bloginfo('name'); ?>';}" type="text"></div>

<div class="field">
<label for="gfgfg"><?php _e('<strong>Your Photo</strong>'); ?></label>
<input type="file" name="upload_attachment[]" class="files" multiple="multiple" multiple accept="image/*" id="gfgfg"/>
</div>
<?php wp_nonce_field( 'upload_attachment', 'my_image_upload_nonce' ); ?>
<input id="submit" type="submit" value="Upload" />
</form>
</div> </div>
</div>

<div class="block_single"><h1>Media</h1>

<div class="tp_src">
    <form action="<?php echo $url5.'?page=media';  ?>" id="search_form" method="get">
	    <input type="hidden" name="page" value="media" />		
	</form>
</div>
	
<div id="post_buj">
	<?php // Search Result Count
    $search = isset( $_REQUEST['search'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['search'] )
	: $_REQUEST['search'] ) : '';
	$search     = urldecode( $search ); 
	$curnt_user = get_current_user_id();
	
    $item = array(
    		'post_type' => 'attachment',
			'post_status' => array('publish', 'draft', 'inherit'),
			's' => $search,
			'showposts' => -1,
			'posts_per_page' => 16,
			'orderby' => 'menu_order ID',
			'order' => 'DESC',
			'post_mime_type' => 'image',
			'author' => $curnt_user,
		    'paged' => get_query_var('paged')
	); 
	$s_query = new WP_Query($item);
	$num = $s_query->post_count;	
	
	// Query Strart
	$args = array(
    		'post_type' => 'attachment',
			'post_status' => array('publish', 'draft', 'inherit'),
			's' => $search,
			'numberposts' => -1,
			'posts_per_page' => 16,
			'orderby' => 'menu_order ID',
			'order' => 'DESC',
			'post_mime_type' => 'image',
			'author' => $curnt_user,
		    'paged' => get_query_var('paged')
	); 
	$wp_query = new WP_Query($args); ?>
	
	<?php if($search){ ?>
        <div id="s_head">
          	<script> jQuery('#at-search').val('<?php echo $search; ?>')</script>
    		<p> <?php _e('Search Result for') ?><span> 鈥�<?php echo $search; ?>鈥� </span><?php _e('&mdash;') ?> <?php echo $num; ?> <?php if( $num > 1 ){ _e( 'files' ); }else{ _e( 'file' ); } ?></p>
	    	<a href="<?php echo $url5.'?page=media';  ?>" id="cancel"></a>
     	</div>
	<?php } 
	
	if( have_posts() ): echo '<div id="at_content"><ul>'; while( have_posts() ): the_post(); 
     	$image_url = wp_get_attachment_url();
     	$image_id = get_image_id($image_url);
		
		$basename = basename(get_attached_file($image_id)); 
		$pathname = pathinfo($basename);
		
		$filename = remove_unnecessary_slug(get_the_title($image_id ));
		
		$type = get_post_mime_type($image_id);
		
		$docsize = @filesize( get_attached_file( $image_id ) );
		if (FALSE === $docsize){$docsize = 0;}else{$docsize = size_format($docsize, 2);}
		
		$permanent_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "delete-post_$image_id" ) );
		$perman_url = home_url( '/' )."wp-admin/post.php?post=$image_id";
		$delete_url = esc_url( $perman_url . "&action=delete&$permanent_wpnonce" );
		
		if(wp_attachment_is_image( $image_id)){$metadata = wp_get_attachment_metadata($image_id);
		$width = $metadata['width'];
		$height = $metadata['height'];}
		
		$file_date = get_the_date('M j, Y'); ?>
      
   <div class="list-image">
<div class="single_image"><a href="<?php echo wp_get_attachment_url( $image_id ); ?>" title="<?php echo $name; ?>" target="_blank"><?php echo wp_get_attachment_image( $image_id, 'figcaption' ); ?></a><div class="id_Code"><input type="text" value="[img=<?php echo $image_id; ?>]" /></div></div></div>
		
	<?php endwhile; echo'</ul></div></div><div id="media-pag">';
	if ($wp_query->max_num_pages > 1){ 
	    echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>';
	} ?><?php else: ?>
     	<div id="at_content">
           <p><?php _e('No Result match in you query.','tie'); ?></p>
		</div>
	<?php endif; wp_reset_query(); ?>
</div></div>

<?php }elseif ($_GET['action'] == "confirm_thumb"){ 
    
	//Set Post Thumbnail
	if ( $_REQUEST['page'] == 'thumbnail' && isset( $_POST['submit'] ) ){
        $return_id  = $_POST['return_id'];
		$thumb_id = isset( $_REQUEST['thumb_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['thumb_id'] )
	    : $_REQUEST['thumb_id'] ) : '';
	    $thumb_id = urldecode( $thumb_id );
		
		if( ! $return_id || ! $thumb_id ){
			$err .= "<strong>ERROR</strong>: An Error was occurrence.";
		}
		
		if ( $err == "" ){
	     	if( (current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) ){
				$post_sts = 'publish';
			}else{
				$post_sts = 'pending';
			}
			
			$my_post = array(
               	'ID' => $return_id,
	     		'post_status'	=> $post_sts,
			);
			
			// Upgrade post to publish
	    	wp_update_post($my_post);
			
			// Set Post Thumbnail
			update_post_meta($return_id,'_thumbnail_id',$thumb_id);
			
			//Post save as draft and redirect url
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			$redirected_ul = $url5;
			header('Location:'.$redirected_ul);
		}
    } 
	
	global $wpdb;
	$options1 = get_option('ln_options1'); 
	$url5 = $options1["plink_dash"]; 
	get_header(); ?>
	
    <div id="conf_bujj">
	
	<div class="page-head">
    	<h1>Confirm thumbnail</h1>
	</div>
	
	<div class="notify">Confirm to make this thumbnail?</div>
	
	<?php if ( $err != "" ){
		echo "<div id='error_rep'>".$err."</div>";
	}elseif ( $_REQUEST['success'] ){ ?>
     	<div id='error_suc'><?php _e('The Post has been Published. '); ?><a href="<?php echo $post_short; ?>"><?php _e('View Post'); ?></a></div>
	<?php } ?>
	
	<?php // get post link from post
	    $return_id = isset( $_REQUEST['return_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['return_id'] )
	    : $_REQUEST['return_id'] ) : '';
	    $return_id = urldecode( $return_id );
		// get post link from post
	    $thumb_id = isset( $_REQUEST['thumb_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['thumb_id'] )
	    : $_REQUEST['thumb_id'] ) : '';
	    $thumb_id = urldecode( $thumb_id );
	 ?>
		
    	<form action="" id="cnf_form" method="post" enctype="multipart/form-data">
	    	<input type="hidden" name="page" value="thumbnail"/>	
			<input type="hidden" id="return_id" name="return_id" value="<?php echo $return_id; ?>"/>
			<input type="hidden" id="thumb_id" name="thumb_id" value="<?php echo $thumb_id; ?>"/>
			
			<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Confirm', 'tie' ) ?>"/>
			<a href="<?php echo $url5.'?page=thumbnail&return_id='.$return_id; ?>" id="back"><?php _e('Back'); ?></a>
		</form>
		
		<div id="cfm_yhum"><?php echo wp_get_attachment_image( $thumb_id, 'figcaption' ); ?></div>
    </div>
<?php }elseif( $_GET['page'] == 'edit_post' ){ 
    
	//Edit the post
    $query = new WP_Query(
     	array(
	    	'post_type' => 'post', 
			'posts_per_page' =>'-1', 
			'post_status' => array('publish', 'pending', 'draft', 'private', 'trash') 
		) );
		
	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
    	if(isset($_GET['post_id']) && $_GET['post_id'] == $post->ID) {
	    	$content_id = $post->ID;
	    	$title      = get_the_title();
			$content    = get_the_content();
			$tags       = strip_tags( get_the_term_list( $content_id, 'post_tag', '', ', ', '' ) ); 
			$cate       = get_the_category($content_id); 
			$category   = $cate[0]->cat_ID;
		}
	endwhile; endif;
	wp_reset_query();
	
	if( isset($_POST['update-post']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce') ){
		$post_id = isset( $_REQUEST['post_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['post_id'] )
		: $_REQUEST['post_id'] ) : '';
		// get thumbnail id
		$thumb_id = isset( $_REQUEST['thumb_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['thumb_id'] )
		: $_REQUEST['thumb_id'] ) : '';
		$thumb_id = urldecode( $thumb_id );
		// get thumbnail
		$image_id     = get_post_thumbnail_id($post_id);
		
		$check_id     = post_exist_check($post_id);   
		$pe_cat       = array();
		$err          = "";
		$user_id      = $current_user->user_id;
		$pe_title 	  = $_POST['posttitle'];
		$pe_content   = $_POST['postcontent'];
		$pe_cat[0]	  = $_POST['cat'];	
		$pe_tags 	  = $_POST['post_tags'];
		$current_page = $_POST['_wp_http_referer'];
		
		if ( $post_id !== $check_id ){
       		$error .= __("<p>The post doesn't exist.</p>");
		}
		if ( $pe_title == "" && $check_id ){
			$error .= __('<p>Please fill in Title field.</p>');
		}
		if ( $pe_content == "" && $check_id ){
			$error .= __('<p>Please fill in Post Content field.</p>');
		}
		if($check_id){
			if ( $pe_cat[0] == "-1" ){
				$error .= __('<p>Please choose the post Category.</p>');
			}else{
				global $wpdb;
				$pe_cat_ids = (array) $wpdb->get_col("SELECT term_id FROM $wpdb->terms");
				if ( !in_array($pe_cat[0], $pe_cat_ids) && $pe_cat != "-1") {
					$error .= __("<p>This category doesn't exist</p>");
				}
			}
		}
		
		if ( $error == "" && $check_id ){
      		if( ((current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) && $thumb_id ) || ((current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) && has_post_thumbnail( $post_id )) ){
				$post_sts = 'publish';
			}elseif( (current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) && ! has_post_thumbnail( $check_id ) ){
				$post_sts = 'draft';
			}else{
				$post_sts = 'pending';
			}
			
			$post_information = array(
     	     	'ID'            => $post_id,
				'post_title'    => $pe_title,
				'post_content'  => $pe_content,
				'post_category'	=> $pe_cat,
				'tags_input'    => $pe_tags,
				'post_type'     => 'post',
				'post_status'   => $post_sts,
			);
			
	    	wp_update_post($post_information);
			
			if( $thumb_id ){
				$confirm_thum = $thumb_id;
			}elseif( has_post_thumbnail( $post_id ) && !$thumb_id ){
				$confirm_thum = $image_id;
			}
			
			// Set Post Thumbnail
			update_post_meta($post_id,'_thumbnail_id',$confirm_thum);
			
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			
			$redirect_edit = $url5;
			header('Location:'.$redirect_edit);
		}	
	}elseif( isset($_POST['edit-draft']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce') ){
		$post_id = isset( $_REQUEST['post_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['post_id'] )
		: $_REQUEST['post_id'] ) : '';
		// get thumbnail id
		$thumb_id = isset( $_REQUEST['thumb_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['thumb_id'] )
		: $_REQUEST['thumb_id'] ) : '';
		$thumb_id = urldecode( $thumb_id );
		// get thumbnail
		$image_id     = get_post_thumbnail_id($post_id);
		
		
		$check_id     = post_exist_check($post_id);   
		$pe_cat       = array();
		$err          = "";
		$user_id      = $current_user->user_id;
		$pe_title 	  = $_POST['posttitle'];
		$pe_content   = $_POST['postcontent'];
		$pe_cat[0]	  = $_POST['cat'];	
		$pe_tags 	  = $_POST['post_tags'];
		$current_page = $_POST['_wp_http_referer'];
		
		if ( $post_id !== $check_id ){
       		$error .= __("<p>The post doesn't exist.</p>");
		}
		
		if ( $error == "" && $check_id ){
      		$post_information = array(
     	     	'ID'            => $post_id,
				'post_title'    => $pe_title,
				'post_content'  => $pe_content,
				'post_category'	=> $pe_cat,
				'tags_input'    => $pe_tags,
				'post_type'     => 'post',
				'post_status'   => 'draft',
			);
			
	    	wp_update_post($post_information);
			
			if( $thumb_id ){
				$confirm_thum = $thumb_id;
			}elseif( has_post_thumbnail( $post_id ) && !$thumb_id ){
				$confirm_thum = $image_id;
			}
			
			// Set Post Thumbnail
			update_post_meta($post_id,'_thumbnail_id',$confirm_thum);
			
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			
			$redirect_edit = $url5;
			header('Location:'.$redirect_edit);
		}	
	} get_header(); ?>
	
	<div id="post_edit_bujj">
    	<?php global $wpdb; $options1 = get_option('ln_options1'); $url5 = $options1["plink_dash"]; ?>
		
	    <div class="page-head">
           	<h1><a href="<?php echo $url5; ?>">All Post</a> | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a> | Editor</h1>
		</div>
		
		<?php 
	   	$post_id = isset( $_REQUEST['post_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['post_id'] )
		: $_REQUEST['post_id'] ) : '';
		$post_id = urldecode( $post_id );
		// get thumbnail id
		$thumb_id = isset( $_REQUEST['thumb_id'] ) ? ( get_magic_quotes_gpc( ) ? stripcslashes( $_REQUEST['thumb_id'] )
		: $_REQUEST['thumb_id'] ) : '';
		$thumb_id = urldecode( $thumb_id );
		// get thumbnail
		$image_id     = get_post_thumbnail_id($post_id);
		// Short Url for visit Post
		$edit_short	= esc_url(wp_get_shortlink( $post_id ));
		
		if ($error != "") {
	    	echo "<div id='error_edit'>".$error."</div>";
		}elseif ( $_REQUEST['return'] ){ ?>
         	<div id='success_edit'><?php _e('Post has been Updated. '); ?><a href="<?php echo $edit_short; ?>"><?php _e('View Post'); ?></a></div>
		<?php } ?>
		
		<?php if( $thumb_id ){ ?>
	    	<div id="feature">
		    	<div id="img_sec"><?php echo wp_get_attachment_image( $thumb_id, 'figcaption' ); ?></div>
				<div id="thum"><a href="<?php echo $url5.'?page=edit_thumb&return_id='.$post_id; ?>" id="op_Thi"><?php _e('Change thumbnail'); ?></a></div>
			</div>
		<?php }elseif( has_post_thumbnail( $post_id ) && !$thumb_id ){ ?>
	    	<div id="feature">
		    	<div id="img_sec"><?php echo wp_get_attachment_image( $image_id, 'figcaption' ); ?></div>
				<div id="thum"><a href="<?php echo $url5.'?page=edit_thumb&return_id='.$post_id; ?>" id="op_Thi"><?php _e('Change thumbnail'); ?></a></div>
			</div>
		<?php }elseif( !has_post_thumbnail( $post_id ) && !$thumb_id ){ ?>
			<div id="thum">
		        <a href="<?php echo $url5.'?page=edit_thumb&return_id='.$post_id; ?>" id="op_Thi">Add Thumbnail</a>	
		    </div>
		<?php } ?>
		
		<form action="" id="primary_edit" method="post" enctype="multipart/form-data">
         	<div class="field">
	            <label for="posttitle"><?php _e('Post Title'); ?></label>
	          	<input id="posttitle" name="posttitle" value="<?php echo $title; ?>" type="text">
			</div>
			
		 	<div class="field">
	            <label for="postcontent"><?php _e('Post Content'); ?></label>
	            <textarea name="postcontent" id="postcontent" cols="7" rows="6"><?php echo $content; ?></textarea>
			</div>
			
			<div class="field">
	           	<label for="cat"><?php _e('Category'); ?></label>
	     		<?php wp_dropdown_categories('show_option_none=Select Category — &selected='.$category.'&orderby=name&order=ASC&hide_empty=0&hierarchical=1'); ?>
			</div>
			
	 		<div class="field">
	        	<label for="post_tags"><?php _e('Post Tags - '); ?><span> <?php _e('comma(,) separate'); ?></span></label>
	          	<input id="post_tags" name="post_tags" value="<?php echo $tags; ?>" type="text">
			</div>
			
			<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
			<input id="update-post" name="update-post" type="submit" value="Update post" />
			<a href="<?php echo $edit_short; ?>" id="prev">Preview</a>
			<input id="edit-draft" name="edit-draft" type="submit" value="Save as draft" />	
		</form>
	</div>
	
<?php }elseif($_GET['page'] == "new_post"){ 

    //Submit Post as Drafts
	if (isset($_POST['submit-post']) && isset($_POST['action']) && $_POST['action'] == 'post' && wp_verify_nonce($_POST['_wpnonce'],'sub_post')){
		
		$cat = array();
		$err = "";
		$user_id 		= $current_user->user_id;
		$post_title 	= $_POST['post_title'];
		$post_content 	= $_POST['post_content'];
		$cat[0]	 		= $_POST['cat'];	
		$post_tags 		= $_POST['post_tags'];
		$current_page   = $_POST['_wp_http_referer'];
		
		if ( $post_title == "" ){
	     	$err .= __('Please fill in Post Title field') . "<br />";
		}
		if ( $post_content == "" ){
	     	$err .= __('Please fill in Post Content field') . "<br />";
		}
		if ( $cat[0] == "-1" ){
			$err .= __('Please choose your Post Category') . "<br />";
		}else{
			global $wpdb;
			$cat_ids = (array) $wpdb->get_col("SELECT term_id FROM $wpdb->terms");
			if ( !in_array($cat[0], $cat_ids) && $cat != "-1") {
				$err .= __('This category doesn\'t exist') . "<br />";
			}
		}	
		
		if ( $err == "" ){
	    	if( (current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) && has_post_thumbnail( $return_id ) ){
				$post_sts = 'publish';
			}elseif( (current_user_can('editor') || current_user_can('administrator') || current_user_can('author')) && ! has_post_thumbnail( $return_id ) ){
				$post_sts = 'draft';
			}else{
				$post_sts = 'pending';
			}
			
			$post_information = array(
	    		'post_title'	=> $post_title,
				'post_content'	=> $post_content,
				'post_category'	=> $cat,
				'tags_input'	=> $post_tags,
				'post_type'     => 'post',
				'post_status'	=> $post_sts
			);
			
			$post_id = wp_insert_post($post_information);
			
			//Post save as draft and redirect link
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			$redirect_link = $url5.'?page=thumbnail&return_id='.$post_id;
			wp_redirect( $redirect_link );
		}
	}elseif (isset($_POST['draft-post']) && isset($_POST['action']) && $_POST['action'] == 'post' && wp_verify_nonce($_POST['_wpnonce'],'sub_post')){
		
		$cat = array();
		$err = "";
		$user_id 		= $current_user->user_id;
		$post_title 	= $_POST['post_title'];
		$post_content 	= $_POST['post_content'];
		$cat[0]	 		= $_POST['cat'];	
		$post_tags 		= $_POST['post_tags'];
		$current_page   = $_POST['_wp_http_referer'];
		
		if ( $err == "" ){
	    	$post_information = array(
	    		'post_title'	=> $post_title,
				'post_content'	=> $post_content,
				'post_category'	=> $cat,
				'tags_input'	=> $post_tags,
				'post_type'     => 'post',
				'post_status'	=> 'draft'
			);
			
			$post_id = wp_insert_post($post_information);
			
			//Post save as draft and redirect link
			global $wpdb;
			$options1 = get_option('ln_options1'); 
			$url5 = $options1["plink_dash"]; 
			$redirect_link = $url5.'?page=edit_post&post_id='.$post_id.'&status=drsft';
			wp_redirect( $redirect_link );
		}
	} get_header(); ?>
	
	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		$media = $url5.'?page=media';
		$bbcode = $url5.'?page=bb_code';
	?>

<div class="block_posts mobile-post-input">
<h2><a href="<?php echo $url5; ?>">Posts</a> | Add Post | <a href="<?php echo $media; ?>">Add Screenshot</a> | <a href="/bb-codes">Add BB Codes</a> </h2>
<div class="notify"><b>Basic Rules: (Updated at 16.12.16) </b>
<ul>
<li>বাংলায় পোষ্ট করলে শুদ্ধ বাংলা ব্যবহার করুন</li>
<li>পোষ্ট এর সাথে সম্পুর্কযুক্ত ক্যাটাগরী/ফিচারড ইমেজ/ট্যাগ ব্যবহার করুন</li>
<li><a href="/featured/42">মোবাইল থেকে যেভাবে নতুন TrickzBD.GQ তে পোষ্ট করবেন</a></li>
<li><a href="/featured/38">ট্রেইনার দের পোষ্ট করার জন্য নীতিমালা</a></li>
<li><b>বাধ্যতামূলক কিছু নিয়মঃ</b><br>
**সম্পূর্ন নিজের ভাষায় পোষ্ট লিখুন...<a href="/featured/23">কপিপেষ্ট পরিহার করুন।</a><h4>কেউ কপিপেষ্ট করেছে এমন প্রমান পেলে সাথে সাথে ট্রেইনার থেকে তাকে বাতিল করা হবে </h4>
**পোষ্ট এর একেবারে শেষ ছাড়া কোথাও পোষ্ট দাতার সাইট লিংক থাকতে পারবে না<br>
**এপ/গেম এর রিভিও দিলে এপ/গেম ডাওনলোড এর ডাইরেক্ট লিংক দিতে হবে এবং বিস্তারিত পোষ্ট+স্ক্রিনশুট দিতে হবে<br>
**<h4>এপ ডাওনলোড করিয়ে টাকা আয়ের উদ্দেশ্যে কাউকে পোষ্ট করতে দেখলে ট্রেইনার পদ বাতিল করা হবে। </h4>
</li>
</ul>
</div>
<div class="pad4">
<?php if ($err != "") {
	    echo "<div id='eror'><p>".$err."</p></div>";
	} ?> 
<form action="" id="post_form" method="post" enctype="multipart/form-data">
<label for="post_title"><?php _e('Enter title here'); ?></label>
	    		<input type="text" id="post_title" name="post_title" placeholder="Enter title here" value="<?php echo $post_title;?>" size="60" tabindex="1"/>
<label for="post_content"><?php _e('Content'); ?></label>
<textarea name="post_content" id="post_content" cols="7" rows="5" tabindex="2"><?php echo $post_content; ?></textarea>
<label for="cat">Category</label>
<?php if ( current_user_can('administrator')) { wp_dropdown_categories('show_option_none=Choose Post Category&orderby=name&order=ASC&hide_empty=0&hierarchical=1'); }else{	wp_dropdown_categories('show_option_none=Choose Post Category&orderby=name&order=ASC&hide_empty=0&hierarchical=1&exclude=2,3'); }?>
<?php _e('<label for="">Tags</label>'); ?><small> <?php _e('<span>Enter more tags by (,) comma</span>'); ?></small>
 <input type="text" id="post_tags" name="post_tags" value="<?php echo $post_tags;?>" size="60" tabindex="3"/>
			</div>
<input type="hidden" name="action" value="post" />
			<?php wp_nonce_field( 'sub_post' ); ?>
			
			<input id="submit-post" name="submit-post" type="submit" value="Submit post" />
			<input id="draft-post" name="draft-post" type="submit" value="Save as draft" />					</form></div></div>

	
<?php }elseif($_GET['post_status'] == "publish"){ ?>

	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		get_header();
	?>
	
	<div class="dash_arae">
	
	<div class="page-head">
    	<h1>All Post | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a></h1>
	</div>
	
	<div class="post_bar"><?php
	    $user_id = get_current_user_id();
		$publish = PostCount($user_id, 'publish');
		$pending = PostCount($user_id, 'pending');
		$draft = PostCount($user_id, 'draft');
		$trash = PostCount($user_id, 'trash');
		$all = $publish + $pending + $draft + $trash;?>
		    <a href='<?php echo $url5.'?post_status=all'; ?>'><?php _e('All'); ?><?php if ($all > 0){ ?> <span class='count'>(<?php echo $all; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=publish'; ?>' class="active"><?php _e('Published'); ?><?php if ($publish > 0){ ?> <span class='count'>(<?php echo $publish; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=draft'; ?>'><?php _e('Draft'); ?><?php if ($draft > 0){ ?> <span class='count'>(<?php echo $draft; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href='<?php echo $url5.'?post_status=pending'; ?>'><?php _e('Pending'); ?><?php if ($pending > 0){ ?> <span class='count'>(<?php echo $pending; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href="<?php echo $url5.'?post_status=trash'; ?>"><?php _e('Trash'); ?><?php if ($trash > 0){ ?> <span class='count'>(<?php echo $trash; ?>)</span><?php } ?></a>
	</div>

	<div class="post_list">
	<?php $user_id = get_current_user_id();
		$args=array(
		    'post_type' => 'post',
			'post_status' => 'publish',
			'author' => $user_id,
			'numberposts' => -1,
			'posts_per_page' => 15,
			'paged' => get_query_var('paged'));                       
		
		$wp_query = new WP_Query($args);
		if( have_posts() ): echo '<ul>'; while( have_posts() ): the_post();
		
		// Move to Trash
		$postid = get_the_ID();
		$trash_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "trash-post_$postid" ) );
		$trash_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$trash_del = esc_url( $trash_url . "&action=trash&$trash_wpnonce" );
	?>
		<li class="item-list">
            <div class="list_thumb">
    		    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>	
	     	        <?php the_post_thumbnail('small');  ?>
    			<?php else: ?>
    				<img src="<?php echo get_template_directory_uri(); ?>/img/no-image.png" />	
     			<?php endif; ?>
            </div>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php _e('-'); echo get_post_status( get_the_ID() ); ?></h2> 
			<div id="list_time"><?php the_time( 'j M, Y' ); ?></div>
			<div id="list_head">
			    <a href="<?php echo $url5.'?page=edit_post&post_id='.$postid; ?>"><?php _e('Edit'); ?></a><?php _e(' | '); ?><a href="<?php the_permalink(); ?>"><?php _e('View'); ?></a><?php _e(' | '); ?><a href="<?php echo $trash_del ?>"><?php _e('Trash'); ?></a>
			</div>
		</li><!-- .item-list -->
    <?php endwhile;?>
    </ul>
	<?php echo '<div id="media-pag">'; if ($wp_query->max_num_pages > 1){echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>';}  echo '</div>'; ?>
	<?php else: ?>
		<div class="archive-meta">
		    <p><?php _e('No posts found in Publish','tie'); ?></p>
		</div>
	<?php endif; wp_reset_query(); ?>
	</div><!-- .post_list /-->
	</div>
	
<?php }elseif($_GET['post_status'] == "draft"){ ?>
	
	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		get_header();
	?>
	
	<div class="dash_arae">
	
	<div class="page-head">
    	<h1>All Post | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a></h1>
	</div>
	
	<div class="post_bar"><?php
	    $user_id = get_current_user_id();
		$publish = PostCount($user_id, 'publish');
		$pending = PostCount($user_id, 'pending');
		$draft = PostCount($user_id, 'draft');
		$trash = PostCount($user_id, 'trash');
		$all = $publish + $pending + $draft + $trash;?>
		    <a href='<?php echo $url5.'?post_status=all'; ?>'><?php _e('All'); ?><?php if ($all > 0){ ?> <span class='count'>(<?php echo $all; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=publish'; ?>'><?php _e('Published'); ?><?php if ($publish > 0){ ?> <span class='count'>(<?php echo $publish; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=draft'; ?>' class="active"><?php _e('Draft'); ?><?php if ($draft > 0){ ?> <span class='count'>(<?php echo $draft; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href='<?php echo $url5.'?post_status=pending'; ?>'><?php _e('Pending'); ?><?php if ($pending > 0){ ?> <span class='count'>(<?php echo $pending; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href="<?php echo $url5.'?post_status=trash'; ?>"><?php _e('Trash'); ?><?php if ($trash > 0){ ?> <span class='count'>(<?php echo $trash; ?>)</span><?php } ?></a>
	</div>
	
	<div class="post_list">
	<?php $user_id = get_current_user_id();
		$args=array(
		    'post_type' => 'post',
			'post_status' => 'draft',
			'author' => $user_id,
			'numberposts' => -1,
			'posts_per_page' => 15,
			'paged' => get_query_var('paged'));                       
		
		$wp_query = new WP_Query($args);
		if( have_posts() ): echo '<ul>'; while( have_posts() ): the_post(); 
		
		// Move to Trash
		$postid = get_the_ID();
		$trash_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "trash-post_$postid" ) );
		$trash_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$trash_del = esc_url( $trash_url . "&action=trash&$trash_wpnonce" );
	?>
		<li class="item-list">
            <div class="list_thumb">
    		    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>	
	     	        <?php the_post_thumbnail('small');  ?>
    			<?php else: ?>
    				<img src="<?php echo get_template_directory_uri(); ?>/img/no-image.png" />	
     			<?php endif; ?>
            </div>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php _e('-'); echo get_post_status( get_the_ID() ); ?></h2> 
			<div id="list_time"><?php the_time( 'j M, Y' ); ?></div>
			<div id="list_head">
			    <a href="<?php echo $url5.'?page=edit_post&post_id='.$postid; ?>"><?php _e('Edit'); ?></a><?php _e(' | '); ?><a href="<?php the_permalink(); ?>"><?php _e('View'); ?></a><?php _e(' | '); ?><a href="<?php echo $trash_del ?>"><?php _e('Trash'); ?></a>
			</div>
		</li><!-- .item-list -->
    <?php endwhile;?>
       	</ul>
	<?php echo '<div id="media-pag">'; if ($wp_query->max_num_pages > 1){echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; }  echo '</div>'; ?>
		<?php else: ?>
	<div class="archive-meta">
     	<p><?php _e('No posts found in Draft','tie'); ?></p>
	</div>
	<?php endif; wp_reset_query(); ?>
	</div><!-- .post_list /-->
	</div>
		
<?php }elseif($_GET['post_status'] == "pending"){ ?>
	
	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		get_header();
	?>
	
	<div class="dash_arae">
	
	<div class="page-head">
    	<h1>All Post | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a></h1>
	</div>
	
	<div class="post_bar"><?php
	    $user_id = get_current_user_id();
		$publish = PostCount($user_id, 'publish');
		$pending = PostCount($user_id, 'pending');
		$draft = PostCount($user_id, 'draft');
		$trash = PostCount($user_id, 'trash');
		$all = $publish + $pending + $draft + $trash;?>
		    <a href='<?php echo $url5.'?post_status=all'; ?>'><?php _e('All'); ?><?php if ($all > 0){ ?> <span class='count'>(<?php echo $all; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=publish'; ?>'><?php _e('Published'); ?><?php if ($publish > 0){ ?> <span class='count'>(<?php echo $publish; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=draft'; ?>'><?php _e('Draft'); ?><?php if ($draft > 0){ ?> <span class='count'>(<?php echo $draft; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href='<?php echo $url5.'?post_status=pending'; ?>' class="active"><?php _e('Pending'); ?><?php if ($pending > 0){ ?> <span class='count'>(<?php echo $pending; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href="<?php echo $url5.'?post_status=trash'; ?>"><?php _e('Trash'); ?><?php if ($trash > 0){ ?> <span class='count'>(<?php echo $trash; ?>)</span><?php } ?></a>
	</div>

	<div class="post_list">
	<?php $user_id = get_current_user_id();
		$args=array(
		    'post_type' => 'post',
			'post_status' => 'pending',
			'author' => $user_id,
			'numberposts' => -1,
			'posts_per_page' => 15,
			'paged' => get_query_var('paged'));                       
		
		$wp_query = new WP_Query($args);
		if( have_posts() ): echo '<ul>'; while( have_posts() ): the_post(); 
		
		// Move to Trash
		$postid = get_the_ID();
		$trash_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "trash-post_$postid" ) );
		$trash_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$trash_del = esc_url( $trash_url . "&action=trash&$trash_wpnonce" );
	?>
		<li class="item-list">
            <div class="list_thumb">
    		    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>	
	     	        <?php the_post_thumbnail('small');  ?>
    			<?php else: ?>
    				<img src="<?php echo get_template_directory_uri(); ?>/img/no-image.png" />	
     			<?php endif; ?>
            </div>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php _e('-'); echo get_post_status( get_the_ID() ); ?></h2> 
			<div id="list_time"><?php the_time( 'j M, Y' ); ?></div>
			<div id="list_head">
			    <a href="<?php echo $url5.'?page=edit_post&post_id='.$postid; ?>"><?php _e('Edit'); ?></a><?php _e(' | '); ?><a href="<?php the_permalink(); ?>"><?php _e('View'); ?></a><?php _e(' | '); ?><a href="<?php echo $trash_del ?>"><?php _e('Trash'); ?></a>
			</div>
    	</li><!-- .item-list -->
   	<?php endwhile;?>
      	</ul>
      	<?php echo '<div id="media-pag">'; if ($wp_query->max_num_pages > 1){echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; }  echo '</div>'; ?>
	<?php else: ?>
		<div class="archive-meta">
		    <p><?php _e('No posts found in Pending','tie'); ?></p>
		</div>
	<?php endif; wp_reset_query(); ?>
	</div><!-- .post_list /-->
	</div>
		
<?php }elseif($_GET['post_status'] == "trash"){ ?>
	
	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		get_header();
	?>
	<div class="dash_arae">
	
	<div class="page-head">
    	<h1>All Post | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a></h1>
	</div>
	
	<div class="post_bar"><?php
	    $user_id = get_current_user_id();
		$publish = PostCount($user_id, 'publish');
		$pending = PostCount($user_id, 'pending');
		$draft = PostCount($user_id, 'draft');
		$trash = PostCount($user_id, 'trash');
		$all = $publish + $pending + $draft + $trash;?>
		    <a href='<?php echo $url5.'?post_status=all'; ?>'><?php _e('All'); ?><?php if ($all > 0){ ?> <span class='count'>(<?php echo $all; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=publish'; ?>'><?php _e('Published'); ?><?php if ($publish > 0){ ?> <span class='count'>(<?php echo $publish; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=draft'; ?>'><?php _e('Draft'); ?><?php if ($draft > 0){ ?> <span class='count'>(<?php echo $draft; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href='<?php echo $url5.'?post_status=pending'; ?>'><?php _e('Pending'); ?><?php if ($pending > 0){ ?> <span class='count'>(<?php echo $pending; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href="<?php echo $url5.'?post_status=trash'; ?>" class="active"><?php _e('Trash'); ?><?php if ($trash > 0){ ?> <span class='count'>(<?php echo $trash; ?>)</span><?php } ?></a>
	</div>
	
	<div class="post_list">
	<?php $user_id = get_current_user_id();
		$args=array(
		    'post_type' => 'post',
			'post_status' => 'trash',
			'author' => $user_id,
			'numberposts' => -1,
			'posts_per_page' => 15,
			'paged' => get_query_var('paged'));                       
		
		$wp_query = new WP_Query($args);
		if( have_posts() ): echo '<ul>'; while( have_posts() ): the_post(); 
		
		// Premanent Delete
		$postid = get_the_ID();
		$permanent_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "delete-post_$postid" ) );
		$perman_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$permanent_url = esc_url( $perman_url . "&action=delete&$permanent_wpnonce" );
		
		// Restore from Trash
		$store_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "untrash-post_$postid" ) );
		$store_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$restore_url = esc_url( $store_url . "&action=untrash&$store_wpnonce" );
	?>
		<li class="item-list">
            <div class="list_thumb">
    		    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>	
	     	        <?php the_post_thumbnail('small');  ?>
    			<?php else: ?>
    				<img src="<?php echo get_template_directory_uri(); ?>/img/no-image.png" />	
     			<?php endif; ?>
            </div>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php _e('-'); echo get_post_status( get_the_ID() ); ?></h2> 
			<div id="list_time"><?php the_time( 'j M, Y' ); ?></div>
			<div id="list_head">
			    <a href="<?php echo $url5.'?page=edit_post&post_id='.$postid; ?>"><?php _e('Edit'); ?></a><?php _e(' | '); ?><a href="<?php the_permalink(); ?>"><?php _e('View'); ?></a>
			</div>
		</li><!-- .item-list -->
   	<?php endwhile;?>
       	</ul>
       		<?php echo '<div id="media-pag">'; if ($wp_query->max_num_pages > 1){echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; }  echo '</div>'; ?>
	<?php else: ?>
		<div class="archive-meta">
		    <p><?php _e('No posts found in Trash','tie'); ?></p>
		</div>
	<?php endif; wp_reset_query(); ?>
	</div><!-- .post_list /-->
	</div>
		
<?php }else{ ?>
	
	<?php global $wpdb;
		$options1 = get_option('ln_options1'); 
		$url5 = $options1["plink_dash"]; 
		get_header();
	?>
	
	<div class="dash_arae">
	
	<div class="page-head">
    	<h1>All Post | <a href="<?php echo $url5.'?page=new_post'; ?>">Add New Post</a></h1>
	</div>
		
	<div class="post_bar"><?php
	    $user_id = get_current_user_id();
		$publish = PostCount($user_id, 'publish');
		$pending = PostCount($user_id, 'pending');
		$draft = PostCount($user_id, 'draft');
		$trash = PostCount($user_id, 'trash');
		$all = $publish + $pending + $draft + $trash;?>
		    <a href='<?php echo $url5.'?post_status=all'; ?>' class="active"><?php _e('All'); ?><?php if ($all > 0){ ?> <span class='count'>(<?php echo $all; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=publish'; ?>'><?php _e('Published'); ?><?php if ($publish > 0){ ?> <span class='count'>(<?php echo $publish; ?>)</span><?php } ?></a> <?php _e('|'); ?> 
			<a href='<?php echo $url5.'?post_status=draft'; ?>'><?php _e('Draft'); ?><?php if ($draft > 0){ ?> <span class='count'>(<?php echo $draft; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href='<?php echo $url5.'?post_status=pending'; ?>'><?php _e('Pending'); ?><?php if ($pending > 0){ ?> <span class='count'>(<?php echo $pending; ?>)</span><?php } ?></a> <?php _e('|'); ?>
			<a href="<?php echo $url5.'?post_status=trash'; ?>"><?php _e('Trash'); ?><?php if ($trash > 0){ ?> <span class='count'>(<?php echo $trash; ?>)</span><?php } ?></a>
	</div>

	<div class="post_list">
	<?php $user_id = get_current_user_id();
		$args=array(
		    'post_type' => 'post',
			'post_status' => 'publish,pending,draft,trash',
			'author' => $user_id,
			'numberposts' => -1,
			'posts_per_page' => 15,
			'paged' => get_query_var('paged'));                       
		
		$wp_query = new WP_Query($args);
		if( have_posts() ): echo '<ul>'; while( have_posts() ): the_post(); 
		
		// Move to Trash
		$postid = get_the_ID();
		$trash_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "trash-post_$postid" ) );
		$trash_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$trash_del = esc_url( $trash_url . "&action=trash&$trash_wpnonce" );
		
		// Premanent Delete
		$postid = get_the_ID();
		$permanent_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "delete-post_$postid" ) );
		$perman_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$permanent_url = esc_url( $perman_url . "&action=delete&$permanent_wpnonce" );
		
		// Restore from Trash
		$store_wpnonce = esc_html( '_wpnonce=' . wp_create_nonce( "untrash-post_$postid" ) );
		$store_url = home_url( '/' )."wp-admin/post.php?post=$postid";
		$restore_url = esc_url( $store_url . "&action=untrash&$store_wpnonce" );
	?>
		<li class="item-list">
            <div class="list_thumb">
    		    <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>	
	     	        <?php the_post_thumbnail('small');  ?>
    			<?php else: ?>
    				<img src="<?php echo get_template_directory_uri(); ?>/img/no-image.png" />	
     			<?php endif; ?>
            </div>
			<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php _e('-'); echo get_post_status( get_the_ID() ); ?></h2> 
			<div id="list_time"><?php the_time( 'j M, Y' ); ?></div>
			<div id="list_head">
			    <a href="<?php echo $url5.'?page=edit_post&post_id='.$postid; ?>"><?php _e('Edit'); ?></a><?php _e(' | '); ?><a href="<?php the_permalink(); ?>"><?php _e('View'); ?></a><?php _e(' | '); ?><a href="<?php echo $trash_del ?>"><?php _e('Trash'); ?></a>
			</div>
    	</li><!-- .item-list -->
    <?php endwhile;?>
        </ul>
   	<?php echo '<div id="media-pag">'; if ($wp_query->max_num_pages > 1){echo '<div class="wp-pagenavi">'; if(function_exists('pagenavi')) { pagenavi(); } echo '</div>'; }  echo '</div>'; ?>
	    <?php else: ?>
   	<div class="archive-meta">
	    <p><?php _e("You doesn't have any Post.",'tie'); ?></p>
	</div>
	<?php endif; wp_reset_query(); ?>
	</div><!-- .post_list /-->
	</div>	
<?php }
} ?>

<?php get_footer(); ?>
