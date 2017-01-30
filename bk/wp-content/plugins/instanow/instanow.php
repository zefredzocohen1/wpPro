<?php
/*
	Plugin Name: TieLabs InstaNow - Instagram Feed for WordPress
	Plugin URI: http://codecanyon.net/item/tielabs/9241826?ref=tielabs
	Description: Simple, stylish, clean, customizable, and responsive plugin for displaying Instagram feed in a sidebar, post, or page
	Author: TieLabs
	Version: 2.0.0
	Author URI: http://tielabs.com
*/

define ( 'TIEINSTA_PLUGIN_NAME', 	'InstaNOW' );
define ( 'TIEINSTA_PLUGIN_SLUG', 	plugin_basename( __FILE__ )	);
define ( 'TIEINSTA_CLIENT_ID', 		'bdbbcfc8af4e4f2d83121d6e65688e74'	);

require_once( 'instanow-admin.php' );


/*-----------------------------------------------------------------------------------*/
# Plugin Activation
/*-----------------------------------------------------------------------------------*/
function tie_insta_activate() {

	//Disable the old Instagramy plugin and InstaNOW Lite if installed 
	deactivate_plugins( 'instagramy/instagramy.php' );
	deactivate_plugins( 'instanow-lite/instanow-lite.php' );
}
register_activation_hook( __FILE__, 'tie_insta_activate' );


/*-----------------------------------------------------------------------------------*/
# Register and Enquee plugin's styles and scripts
/*-----------------------------------------------------------------------------------*/
function tie_insta_scripts_styles(){

	$tieinsta_options = get_option( 'tie_instagramy' );

	wp_register_script( 'tie-insta-ilightbox'     , plugins_url('assets/ilightbox/js/ilightbox.packed.js', __FILE__) , array( 'jquery' ), false, true );  
	wp_register_script( 'tie-insta-slider-scripts', plugins_url('assets/js/slider-scripts.js', __FILE__) , array( 'jquery' ), false, false );  
	wp_register_script( 'tie-insta-admin-scripts' , plugins_url('assets/js/admin-scripts.js' , __FILE__) , array( 'jquery' ), false, true  );  
	wp_register_style ( 'tie-insta-style'		  , plugins_url('assets/style.css'			 , __FILE__) ) ;

	if( !is_admin()){
		wp_enqueue_style( 'tie-insta-style' );
		
		//Get The Light Box Skin
		$load_ilightbox = apply_filters( 'tie_instagram_force_avoid_ilightbox', true );
		if( true === $load_ilightbox ) {
			$lightbox_skin = ( !empty ( $tieinsta_options[ 'lightbox_skin' ] ) ? $tieinsta_options[ 'lightbox_skin' ] : 'dark' );
			wp_enqueue_style('tie-insta-ilightbox-skin',  plugins_url('assets/ilightbox/'.$lightbox_skin.'-skin/skin.css' , __FILE__) );
		}

		wp_localize_script(
			'jquery',
			'tie_insta',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))
		);

	}
	else{
		wp_enqueue_style ( 'tie-insta-admin-css',  plugins_url('assets/admin.css' , __FILE__) );
		wp_enqueue_script( 'tie-insta-admin-scripts' );

		$tieinsta_translation_array = array(
			'shortcodes_tooltip'	=>		__( 'InstaNow Shortcodes'							, 'tieinsta' ),
			'mediaFrom' 			=>		__( 'Get media from'								, 'tieinsta' ),
			'username' 				=>		__( 'Instagram account Username'					, 'tieinsta' ),
			'usname' 				=>		__( 'User Account'									, 'tieinsta' ),
			'hashtag' 				=>		__( 'Instagram HashTag (Without #)'					, 'tieinsta' ),
			'hatag' 				=>		__( 'Hash Tag' 										, 'tieinsta' ),
			'hashtag_name_show' 	=>		__( 'Show the Hash Tag name'						, 'tieinsta' ),
			'skin' 					=> 		__( 'Skin' 											, 'tieinsta' ),
			'default_skin' 			=>		__( 'Default Skin' 									, 'tieinsta' ),
			'lite_skin' 			=> 		__( 'Lite Skin' 									, 'tieinsta' ),
			'dark_skin' 			=> 		__( 'Dark Skin' 									, 'tieinsta' ),
			'logo_bar' 				=> 		__( 'Show the Instagram logo bar' 					, 'tieinsta' ),
			'new_window' 			=> 		__( 'Open links in a new window' 					, 'tieinsta' ),
			'nofollow' 				=> 		__( 'Nofollow' 										, 'tieinsta' ),
			'credit' 				=> 		__( 'Give us a credit' 								, 'tieinsta' ),
			'account_info' 			=> 		__( 'Show the Account Info area' 					, 'tieinsta' ),
			'position' 				=> 		__( 'Position' 										, 'tieinsta' ),
			'top' 					=> 		__( 'Top of the widget' 							, 'tieinsta' ),
			'bottom' 				=> 		__( 'End of the widget' 							, 'tieinsta' ),
			'layout' 				=> 		__( 'Layout' 										, 'tieinsta' ),
			'layout1' 				=> 		__( 'Layout 1' 										, 'tieinsta' ),
			'layout2' 				=> 		__( 'Layout 2' 										, 'tieinsta' ),
			'layout3' 				=> 		__( 'Layout 3' 										, 'tieinsta' ),
			'full_name' 			=> 		__( 'Show the Full name' 							, 'tieinsta' ),
			'website_url' 			=> 		__( 'Show the Website URL' 							, 'tieinsta' ),
			'bio' 					=> 		__( 'Show the bio' 									, 'tieinsta' ),
			'account_stats' 		=> 		__( 'Show the account stats' 						, 'tieinsta' ),
			'avatar_shape' 			=>		__( 'Avatar shape' 									, 'tieinsta' ),
			'square' 				=> 		__( 'Square' 										, 'tieinsta' ),
			'round' 				=> 		__( 'Round' 										, 'tieinsta' ),
			'circle' 				=> 		__( 'Circle' 										, 'tieinsta' ),
			'avatar_size' 			=> 		__( 'Avatar Width & Height' 						, 'tieinsta' ),
			'media_items' 			=>		__( 'Number of Media items' 						, 'tieinsta' ),
			'link_to' 				=> 		__( 'Link to' 										, 'tieinsta' ),
			'media_file' 			=> 		__( 'Media File' 									, 'tieinsta' ),
			'media_page' 			=> 		__( 'Media page on Instagram' 						, 'tieinsta' ),
			'none' 					=> 		__( 'None' 											, 'tieinsta' ),
			'grid' 					=> 		__( 'Grid' 											, 'tieinsta' ),
			'slider' 				=>		__( 'Slider' 										, 'tieinsta' ),
			'columns' 				=> 		__( 'GRID - Number of Columns' 						, 'tieinsta' ),
			'flat' 		=> 		__( 'GRID - Flat Images (Without borders, margins and shadows)'	, 'tieinsta' ),
			'load_more' 			=> 		__( 'GRID - Enable Load More Button'				, 'tieinsta' ),
			'load_more_number' 		=> 		__( 'GRID - Number of items to load each time'		, 'tieinsta' ),
			'slider_speed' 			=> 		__( 'SLIDER - Speed (ms)' 							, 'tieinsta' ),
			'slider_effect' 		=>		__( 'SLIDER - Animation Effect' 					, 'tieinsta' ),
			'comments_likes' 		=> 		__( 'SLIDER - Show Media comments and likes number' , 'tieinsta' )
		);
		
		wp_localize_script( 'tie-insta-admin-scripts', 'tieinsta_js', $tieinsta_translation_array );
	}
}
add_action( 'init', 'tie_insta_scripts_styles', 9 );


/*-----------------------------------------------------------------------------------*/
# Load Text Domain
/*-----------------------------------------------------------------------------------*/
add_action('plugins_loaded', 'tie_insta_init');
function tie_insta_init() {
	load_plugin_textdomain( 'tieinsta' , false, dirname( TIEINSTA_PLUGIN_SLUG ).'/languages' );
}


/*-----------------------------------------------------------------------------------*/
# Get Data From API's
/*-----------------------------------------------------------------------------------*/
function tie_insta_remote_get( $url , $json = true) {
	$get_request = wp_remote_get( $url , array( 'timeout' => 18 , 'sslverify' => false ) );
	$request = wp_remote_retrieve_body( $get_request );
	if( $json ) $request = @json_decode( $request , true );
	return $request;  
}


/*-----------------------------------------------------------------------------------*/
# Number Format Function
/*-----------------------------------------------------------------------------------*/
function tie_insta_format_num( $number ){
	if( !is_numeric( $number ) ) return $number ;
	
	if($number >= 1000000){
		return round( ($number/1000)/1000 , 1) . "M";
    }	
	elseif($number >= 100000){
		return round( $number/1000, 0) . "k";
    }
	else{
		return @number_format( $number );
	}
}


/*-----------------------------------------------------------------------------------*/
# Keep necessary data only
/*-----------------------------------------------------------------------------------*/
function tie_insta_clean_data( $data ){
	unset( $data['pagination'] );
	unset( $data['meta'] );

	for( $i=0; ; $i++ ){
		if( !isset( $data['data'][$i] ) ) break;
		unset( $data['data'][$i]['tags'] );
		unset( $data['data'][$i]['attribution'] );
		unset( $data['data'][$i]['filter'] );
		unset( $data['data'][$i]['created_time'] );
		unset( $data['data'][$i]['users_in_photo'] );
		unset( $data['data'][$i]['user_has_liked'] );
		unset( $data['data'][$i]['location'] );
		unset( $data['data'][$i]['comments']['data'] );
		unset( $data['data'][$i]['likes']['data'] );
		unset( $data['data'][$i]['images']['thumbnail']['height'] );
		unset( $data['data'][$i]['images']['thumbnail']['width'] );
		unset( $data['data'][$i]['images']['standard_resolution']['height'] );
		unset( $data['data'][$i]['images']['standard_resolution']['width'] );
		unset( $data['data'][$i]['caption']['created_time'] );
		unset( $data['data'][$i]['caption']['from'] );
		unset( $data['data'][$i]['caption']['id'] );
		unset( $data['data'][$i]['user']);
		unset( $data['data'][$i]['id']);
	}

	return $data;
}


/*-----------------------------------------------------------------------------------*/
# Instagram Photos
/*-----------------------------------------------------------------------------------*/
function tie_insta_media( $options ) {

	$tieinsta_options = get_option( 'tie_instagramy' );
	$api 			  = get_option( 'instagramy_access_token' );

// Default Values
	$media_source			 = 'user';
	$account_info_layout 	 = 2;
	$account_info_position 	 = 'top';
	$media_layout 			 = 'grid';
	$box_style 				 = 'default';
	$columns_number 		 = 3;
	$load_more_per_page 	 = 6;
	$cache 					 = 2 ;
	$img_size 				 = 'low_resolution';
	$follow_text 			 = __( 'Follow', 'tieinsta');
	$tie_instagram_random_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
	$link_target = $rel_nofollow = $size = $img_class = $lightbox_attr = $flat_class = '';

	extract( $options );

	if( !empty( $new_window ))
		$link_target = ' target="_blank"';
		
	if( !empty( $nofollow ))
		$rel_nofollow = ' rel="nofollow"';
		
	if( !empty( $avatar_size )){
		$size = ' style="width:'.$avatar_size.'px; height:'.$avatar_size.'px;"';
		if( $avatar_size < 60 )
			$follow_text = ' + ';
	}
				
	if( !empty( $avatar_shape ))
		$img_class = ' class="'.$avatar_shape.'"';

	if( !empty( $flat ))
		$flat_class = ' tie-insta-flat-images';	
		
	if( !empty( $large_img ))
		$img_size = 'standard_resolution';	
	
	if( !empty( $tieinsta_options['cache'] ) && is_int($tieinsta_options['cache']) )
		$cache = $tieinsta_options['cache'];

// Get Stored Cache or store a new version
	if( $media_source == 'hashtag' ){ //HashTag
		$tie_instagram_transient 	= get_transient( 'tie_instagram_hashtag_'.$hashtag );
		$account_info_layout 		= 1;
		$instagram_logo_url 		= 'explore/tags/'. $hashtag;

		if( false !== $tie_instagram_transient  ){
			$data_photos = json_decode( $tie_instagram_transient , true );
		}
		else{			
			if( !empty( $api ) ){

				$data_photos_source 	= tie_insta_remote_get("https://api.instagram.com/v1/tags/$hashtag/media/recent/?access_token=$api");
				$data_photos_meta_code 	= $data_photos_source[ 'meta' ][ 'code' ];
				$data_photos 			= tie_insta_clean_data( $data_photos_source );

				/* Get more than 20 photos */
				if( $media_number > 20 && !empty( $data_photos_source['pagination']['next_url'] ) ){

					$data_photos 		= $data_photos['data'];
					$number_of_pages 	= ( ceil( $media_number / 20 ) - 1 );

					for( $i=1 ; $i<=$number_of_pages ; $i++ ){

						if( !empty( $data_photos_source['pagination']['next_url'] ) ){
							$next_page 			= $data_photos_source['pagination']['next_url'];
							$data_photos_source = tie_insta_remote_get( $next_page );

							if( $data_photos_source[ 'meta' ][ 'code' ] == 200 ){
								$data_photos_next_cleaned 	= tie_insta_clean_data( $data_photos_source );
								$data_photos 				= array_merge( $data_photos , $data_photos_next_cleaned['data'] );
							}
						}
					}

					$data_photos = array( 'data' => $data_photos );
				}

				if( $data_photos_meta_code == 200 ){
					set_transient( 'tie_instagram_hashtag_'.$hashtag , json_encode( $data_photos ) , $cache*60*60 );
					update_option( 'tie_instagram_hashtag_'.$hashtag , json_encode( $data_photos ) );
				}
				else{
					if( get_option( 'tie_instagram_hashtag_'.$hashtag ) ){
						$data_photos  = json_decode( get_option( 'tie_instagram_hashtag_'.$hashtag ) , true );
					}else{
						echo _e( "Error : Couldn't Get Data From Instegram" , "tieinsta" );
					}
				}
			
			}else {
				_e ( 'Set an access token first' ,  "tieinsta" ) ;
			}
		}
	}else{ //User
		$tie_instagram_transient 	= get_transient( 'tie_instagram_'.$username );
		$instagram_logo_url 		= $username;

		if( false !== $tie_instagram_transient  ){
			$tie_instagram_transient = json_decode( $tie_instagram_transient , true );
			$data 					 = $tie_instagram_transient['data'];
			$data_photos 			 = $tie_instagram_transient['data_photos'];
		}
		else{
			if( !empty( $api )){
				$data_user_serach = tie_insta_remote_get("https://api.instagram.com/v1/users/search?q=$username&access_token=$api");
				if( !empty( $data_user_serach[ 'data' ] ) && is_array( $data_user_serach[ 'data' ] )){
					foreach ( $data_user_serach[ 'data' ] as $data_serach_result ) {
						if( !empty( $data_serach_result['username'] ) && !empty( $username ) && strtolower( $data_serach_result['username'] ) == strtolower( $username ) ){
							$id = $data_serach_result['id'];
							break;
						}
					}
				}
				
				$data 					= tie_insta_remote_get("https://api.instagram.com/v1/users/$id/?access_token=$api");
				$data_photos_source 	= tie_insta_remote_get("https://api.instagram.com/v1/users/$id/media/recent/?access_token=$api");
				$data_photos 			= tie_insta_clean_data( $data_photos_source );
				$data_photos_meta_code 	= $data_photos_source[ 'meta' ][ 'code' ];


				/* Get more than 20 photos */
				if( $media_number > 20 && !empty( $data_photos_source['pagination']['next_url'] ) ){

					$data_photos 		= $data_photos['data'];
					$number_of_pages 	= ( ceil( $media_number / 20 ) - 1 );

					for( $i=1 ; $i<=$number_of_pages ; $i++ ){

						if( !empty( $data_photos_source['pagination']['next_url'] ) ){
							$next_page 			= $data_photos_source['pagination']['next_url'];
							$data_photos_source = tie_insta_remote_get( $next_page );

							if( $data_photos_source[ 'meta' ][ 'code' ] == 200 ){
								$data_photos_next_cleaned 	= tie_insta_clean_data( $data_photos_source );
								$data_photos 				= array_merge( $data_photos , $data_photos_next_cleaned['data'] );
							}
						}
					}

					$data_photos = array( 'data' => $data_photos );
				}
					

				if( $data_user_serach[ 'meta' ][ 'code' ] == 200 && $data[ 'meta' ][ 'code' ] == 200 && $data_photos_meta_code == 200 ){

					$tie_instagram_data = array(
						'data'			=>	$data,
						'data_photos'	=>	$data_photos,
					);

					set_transient( 'tie_instagram_'.$username , json_encode( $tie_instagram_data ) , $cache*60*60 );
					update_option( 'tie_instagram_'.$username , json_encode( $tie_instagram_data ) );
				}
				else{
					if( get_option( 'tie_instagram_'.$username ) ){
						$tie_instagram_stored_data  = json_decode( get_option( 'tie_instagram_'.$username ) , true );
						$data 						= $tie_instagram_stored_data['data'];
						$data_photos 				= $tie_instagram_stored_data['data_photos'];
					}else{
						echo _e( "Error : Couldn't Get Data From Instegram" , "tieinsta" );
					}
				}
			
			}else {
				_e ( 'Set an access token first' ,  "tieinsta" ) ;
			}
		}

	}
	
	if( !empty( $data ) || !empty( $data_photos ) ){

		if( $media_source == 'hashtag' && !empty( $hashtag_info ) ){

			$tie_instagram_header = '
				<div class="tie-instagram-header">
					<div class="tie-instagram-header-tag"><a href="https://instagram.com/explore/tags/'. $hashtag .'"'.$link_target.$rel_nofollow.'>#'.$hashtag.'</a></div>
				</div> <!-- .tie-instagram-header -->';
		
		}elseif( $media_source != 'hashtag' && !empty( $account_info ) ){
			
			$tie_instagram_header = '
				<div class="tie-instagram-header">
					<div class="tie-instagram-avatar">
						<a href="https://instagram.com/'.$data['data']['username'].'"'.$img_class.$link_target.$rel_nofollow.$size.'>
							<img src="'.$data['data']['profile_picture'].'" alt="'. $data['data']['username'] .'"'.$size.' />
							<span class="tie-instagram-follow"><span>'.$follow_text.'</span></span>
						</a>
					</div>
					<div class="tie-instagram-info">
						<a href="http://instagram.com/'.$data['data']['username'].'"'.$link_target.$rel_nofollow.' class="tie-instagram-username">'.$data['data']['username'] .'</a>';
					
				if( !empty( $full_name ) && !empty( $data['data']['full_name'] ) ) $tie_instagram_header .= '<span class="tie-instagram-full_name">'.$data['data']['full_name'] .'</span>';
				if( !empty( $website   ) && !empty( $data['data']['website'] ) ) $tie_instagram_header .= '<a href="'. $data['data']['website'] .'" class="tie-instagram-website"'.$link_target.$rel_nofollow.'>'. $data['data']['website'] .'</a>';
				
				$tie_instagram_header .= '			
					</div>';
				
				if( !empty( $bio ) && !empty( $data['data']['bio'] ) )  $tie_instagram_header .= '<div class="tie-instagram-desc">'. tie_insta_links_mentions ( $data['data']['bio'], true ) .'</div>';
					
				if( !empty( $stats ) )
					$tie_instagram_header .= '
					<div class="tie-instagram-counts">
						<ul>
							<li>
								<span class="number-stat">'. tie_insta_format_num ( $data['data']['counts']['media'] ) .'</span>
								<span>'.__( 'Posts' , 'tieinsta' ).'</span>
							</li>
							<li>
								<span class="number-stat">'. tie_insta_format_num ( $data['data']['counts']['followed_by'] ) .'</span>
								<span>'.__( 'Followers' , 'tieinsta' ).'</span>
							</li>
							<li>
								<span class="number-stat">'. tie_insta_format_num ( $data['data']['counts']['follows'] ) .'</span>
								<span>'.__( 'Following' , 'tieinsta' ).'</span>
							</li>
						</ul>
					</div> <!-- .tie-instagram-counts --> ';
							
				$tie_instagram_header .= '		
				</div> <!-- .tie-instagram-header -->';
		}?>
				
			<div id="tie-instagram-<?php echo $tie_instagram_random_id ?>" class="tie-instagram <?php echo $box_style ?>-skin tieinsta-<?php echo $media_layout ?> grid-col-<?php echo $columns_number ?> header-layout-<?php echo $account_info_layout ?> header-layout-<?php echo $account_info_position ?><?php echo $flat_class ?><?php if( empty( $instagram_logo ) ) echo ' no-insta-logo' ?>">

			<?php if( !empty( $instagram_logo ) ): ?>
				<div class="tie-instagram-bar">
					<a class="instagram-logo" href="https://instagram.com/<?php echo $instagram_logo_url ?>"<?php echo $link_target.$rel_nofollow; ?>></a>
				</div>
			<?php endif; ?>
			
			<?php
				if( !empty( $link ) && $link == 'file' ){
				
					$load_ilightbox = apply_filters( 'tie_instagram_force_avoid_ilightbox', true );
					if( true === $load_ilightbox ) {
						wp_enqueue_script( 'tie-insta-ilightbox' ); ?>
						
					<script>
						var api_<?php echo $tie_instagram_random_id ?> = {};
						jQuery( document ).ready(function() {
							 api_<?php echo $tie_instagram_random_id ?> = jQuery('.tieinsta-ilightbox-<?php echo $tie_instagram_random_id ?>').iLightBox({
								skin: '<?php echo ( !empty ( $tieinsta_options[ 'lightbox_skin' ] ) ? $tieinsta_options[ 'lightbox_skin' ] : 'dark' ); ?>',
								path: '<?php echo ( !empty ( $tieinsta_options[ 'lightbox_thumbs' ] ) ? $tieinsta_options[ 'lightbox_thumbs' ] : 'vertical' ); ?>',
								controls: {
									arrows: <?php echo ( !empty ( $tieinsta_options[ 'lightbox_arrows' ] ) ? 'true' : 'false' ); ?>,
								},
								caption: {
									show: 'mouseout',
									hide: 'mouseenter'
								},
								social: {
								  start: false
								}
							});
						});
					</script>
			<?php
					}
					else { //Comparability With TieLabs's Themes ?>
					<script>
						var api_<?php echo $tie_instagram_random_id ?> = {};
						jQuery( document ).ready(function() {
							api_<?php echo $tie_instagram_random_id ?> = jQuery('.tieinsta-ilightbox-<?php echo $tie_instagram_random_id ?>').iLightBox({
								skin:  tie.lightbox_skin,
								path:  tie.lightbox_thumb,
								controls: {
									arrows: tie.lightbox_arrows,
								},
								caption: {
									show: 'mouseout',
									hide: 'mouseenter'
								},
								social: {
								  start: false
								}
							});
						});
					</script>
					<?php
					}
				}
			?>
					
			<?php if( ( !empty( $tie_instagram_header ) && $media_source != 'hashtag' && !empty( $account_info_position ) && $account_info_position == 'top' ) ||
					  ( !empty( $tie_instagram_header ) && $media_source == 'hashtag' )  ) echo $tie_instagram_header;
			?>
				<div class="tie-instagram-photos">
					<div class="tie-instagram-photos-content">
						<div class="tie-instagram-photos-content-inner">
						<?php	
						$count = 0; 
						foreach ( $data_photos[ 'data' ] as $photo ){
						$count ++;
											
						if(  $link == 'page' ){
							$media_link = $photo['link'];
						}
						else{
							if( !empty( $photo['caption']['text'] )){
								$photo_desc = wp_trim_words ( $photo['caption']['text'] , 40 );
								$photo_desc = tie_insta_links_mentions ( $photo_desc );
							}  

							$lightbox_attr = ' class="tieinsta-ilightbox-'.$tie_instagram_random_id .'" data-options="thumbnail: \''.$photo[ 'images' ]['thumbnail'][ 'url' ] .'\', width: 640, height: 640" data-title="'. $photo_desc .'" data-caption="&lt;i class=\'tieinstaicon-heart\'&gt;&lt;/i&gt; &nbsp;'.tie_insta_format_num ( $photo['likes']['count'] ) .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\'tieinstaicon-comment-alt\'&gt;&lt;/i&gt; &nbsp;'.tie_insta_format_num ( $photo['comments']['count'] ) .'"';
							if( !empty( $photo[ 'videos' ]['standard_resolution'][ 'url' ]) )
								$media_link = $photo[ 'videos' ]['standard_resolution'][ 'url' ] ;
							else
								$media_link = $photo[ 'images' ]['standard_resolution'][ 'url' ];
						}
					?>
						<div class="tie-instagram-post">
							<?php if( !empty( $link ) && $link != 'none' ): ?>
							<a href="<?php echo $media_link ?>"<?php echo $lightbox_attr.$link_target.$rel_nofollow; ?>>
							<?php endif; ?>
								<img src="<?php echo $photo[ 'images' ][$img_size][ 'url' ] ?>" alt="" />
							<?php
							if( $media_layout == 'slider' && !empty( $comments_likes ) ){ ?>
								<div class="media-comment-likes">
									<span class="media-likes"><i class="tieinstaicon-heart"></i><?php echo tie_insta_format_num ( $photo['likes']['count'] ); ?></span>
									<span class="media-comments"><i class="tieinstaicon-comment-alt"></i><?php echo tie_insta_format_num ( $photo['comments']['count'] ); ?></span>
								</div>
							<?php
							}
							if( !empty( $photo[ 'videos' ]['standard_resolution'][ 'url' ]) ){?>
								<span class="media-video"><i class="tieinstaicon-play"></i></span>
							<?php
							}
							if( !empty( $link ) && $link != 'none' ): ?>
							</a>
							<?php endif; ?>
						</div>
					<?php
							if( !empty( $load_more ) && $count == $load_more_per_page ) break;
							elseif( ( !empty( $media_number ) && $count == $media_number ) || ( empty( $media_number ) && $count == 9  ) ) break;
						}
					?>

						</div> <!-- .tie-instagram-photos-content-inner -->

						<?php
						if( !empty( $load_more ) ){ //Load More Module
							$load_more_source = $username;
							if( $media_source == 'hashtag' ){
								$load_more_source = $hashtag;
							}

							if( is_array($data_photos[ 'data' ] )){
								$number_of_items =  count( $data_photos[ 'data' ] );
								if( $media_number <= $number_of_items ){
									$number_of_items = $media_number;
								}
								$load_more_number_pages = ceil ( $number_of_items / $load_more_per_page ) -1;
							}

						?>
						<div class="tie-insta-load-more-button">
							<div class="tie-insta-clear-fix"></div>
							<a href="#" class="tie-insta-load-more" data-loaded="<?php echo $load_more_per_page ?>" data-pages="<?php echo $load_more_number_pages ?>" data-total="<?php echo $media_number-$load_more_per_page  ?>"><?php _e( 'Load More' , 'tieinsta' ) ?></a>
							<span class="tie-insta-loading"></span>
						</div>

						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery(document).on("click", "#tie-instagram-<?php echo $tie_instagram_random_id ?> a.tie-insta-load-more" , function() {
									var InstaLoad 		= jQuery( this );
									var InstaParent 	= InstaLoad.parent();
									var loaded 			= InstaLoad.attr('data-loaded' );
									var totalItems 		= InstaLoad.attr('data-total' );
									var InstaContainer 	= jQuery( '#tie-instagram-<?php echo $tie_instagram_random_id ?> .tie-instagram-photos-content-inner' );
									var number 			= <?php echo $load_more_per_page ?>;

									if( InstaLoad.attr('data-pages' ) > 0 ){
										InstaParent.addClass( 'tie-insta-state-loading' );

										if(  totalItems < <?php echo $load_more_per_page ?> ){
											number 	= totalItems;
										}

										jQuery.post( tie_insta.ajaxurl, { action:'tie_insta_load_more', number:number, loaded:loaded, source:'<?php echo $media_source ?>', name:'<?php echo $load_more_source ?>', id:'<?php echo $tie_instagram_random_id ?>', new_window:'<?php echo $new_window?>', nofollow:'<?php echo $nofollow ?>', link:'<?php echo $link ?>', size:'<?php echo $img_size ?>' }, function(data) {
											var content = jQuery(data);
											var AnimationDelay = 0 ;
											content.each(function(index){
												AnimationDelay = index/30;
												jQuery(this).addClass("tie-insta-loaded-item").attr( 'style', ' animation-delay: '+ AnimationDelay +'s;  -webkit-animation-delay: '+ AnimationDelay +'s;');
											});

											InstaContainer.append( content );
											InstaParent.removeClass( 'tie-insta-state-loading' );
											InstaLoad.attr( 'style', ' animation-delay: '+ (AnimationDelay + 0.1) +'s;  -webkit-animation-delay: '+ (AnimationDelay + 0.1) +'s;');
											api_<?php echo $tie_instagram_random_id ?>.refresh();

											var loaded 	= InstaLoad.attr('data-loaded' );
											var pages 	= InstaLoad.attr('data-pages' );
											var remains = InstaLoad.attr('data-total' );

											pages--;
											if( pages <= 0 ){
												InstaParent.hide();
											}

											InstaLoad.attr('data-loaded', parseInt(loaded)+parseInt( <?php echo $load_more_per_page ?> ) );
											InstaLoad.attr('data-total',  parseInt(remains)-parseInt( <?php echo $load_more_per_page ?> ) );
											InstaLoad.attr('data-pages', pages );

										}, 'html');
									}else{
										InstaParent.hide();
									}
									return false;
								});
							});
						</script>
						<?php
						} //End Of the load more module code
						?>
					</div> <!-- .tie-instagram-photos-content -->
						
					<?php if( $media_layout == 'slider' ){ ?>
					<div class="tie-instagram-nav" class="tie-instagram-nav-prev">
						<a id="prev-<?php echo $tie_instagram_random_id ?>" class="tie-instagram-nav-prev" href="#"><i class="tieinstaicon-left-open"></i></a>
						<a id="next-<?php echo $tie_instagram_random_id ?>" class="tie-instagram-nav-next" href="#"><i class="tieinstaicon-right-open"></i></a>
					</div>
					<?php } ?>
						
				</div>  <!-- .tie-instagram-photos -->
					
				<?php if( !empty( $tie_instagram_header ) && $media_source != 'hashtag' && !empty( $account_info_position ) && $account_info_position == 'bottom' ) echo $tie_instagram_header ?>
							
				<?php if( !empty( $credit ) ): ?>
				<span class="tie-instagram-credit"><a href="http://tielabs.com/"<?php echo $link_target.$rel_nofollow; ?>><?php _e( 'InstaNow - WordPress Plugin' , 'tieinsta' ) ?></a><span>
				<?php endif; ?>	
			</div> <!-- .tie-instagram -->
				
			<?php if( $media_layout == 'slider' ){
				wp_enqueue_script( 'tie-insta-slider-scripts' );
			?>
			<script type="text/javascript">
				jQuery( document ).ready(function() {
					new imagesLoaded( '#tie-instagram-<?php echo $tie_instagram_random_id ?>', function() {
						jQuery( '#tie-instagram-<?php echo $tie_instagram_random_id ?>' ).addClass( 'tieinsta-slider-active' );
						jQuery(function() {
							jQuery('#tie-instagram-<?php echo $tie_instagram_random_id ?>.tieinsta-slider .tie-instagram-photos-content-inner').cycle({
								fx:     '<?php if( !empty( $slider_effect ) ) echo $slider_effect ; else echo 'scrollHorz' ?>',
								timeout: <?php if( !empty( $slider_speed ) ) echo $slider_speed ; else echo '3000' ?>,
								next:   '#next-<?php echo $tie_instagram_random_id ?>',
								prev:   '#prev-<?php echo $tie_instagram_random_id ?>',
								speed: 350,
								pause: true
							});
						});
					});
				});
			</script>
			<?php } ?>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Active Links and Mentions
/*-----------------------------------------------------------------------------------*/
function tie_insta_links_mentions( $text , $html = false ){
	$text = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1&lt;a href='\\2' target='_blank'&gt;\\2&lt;/a&gt;", $text);
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1&lt;a href='http://\\2' target='_blank'&gt;\\2&lt;/a&gt;", $text);
	$text = preg_replace("/@(\w+)/", "&lt;a href='http://instagram.com/\\1' target='_blank'&gt;@\\1&lt;/a&gt;", $text);
	$text = preg_replace("/#(\w+)/", "&lt;a href='http://instagram.com/explore/tags/\\1' target='_blank'&gt;#\\1&lt;/a&gt; ", $text);

	if( $html ){
		$text = htmlspecialchars_decode( $text );
	}
	
	return $text;
}


/*-----------------------------------------------------------------------------------*/
# Custom CSS
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'tie_insta_css');
function tie_insta_css() {

	$tieinsta_options = get_option( 'tie_instagramy' );
	if( !empty( $tieinsta_options['css'] ) ){ ?>
	
<style type="text/css" media="screen"> 

<?php $css_code = str_replace("<pre>" , "", htmlspecialchars_decode( $tieinsta_options['css'] ) ); 
 echo $css_code = str_replace("</pre>", "", $css_code )  , "\n";?>

</style> 

	<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Like Post  - Update Post Likes
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_nopriv_tie_insta_load_more', 'tie_insta_load_more');
add_action('wp_ajax_tie_insta_load_more', 'tie_insta_load_more');
function tie_insta_load_more(){
 	
 	$link_target = $rel_nofollow = $size = $img_class = $lightbox_attr = '';

 	$source 					= $_REQUEST['source'];
 	$name 						= $_REQUEST['name'];
 	$number 					= $_REQUEST['number'];
 	$loaded 					= $_REQUEST['loaded'];
 	$new_window					= $_REQUEST['new_window'];
 	$nofollow 					= $_REQUEST['nofollow'];
 	$link 						= $_REQUEST['link'];
 	$img_size 					= $_REQUEST['size'];
	$tie_instagram_random_id	= $_REQUEST['id'];


	if( $source == 'hashtag' ){
		$tie_instagram_stored_data  = json_decode( get_option( 'tie_instagram_hashtag_'.$name ) , true );
	}else{
 		$tie_instagram_stored_data  = json_decode( get_option( 'tie_instagram_'.$name ) , true );
 		$tie_instagram_stored_data 	= $tie_instagram_stored_data['data_photos'];
 	}
	$data_photos 				= array_slice( $tie_instagram_stored_data[ 'data' ], $loaded , $number );

	if( !empty( $new_window ))
		$link_target = ' target="_blank"';
		
	if( !empty( $nofollow ))
		$rel_nofollow = ' rel="nofollow"';

 	foreach ( $data_photos as $photo ){

 		if(  $link == 'page' ){
			$media_link = $photo['link'];
		}
		else{
			if( !empty( $photo['caption']['text'] )){
				$photo_desc = wp_trim_words ( $photo['caption']['text'] , 40 );
				$photo_desc = tie_insta_links_mentions ( $photo_desc );
			}  

			$lightbox_attr = ' class="tieinsta-ilightbox-'.$tie_instagram_random_id .'" data-options="thumbnail: \''.$photo[ 'images' ]['thumbnail'][ 'url' ] .'\', width: 640, height: 640" data-title="'. $photo_desc .'" data-caption="&lt;i class=\'tieinstaicon-heart\'&gt;&lt;/i&gt; &nbsp;'.tie_insta_format_num ( $photo['likes']['count'] ) .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;i class=\'tieinstaicon-comment-alt\'&gt;&lt;/i&gt; &nbsp;'.tie_insta_format_num ( $photo['comments']['count'] ) .'"';
			
			if( !empty( $photo[ 'videos' ]['standard_resolution'][ 'url' ]) )
				$media_link = $photo[ 'videos' ]['standard_resolution'][ 'url' ] ;
			else
				$media_link = $photo[ 'images' ]['standard_resolution'][ 'url' ];
		}

 		?>
 		<div class="tie-instagram-post">
			<?php if( !empty( $link ) && $link != 'none' ): ?>
			<a href="<?php echo $media_link ?>"<?php echo $lightbox_attr.$link_target.$rel_nofollow; ?>>
			<?php endif; ?>
				<img src="<?php echo $photo[ 'images' ][$img_size][ 'url' ] ?>" alt="" />
				<?php
				if( !empty( $photo[ 'videos' ]['standard_resolution'][ 'url' ]) ){?>
				<span class="media-video"><i class="tieinstaicon-play"></i></span>
				<?php
				}
			if( !empty( $link ) && $link != 'none' ): ?>
			</a>
			<?php endif; ?>
		</div>
		<?php
 	}
	
    die;
}


?>