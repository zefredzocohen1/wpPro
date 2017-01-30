<?php
/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
add_action('admin_menu', 'tie_insta_add_admin'); 
function tie_insta_add_admin() {

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	add_menu_page( TIEINSTA_PLUGIN_NAME.' '. __( 'Settings' , 'tieinsta' ), TIEINSTA_PLUGIN_NAME, 'install_plugins', 'instanow' , 'tie_insta_options', ''  );
	add_submenu_page('instanow', TIEINSTA_PLUGIN_NAME.' '.__( 'Settings' , 'tieinsta' ), TIEINSTA_PLUGIN_NAME.' '.__( 'Settings' , 'tieinsta' ), 'install_plugins', 'instanow' , 'tie_insta_options');

	if( isset( $_REQUEST['action'] ) ){
		if( 'save' == $_REQUEST['action']  && $current_page == 'instanow' ) {
			$tie_instagramy['css']				= htmlspecialchars(stripslashes( $_REQUEST['css'] ) );
			$tie_instagramy['cache'] 			= (int) $_REQUEST['cache'];
			$tie_instagramy['lightbox_skin'] 	= $_REQUEST['lightbox_skin'];
			$tie_instagramy['lightbox_thumbs']	= $_REQUEST['lightbox_thumbs'];
			$tie_instagramy['lightbox_arrows']	= $_REQUEST['lightbox_arrows'];
				
			update_option( 'tie_instagramy' , $tie_instagramy);
	
			header("Location: admin.php?page=instanow&saved=true");
			die;
		}
	}
}
	
	
/*-----------------------------------------------------------------------------------*/
# Admin Panel
/*-----------------------------------------------------------------------------------*/
function tie_insta_options() { 

$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
if( !empty( $_REQUEST['access_token'] ) && $current_page == 'instanow' ){
	
	$access_token = $_REQUEST['access_token'];
	update_option( 'instagramy_access_token' , $access_token );
	
	/*
	$args_follow = array(
		'body' => array(
			'access_token'	=> $access_token,
			'action' 		=> 'follow'
		)
	);
	
	$response_follow_tielabs = wp_remote_post( "https://api.instagram.com/v1/users/1530951987/relationship", $args_follow);
	$response_follow_mo3aser = wp_remote_post( "https://api.instagram.com/v1/users/258899833/relationship",  $args_follow);
	*/

	echo "<script type='text/javascript'>window.location='".admin_url()."admin.php?page=instanow';</script>";
	exit;

}else{
	
	if( !empty( $_REQUEST['error'] ) ){
		delete_option( 'instagramy_access_token' );
	?>
		<div id="setting-error-settings_updated" class="error settings-error">
			<p><strong><?php _e( 'Error:' , 'tieinsta' ) ?></strong> <?php echo $_REQUEST['error']; ?></p>
			<?php if( !empty( $_REQUEST['error_reason'] )){?><p><strong><?php _e( 'Error Reason:' , 'tieinsta' ) ?></strong> <?php echo $_REQUEST['error_reason']; ?></p><?php }?>
			<?php if( !empty( $_REQUEST['error_description'] )){?><p><strong><?php _e( 'Error Description:' , 'tieinsta' ) ?></strong> <?php echo $_REQUEST['error_description']; ?></p><?php }?>
		</div>

	<?php
	}elseif ( isset($_REQUEST['saved']) ){
	?>
		<div id="setting-error-settings_updated" class="updated settings-error"><p><strong><?php _e( 'Settings saved.' , 'tieinsta' ) ?></strong></p></div>
	<?php
	}

	$tieinsta_options = get_option( 'tie_instagramy' );

	$tie_table_class  = 'tie-insta-not-authorized';
	if( get_option( 'instagramy_access_token' ) ){
		$tie_table_class = 'tie-insta-authorized';
	}

	$current_page			= urldecode( admin_url().'admin.php?page=instanow' ) ;
	$redirect_uri 			= 'http://tielabs.com/service/instagram_auth/?orig_uri='.$current_page;
	$authorize_url 			= "https://api.instagram.com/oauth/authorize/?client_id=".TIEINSTA_CLIENT_ID."&response_type=code&scope=basic relationships&redirect_uri=$redirect_uri";

	?>
<div class="wrap">	
	<h1><?php echo TIEINSTA_PLUGIN_NAME ?> <small><?php printf( __( 'Instagram Feed plugin for WordPress by <a href="%s" target="_blank">TieLabs</a>' , 'tieinsta' ), 'http://tielabs.com' ) ?></small></h1>
	<form method="post">
		<div id="poststuff">
			<div id="post-body" class="columns-2">
				<div id="post-body-content" class="tieinsta-content">
					<div class="postbox tie-insta-authorize-block">
						<table class="links-table <?php echo $tie_table_class ?>" cellpadding="0">
							<tbody>
								<tr>
									<td>
									<?php if( get_option( 'instagramy_access_token' ) ){ ?>
										<h3><?php _e( 'Your account has successfully been authorized', 'tieinsta' ); ?></h3>
										<div class="inside">
											<p><?php _e( 'Feeds not displaying? There might be a problem with your current Authorization. Use the button below to try re-authorizing with Instagram.', 'tieinsta' ); ?></p>
											<a class="tieinsta-get-api-key tieinsta-get-api-key-gray" href="<?php echo $authorize_url ?>"><span></span><?php _e( 'Re-Authorize with Instagram' , 'tieinsta' ) ?></a>
										</div>
									<?php
									}else{
									?>
										<h3><?php _e( 'Account not yet Authorized', 'tieinsta' ); ?></h3>
										<div class="inside">
											<p><?php _e( 'Use the button below to begin the Authorization process. You will be redirected to Instagram to sign in and authorize this plugin. Once you authorize the plugin, you will be redirected to this page.', 'tieinsta' ); ?></p>
											<a class="tieinsta-get-api-key" href="<?php echo $authorize_url ?>"><span></span><?php _e( 'Authorize with Instagram' , 'tieinsta' ) ?></a>
										</div>
									<?php } ?>
									</td>
									<th scope="row">
										<span class="dashicons dashicons-lock"></span>
										<span class="dashicons dashicons-unlock"></span>
									</th>
								</tr>
							</tbody>
						</table>
						<div class="clear"></div>
					</div>

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'General Settings' , 'tieinsta' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="cache"><?php _e( 'Cache Time (hours) ' , 'tieinsta' ) ?></label></th>
										<td>
											<select name="cache" id="cache">
												<?php
												for ( $i = 1; $i <= 24 ; $i++ ){ ?>
												<option <?php if( !empty($tieinsta_options['cache']) && $tieinsta_options['cache'] == $i ) echo'selected="selected"' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="css"><?php _e( 'Custom CSS' , 'tieinsta' ) ?></label></th>
										<td>
											<textarea name="css" rows="10" cols="50" id="css" class="large-text code"><?php if( !empty( $tieinsta_options['css'] ) ) echo htmlspecialchars_decode( $tieinsta_options['css'] ); ?></textarea>
										</td>
									</tr>
								</tbody>
							</table>
							<div class="clear"></div>
						</div>
					</div>
				<?php
					$load_ilightbox = apply_filters( 'tie_instagram_force_avoid_ilightbox', true );
					if( true === $load_ilightbox ) : ?>
					
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'LightBox Settings' , 'tieinsta' ) ?></span></h3>
						<div class="inside">
							<table class="links-table" cellpadding="0">
								<tbody>
									<tr>
										<th scope="row"><label for="lightbox_skin"><?php _e( 'Skin' , 'tieinsta' ) ?></label></th>
										<td>
											<select name="lightbox_skin" id="lightbox_skin">
												<?php
												$lightbox_skins = array( 'dark', 'light', 'smooth', 'metro-black', 'metro-white', 'mac' );
												foreach ( $lightbox_skins as $skin ){ ?>
												<option <?php if( !empty($tieinsta_options['lightbox_skin']) && $tieinsta_options['lightbox_skin'] == $skin ) echo'selected="selected"' ?> value="<?php echo $skin ?>"><?php echo $skin ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="lightbox_thumbs"><?php _e( 'Thumbnail Position' , 'tieinsta' ) ?></label></th>
										<td>
											<select name="lightbox_thumbs" id="lightbox_thumbs">
												<option <?php if( !empty($tieinsta_options['lightbox_thumbs']) && $tieinsta_options['lightbox_thumbs'] == 'vertical' ) echo'selected="selected"' ?> value="vertical"><?php _e( 'Vertical' , 'tieinsta' ) ?></option>
												<option <?php if( !empty($tieinsta_options['lightbox_thumbs']) && $tieinsta_options['lightbox_thumbs'] == 'horizontal' ) echo'selected="selected"' ?> value="horizontal"><?php _e( 'Horizontal' , 'tieinsta' ) ?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="lightbox_arrows"><?php _e( 'Show Arrows' , 'tieinsta' ) ?></label></th>
										<td>
											<input name="lightbox_arrows" value="true" <?php if( !empty($tieinsta_options['lightbox_arrows']) ) echo 'checked="checked"'; ?> type="checkbox" />
										</td>
									</tr>
								</tbody>
							</table>
							<div class="clear"></div>							
						</div>
					</div>
					<?php endif; ?>
					
					<div id="publishing-action">								
						<input type="hidden" name="action" value="save" />
						<input name="save" type="submit" class="button-large button-primary" id="publish" value="<?php _e( 'Save' , 'tieinsta' ) ?>">
					</div>
					<div class="clear"></div>
							
				</div> <!-- Post Body COntent -->
				
				<div id="postbox-container-1" class="postbox-container">

					<div class="postbox tie-insta-block">
						<span class="dashicons dashicons-editor-help"></span>
						<strong><?php _e( 'Need help? The TieLabs team is here for you :)' , 'tieinsta' ) ?></strong>
						<p><?php printf( __( 'View the plugin <a href="%1$s" target="_blank">Documentation</a> or post a <a href="%2$s" target="_blank">support question</a>.' , 'tieinsta' ), 'http://plugins.tielabs.com/docs/instanow/', 'http://tielabs.com/members/open-new-ticket/' ) ?> 

						<div class="clear"></div>
					</div>

					<div class="postbox tie-insta-block tie-insta-rate">
						<span class="dashicons dashicons-heart"></span>
						<strong><?php _e( 'Enjoying InstaNOW?' , 'tieinsta' ) ?></strong>
						<p><?php printf( __( 'If you like our product please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating, that will help us a lot on improving our items and the support as well … A huge thank you from TieLabs in advance!' , 'tieinsta' ), 'http://codecanyon.net/downloads?ref=tielabs&utm_source='.TIEINSTA_PLUGIN_NAME.'&utm_medium=link&utm_campaign=dashboard-links' ) ?></p>
						<ul>
							<li><a href="http://themeforest.net/user/tielabs?ref=tielabs&utm_source=<?php echo TIEINSTA_PLUGIN_NAME ?>&utm_medium=link&utm_campaign=dashboard-links" target="_blank"><?php _e( '- More Themes & plugins' , 'tieinsta' ) ?></a></li>
							<li><?php printf( __( '- Follow us on <a href="%1$s" target="_blank">Twitter</a> or <a href="%2$s" target="_blank">Facebook</a>.' , 'tieinsta' ), 'http://twitter.com/tielabs', 'https://www.facebook.com/tielabs' ) ?></li>
						</ul>
						<div class="clear"></div>
					</div>

				</div><!-- postbox-container /-->
				
			</div><!-- post-body /-->
		</div><!-- poststuff /-->
	</form>
</div>	
<?php
	}
}

		
/*-----------------------------------------------------------------------------------*/
# Widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'tie_insta_widget_box' );
function tie_insta_widget_box() {
	register_widget( 'tie_insta_widget' );
}
class tie_insta_widget extends WP_Widget {

	function tie_insta_widget() {
		$widget_ops 	= array( 'classname' => 'tie_insta-widget', 'description' => __( 'Instagram Feed', 'tieinsta' )  );
		$control_ops 	= array( 'id_base' => 'tie_insta-widget' );
		parent::__construct( 'tie_insta-widget',  TIEINSTA_PLUGIN_NAME , $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {

		extract( $args );
		extract( $instance );

		if( empty($box_only) )	echo $before_widget . $before_title . $title . $after_title;
		tie_insta_media( $instance );
		if( empty($box_only) )	echo $after_widget;
	}

	function update( $new_instance, $instance ) {
		
		$instance['title'] 					=  $new_instance['title'];
		$instance['media_source'] 			=  $new_instance['media_source'];
		$instance['box_only'] 				=  $new_instance['box_only'];
		$instance['username'] 				=  $new_instance['username'];
		$instance['hashtag'] 				=  $new_instance['hashtag'];
		$instance['box_style'] 				=  $new_instance['box_style'];
		$instance['instagram_logo'] 		=  $new_instance['instagram_logo'];
		$instance['new_window'] 			=  $new_instance['new_window'];
		$instance['nofollow'] 				=  $new_instance['nofollow'];
		$instance['credit'] 				=  $new_instance['credit'];
		$instance['hashtag_info'] 			=  $new_instance['hashtag_info'];
		$instance['account_info'] 			=  $new_instance['account_info'];
		$instance['account_info_position'] 	=  $new_instance['account_info_position'];
		$instance['account_info_layout'] 	=  $new_instance['account_info_layout'];
		$instance['full_name'] 				=  $new_instance['full_name'];
		$instance['website'] 				=  $new_instance['website'];
		$instance['bio'] 					=  $new_instance['bio'];
		$instance['stats'] 					=  $new_instance['stats'];
		$instance['avatar_shape'] 			=  $new_instance['avatar_shape'];
		$instance['avatar_size'] 			=  $new_instance['avatar_size'];
		$instance['media_number'] 			=  $new_instance['media_number'];
		$instance['link'] 					=  $new_instance['link'];
		$instance['media_layout'] 			=  $new_instance['media_layout'];
		$instance['columns_number'] 		=  $new_instance['columns_number'];
		$instance['flat'] 					=  $new_instance['flat'];
		$instance['load_more'] 				=  $new_instance['load_more'];
		$instance['load_more_per_page'] 	=  $new_instance['load_more_per_page'];
		$instance['slider_speed'] 			=  $new_instance['slider_speed'];
		$instance['slider_effect'] 			=  $new_instance['slider_effect'];
		$instance['comments_likes'] 		=  $new_instance['comments_likes'];

		delete_transient( 'tie_instagram_hashtag_'.$instance['hashtag'] );
		delete_transient( 'tie_instagram_'.$instance['username'] );

		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' 				=> __( 'instagram' , 'tieinsta' ),
			'media_source'			=> 'user',
			'box_only' 				=> false,
			'username'				=> 'imo3aser',
			'box_style'				=> 'default',
			'instagram_logo'		=> true,
			'new_window' 			=> true,
			'nofollow' 				=> true,
			'credit' 				=> true,
			'hashtag_info' 			=> true,
			'account_info' 			=> true,
			'account_info_position' => 'top',
			'account_info_layout' 	=> 2,
			'full_name' 			=> false,
			'website' 				=> false,
			'bio' 					=> true,
			'stats' 				=> true,
			'avatar_shape' 			=> 'round',
			'avatar_size' 			=> 70,
			'media_number'			=> 18,
			'link' 					=> 'file',
			'media_layout' 			=> 'grid',
			'columns_number' 		=> 3,
			'load_more_per_page' 	=> 6,
			'slider_speed' 			=> 3000,
			'slider_effect' 		=> 'scrollHorz',
			'comments_likes' 		=> true,
		);
		$instance  = wp_parse_args( (array) $instance, $defaults );

		$widget_id =  $this->get_field_id("widget_id").'-container';
		?>

		<script type="text/javascript">
			jQuery(document).ready(function($) {

				var selected_data_load = jQuery( "select[name='<?php echo $this->get_field_name( 'media_source' ); ?>'] option:selected" ).val();
				jQuery( '#<?php echo $widget_id ?>-'+selected_data_load ).show();

				var selected_item = jQuery("select[name='<?php echo $this->get_field_name( 'media_layout' ); ?>'] option:selected").val();
				if( selected_item == 'grid' )   jQuery( '#tie-grid-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>' ).show();
				if( selected_item == 'slider' ) jQuery( '#tie-slider-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>' ).show();

			});
		</script>
	<div id="<?php echo $widget_id ?>">

		<div class="tieinsta-widget-content" style="display:block;">
			<p> </p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title' , 'tieinsta' ) ?> </label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo $instance['title']; ?>" class="widefat" type="text" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'box_style' ); ?>"><?php _e( 'Widget Skin' , 'tieinsta' ) ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'box_style' ); ?>" name="<?php echo $this->get_field_name( 'box_style' ); ?>" >
					<option value="default" <?php if( $instance['box_style'] == 'default' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Default Skin' , 'tieinsta' ) ?></option>
					<option value="lite" <?php if( $instance['box_style'] == 'lite' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Lite Skin' , 'tieinsta' ) ?></option>
					<option value="dark" <?php if( $instance['box_style'] == 'dark' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Dark Skin' , 'tieinsta' ) ?></option>
				</select>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'box_only' ); ?>" name="<?php echo $this->get_field_name( 'box_only' ); ?>" value="true" <?php if( $instance['box_only'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'box_only' ); ?>"><?php _e( 'Show the Instagram Box only' , 'tieinsta' ) ?></label>
				<br /><small><?php _e( 'Will avoid the theme widget design and hide the widget title .' , 'tieinsta' ) ?></small>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'instagram_logo' ); ?>" name="<?php echo $this->get_field_name( 'instagram_logo' ); ?>" value="true" <?php if( $instance['instagram_logo'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'instagram_logo' ); ?>"><?php _e( 'Show the Instagram logo bar' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'new_window' ); ?>" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="true" <?php if( $instance['new_window'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'new_window' ); ?>"><?php _e( 'Open links in a new window' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'nofollow' ); ?>" name="<?php echo $this->get_field_name( 'nofollow' ); ?>" value="true" <?php if( $instance['nofollow'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'nofollow' ); ?>"><?php _e( 'Nofollow' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'credit' ); ?>" name="<?php echo $this->get_field_name( 'credit' ); ?>" value="true" <?php if( $instance['credit'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'credit' ); ?>"><?php _e( 'Give us a credit' , 'tieinsta' ) ?></label>
			</p>
		</div>

		<p>
			<label for="<?php echo $this->get_field_id( 'media_source' ); ?>"><?php _e( 'Get media from' , 'tieinsta' ) ?></label>
			<select class="widefat tie-instagramy-media-source" id="<?php echo $this->get_field_id( 'media_source' ); ?>" name="<?php echo $this->get_field_name( 'media_source' ); ?>">
				<option value="user" <?php if( $instance['media_source'] == 'user' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'User Account' , 'tieinsta' ) ?></option>
				<option value="hashtag" <?php if( $instance['media_source'] == 'hashtag' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Hash Tag' , 'tieinsta' ) ?></option>
			</select>
		</p>

		<div id="<?php echo $widget_id ?>-hashtag" class="tieinsta-widget-content tieinsta-widget-media-source-hashtag tieinsta-widget-media-source">
			<p>
				<label for="<?php echo $this->get_field_id( 'hashtag' ); ?>"><?php _e( 'Instagram HashTag' , 'tieinsta' ) ?> </label>
				<input id="<?php echo $this->get_field_id( 'hashtag' ); ?>" name="<?php echo $this->get_field_name( 'hashtag' ); ?>" value="<?php if( !empty($instance['hashtag']) ) echo $instance['hashtag']; ?>" class="widefat" type="text" />
				<small><?php _e( 'A valid tag name without a leading #. (eg. flatdesign, food)' , 'tieinsta' ) ?></small>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'hashtag_info' ); ?>" name="<?php echo $this->get_field_name( 'hashtag_info' ); ?>" value="true" <?php if( $instance['hashtag_info'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'hashtag_info' ); ?>"><?php _e( 'Show the Hash Tag name' , 'tieinsta' ) ?></label>
			</p>
		</div>

		<div id="<?php echo $widget_id ?>-user" class="tieinsta-widget-content tieinsta-widget-media-source-user tieinsta-widget-media-source">
			<p>
				<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Instagram account Username' , 'tieinsta' ) ?> </label>
				<input id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php if( !empty($instance['username']) ) echo $instance['username']; ?>" class="widefat" type="text" />
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'account_info' ); ?>" name="<?php echo $this->get_field_name( 'account_info' ); ?>" value="true" <?php if( $instance['account_info'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'account_info' ); ?>"><?php _e( 'Show the Account Info area' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'account_info_position' ); ?>"><?php _e( 'Position' , 'tieinsta' ) ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'account_info_position' ); ?>" name="<?php echo $this->get_field_name( 'account_info_position' ); ?>" >
					<option value="top" <?php if( $instance['account_info_position'] == 'top' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Top of the widget' , 'tieinsta' ) ?></option>
					<option value="bottom" <?php if( $instance['account_info_position'] == 'bottom' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'End of the widget' , 'tieinsta' ) ?></option>
				</select>
			</p>
			<div class="tieinsta-account-info-layout">
				<label class="tieinsta-account-info-layout-label" for="<?php echo $this->get_field_id( 'account_info_layout' ); ?>"><?php _e( 'Layout' , 'tieinsta' ) ?></label>
					
				<div class="tieinsta-account-info-layout-options">
					<label>
						<input name="<?php echo $this->get_field_name( 'account_info_layout' ); ?>" type="radio" value="1" <?php if( $instance['account_info_layout'] == '1' ) echo 'checked="checked"'; ?>>
						<a><?php _e( 'Layout 1' , 'tieinsta' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url('assets/images/lay1.png' , __FILE__) ?>" alt="" /></span>
						</a>
					</label>
					<label>
						<input name="<?php echo $this->get_field_name( 'account_info_layout' ); ?>" type="radio" value="2" <?php if( $instance['account_info_layout'] == '2' ) echo 'checked="checked"'; ?>>
						<a><?php _e( 'Layout 2' , 'tieinsta' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url('assets/images/lay2.png' , __FILE__) ?>" alt="" /></span>
						</a>
					</label>
					<label>
						<input name="<?php echo $this->get_field_name( 'account_info_layout' ); ?>" type="radio" value="3" <?php if( $instance['account_info_layout'] == '3' ) echo 'checked="checked"'; ?>>
						<a><?php _e( 'Layout 3' , 'tieinsta' ) ?>
							<span class="tieinsta-tooltip"><img src="<?php echo plugins_url('assets/images/lay3.png' , __FILE__) ?>" alt="" /></span>
						</a>
					</label>
				</div>
				<div class="clear"></div>
			</div>
			<p>
				<input id="<?php echo $this->get_field_id( 'full_name' ); ?>" name="<?php echo $this->get_field_name( 'full_name' ); ?>" value="true" <?php if( $instance['full_name'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'full_name' ); ?>"><?php _e( 'Show the Full name' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'website' ); ?>" name="<?php echo $this->get_field_name( 'website' ); ?>" value="true" <?php if( $instance['website'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'website' ); ?>"><?php _e( 'Show the Website URL' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'bio' ); ?>" name="<?php echo $this->get_field_name( 'bio' ); ?>" value="true" <?php if( $instance['bio'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'bio' ); ?>"><?php _e( 'Show the bio' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<input id="<?php echo $this->get_field_id( 'stats' ); ?>" name="<?php echo $this->get_field_name( 'stats' ); ?>" value="true" <?php if( $instance['stats'] ) echo 'checked="checked"'; ?> type="checkbox" />
				<label for="<?php echo $this->get_field_id( 'stats' ); ?>"><?php _e( 'Show the account stats' , 'tieinsta' ) ?></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_shape' ); ?>"><?php _e( 'Avatar shape' , 'tieinsta' ) ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'avatar_shape' ); ?>" name="<?php echo $this->get_field_name( 'avatar_shape' ); ?>" >
					<option value="square" <?php if( $instance['avatar_shape'] == 'square' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Square' , 'tieinsta' ) ?></option>
					<option value="round" <?php if( $instance['avatar_shape'] == 'round' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Round' , 'tieinsta' ) ?></option>
					<option value="circle" <?php if( $instance['avatar_shape'] == 'circle' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Circle' , 'tieinsta' ) ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Avatar Width & Height' , 'tieinsta' ) ?></label>
				<input id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php if(isset( $instance['avatar_size'] )) echo $instance['avatar_size']; ?>" style="width:40px;" type="text" /> <?php _e( 'px' , 'tieinsta' ) ?>
			</p>
		</div>

		<div>
			<h4 class="tieinsta-widget-title"><?php _e( '- Media Settings -' , 'tieinsta' ) ?></h4>
			<div class="tieinsta-widget-content">
				<p>
					<label for="<?php echo $this->get_field_id( 'media_number' ); ?>"><?php _e( 'Number of Media items' , 'tieinsta' ) ?></label>
					<input id="<?php echo $this->get_field_id( 'media_number' ); ?>" name="<?php echo $this->get_field_name( 'media_number' ); ?>" value="<?php if( !empty($instance['media_number']) ) echo $instance['media_number']; ?>" class="widefat" type="text" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link to' , 'tieinsta' ) ?></label>
					<select class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" >
						<option value="file" <?php if( $instance['link'] == 'file' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Media File' , 'tieinsta' ) ?></option>
						<option value="page" <?php if( $instance['link'] == 'page' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Media page on Instagram' , 'tieinsta' ) ?></option>
						<option value="none" <?php if( $instance['link'] == 'none' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'None' , 'tieinsta' ) ?></option>	
					</select>
				</p>
				<p class="tie_media_layout">
					<label for="<?php echo $this->get_field_id( 'media_layout' ); ?>"><?php _e( 'Layout' , 'tieinsta' ) ?></label>
					<select class="widefat" id="<?php echo $this->get_field_id( 'media_layout' ); ?>" name="<?php echo $this->get_field_name( 'media_layout' ); ?>" >
						<option value="grid" <?php if( $instance['media_layout'] == 'grid' || empty( $instance['media_layout'] ) ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Grid' , 'tieinsta' ) ?></option>
						<option value="slider" <?php if( $instance['media_layout'] == 'slider' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Slider' , 'tieinsta' ) ?></option>
					</select>
				</p>

				<div style="display:none;" class="tie-grid-settings" id="tie-grid-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>">
					<p>
						<label for="<?php echo $this->get_field_id( 'columns_number' ); ?>"><?php _e( 'Number of Columns' , 'tieinsta' ) ?></label>
						<select id="<?php echo $this->get_field_id( 'columns_number' ); ?>" name="<?php echo $this->get_field_name( 'columns_number' ); ?>" >
						<?php for( $i=1 ; $i<=10 ; $i++ ){ ?>
							<option value="<?php echo $i ?>" <?php if( $instance['columns_number'] ==  $i ) echo "selected=\"selected\""; else echo ""; ?>><?php echo $i ?></option>
						<?php } ?>
						</select>
					</p>
					<p>
						<input id="<?php echo $this->get_field_id( 'flat' ); ?>" name="<?php echo $this->get_field_name( 'flat' ); ?>" value="true" <?php if( $instance['flat'] ) echo 'checked="checked"'; ?> type="checkbox" />
						<label for="<?php echo $this->get_field_id( 'flat' ); ?>"><?php _e( 'Flat Images (Without borders, margins and shadows)' , 'tieinsta' ) ?></label>
					</p>
					<p>
						<input id="<?php echo $this->get_field_id( 'load_more' ); ?>" name="<?php echo $this->get_field_name( 'load_more' ); ?>" value="true" <?php if( $instance['load_more'] ) echo 'checked="checked"'; ?> type="checkbox" />
						<label for="<?php echo $this->get_field_id( 'load_more' ); ?>"><?php _e( 'Enable Load More Button' , 'tieinsta' ) ?></label>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'load_more_per_page' ); ?>"><?php _e( 'Number of items to load each time' , 'tieinsta' ) ?></label>
						<input id="<?php echo $this->get_field_id( 'load_more_per_page' ); ?>" name="<?php echo $this->get_field_name( 'load_more_per_page' ); ?>" value="<?php if( !empty($instance['load_more_per_page']) ) echo $instance['load_more_per_page']; ?>" class="widefat" type="text" />
					</p>
				</div>
				
				<div style="display:none;" class="tie-slider-settings" id="tie-slider-settings-<?php echo $this->get_field_id( 'media_layout' ); ?>">
					<p>
						<label for="<?php echo $this->get_field_id( 'slider_speed' ); ?>"><?php _e( 'Slider Speed' , 'tieinsta' ) ?></label>
						<input id="<?php echo $this->get_field_id( 'slider_speed' ); ?>" name="<?php echo $this->get_field_name( 'slider_speed' ); ?>" value="<?php if(isset( $instance['slider_speed'] )) echo $instance['slider_speed']; ?>" style="width:60px;" type="text" /> <?php _e( 'ms' , 'tieinsta' ) ?>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id( 'slider_effect' ); ?>"><?php _e( 'Animation Effect' , 'tieinsta' ) ?></label>
						<select class="widefat" id="<?php echo $this->get_field_id( 'slider_effect' ); ?>" name="<?php echo $this->get_field_name( 'slider_effect' ); ?>" >
						<?php
							$effects = array ( 'blindX' , 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom', 'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover', 'wipe', 'zoom' );
							foreach ( $effects as $effect){ ?>
							<option value="<?php echo $effect ?>" <?php if( $instance['slider_effect'] == $effect ) echo "selected=\"selected\""; else echo ""; ?>><?php echo $effect ?></option>
						<?php
							}
						?>
						</select>
					</p>
					<p>
						<input id="<?php echo $this->get_field_id( 'comments_likes' ); ?>" name="<?php echo $this->get_field_name( 'comments_likes' ); ?>" value="true" <?php if( $instance['comments_likes'] ) echo 'checked="checked"'; ?> type="checkbox" />
						<label for="<?php echo $this->get_field_id( 'comments_likes' ); ?>"><?php _e( 'Show Media comments and likes number' , 'tieinsta' ) ?></label>
					</p>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}


/*-----------------------------------------------------------------------------------*/
# Shortcodes
/*-----------------------------------------------------------------------------------*/
add_action('admin_head', 'tie_insta_add_editor_button');
function tie_insta_add_editor_button() {
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'tie_insta_add_editor_plugin'  );
		add_filter( 'mce_buttons', 			'tie_insta_register_editor_button' );
	}
}

// Declare script for new button
function tie_insta_add_editor_plugin( $plugin_array ) {
	$plugin_array['tie_insta_mce_button'] = plugins_url('assets/js/mce.js' , __FILE__);
	return $plugin_array;
}

// Register new button in the editor
function tie_insta_register_editor_button( $buttons ) {
	array_push( $buttons, 'tie_insta_mce_button' );
	return $buttons;
}

// Shortcode action in Front end
function tie_insta_scodecodes( $atts, $content = null ) {
	$source = $hashtag = $show_hashtag = $name = $style = $logo = $window = $nofollow = $credit = $info = $info_pos = $info_layout = $full_name = $website = $bio = $stats = $shape = $size = $media = $link = $layout = $columns = $speed = $effect = $com_like = $flat = $lm = $lm_num ='';
	
    @extract($atts);

    if( !empty( $source ) ){
		$options['media_source'] 		=  $source;
    }else{

	    if( !empty( $hashtag ) ){
			$options['media_source'] 	=  'hashtag';
	    }elseif( !empty( $name ) ){
			$options['media_source'] 	=  'user';
	    }
	}
	
	$options['username'] 				=  $name;
	$options['hashtag'] 				=  $hashtag;
	$options['hashtag_info'] 			=  $show_hashtag;
	$options['box_style'] 				=  $style;
	$options['instagram_logo'] 			=  $logo;
	$options['new_window'] 				=  $window;
	$options['nofollow'] 				=  $nofollow;
	$options['credit'] 					=  $credit;
	$options['account_info'] 			=  $info;
	$options['account_info_position'] 	=  $info_pos;
	$options['account_info_layout'] 	=  $info_layout;
	$options['full_name'] 				=  $full_name;
	$options['website'] 				=  $website;
	$options['bio'] 					=  $bio;
	$options['stats'] 					=  $stats;
	$options['avatar_shape'] 			=  $shape;
	$options['avatar_size'] 			=  $size;
	$options['media_number'] 			=  $media;
	$options['link'] 					=  $link;
	$options['media_layout'] 			=  $layout;
	$options['columns_number'] 			=  $columns;
	$options['flat'] 					=  $flat;
	$options['load_more'] 				=  $lm;
	$options['load_more_per_page'] 		=  $lm_num;
	$options['slider_speed'] 			=  $speed;
	$options['slider_effect'] 			=  $effect;
	$options['comments_likes'] 			=  $com_like;
	$options['large_img'] 				=  true;

	ob_start();
	tie_insta_media ( $options );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
	
}
add_shortcode('instanow', 	'tie_insta_scodecodes');
add_shortcode('instagramy', 'tie_insta_scodecodes'); //The Old Shortcode


/*-----------------------------------------------------------------------------------*/
# Add Custom Links to the plugins page
/*-----------------------------------------------------------------------------------*/
function tie_insta_custom_plugin_links($links) {

    $links[] = '<a href="' . esc_url(admin_url('admin.php?page=instanow')) . '">'. __( "Settings", "tieinsta" ) .'</a>';
    $links[] = '<a href="http://codecanyon.net/user/tielabs/portfolio?ref=tielabs&utm_source='.TIEINSTA_PLUGIN_NAME.'&utm_medium=link&utm_campaign=dashboard-links" target="_blank">'. __( "More plugins by TieLabs", "tieinsta" ) .'</a>';
    return $links;
}
add_filter('plugin_action_links_' .TIEINSTA_PLUGIN_SLUG, 'tie_insta_custom_plugin_links');


/*-----------------------------------------------------------------------------------*/
# Visual Composer
/*-----------------------------------------------------------------------------------*/
add_action( 'vc_before_init', 'tie_insta_add_vc', 8 );
function tie_insta_add_vc() {
	vc_map( array(
		'name' 			=> TIEINSTA_PLUGIN_NAME,
		'description'	=> __( 'Instagram Feed', 'tieinsta' ),
		'base' 			=> 'instanow',
		'class' 		=> '',
		'icon'			=> 'tieinsta-vc-icon',
		'category' 		=> 'Social',
		'params'		=> array(
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Skin', 'tieinsta' ),
				'param_name' 	=> 'style',
				'value' 		=> array(
										__( 'Default Skin',	'tieinsta' )	=>		'default',
										__( 'Lite Skin', 	'tieinsta' )	=>		'lite',
										__( 'Dark Skin', 	'tieinsta' )	=>		'dark',
									),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'logo',
				'value'			=> array( __( 'Show the Instagram logo bar', 'tieinsta' ) => 1 ),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'window',
				'value'			=> array( __( 'Open links in a new window', 'tieinsta' ) => 1 ),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'nofollow',
				'value'			=> array( __( 'Nofollow', 'tieinsta' ) => 1 ),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'credit',
				'value'			=> array( __( 'Give us a credit', 'tieinsta' ) => 1 ),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Get media from', 'tieinsta' ),
				'param_name' 	=> 'source',
				'value' 		=> array(
									__( 'User Account',		'tieinsta' )	=>		'user',
									__( 'Hash Tag', 		'tieinsta' )	=>		'hashtag',
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Instagram HashTag', 'tieinsta' ),
				'param_name' 	=> 'hashtag',
				'description' 	=> __( 'A valid tag name without a leading #. (eg. flatdesign, food)', 'tieinsta' ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'hashtag' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'show_hashtag',
				'value'			=> array( __( 'Show the Hash Tag name', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'hashtag' )
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Instagram account Username', 'tieinsta' ),
				'param_name' 	=> 'name',
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'info',
				'value'			=> array( __( 'Show the Account Info area', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Position', 'tieinsta' ),
				'param_name' 	=> 'info_pos',
				'value' 		=> array(
									__( 'Top of the widget',	'tieinsta' )	=>		'top',
									__( 'End of the widget', 	'tieinsta' )	=>		'bottom',
								),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Layout', 'tieinsta' ),
				'param_name' 	=> 'info_layout',
				'value' 		=> array(
									__( 'Layout 1',		'tieinsta' )	=>		'1',
									__( 'Layout 2', 	'tieinsta' )	=>		'2',
									__( 'Layout 3', 	'tieinsta' )	=>		'3',
								),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'full_name',
				'value'			=> array( __( 'Show the Full name', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'website',
				'value'			=> array( __( 'Show the Website URL', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'bio',
				'value'			=> array( __( 'Show the bio', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'stats',
				'value'			=> array( __( 'Show the account stats', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Avatar shape', 'tieinsta' ),
				'param_name' 	=> 'shape',
				'value' 		=> array(
									__( 'Square',	'tieinsta' )	=>		'square',
									__( 'Round', 	'tieinsta' )	=>		'round',
									__( 'Circle', 	'tieinsta' )	=>		'circle',
								),
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Avatar Width & Height', 'tieinsta' ),
				'param_name' 	=> 'size',
				'dependency' 	=> array(
									'element' 	=> 	'source',
									'value' 	=> 	array( 'user' )
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Number of Media items', 'tieinsta' ),
				'param_name' 	=> 'media'
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Link to', 'tieinsta' ),
				'param_name' 	=> 'link',
				'value' 		=> array(
									__( 'Media File',				'tieinsta' )	=>		'file',
									__( 'Media page on Instagram', 	'tieinsta' )	=>		'page',
									__( 'None', 					'tieinsta' )	=>		'none',
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Layout', 'tieinsta' ),
				'param_name' 	=> 'layout',
				'value' 		=> array(
									__( 'Grid',		'tieinsta' )	=>		'grid',
									__( 'Slider', 	'tieinsta' )	=>		'slider',
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Number of Columns', 'tieinsta' ),
				'param_name' 	=> 'columns',
				'value' 		=> array(
									'1'	=>		'1',
									'2'	=>		'2',
									'3'	=>		'3',
									'4'	=>		'4',
									'5'	=>		'5',
									'6'	=>		'6',
									'7'	=>		'7',
									'8'	=>		'8',
									'9'	=>		'9',
									'10'=>		'10',
								),
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'grid' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'lm',
				'heading' 		=> __( 'Enable Load More Button', 'tieinsta' ),
				'value'			=> array( __( 'Enable Load More Button', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'grid' )
								),
			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'flat',
				'heading' 		=> __( 'Flat Images', 'tieinsta' ),
				'value'			=> array( __( 'Flat Images (Without borders, margins and shadows)', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'grid' )
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Number of items to load each time', 'tieinsta' ),
				'param_name' 	=> 'lm_num',
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'grid' )
								),
			),
			array(
				'type' 			=> 'textfield',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Slider Speed', 'tieinsta' ),
				'param_name' 	=> 'speed',
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'slider' )
								),
			),
			array(
				'type' 			=> 'dropdown',
				'holder' 		=> 'div',
				'class' 		=> '',
				'heading' 		=> __( 'Animation Effect', 'tieinsta' ),
				'param_name' 	=> 'effect',
				'value' 		=> array(
									'blindX'		=>		'blindX',
									'blindY'		=>		'blindY',
									'blindZ'		=>		'blindZ',
									'cover'			=>		'cover',
									'curtainX'		=>		'curtainX',
									'curtainY'		=>		'curtainY',
									'fade'			=>		'fade',
									'fadeZoom'		=>		'fadeZoom',
									'growX'			=>		'growX',
									'growY'			=>		'growY',
									'scrollUp'		=>		'scrollUp',
									'scrollDown'	=>		'scrollDown',
									'scrollLeft'	=>		'scrollLeft',
									'scrollRight'	=>		'scrollRight',
									'scrollHorz'	=>		'scrollHorz',
									'scrollVert'	=>		'scrollVert',
									'slideX'		=>		'slideX',
									'slideY'		=>		'slideY',
									'toss'			=>		'toss',
									'turnUp'		=>		'turnUp',
									'turnDown'		=>		'turnDown',
									'turnLeft'		=>		'turnLeft',
									'turnRight'		=>		'turnRight',
									'uncover'		=>		'uncover',
									'wipe'			=>		'wipe',
									'zoom'			=>		'zoom',
								),
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'slider' )
								),

			),
			array(
				'type' 			=> 'checkbox',
				'holder' 		=> 'div',
				'class' 		=> '',
				'param_name' 	=> 'com_like',
				'heading' 		=> __( 'Show Media comments and likes number', 'tieinsta' ),
				'value'			=> array( __( 'Show Media comments and likes number', 'tieinsta' ) => 1 ),
				'dependency' 	=> array(
									'element' 	=> 	'layout',
									'value' 	=> 	array( 'slider' )
								),
			),
		)
	));
}

?>