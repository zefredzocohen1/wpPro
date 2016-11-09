<?php
/*
	Plugin Name: Taqyeem - Buttons Addon
	Plugin URI: http://codecanyon.net/item/taqyeem-buttons-addon/8780870?ref=tielabs
	Description: Add Amazingly Beautiful Buttons To Your Review Summary
	Author: TieLabs
	Version: 1.0.3
	Author URI: http://tielabs.com/
*/

/*-----------------------------------------------------------------------------------*/
# Load Text Domain
/*-----------------------------------------------------------------------------------*/
add_action('plugins_loaded', 'taqyeem_buttons_init');
function taqyeem_buttons_init() {
	load_plugin_textdomain( 'taq' , false, dirname( plugin_basename( __FILE__ ) ).'/languages' ); 
}

/*-----------------------------------------------------------------------------------*/
# Check if Taqyeem is installed and check it's Version
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_notices', 'taq_button_admin_notice' );
function taq_button_admin_notice() {
    ?>
	<?php
	if( !function_exists ( 'taqyeem_init' ) ){ ?>
	<div class="error">
        <p><strong><?php _e( '&quot;Taqyeem Predefined Criteria&quot; plugin is an extension for and requires  <a href="http://codecanyon.net/item/taqyeem-wordpress-review-plugin/4558799?ref=tielabs">Taqyeem Plugin</a>', 'taq' ); ?><strong></p>
	</div>
	<?php }
	if( function_exists ( 'taqyeem_init' ) && ( !defined( 'TIE_Plugin_ver' ) || version_compare( '2.1.0' , TIE_Plugin_ver , '>') ) ){ ?>
	<div class="error">
        <p><strong><?php _e( '&quot;Taqyeem Predefined Criteria&quot; plugin requires <a href="http://codecanyon.net/item/taqyeem-wordpress-review-plugin/4558799?ref=tielabs">Taqyeem Plugin</a> Version 2.1 or above.', 'taq' ); ?><strong></p>
	</div>
	<?php }
}

/*-----------------------------------------------------------------------------------*/
# Save Buttons data
/*-----------------------------------------------------------------------------------*/
add_filter( 'tie_taqyeem_save_review_fields' , 'tie_taqyeem_save_review_button_fields'  );
function tie_taqyeem_save_review_button_fields( $fields ){ 
	$custom_meta_fields = array(
		'taq_review_button_text',
		'taq_review_button_enable',
		'taq_review_button_size',
		'taq_review_button_shape',
		'taq_review_button_color',
		'taq_button_icon',
		'taq_review_button_type',
		'taq_review_button_target',
		'taq_review_button_nofollow',
		'taq_review_button_url');
		
		return $fields = array_merge($custom_meta_fields, $fields);
}


/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles in Dashboard
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_enqueue_scripts', 'taq_buttons_admin_register' );
function taq_buttons_admin_register() {

	wp_register_style ( 'taqyeem-admin-fontawesome', plugins_url('assets/fontawesome.css' , __FILE__), array(), '', 'all' ); 
	wp_register_style ( 'taqyeem-admin-buttons-style', plugins_url('assets/style.css' , __FILE__), array(), '', 'all' ); 
	wp_register_script( 'taqyeem-admin-buttons-js',  plugins_url('assets/tie-buttons.js' , __FILE__), array( 'jquery' ) , false , false );  
	
	wp_enqueue_script( 'taqyeem-admin-buttons-js' );
	wp_enqueue_style ( 'taqyeem-admin-buttons-style' );
	
	$load_fontawesome = apply_filters( 'taqyeem_buttons_force_avoid_fontawesome_admin', true );
		if( true === $load_fontawesome ) wp_enqueue_style ( 'taqyeem-admin-fontawesome' );

}

/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles in frontend
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'taq_buttons_frontend_register' );
function taq_buttons_frontend_register() {

	wp_register_style( 'taqyeem-fontawesome', plugins_url('assets/fontawesome.css' , __FILE__), array(), '' , 'all' ); 
	wp_register_style( 'taqyeem-buttons-style', plugins_url('assets/style.css' , __FILE__), array(), '' , 'all' ); 
	
	$load_fontawesome = apply_filters( 'taqyeem_buttons_force_avoid_fontawesome', true );
	if( function_exists( 'taqyeem_get_option' ) && !taqyeem_get_option('taq_button_disable_fontawesome') && true === $load_fontawesome ) wp_enqueue_style ( 'taqyeem-fontawesome' );
	
	wp_enqueue_style ( 'taqyeem-buttons-style' );

}


/*-----------------------------------------------------------------------------------*/
# Add Buttons tab to Taqyeem dashboard
/*-----------------------------------------------------------------------------------*/
add_action( 'tie_taqyeem_panel_tabs' , 'tie_taqyeem_buttons_panel_tab' , 2  );
function tie_taqyeem_buttons_panel_tab(){ ?>
	<li class="tie-tabs pre-defined_criteria"><a href="#taq-button-tab"><span class="taq-icon-menu dashicons-before dashicons-screenoptions taq-icon-menu"></span><?php _e('Buttons Settings','taq'); ?></a></li>
<?php
}


/*-----------------------------------------------------------------------------------*/
# Buttons tab Content
/*-----------------------------------------------------------------------------------*/
add_action( 'tie_taqyeem_panel_tabs_content' , 'tie_taqyeem_buttons_panel_tab_content' , 2  );
function tie_taqyeem_buttons_panel_tab_content(){ ?>
<div id="taq-button-tab" class="tab_content taq-tabs-wrap">
	<h2><?php _e('Buttons Settings','taq'); ?></h2> <?php taq_save_button(); ?>		
	<div class="taqyeem-item">
		<h3><?php _e( "FontAwesome Icons" ,"taq"); ?></h3>
		<?php
			taqyeem_option(
				array(	"name" => __( "Don't include the FontAwesome icon set files" ,"taq" ),
						"id" => "taq_button_disable_fontawesome",
						"type" => "checkbox"));
			?>
		<p class="taqyeem_message_hint"><?php _e("Enable this option if your theme already includes it.","taq"); ?></p>
	</div>
</div> <!-- Buttons Settings -->
<?php
}

/*-----------------------------------------------------------------------------------*/
# List of All predefined buttons items
/*-----------------------------------------------------------------------------------*/
function taqyeem_predefined_buttons(){
	
	$predefined_buttons = array(
		array(
			'text'  => 	__( 'Download' , 'taq' ),
			'color' => 	'a0ce4e',
			'icon'  => 'fa-download'
		),
		array(
			'text'  => 	__( 'Learn More' , 'taq' ),
			'color' => 	'5da7ce',
			'icon'  => 'fa-plus'
		),
		array(
			'text'  => 	__( 'Warning' , 'taq' ),
			'color' => 	'FFC200',
			'icon'  => 'fa-warning'
		),
		array(
			'text'  => 	__( 'Like' , 'taq' ),
			'color' => 	'3b5998',
			'icon'  => 'fa-thumbs-up'
		),
		array(
			'text'  => 	__( 'Follow' , 'taq' ),
			'color' => 	'55acee',
			'icon'  => 'fa-twitter'
		),
		array(
			'text'  => 	__( 'Danger' , 'taq' ),
			'color' => 	'D32123',
			'icon'  => 'fa-ban'
		),
		array(
			'text'  => 	__( 'Contact' , 'taq' ),
			'color' => 	'333333',
			'icon'  => 'fa-envelope'
		),
		array(
			'text'  => 	__( 'Shop Now' , 'taq' ),
			'color' => 	'1abc9c',
			'icon'  => 'fa-shopping-cart'
		),
		array(
			'text'  => 	__( 'View Now' , 'taq' ),
			'color' => 	'9b59b6',
			'icon'  => 'fa-external-link'
		),
	);
	
	if( has_filter('tie_taqyeem_buttons_predefined') ) {
		$predefined_buttons = apply_filters('tie_taqyeem_buttons_predefined', $predefined_buttons );
	}
	
	return $predefined_buttons;
}

/*-----------------------------------------------------------------------------------*/
# Add Button to the review box
/*-----------------------------------------------------------------------------------*/
add_filter( 'tie_taqyeem_after_summary_text' , 'tie_taqyeem_button_output'  );
function tie_taqyeem_button_output( $output ){
	global $post;
	$get_meta = get_post_custom($post->ID);
	$button_enable = $button_text = $button_icon = $button_size = $button_shape = $button_type = $button_url = $button_target = $button_nofollow = '';
	
	if( !empty( $get_meta['taq_review_button_enable'][0] ) )
		$button_enable = $get_meta['taq_review_button_enable'][0];	
	
	if( !empty( $button_enable ) ){
		
		if( !empty( $get_meta['taq_review_button_text'][0] ) )
			$button_text = $get_meta['taq_review_button_text'][0];
			
		if( !empty( $get_meta['taq_button_icon'][0] ) )
			$button_icon = $get_meta['taq_button_icon'][0];
			
		if( !empty( $get_meta['taq_review_button_color'][0] ) )
			$button_color = $get_meta['taq_review_button_color'][0];
					
		if( !empty( $get_meta['taq_review_button_size'][0] ) )
			$button_size = $get_meta['taq_review_button_size'][0];
			
		if( !empty( $get_meta['taq_review_button_shape'][0] ) )
			$button_shape = $get_meta['taq_review_button_shape'][0];
			
		if( !empty( $get_meta['taq_review_button_type'][0] ) )
			$button_type = $get_meta['taq_review_button_type'][0];

		if( !empty( $get_meta['taq_review_button_url'][0] ) )
			$button_url = $get_meta['taq_review_button_url'][0];
			
		if( !empty( $get_meta['taq_review_button_target'][0] ) )
			$button_target = ' target="_blank"';
				
		if( !empty( $get_meta['taq_review_button_nofollow'][0] ) )
			$button_nofollow = ' rel="nofollow"';
		
		$button_class = ' taq-'.$button_size.' taq-'.$button_shape.' taq-'.$button_type;
		
		if( $button_icon == 'fa none') $button_class .= ' without-icon';

		$output .= '<a href="'.esc_url( $button_url ).'" class="taq-button'.$button_class.'" style="background-color:'.$button_color.'"'.$button_target.$button_nofollow.'>';
		
		if( $button_icon != 'fa none')
			$output .= '<i class="'.$button_icon.'"></i>';
			
		$output .= '<span class="button-text">'.$button_text.'</span></a>';
		
	}
	return $output;
}
 
 
/*-----------------------------------------------------------------------------------*/
# Add Button options
/*-----------------------------------------------------------------------------------*/
add_action( 'tie_taqyeem_after_review_options' , 'taqyeem_buttons_addon_options' , 2  );
function taqyeem_buttons_addon_options(){
	global $post;
	
	$get_meta = get_post_custom($post->ID);
	if( !empty( $get_meta['taq_review_button_text'][0] ) )
		$button_text = $get_meta['taq_review_button_text'][0];
	else
		$button_text =  __( 'Button Preview' , 'taq' );

	if( !empty( $get_meta['taq_button_icon'][0] ) )
		$button_icon = $get_meta['taq_button_icon'][0];
	else
		$button_icon = 'fa fa-check';
		
	if( !empty( $get_meta['taq_review_button_color'][0] ) )
		$button_color = $get_meta['taq_review_button_color'][0];
	else
		$button_color = '#c7c7c7';
		
		
		
	taqyeem_options_items(				
		array(	"name" => __('Add Button?','taq'),
				"id" => "taq_review_button_enable",
				"type" => "checkbox"));		
?>
<div id="taq-button-wrapper">

	<div id="taq-button-preview">
		<a href="#" class="taq-button taq-gradiant" style="background-Color:<?php echo $button_color ?>;"><i class="<?php echo $button_icon ?>"></i><span class="button-text"><?php echo $button_text ?></span></a>
	</div>
	
	<div class="taqyeem-option-item" id="taq-predefined-buttons-item">
		<span class="label"><?php _e('Predefined Buttons','taq') ?></span>
		
		<ul id="taq-button-colour">
			<?php
			$predefined_buttons = taqyeem_predefined_buttons();
			foreach( $predefined_buttons as $predefined_button ){ ?>
			<li>
				<a href="#" data-text="<?php echo $predefined_button['text'] ?>" data-color="<?php echo $predefined_button['color'] ?>" data-icon="<?php echo $predefined_button['icon'] ?>"><span style="background:#<?php echo $predefined_button['color'] ?>;"><?php echo $predefined_button['text'] ?></span></a>
			</li>
			<?php
			}
			?>	
		</ul>
	</div>	
	
<?php								
	taqyeem_options_items(				
		array(	"name" => __('Button text','taq'),
				"id" => "taq_review_button_text",
				"type" => "text"));
				
	taqyeem_options_items(				
		array(	"name" => __('Button URL','taq'),
				"id" => "taq_review_button_url",
				"type" => "text"));	
				
	taqyeem_options_items(				
		array(	"name" => __('Open links in a new window ?','taq'),
				"id" => "taq_review_button_target",
				"type" => "checkbox"));
				
	taqyeem_options_items(				
		array(	"name" => __('Nofollow?','taq'),
				"id" => "taq_review_button_nofollow",
				"type" => "checkbox"));
				
	taqyeem_options_items(				
		array(	"name" => __('Button Size','taq'),
				"id" => "taq_review_button_size",
				"type" => "radio",
				"options" => array(  "medium" => __('Medium','taq'), "small" => __('Small','taq'), "large" => __('Large','taq'))));
								
	taqyeem_options_items(				
		array(	"name" => __('Button Shape','taq'),
				"id" => "taq_review_button_shape",
				"type" => "radio",
				"options" => array(  "square" => __('Square','taq'), "round" => __('Round','taq'), "pill" => __('Pill','taq'))));
									
	taqyeem_options_items(				
		array(	"name" => __('Button Type','taq'),
				"id" => "taq_review_button_type",
				"type" => "radio",
				"options" => array(  "flat" => __('Flat','taq'), "gradient" => __('Gradient','taq'))));
?>

	<div class="taqyeem-option-item" id="taq_review_button_color-item">
		<span class="label"><?php  _e('Button Color','taq') ?></span>
			<div id="taq_review_button_colorcolorSelector" class="color-pic"><div style="background-Color:<?php echo $button_color ?>;"></div></div>
			<input style="width: 80px;" name="taq_review_button_color" id="taq_review_button_color" type="text" value="<?php echo $button_color ?>">
		
			<script>
				jQuery('#taq_review_button_colorcolorSelector').ColorPicker({
					color: '#<?php echo $button_color ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#taq-button-preview .taq-button').css('backgroundColor', '#' + hex);
						jQuery('#taq_review_button_colorcolorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#taq_review_button_color').val('#'+hex);
					}
				});
			</script>
	</div>
	
	<div class="taqyeem-option-item" id="taq_review_button_icon-item">
		<input type="hidden" name="taq_button_icon" id="taq_button_icon" value="<?php echo $button_icon ?>">
		<span class="label"><?php  _e('Button Icon','taq') ?></span>
		<div class="clear"></div>
		<ul id="taq-button-icons">
			<li><a id="none-icon" href="#none"><i class="fa none"></i></a></li>
			<li><a href="#angellist"><i class="fa fa-angellist"></i> fa-angellist</a></li>
			<li><a href="#area-chart"><i class="fa fa-area-chart"></i> fa-area-chart</a></li>
			<li><a href="#at"><i class="fa fa-at"></i> fa-at</a></li>
			<li><a href="#bell-slash"><i class="fa fa-bell-slash"></i> fa-bell-slash</a></li>
			<li><a href="#bell-slash-o"><i class="fa fa-bell-slash-o"></i> fa-bell-slash-o</a></li>
			<li><a href="#bicycle"><i class="fa fa-bicycle"></i> fa-bicycle</a></li>
			<li><a href="#binoculars"><i class="fa fa-binoculars"></i> fa-binoculars</a></li>
			<li><a href="#birthday-cake"><i class="fa fa-birthday-cake"></i> fa-birthday-cake</a></li>
			<li><a href="#bus"><i class="fa fa-bus"></i> fa-bus</a></li>
			<li><a href="#calculator"><i class="fa fa-calculator"></i> fa-calculator</a></li>
			<li><a href="#cc"><i class="fa fa-cc"></i> fa-cc</a></li>
			<li><a href="#cc-amex"><i class="fa fa-cc-amex"></i> fa-cc-amex</a></li>
			<li><a href="#cc-discover"><i class="fa fa-cc-discover"></i> fa-cc-discover</a></li>
			<li><a href="#cc-mastercard"><i class="fa fa-cc-mastercard"></i> fa-cc-mastercard</a></li>
			<li><a href="#cc-paypal"><i class="fa fa-cc-paypal"></i> fa-cc-paypal</a></li>
			<li><a href="#cc-stripe"><i class="fa fa-cc-stripe"></i> fa-cc-stripe</a></li>
			<li><a href="#cc-visa"><i class="fa fa-cc-visa"></i> fa-cc-visa</a></li>
			<li><a href="#copyright"><i class="fa fa-copyright"></i> fa-copyright</a></li>
			<li><a href="#eyedropper"><i class="fa fa-eyedropper"></i> fa-eyedropper</a></li>
			<li><a href="#futbol-o"><i class="fa fa-futbol-o"></i> fa-futbol-o</a></li>
			<li><a href="#google-wallet"><i class="fa fa-google-wallet"></i> fa-google-wallet</a></li>
			<li><a href="#ils"><i class="fa fa-ils"></i> fa-ils</a></li>
			<li><a href="#ioxhost"><i class="fa fa-ioxhost"></i> fa-ioxhost</a></li>
			<li><a href="#lastfm"><i class="fa fa-lastfm"></i> fa-lastfm</a></li>
			<li><a href="#lastfm-square"><i class="fa fa-lastfm-square"></i> fa-lastfm-square</a></li>
			<li><a href="#line-chart"><i class="fa fa-line-chart"></i> fa-line-chart</a></li>
			<li><a href="#meanpath"><i class="fa fa-meanpath"></i> fa-meanpath</a></li>
			<li><a href="#newspaper-o"><i class="fa fa-newspaper-o"></i> fa-newspaper-o</a></li>
			<li><a href="#paint-brush"><i class="fa fa-paint-brush"></i> fa-paint-brush</a></li>
			<li><a href="#paypal"><i class="fa fa-paypal"></i> fa-paypal</a></li>
			<li><a href="#pie-chart"><i class="fa fa-pie-chart"></i> fa-pie-chart</a></li>
			<li><a href="#plug"><i class="fa fa-plug"></i> fa-plug</a></li>
			<li><a href="#ils"><i class="fa fa-shekel"></i> fa-shekel <span class="text-muted">(alias)</span></a></li>
			<li><a href="#ils"><i class="fa fa-sheqel"></i> fa-sheqel <span class="text-muted">(alias)</span></a></li>
			<li><a href="#slideshare"><i class="fa fa-slideshare"></i> fa-slideshare</a></li>
			<li><a href="#futbol-o"><i class="fa fa-soccer-ball-o"></i> fa-soccer-ball-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#toggle-off"><i class="fa fa-toggle-off"></i> fa-toggle-off</a></li>
			<li><a href="#toggle-on"><i class="fa fa-toggle-on"></i> fa-toggle-on</a></li>
			<li><a href="#trash"><i class="fa fa-trash"></i> fa-trash</a></li>
			<li><a href="#tty"><i class="fa fa-tty"></i> fa-tty</a></li>
			<li><a href="#twitch"><i class="fa fa-twitch"></i> fa-twitch</a></li>
			<li><a href="#wifi"><i class="fa fa-wifi"></i> fa-wifi</a></li>
			<li><a href="#yelp"><i class="fa fa-yelp"></i> fa-yelp</a></li>
			<li><a href="#adjust"><i class="fa fa-adjust"></i> fa-adjust</a></li>
			<li><a href="#anchor"><i class="fa fa-anchor"></i> fa-anchor</a></li>
			<li><a href="#archive"><i class="fa fa-archive"></i> fa-archive</a></li>
			<li><a href="#area-chart"><i class="fa fa-area-chart"></i> fa-area-chart</a></li>
			<li><a href="#arrows"><i class="fa fa-arrows"></i> fa-arrows</a></li>
			<li><a href="#arrows-h"><i class="fa fa-arrows-h"></i> fa-arrows-h</a></li>
			<li><a href="#arrows-v"><i class="fa fa-arrows-v"></i> fa-arrows-v</a></li>
			<li><a href="#asterisk"><i class="fa fa-asterisk"></i> fa-asterisk</a></li>
			<li><a href="#at"><i class="fa fa-at"></i> fa-at</a></li>
			<li><a href="#car"><i class="fa fa-automobile"></i> fa-automobile <span class="text-muted">(alias)</span></a></li>
			<li><a href="#ban"><i class="fa fa-ban"></i> fa-ban</a></li>
			<li><a href="#university"><i class="fa fa-bank"></i> fa-bank <span class="text-muted">(alias)</span></a></li>
			<li><a href="#bar-chart"><i class="fa fa-bar-chart"></i> fa-bar-chart</a></li>
			<li><a href="#bar-chart"><i class="fa fa-bar-chart-o"></i> fa-bar-chart-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#barcode"><i class="fa fa-barcode"></i> fa-barcode</a></li>
			<li><a href="#bars"><i class="fa fa-bars"></i> fa-bars</a></li>
			<li><a href="#beer"><i class="fa fa-beer"></i> fa-beer</a></li>
			<li><a href="#bell"><i class="fa fa-bell"></i> fa-bell</a></li>
			<li><a href="#bell-o"><i class="fa fa-bell-o"></i> fa-bell-o</a></li>
			<li><a href="#bell-slash"><i class="fa fa-bell-slash"></i> fa-bell-slash</a></li>
			<li><a href="#bell-slash-o"><i class="fa fa-bell-slash-o"></i> fa-bell-slash-o</a></li>
			<li><a href="#bicycle"><i class="fa fa-bicycle"></i> fa-bicycle</a></li>
			<li><a href="#binoculars"><i class="fa fa-binoculars"></i> fa-binoculars</a></li>
			<li><a href="#birthday-cake"><i class="fa fa-birthday-cake"></i> fa-birthday-cake</a></li>
			<li><a href="#bolt"><i class="fa fa-bolt"></i> fa-bolt</a></li>
			<li><a href="#bomb"><i class="fa fa-bomb"></i> fa-bomb</a></li>
			<li><a href="#book"><i class="fa fa-book"></i> fa-book</a></li>
			<li><a href="#bookmark"><i class="fa fa-bookmark"></i> fa-bookmark</a></li>
			<li><a href="#bookmark-o"><i class="fa fa-bookmark-o"></i> fa-bookmark-o</a></li>
			<li><a href="#briefcase"><i class="fa fa-briefcase"></i> fa-briefcase</a></li>
			<li><a href="#bug"><i class="fa fa-bug"></i> fa-bug</a></li>
			<li><a href="#building"><i class="fa fa-building"></i> fa-building</a></li>
			<li><a href="#building-o"><i class="fa fa-building-o"></i> fa-building-o</a></li>
			<li><a href="#bullhorn"><i class="fa fa-bullhorn"></i> fa-bullhorn</a></li>
			<li><a href="#bullseye"><i class="fa fa-bullseye"></i> fa-bullseye</a></li>
			<li><a href="#bus"><i class="fa fa-bus"></i> fa-bus</a></li>
			<li><a href="#taxi"><i class="fa fa-cab"></i> fa-cab <span class="text-muted">(alias)</span></a></li>
			<li><a href="#calculator"><i class="fa fa-calculator"></i> fa-calculator</a></li>
			<li><a href="#calendar"><i class="fa fa-calendar"></i> fa-calendar</a></li>
			<li><a href="#calendar-o"><i class="fa fa-calendar-o"></i> fa-calendar-o</a></li>
			<li><a href="#camera"><i class="fa fa-camera"></i> fa-camera</a></li>
			<li><a href="#camera-retro"><i class="fa fa-camera-retro"></i> fa-camera-retro</a></li>
			<li><a href="#car"><i class="fa fa-car"></i> fa-car</a></li>
			<li><a href="#caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down</a></li>
			<li><a href="#caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left</a></li>
			<li><a href="#caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right</a></li>
			<li><a href="#caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up</a></li>
			<li><a href="#cc"><i class="fa fa-cc"></i> fa-cc</a></li>
			<li><a href="#certificate"><i class="fa fa-certificate"></i> fa-certificate</a></li>
			<li><a href="#check"><i class="fa fa-check"></i> fa-check</a></li>
			<li><a href="#check-circle"><i class="fa fa-check-circle"></i> fa-check-circle</a></li>
			<li><a href="#check-circle-o"><i class="fa fa-check-circle-o"></i> fa-check-circle-o</a></li>
			<li><a href="#check-square"><i class="fa fa-check-square"></i> fa-check-square</a></li>
			<li><a href="#check-square-o"><i class="fa fa-check-square-o"></i> fa-check-square-o</a></li>
			<li><a href="#child"><i class="fa fa-child"></i> fa-child</a></li>
			<li><a href="#circle"><i class="fa fa-circle"></i> fa-circle</a></li>
			<li><a href="#circle-o"><i class="fa fa-circle-o"></i> fa-circle-o</a></li>
			<li><a href="#circle-o-notch"><i class="fa fa-circle-o-notch"></i> fa-circle-o-notch</a></li>
			<li><a href="#circle-thin"><i class="fa fa-circle-thin"></i> fa-circle-thin</a></li>
			<li><a href="#clock-o"><i class="fa fa-clock-o"></i> fa-clock-o</a></li>
			<li><a href="#times"><i class="fa fa-close"></i> fa-close <span class="text-muted">(alias)</span></a></li>
			<li><a href="#cloud"><i class="fa fa-cloud"></i> fa-cloud</a></li>
			<li><a href="#cloud-download"><i class="fa fa-cloud-download"></i> fa-cloud-download</a></li>
			<li><a href="#cloud-upload"><i class="fa fa-cloud-upload"></i> fa-cloud-upload</a></li>
			<li><a href="#code"><i class="fa fa-code"></i> fa-code</a></li>
			<li><a href="#code-fork"><i class="fa fa-code-fork"></i> fa-code-fork</a></li>
			<li><a href="#coffee"><i class="fa fa-coffee"></i> fa-coffee</a></li>
			<li><a href="#cog"><i class="fa fa-cog"></i> fa-cog</a></li>
			<li><a href="#cogs"><i class="fa fa-cogs"></i> fa-cogs</a></li>
			<li><a href="#comment"><i class="fa fa-comment"></i> fa-comment</a></li>
			<li><a href="#comment-o"><i class="fa fa-comment-o"></i> fa-comment-o</a></li>
			<li><a href="#comments"><i class="fa fa-comments"></i> fa-comments</a></li>
			<li><a href="#comments-o"><i class="fa fa-comments-o"></i> fa-comments-o</a></li>
			<li><a href="#compass"><i class="fa fa-compass"></i> fa-compass</a></li>
			<li><a href="#copyright"><i class="fa fa-copyright"></i> fa-copyright</a></li>
			<li><a href="#credit-card"><i class="fa fa-credit-card"></i> fa-credit-card</a></li>
			<li><a href="#crop"><i class="fa fa-crop"></i> fa-crop</a></li>
			<li><a href="#crosshairs"><i class="fa fa-crosshairs"></i> fa-crosshairs</a></li>
			<li><a href="#cube"><i class="fa fa-cube"></i> fa-cube</a></li>
			<li><a href="#cubes"><i class="fa fa-cubes"></i> fa-cubes</a></li>
			<li><a href="#cutlery"><i class="fa fa-cutlery"></i> fa-cutlery</a></li>
			<li><a href="#tachometer"><i class="fa fa-dashboard"></i> fa-dashboard <span class="text-muted">(alias)</span></a></li>
			<li><a href="#database"><i class="fa fa-database"></i> fa-database</a></li>
			<li><a href="#desktop"><i class="fa fa-desktop"></i> fa-desktop</a></li>
			<li><a href="#dot-circle-o"><i class="fa fa-dot-circle-o"></i> fa-dot-circle-o</a></li>
			<li><a href="#download"><i class="fa fa-download"></i> fa-download</a></li>
			<li><a href="#pencil-square-o"><i class="fa fa-edit"></i> fa-edit <span class="text-muted">(alias)</span></a></li>
			<li><a href="#ellipsis-h"><i class="fa fa-ellipsis-h"></i> fa-ellipsis-h</a></li>
			<li><a href="#ellipsis-v"><i class="fa fa-ellipsis-v"></i> fa-ellipsis-v</a></li>
			<li><a href="#envelope"><i class="fa fa-envelope"></i> fa-envelope</a></li>
			<li><a href="#envelope-o"><i class="fa fa-envelope-o"></i> fa-envelope-o</a></li>
			<li><a href="#envelope-square"><i class="fa fa-envelope-square"></i> fa-envelope-square</a></li>
			<li><a href="#eraser"><i class="fa fa-eraser"></i> fa-eraser</a></li>
			<li><a href="#exchange"><i class="fa fa-exchange"></i> fa-exchange</a></li>
			<li><a href="#exclamation"><i class="fa fa-exclamation"></i> fa-exclamation</a></li>
			<li><a href="#exclamation-circle"><i class="fa fa-exclamation-circle"></i> fa-exclamation-circle</a></li>
			<li><a href="#exclamation-triangle"><i class="fa fa-exclamation-triangle"></i> fa-exclamation-triangle</a></li>
			<li><a href="#external-link"><i class="fa fa-external-link"></i> fa-external-link</a></li>
			<li><a href="#external-link-square"><i class="fa fa-external-link-square"></i> fa-external-link-square</a></li>
			<li><a href="#eye"><i class="fa fa-eye"></i> fa-eye</a></li>
			<li><a href="#eye-slash"><i class="fa fa-eye-slash"></i> fa-eye-slash</a></li>
			<li><a href="#eyedropper"><i class="fa fa-eyedropper"></i> fa-eyedropper</a></li>
			<li><a href="#fax"><i class="fa fa-fax"></i> fa-fax</a></li>
			<li><a href="#female"><i class="fa fa-female"></i> fa-female</a></li>
			<li><a href="#fighter-jet"><i class="fa fa-fighter-jet"></i> fa-fighter-jet</a></li>
			<li><a href="#file-archive-o"><i class="fa fa-file-archive-o"></i> fa-file-archive-o</a></li>
			<li><a href="#file-audio-o"><i class="fa fa-file-audio-o"></i> fa-file-audio-o</a></li>
			<li><a href="#file-code-o"><i class="fa fa-file-code-o"></i> fa-file-code-o</a></li>
			<li><a href="#file-excel-o"><i class="fa fa-file-excel-o"></i> fa-file-excel-o</a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-image-o"></i> fa-file-image-o</a></li>
			<li><a href="#file-video-o"><i class="fa fa-file-movie-o"></i> fa-file-movie-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-pdf-o"><i class="fa fa-file-pdf-o"></i> fa-file-pdf-o</a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-photo-o"></i> fa-file-photo-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-picture-o"></i> fa-file-picture-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> fa-file-powerpoint-o</a></li>
			<li><a href="#file-audio-o"><i class="fa fa-file-sound-o"></i> fa-file-sound-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-video-o"><i class="fa fa-file-video-o"></i> fa-file-video-o</a></li>
			<li><a href="#file-word-o"><i class="fa fa-file-word-o"></i> fa-file-word-o</a></li>
			<li><a href="#file-archive-o"><i class="fa fa-file-zip-o"></i> fa-file-zip-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#film"><i class="fa fa-film"></i> fa-film</a></li>
			<li><a href="#filter"><i class="fa fa-filter"></i> fa-filter</a></li>
			<li><a href="#fire"><i class="fa fa-fire"></i> fa-fire</a></li>
			<li><a href="#fire-extinguisher"><i class="fa fa-fire-extinguisher"></i> fa-fire-extinguisher</a></li>
			<li><a href="#flag"><i class="fa fa-flag"></i> fa-flag</a></li>
			<li><a href="#flag-checkered"><i class="fa fa-flag-checkered"></i> fa-flag-checkered</a></li>
			<li><a href="#flag-o"><i class="fa fa-flag-o"></i> fa-flag-o</a></li>
			<li><a href="#bolt"><i class="fa fa-flash"></i> fa-flash <span class="text-muted">(alias)</span></a></li>
			<li><a href="#flask"><i class="fa fa-flask"></i> fa-flask</a></li>
			<li><a href="#folder"><i class="fa fa-folder"></i> fa-folder</a></li>
			<li><a href="#folder-o"><i class="fa fa-folder-o"></i> fa-folder-o</a></li>
			<li><a href="#folder-open"><i class="fa fa-folder-open"></i> fa-folder-open</a></li>
			<li><a href="#folder-open-o"><i class="fa fa-folder-open-o"></i> fa-folder-open-o</a></li>
			<li><a href="#frown-o"><i class="fa fa-frown-o"></i> fa-frown-o</a></li>
			<li><a href="#futbol-o"><i class="fa fa-futbol-o"></i> fa-futbol-o</a></li>
			<li><a href="#gamepad"><i class="fa fa-gamepad"></i> fa-gamepad</a></li>
			<li><a href="#gavel"><i class="fa fa-gavel"></i> fa-gavel</a></li>
			<li><a href="#cog"><i class="fa fa-gear"></i> fa-gear <span class="text-muted">(alias)</span></a></li>
			<li><a href="#cogs"><i class="fa fa-gears"></i> fa-gears <span class="text-muted">(alias)</span></a></li>
			<li><a href="#gift"><i class="fa fa-gift"></i> fa-gift</a></li>
			<li><a href="#glass"><i class="fa fa-glass"></i> fa-glass</a></li>
			<li><a href="#globe"><i class="fa fa-globe"></i> fa-globe</a></li>
			<li><a href="#graduation-cap"><i class="fa fa-graduation-cap"></i> fa-graduation-cap</a></li>
			<li><a href="#users"><i class="fa fa-group"></i> fa-group <span class="text-muted">(alias)</span></a></li>
			<li><a href="#hdd-o"><i class="fa fa-hdd-o"></i> fa-hdd-o</a></li>
			<li><a href="#headphones"><i class="fa fa-headphones"></i> fa-headphones</a></li>
			<li><a href="#heart"><i class="fa fa-heart"></i> fa-heart</a></li>
			<li><a href="#heart-o"><i class="fa fa-heart-o"></i> fa-heart-o</a></li>
			<li><a href="#history"><i class="fa fa-history"></i> fa-history</a></li>
			<li><a href="#home"><i class="fa fa-home"></i> fa-home</a></li>
			<li><a href="#picture-o"><i class="fa fa-image"></i> fa-image <span class="text-muted">(alias)</span></a></li>
			<li><a href="#inbox"><i class="fa fa-inbox"></i> fa-inbox</a></li>
			<li><a href="#info"><i class="fa fa-info"></i> fa-info</a></li>
			<li><a href="#info-circle"><i class="fa fa-info-circle"></i> fa-info-circle</a></li>
			<li><a href="#university"><i class="fa fa-institution"></i> fa-institution <span class="text-muted">(alias)</span></a></li>
			<li><a href="#key"><i class="fa fa-key"></i> fa-key</a></li>
			<li><a href="#keyboard-o"><i class="fa fa-keyboard-o"></i> fa-keyboard-o</a></li>
			<li><a href="#language"><i class="fa fa-language"></i> fa-language</a></li>
			<li><a href="#laptop"><i class="fa fa-laptop"></i> fa-laptop</a></li>
			<li><a href="#leaf"><i class="fa fa-leaf"></i> fa-leaf</a></li>
			<li><a href="#gavel"><i class="fa fa-legal"></i> fa-legal <span class="text-muted">(alias)</span></a></li>
			<li><a href="#lemon-o"><i class="fa fa-lemon-o"></i> fa-lemon-o</a></li>
			<li><a href="#level-down"><i class="fa fa-level-down"></i> fa-level-down</a></li>
			<li><a href="#level-up"><i class="fa fa-level-up"></i> fa-level-up</a></li>
			<li><a href="#life-ring"><i class="fa fa-life-bouy"></i> fa-life-bouy <span class="text-muted">(alias)</span></a></li>
			<li><a href="#life-ring"><i class="fa fa-life-buoy"></i> fa-life-buoy <span class="text-muted">(alias)</span></a></li>
			<li><a href="#life-ring"><i class="fa fa-life-ring"></i> fa-life-ring</a></li>
			<li><a href="#life-ring"><i class="fa fa-life-saver"></i> fa-life-saver <span class="text-muted">(alias)</span></a></li>
			<li><a href="#lightbulb-o"><i class="fa fa-lightbulb-o"></i> fa-lightbulb-o</a></li>
			<li><a href="#line-chart"><i class="fa fa-line-chart"></i> fa-line-chart</a></li>
			<li><a href="#location-arrow"><i class="fa fa-location-arrow"></i> fa-location-arrow</a></li>
			<li><a href="#lock"><i class="fa fa-lock"></i> fa-lock</a></li>
			<li><a href="#magic"><i class="fa fa-magic"></i> fa-magic</a></li>
			<li><a href="#magnet"><i class="fa fa-magnet"></i> fa-magnet</a></li>
			<li><a href="#share"><i class="fa fa-mail-forward"></i> fa-mail-forward <span class="text-muted">(alias)</span></a></li>
			<li><a href="#reply"><i class="fa fa-mail-reply"></i> fa-mail-reply <span class="text-muted">(alias)</span></a></li>
			<li><a href="#reply-all"><i class="fa fa-mail-reply-all"></i> fa-mail-reply-all <span class="text-muted">(alias)</span></a></li>
			<li><a href="#male"><i class="fa fa-male"></i> fa-male</a></li>
			<li><a href="#map-marker"><i class="fa fa-map-marker"></i> fa-map-marker</a></li>
			<li><a href="#meh-o"><i class="fa fa-meh-o"></i> fa-meh-o</a></li>
			<li><a href="#microphone"><i class="fa fa-microphone"></i> fa-microphone</a></li>
			<li><a href="#microphone-slash"><i class="fa fa-microphone-slash"></i> fa-microphone-slash</a></li>
			<li><a href="#minus"><i class="fa fa-minus"></i> fa-minus</a></li>
			<li><a href="#minus-circle"><i class="fa fa-minus-circle"></i> fa-minus-circle</a></li>
			<li><a href="#minus-square"><i class="fa fa-minus-square"></i> fa-minus-square</a></li>
			<li><a href="#minus-square-o"><i class="fa fa-minus-square-o"></i> fa-minus-square-o</a></li>
			<li><a href="#mobile"><i class="fa fa-mobile"></i> fa-mobile</a></li>
			<li><a href="#mobile"><i class="fa fa-mobile-phone"></i> fa-mobile-phone <span class="text-muted">(alias)</span></a></li>
			<li><a href="#money"><i class="fa fa-money"></i> fa-money</a></li>
			<li><a href="#moon-o"><i class="fa fa-moon-o"></i> fa-moon-o</a></li>
			<li><a href="#graduation-cap"><i class="fa fa-mortar-board"></i> fa-mortar-board <span class="text-muted">(alias)</span></a></li>
			<li><a href="#music"><i class="fa fa-music"></i> fa-music</a></li>
			<li><a href="#bars"><i class="fa fa-navicon"></i> fa-navicon <span class="text-muted">(alias)</span></a></li>
			<li><a href="#newspaper-o"><i class="fa fa-newspaper-o"></i> fa-newspaper-o</a></li>
			<li><a href="#paint-brush"><i class="fa fa-paint-brush"></i> fa-paint-brush</a></li>
			<li><a href="#paper-plane"><i class="fa fa-paper-plane"></i> fa-paper-plane</a></li>
			<li><a href="#paper-plane-o"><i class="fa fa-paper-plane-o"></i> fa-paper-plane-o</a></li>
			<li><a href="#paw"><i class="fa fa-paw"></i> fa-paw</a></li>
			<li><a href="#pencil"><i class="fa fa-pencil"></i> fa-pencil</a></li>
			<li><a href="#pencil-square"><i class="fa fa-pencil-square"></i> fa-pencil-square</a></li>
			<li><a href="#pencil-square-o"><i class="fa fa-pencil-square-o"></i> fa-pencil-square-o</a></li>
			<li><a href="#phone"><i class="fa fa-phone"></i> fa-phone</a></li>
			<li><a href="#phone-square"><i class="fa fa-phone-square"></i> fa-phone-square</a></li>
			<li><a href="#picture-o"><i class="fa fa-photo"></i> fa-photo <span class="text-muted">(alias)</span></a></li>
			<li><a href="#picture-o"><i class="fa fa-picture-o"></i> fa-picture-o</a></li>
			<li><a href="#pie-chart"><i class="fa fa-pie-chart"></i> fa-pie-chart</a></li>
			<li><a href="#plane"><i class="fa fa-plane"></i> fa-plane</a></li>
			<li><a href="#plug"><i class="fa fa-plug"></i> fa-plug</a></li>
			<li><a href="#plus"><i class="fa fa-plus"></i> fa-plus</a></li>
			<li><a href="#plus-circle"><i class="fa fa-plus-circle"></i> fa-plus-circle</a></li>
			<li><a href="#plus-square"><i class="fa fa-plus-square"></i> fa-plus-square</a></li>
			<li><a href="#plus-square-o"><i class="fa fa-plus-square-o"></i> fa-plus-square-o</a></li>
			<li><a href="#power-off"><i class="fa fa-power-off"></i> fa-power-off</a></li>
			<li><a href="#print"><i class="fa fa-print"></i> fa-print</a></li>
			<li><a href="#puzzle-piece"><i class="fa fa-puzzle-piece"></i> fa-puzzle-piece</a></li>
			<li><a href="#qrcode"><i class="fa fa-qrcode"></i> fa-qrcode</a></li>
			<li><a href="#question"><i class="fa fa-question"></i> fa-question</a></li>
			<li><a href="#question-circle"><i class="fa fa-question-circle"></i> fa-question-circle</a></li>
			<li><a href="#quote-left"><i class="fa fa-quote-left"></i> fa-quote-left</a></li>
			<li><a href="#quote-right"><i class="fa fa-quote-right"></i> fa-quote-right</a></li>
			<li><a href="#random"><i class="fa fa-random"></i> fa-random</a></li>
			<li><a href="#recycle"><i class="fa fa-recycle"></i> fa-recycle</a></li>
			<li><a href="#refresh"><i class="fa fa-refresh"></i> fa-refresh</a></li>
			<li><a href="#times"><i class="fa fa-remove"></i> fa-remove <span class="text-muted">(alias)</span></a></li>
			<li><a href="#bars"><i class="fa fa-reorder"></i> fa-reorder <span class="text-muted">(alias)</span></a></li>
			<li><a href="#reply"><i class="fa fa-reply"></i> fa-reply</a></li>
			<li><a href="#reply-all"><i class="fa fa-reply-all"></i> fa-reply-all</a></li>
			<li><a href="#retweet"><i class="fa fa-retweet"></i> fa-retweet</a></li>
			<li><a href="#road"><i class="fa fa-road"></i> fa-road</a></li>
			<li><a href="#rocket"><i class="fa fa-rocket"></i> fa-rocket</a></li>
			<li><a href="#rss"><i class="fa fa-rss"></i> fa-rss</a></li>
			<li><a href="#rss-square"><i class="fa fa-rss-square"></i> fa-rss-square</a></li>
			<li><a href="#search"><i class="fa fa-search"></i> fa-search</a></li>
			<li><a href="#search-minus"><i class="fa fa-search-minus"></i> fa-search-minus</a></li>
			<li><a href="#search-plus"><i class="fa fa-search-plus"></i> fa-search-plus</a></li>
			<li><a href="#paper-plane"><i class="fa fa-send"></i> fa-send <span class="text-muted">(alias)</span></a></li>
			<li><a href="#paper-plane-o"><i class="fa fa-send-o"></i> fa-send-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#share"><i class="fa fa-share"></i> fa-share</a></li>
			<li><a href="#share-alt"><i class="fa fa-share-alt"></i> fa-share-alt</a></li>
			<li><a href="#share-alt-square"><i class="fa fa-share-alt-square"></i> fa-share-alt-square</a></li>
			<li><a href="#share-square"><i class="fa fa-share-square"></i> fa-share-square</a></li>
			<li><a href="#share-square-o"><i class="fa fa-share-square-o"></i> fa-share-square-o</a></li>
			<li><a href="#shield"><i class="fa fa-shield"></i> fa-shield</a></li>
			<li><a href="#shopping-cart"><i class="fa fa-shopping-cart"></i> fa-shopping-cart</a></li>
			<li><a href="#sign-in"><i class="fa fa-sign-in"></i> fa-sign-in</a></li>
			<li><a href="#sign-out"><i class="fa fa-sign-out"></i> fa-sign-out</a></li>
			<li><a href="#signal"><i class="fa fa-signal"></i> fa-signal</a></li>
			<li><a href="#sitemap"><i class="fa fa-sitemap"></i> fa-sitemap</a></li>
			<li><a href="#sliders"><i class="fa fa-sliders"></i> fa-sliders</a></li>
			<li><a href="#smile-o"><i class="fa fa-smile-o"></i> fa-smile-o</a></li>
			<li><a href="#futbol-o"><i class="fa fa-soccer-ball-o"></i> fa-soccer-ball-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#sort"><i class="fa fa-sort"></i> fa-sort</a></li>
			<li><a href="#sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i> fa-sort-alpha-asc</a></li>
			<li><a href="#sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i> fa-sort-alpha-desc</a></li>
			<li><a href="#sort-amount-asc"><i class="fa fa-sort-amount-asc"></i> fa-sort-amount-asc</a></li>
			<li><a href="#sort-amount-desc"><i class="fa fa-sort-amount-desc"></i> fa-sort-amount-desc</a></li>
			<li><a href="#sort-asc"><i class="fa fa-sort-asc"></i> fa-sort-asc</a></li>
			<li><a href="#sort-desc"><i class="fa fa-sort-desc"></i> fa-sort-desc</a></li>
			<li><a href="#sort-desc"><i class="fa fa-sort-down"></i> fa-sort-down <span class="text-muted">(alias)</span></a></li>
			<li><a href="#sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i> fa-sort-numeric-asc</a></li>
			<li><a href="#sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i> fa-sort-numeric-desc</a></li>
			<li><a href="#sort-asc"><i class="fa fa-sort-up"></i> fa-sort-up <span class="text-muted">(alias)</span></a></li>
			<li><a href="#space-shuttle"><i class="fa fa-space-shuttle"></i> fa-space-shuttle</a></li>
			<li><a href="#spinner"><i class="fa fa-spinner"></i> fa-spinner</a></li>
			<li><a href="#spoon"><i class="fa fa-spoon"></i> fa-spoon</a></li>
			<li><a href="#square"><i class="fa fa-square"></i> fa-square</a></li>
			<li><a href="#square-o"><i class="fa fa-square-o"></i> fa-square-o</a></li>
			<li><a href="#star"><i class="fa fa-star"></i> fa-star</a></li>
			<li><a href="#star-half"><i class="fa fa-star-half"></i> fa-star-half</a></li>
			<li><a href="#star-half-o"><i class="fa fa-star-half-empty"></i> fa-star-half-empty <span class="text-muted">(alias)</span></a></li>
			<li><a href="#star-half-o"><i class="fa fa-star-half-full"></i> fa-star-half-full <span class="text-muted">(alias)</span></a></li>
			<li><a href="#star-half-o"><i class="fa fa-star-half-o"></i> fa-star-half-o</a></li>
			<li><a href="#star-o"><i class="fa fa-star-o"></i> fa-star-o</a></li>
			<li><a href="#suitcase"><i class="fa fa-suitcase"></i> fa-suitcase</a></li>
			<li><a href="#sun-o"><i class="fa fa-sun-o"></i> fa-sun-o</a></li>
			<li><a href="#life-ring"><i class="fa fa-support"></i> fa-support <span class="text-muted">(alias)</span></a></li>
			<li><a href="#tablet"><i class="fa fa-tablet"></i> fa-tablet</a></li>
			<li><a href="#tachometer"><i class="fa fa-tachometer"></i> fa-tachometer</a></li>
			<li><a href="#tag"><i class="fa fa-tag"></i> fa-tag</a></li>
			<li><a href="#tags"><i class="fa fa-tags"></i> fa-tags</a></li>
			<li><a href="#tasks"><i class="fa fa-tasks"></i> fa-tasks</a></li>
			<li><a href="#taxi"><i class="fa fa-taxi"></i> fa-taxi</a></li>
			<li><a href="#terminal"><i class="fa fa-terminal"></i> fa-terminal</a></li>
			<li><a href="#thumb-tack"><i class="fa fa-thumb-tack"></i> fa-thumb-tack</a></li>
			<li><a href="#thumbs-down"><i class="fa fa-thumbs-down"></i> fa-thumbs-down</a></li>
			<li><a href="#thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> fa-thumbs-o-down</a></li>
			<li><a href="#thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> fa-thumbs-o-up</a></li>
			<li><a href="#thumbs-up"><i class="fa fa-thumbs-up"></i> fa-thumbs-up</a></li>
			<li><a href="#ticket"><i class="fa fa-ticket"></i> fa-ticket</a></li>
			<li><a href="#times"><i class="fa fa-times"></i> fa-times</a></li>
			<li><a href="#times-circle"><i class="fa fa-times-circle"></i> fa-times-circle</a></li>
			<li><a href="#times-circle-o"><i class="fa fa-times-circle-o"></i> fa-times-circle-o</a></li>
			<li><a href="#tint"><i class="fa fa-tint"></i> fa-tint</a></li>
			<li><a href="#caret-square-o-down"><i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span></a></li>
			<li><a href="#caret-square-o-left"><i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span></a></li>
			<li><a href="#toggle-off"><i class="fa fa-toggle-off"></i> fa-toggle-off</a></li>
			<li><a href="#toggle-on"><i class="fa fa-toggle-on"></i> fa-toggle-on</a></li>
			<li><a href="#caret-square-o-right"><i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span></a></li>
			<li><a href="#caret-square-o-up"><i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span></a></li>
			<li><a href="#trash"><i class="fa fa-trash"></i> fa-trash</a></li>
			<li><a href="#trash-o"><i class="fa fa-trash-o"></i> fa-trash-o</a></li>
			<li><a href="#tree"><i class="fa fa-tree"></i> fa-tree</a></li>
			<li><a href="#trophy"><i class="fa fa-trophy"></i> fa-trophy</a></li>
			<li><a href="#truck"><i class="fa fa-truck"></i> fa-truck</a></li>
			<li><a href="#tty"><i class="fa fa-tty"></i> fa-tty</a></li>
			<li><a href="#umbrella"><i class="fa fa-umbrella"></i> fa-umbrella</a></li>
			<li><a href="#university"><i class="fa fa-university"></i> fa-university</a></li>
			<li><a href="#unlock"><i class="fa fa-unlock"></i> fa-unlock</a></li>
			<li><a href="#unlock-alt"><i class="fa fa-unlock-alt"></i> fa-unlock-alt</a></li>
			<li><a href="#sort"><i class="fa fa-unsorted"></i> fa-unsorted <span class="text-muted">(alias)</span></a></li>
			<li><a href="#upload"><i class="fa fa-upload"></i> fa-upload</a></li>
			<li><a href="#user"><i class="fa fa-user"></i> fa-user</a></li>
			<li><a href="#users"><i class="fa fa-users"></i> fa-users</a></li>
			<li><a href="#video-camera"><i class="fa fa-video-camera"></i> fa-video-camera</a></li>
			<li><a href="#volume-down"><i class="fa fa-volume-down"></i> fa-volume-down</a></li>
			<li><a href="#volume-off"><i class="fa fa-volume-off"></i> fa-volume-off</a></li>
			<li><a href="#volume-up"><i class="fa fa-volume-up"></i> fa-volume-up</a></li>
			<li><a href="#exclamation-triangle"><i class="fa fa-warning"></i> fa-warning <span class="text-muted">(alias)</span></a></li>
			<li><a href="#wheelchair"><i class="fa fa-wheelchair"></i> fa-wheelchair</a></li>
			<li><a href="#wifi"><i class="fa fa-wifi"></i> fa-wifi</a></li>
			<li><a href="#wrench"><i class="fa fa-wrench"></i> fa-wrench</a></li>
			<li><a href="#file"><i class="fa fa-file"></i> fa-file</a></li>
			<li><a href="#file-archive-o"><i class="fa fa-file-archive-o"></i> fa-file-archive-o</a></li>
			<li><a href="#file-audio-o"><i class="fa fa-file-audio-o"></i> fa-file-audio-o</a></li>
			<li><a href="#file-code-o"><i class="fa fa-file-code-o"></i> fa-file-code-o</a></li>
			<li><a href="#file-excel-o"><i class="fa fa-file-excel-o"></i> fa-file-excel-o</a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-image-o"></i> fa-file-image-o</a></li>
			<li><a href="#file-video-o"><i class="fa fa-file-movie-o"></i> fa-file-movie-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-o"><i class="fa fa-file-o"></i> fa-file-o</a></li>
			<li><a href="#file-pdf-o"><i class="fa fa-file-pdf-o"></i> fa-file-pdf-o</a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-photo-o"></i> fa-file-photo-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-image-o"><i class="fa fa-file-picture-o"></i> fa-file-picture-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> fa-file-powerpoint-o</a></li>
			<li><a href="#file-audio-o"><i class="fa fa-file-sound-o"></i> fa-file-sound-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#file-text"><i class="fa fa-file-text"></i> fa-file-text</a></li>
			<li><a href="#file-text-o"><i class="fa fa-file-text-o"></i> fa-file-text-o</a></li>
			<li><a href="#file-video-o"><i class="fa fa-file-video-o"></i> fa-file-video-o</a></li>
			<li><a href="#file-word-o"><i class="fa fa-file-word-o"></i> fa-file-word-o</a></li>
			<li><a href="#file-archive-o"><i class="fa fa-file-zip-o"></i> fa-file-zip-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#circle-o-notch"><i class="fa fa-circle-o-notch"></i> fa-circle-o-notch</a></li>
			<li><a href="#cog"><i class="fa fa-cog"></i> fa-cog</a></li>
			<li><a href="#cog"><i class="fa fa-gear"></i> fa-gear <span class="text-muted">(alias)</span></a></li>
			<li><a href="#refresh"><i class="fa fa-refresh"></i> fa-refresh</a></li>
			<li><a href="#spinner"><i class="fa fa-spinner"></i> fa-spinner</a></li>
			<li><a href="#check-square"><i class="fa fa-check-square"></i> fa-check-square</a></li>
			<li><a href="#check-square-o"><i class="fa fa-check-square-o"></i> fa-check-square-o</a></li>
			<li><a href="#circle"><i class="fa fa-circle"></i> fa-circle</a></li>
			<li><a href="#circle-o"><i class="fa fa-circle-o"></i> fa-circle-o</a></li>
			<li><a href="#dot-circle-o"><i class="fa fa-dot-circle-o"></i> fa-dot-circle-o</a></li>
			<li><a href="#minus-square"><i class="fa fa-minus-square"></i> fa-minus-square</a></li>
			<li><a href="#minus-square-o"><i class="fa fa-minus-square-o"></i> fa-minus-square-o</a></li>
			<li><a href="#plus-square"><i class="fa fa-plus-square"></i> fa-plus-square</a></li>
			<li><a href="#plus-square-o"><i class="fa fa-plus-square-o"></i> fa-plus-square-o</a></li>
			<li><a href="#square"><i class="fa fa-square"></i> fa-square</a></li>
			<li><a href="#square-o"><i class="fa fa-square-o"></i> fa-square-o</a></li>
			<li><a href="#cc-amex"><i class="fa fa-cc-amex"></i> fa-cc-amex</a></li>
			<li><a href="#cc-discover"><i class="fa fa-cc-discover"></i> fa-cc-discover</a></li>
			<li><a href="#cc-mastercard"><i class="fa fa-cc-mastercard"></i> fa-cc-mastercard</a></li>
			<li><a href="#cc-paypal"><i class="fa fa-cc-paypal"></i> fa-cc-paypal</a></li>
			<li><a href="#cc-stripe"><i class="fa fa-cc-stripe"></i> fa-cc-stripe</a></li>
			<li><a href="#cc-visa"><i class="fa fa-cc-visa"></i> fa-cc-visa</a></li>
			<li><a href="#credit-card"><i class="fa fa-credit-card"></i> fa-credit-card</a></li>
			<li><a href="#google-wallet"><i class="fa fa-google-wallet"></i> fa-google-wallet</a></li>
			<li><a href="#paypal"><i class="fa fa-paypal"></i> fa-paypal</a></li>
			<li><a href="#area-chart"><i class="fa fa-area-chart"></i> fa-area-chart</a></li>
			<li><a href="#bar-chart"><i class="fa fa-bar-chart"></i> fa-bar-chart</a></li>
			<li><a href="#bar-chart"><i class="fa fa-bar-chart-o"></i> fa-bar-chart-o <span class="text-muted">(alias)</span></a></li>
			<li><a href="#line-chart"><i class="fa fa-line-chart"></i> fa-line-chart</a></li>
			<li><a href="#pie-chart"><i class="fa fa-pie-chart"></i> fa-pie-chart</a></li>
			<li><a href="#btc"><i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span></a></li>
			<li><a href="#btc"><i class="fa fa-btc"></i> fa-btc</a></li>
			<li><a href="#jpy"><i class="fa fa-cny"></i> fa-cny <span class="text-muted">(alias)</span></a></li>
			<li><a href="#usd"><i class="fa fa-dollar"></i> fa-dollar <span class="text-muted">(alias)</span></a></li>
			<li><a href="#eur"><i class="fa fa-eur"></i> fa-eur</a></li>
			<li><a href="#eur"><i class="fa fa-euro"></i> fa-euro <span class="text-muted">(alias)</span></a></li>
			<li><a href="#gbp"><i class="fa fa-gbp"></i> fa-gbp</a></li>
			<li><a href="#ils"><i class="fa fa-ils"></i> fa-ils</a></li>
			<li><a href="#inr"><i class="fa fa-inr"></i> fa-inr</a></li>
			<li><a href="#jpy"><i class="fa fa-jpy"></i> fa-jpy</a></li>
			<li><a href="#krw"><i class="fa fa-krw"></i> fa-krw</a></li>
			<li><a href="#money"><i class="fa fa-money"></i> fa-money</a></li>
			<li><a href="#jpy"><i class="fa fa-rmb"></i> fa-rmb <span class="text-muted">(alias)</span></a></li>
			<li><a href="#rub"><i class="fa fa-rouble"></i> fa-rouble <span class="text-muted">(alias)</span></a></li>
			<li><a href="#rub"><i class="fa fa-rub"></i> fa-rub</a></li>
			<li><a href="#rub"><i class="fa fa-ruble"></i> fa-ruble <span class="text-muted">(alias)</span></a></li>
			<li><a href="#inr"><i class="fa fa-rupee"></i> fa-rupee <span class="text-muted">(alias)</span></a></li>
			<li><a href="#ils"><i class="fa fa-shekel"></i> fa-shekel <span class="text-muted">(alias)</span></a></li>
			<li><a href="#ils"><i class="fa fa-sheqel"></i> fa-sheqel <span class="text-muted">(alias)</span></a></li>
			<li><a href="#try"><i class="fa fa-try"></i> fa-try</a></li>
			<li><a href="#try"><i class="fa fa-turkish-lira"></i> fa-turkish-lira <span class="text-muted">(alias)</span></a></li>
			<li><a href="#usd"><i class="fa fa-usd"></i> fa-usd</a></li>
			<li><a href="#krw"><i class="fa fa-won"></i> fa-won <span class="text-muted">(alias)</span></a></li>
			<li><a href="#jpy"><i class="fa fa-yen"></i> fa-yen <span class="text-muted">(alias)</span></a></li>
			<li><a href="#align-center"><i class="fa fa-align-center"></i> fa-align-center</a></li>
			<li><a href="#align-justify"><i class="fa fa-align-justify"></i> fa-align-justify</a></li>
			<li><a href="#align-left"><i class="fa fa-align-left"></i> fa-align-left</a></li>
			<li><a href="#align-right"><i class="fa fa-align-right"></i> fa-align-right</a></li>
			<li><a href="#bold"><i class="fa fa-bold"></i> fa-bold</a></li>
			<li><a href="#link"><i class="fa fa-chain"></i> fa-chain <span class="text-muted">(alias)</span></a></li>
			<li><a href="#chain-broken"><i class="fa fa-chain-broken"></i> fa-chain-broken</a></li>
			<li><a href="#clipboard"><i class="fa fa-clipboard"></i> fa-clipboard</a></li>
			<li><a href="#columns"><i class="fa fa-columns"></i> fa-columns</a></li>
			<li><a href="#files-o"><i class="fa fa-copy"></i> fa-copy <span class="text-muted">(alias)</span></a></li>
			<li><a href="#scissors"><i class="fa fa-cut"></i> fa-cut <span class="text-muted">(alias)</span></a></li>
			<li><a href="#outdent"><i class="fa fa-dedent"></i> fa-dedent <span class="text-muted">(alias)</span></a></li>
			<li><a href="#eraser"><i class="fa fa-eraser"></i> fa-eraser</a></li>
			<li><a href="#file"><i class="fa fa-file"></i> fa-file</a></li>
			<li><a href="#file-o"><i class="fa fa-file-o"></i> fa-file-o</a></li>
			<li><a href="#file-text"><i class="fa fa-file-text"></i> fa-file-text</a></li>
			<li><a href="#file-text-o"><i class="fa fa-file-text-o"></i> fa-file-text-o</a></li>
			<li><a href="#files-o"><i class="fa fa-files-o"></i> fa-files-o</a></li>
			<li><a href="#floppy-o"><i class="fa fa-floppy-o"></i> fa-floppy-o</a></li>
			<li><a href="#font"><i class="fa fa-font"></i> fa-font</a></li>
			<li><a href="#header"><i class="fa fa-header"></i> fa-header</a></li>
			<li><a href="#indent"><i class="fa fa-indent"></i> fa-indent</a></li>
			<li><a href="#italic"><i class="fa fa-italic"></i> fa-italic</a></li>
			<li><a href="#link"><i class="fa fa-link"></i> fa-link</a></li>
			<li><a href="#list"><i class="fa fa-list"></i> fa-list</a></li>
			<li><a href="#list-alt"><i class="fa fa-list-alt"></i> fa-list-alt</a></li>
			<li><a href="#list-ol"><i class="fa fa-list-ol"></i> fa-list-ol</a></li>
			<li><a href="#list-ul"><i class="fa fa-list-ul"></i> fa-list-ul</a></li>
			<li><a href="#outdent"><i class="fa fa-outdent"></i> fa-outdent</a></li>
			<li><a href="#paperclip"><i class="fa fa-paperclip"></i> fa-paperclip</a></li>
			<li><a href="#paragraph"><i class="fa fa-paragraph"></i> fa-paragraph</a></li>
			<li><a href="#clipboard"><i class="fa fa-paste"></i> fa-paste <span class="text-muted">(alias)</span></a></li>
			<li><a href="#repeat"><i class="fa fa-repeat"></i> fa-repeat</a></li>
			<li><a href="#undo"><i class="fa fa-rotate-left"></i> fa-rotate-left <span class="text-muted">(alias)</span></a></li>
			<li><a href="#repeat"><i class="fa fa-rotate-right"></i> fa-rotate-right <span class="text-muted">(alias)</span></a></li>
			<li><a href="#floppy-o"><i class="fa fa-save"></i> fa-save <span class="text-muted">(alias)</span></a></li>
			<li><a href="#scissors"><i class="fa fa-scissors"></i> fa-scissors</a></li>
			<li><a href="#strikethrough"><i class="fa fa-strikethrough"></i> fa-strikethrough</a></li>
			<li><a href="#subscript"><i class="fa fa-subscript"></i> fa-subscript</a></li>
			<li><a href="#superscript"><i class="fa fa-superscript"></i> fa-superscript</a></li>
			<li><a href="#table"><i class="fa fa-table"></i> fa-table</a></li>
			<li><a href="#text-height"><i class="fa fa-text-height"></i> fa-text-height</a></li>
			<li><a href="#text-width"><i class="fa fa-text-width"></i> fa-text-width</a></li>
			<li><a href="#th"><i class="fa fa-th"></i> fa-th</a></li>
			<li><a href="#th-large"><i class="fa fa-th-large"></i> fa-th-large</a></li>
			<li><a href="#th-list"><i class="fa fa-th-list"></i> fa-th-list</a></li>
			<li><a href="#underline"><i class="fa fa-underline"></i> fa-underline</a></li>
			<li><a href="#undo"><i class="fa fa-undo"></i> fa-undo</a></li>
			<li><a href="#chain-broken"><i class="fa fa-unlink"></i> fa-unlink <span class="text-muted">(alias)</span></a></li>
			<li><a href="#angle-double-down"><i class="fa fa-angle-double-down"></i> fa-angle-double-down</a></li>
			<li><a href="#angle-double-left"><i class="fa fa-angle-double-left"></i> fa-angle-double-left</a></li>
			<li><a href="#angle-double-right"><i class="fa fa-angle-double-right"></i> fa-angle-double-right</a></li>
			<li><a href="#angle-double-up"><i class="fa fa-angle-double-up"></i> fa-angle-double-up</a></li>
			<li><a href="#angle-down"><i class="fa fa-angle-down"></i> fa-angle-down</a></li>
			<li><a href="#angle-left"><i class="fa fa-angle-left"></i> fa-angle-left</a></li>
			<li><a href="#angle-right"><i class="fa fa-angle-right"></i> fa-angle-right</a></li>
			<li><a href="#angle-up"><i class="fa fa-angle-up"></i> fa-angle-up</a></li>
			<li><a href="#arrow-circle-down"><i class="fa fa-arrow-circle-down"></i> fa-arrow-circle-down</a></li>
			<li><a href="#arrow-circle-left"><i class="fa fa-arrow-circle-left"></i> fa-arrow-circle-left</a></li>
			<li><a href="#arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i> fa-arrow-circle-o-down</a></li>
			<li><a href="#arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i> fa-arrow-circle-o-left</a></li>
			<li><a href="#arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i> fa-arrow-circle-o-right</a></li>
			<li><a href="#arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i> fa-arrow-circle-o-up</a></li>
			<li><a href="#arrow-circle-right"><i class="fa fa-arrow-circle-right"></i> fa-arrow-circle-right</a></li>
			<li><a href="#arrow-circle-up"><i class="fa fa-arrow-circle-up"></i> fa-arrow-circle-up</a></li>
			<li><a href="#arrow-down"><i class="fa fa-arrow-down"></i> fa-arrow-down</a></li>
			<li><a href="#arrow-left"><i class="fa fa-arrow-left"></i> fa-arrow-left</a></li>
			<li><a href="#arrow-right"><i class="fa fa-arrow-right"></i> fa-arrow-right</a></li>
			<li><a href="#arrow-up"><i class="fa fa-arrow-up"></i> fa-arrow-up</a></li>
			<li><a href="#arrows"><i class="fa fa-arrows"></i> fa-arrows</a></li>
			<li><a href="#arrows-alt"><i class="fa fa-arrows-alt"></i> fa-arrows-alt</a></li>
			<li><a href="#arrows-h"><i class="fa fa-arrows-h"></i> fa-arrows-h</a></li>
			<li><a href="#arrows-v"><i class="fa fa-arrows-v"></i> fa-arrows-v</a></li>
			<li><a href="#caret-down"><i class="fa fa-caret-down"></i> fa-caret-down</a></li>
			<li><a href="#caret-left"><i class="fa fa-caret-left"></i> fa-caret-left</a></li>
			<li><a href="#caret-right"><i class="fa fa-caret-right"></i> fa-caret-right</a></li>
			<li><a href="#caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down</a></li>
			<li><a href="#caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left</a></li>
			<li><a href="#caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right</a></li>
			<li><a href="#caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up</a></li>
			<li><a href="#caret-up"><i class="fa fa-caret-up"></i> fa-caret-up</a></li>
			<li><a href="#chevron-circle-down"><i class="fa fa-chevron-circle-down"></i> fa-chevron-circle-down</a></li>
			<li><a href="#chevron-circle-left"><i class="fa fa-chevron-circle-left"></i> fa-chevron-circle-left</a></li>
			<li><a href="#chevron-circle-right"><i class="fa fa-chevron-circle-right"></i> fa-chevron-circle-right</a></li>
			<li><a href="#chevron-circle-up"><i class="fa fa-chevron-circle-up"></i> fa-chevron-circle-up</a></li>
			<li><a href="#chevron-down"><i class="fa fa-chevron-down"></i> fa-chevron-down</a></li>
			<li><a href="#chevron-left"><i class="fa fa-chevron-left"></i> fa-chevron-left</a></li>
			<li><a href="#chevron-right"><i class="fa fa-chevron-right"></i> fa-chevron-right</a></li>
			<li><a href="#chevron-up"><i class="fa fa-chevron-up"></i> fa-chevron-up</a></li>
			<li><a href="#hand-o-down"><i class="fa fa-hand-o-down"></i> fa-hand-o-down</a></li>
			<li><a href="#hand-o-left"><i class="fa fa-hand-o-left"></i> fa-hand-o-left</a></li>
			<li><a href="#hand-o-right"><i class="fa fa-hand-o-right"></i> fa-hand-o-right</a></li>
			<li><a href="#hand-o-up"><i class="fa fa-hand-o-up"></i> fa-hand-o-up</a></li>
			<li><a href="#long-arrow-down"><i class="fa fa-long-arrow-down"></i> fa-long-arrow-down</a></li>
			<li><a href="#long-arrow-left"><i class="fa fa-long-arrow-left"></i> fa-long-arrow-left</a></li>
			<li><a href="#long-arrow-right"><i class="fa fa-long-arrow-right"></i> fa-long-arrow-right</a></li>
			<li><a href="#long-arrow-up"><i class="fa fa-long-arrow-up"></i> fa-long-arrow-up</a></li>
			<li><a href="#caret-square-o-down"><i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span></a></li>
			<li><a href="#caret-square-o-left"><i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span></a></li>
			<li><a href="#caret-square-o-right"><i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span></a></li>
			<li><a href="#caret-square-o-up"><i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span></a></li>
			<li><a href="#arrows-alt"><i class="fa fa-arrows-alt"></i> fa-arrows-alt</a></li>
			<li><a href="#backward"><i class="fa fa-backward"></i> fa-backward</a></li>
			<li><a href="#compress"><i class="fa fa-compress"></i> fa-compress</a></li>
			<li><a href="#eject"><i class="fa fa-eject"></i> fa-eject</a></li>
			<li><a href="#expand"><i class="fa fa-expand"></i> fa-expand</a></li>
			<li><a href="#fast-backward"><i class="fa fa-fast-backward"></i> fa-fast-backward</a></li>
			<li><a href="#fast-forward"><i class="fa fa-fast-forward"></i> fa-fast-forward</a></li>
			<li><a href="#forward"><i class="fa fa-forward"></i> fa-forward</a></li>
			<li><a href="#pause"><i class="fa fa-pause"></i> fa-pause</a></li>
			<li><a href="#play"><i class="fa fa-play"></i> fa-play</a></li>
			<li><a href="#play-circle"><i class="fa fa-play-circle"></i> fa-play-circle</a></li>
			<li><a href="#play-circle-o"><i class="fa fa-play-circle-o"></i> fa-play-circle-o</a></li>
			<li><a href="#step-backward"><i class="fa fa-step-backward"></i> fa-step-backward</a></li>
			<li><a href="#step-forward"><i class="fa fa-step-forward"></i> fa-step-forward</a></li>
			<li><a href="#stop"><i class="fa fa-stop"></i> fa-stop</a></li>
			<li><a href="#youtube-play"><i class="fa fa-youtube-play"></i> fa-youtube-play</a></li>
			<li><a href="#adn"><i class="fa fa-adn"></i> fa-adn</a></li>
			<li><a href="#android"><i class="fa fa-android"></i> fa-android</a></li>
			<li><a href="#angellist"><i class="fa fa-angellist"></i> fa-angellist</a></li>
			<li><a href="#apple"><i class="fa fa-apple"></i> fa-apple</a></li>
			<li><a href="#behance"><i class="fa fa-behance"></i> fa-behance</a></li>
			<li><a href="#behance-square"><i class="fa fa-behance-square"></i> fa-behance-square</a></li>
			<li><a href="#bitbucket"><i class="fa fa-bitbucket"></i> fa-bitbucket</a></li>
			<li><a href="#bitbucket-square"><i class="fa fa-bitbucket-square"></i> fa-bitbucket-square</a></li>
			<li><a href="#btc"><i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span></a></li>
			<li><a href="#btc"><i class="fa fa-btc"></i> fa-btc</a></li>
			<li><a href="#cc-amex"><i class="fa fa-cc-amex"></i> fa-cc-amex</a></li>
			<li><a href="#cc-discover"><i class="fa fa-cc-discover"></i> fa-cc-discover</a></li>
			<li><a href="#cc-mastercard"><i class="fa fa-cc-mastercard"></i> fa-cc-mastercard</a></li>
			<li><a href="#cc-paypal"><i class="fa fa-cc-paypal"></i> fa-cc-paypal</a></li>
			<li><a href="#cc-stripe"><i class="fa fa-cc-stripe"></i> fa-cc-stripe</a></li>
			<li><a href="#cc-visa"><i class="fa fa-cc-visa"></i> fa-cc-visa</a></li>
			<li><a href="#codepen"><i class="fa fa-codepen"></i> fa-codepen</a></li>
			<li><a href="#css3"><i class="fa fa-css3"></i> fa-css3</a></li>
			<li><a href="#delicious"><i class="fa fa-delicious"></i> fa-delicious</a></li>
			<li><a href="#deviantart"><i class="fa fa-deviantart"></i> fa-deviantart</a></li>
			<li><a href="#digg"><i class="fa fa-digg"></i> fa-digg</a></li>
			<li><a href="#dribbble"><i class="fa fa-dribbble"></i> fa-dribbble</a></li>
			<li><a href="#dropbox"><i class="fa fa-dropbox"></i> fa-dropbox</a></li>
			<li><a href="#drupal"><i class="fa fa-drupal"></i> fa-drupal</a></li>
			<li><a href="#empire"><i class="fa fa-empire"></i> fa-empire</a></li>
			<li><a href="#facebook"><i class="fa fa-facebook"></i> fa-facebook</a></li>
			<li><a href="#facebook-square"><i class="fa fa-facebook-square"></i> fa-facebook-square</a></li>
			<li><a href="#flickr"><i class="fa fa-flickr"></i> fa-flickr</a></li>
			<li><a href="#foursquare"><i class="fa fa-foursquare"></i> fa-foursquare</a></li>
			<li><a href="#empire"><i class="fa fa-ge"></i> fa-ge <span class="text-muted">(alias)</span></a></li>
			<li><a href="#git"><i class="fa fa-git"></i> fa-git</a></li>
			<li><a href="#git-square"><i class="fa fa-git-square"></i> fa-git-square</a></li>
			<li><a href="#github"><i class="fa fa-github"></i> fa-github</a></li>
			<li><a href="#github-alt"><i class="fa fa-github-alt"></i> fa-github-alt</a></li>
			<li><a href="#github-square"><i class="fa fa-github-square"></i> fa-github-square</a></li>
			<li><a href="#gittip"><i class="fa fa-gittip"></i> fa-gittip</a></li>
			<li><a href="#google"><i class="fa fa-google"></i> fa-google</a></li>
			<li><a href="#google-plus"><i class="fa fa-google-plus"></i> fa-google-plus</a></li>
			<li><a href="#google-plus-square"><i class="fa fa-google-plus-square"></i> fa-google-plus-square</a></li>
			<li><a href="#google-wallet"><i class="fa fa-google-wallet"></i> fa-google-wallet</a></li>
			<li><a href="#hacker-news"><i class="fa fa-hacker-news"></i> fa-hacker-news</a></li>
			<li><a href="#html5"><i class="fa fa-html5"></i> fa-html5</a></li>
			<li><a href="#instagram"><i class="fa fa-instagram"></i> fa-instagram</a></li>
			<li><a href="#ioxhost"><i class="fa fa-ioxhost"></i> fa-ioxhost</a></li>
			<li><a href="#joomla"><i class="fa fa-joomla"></i> fa-joomla</a></li>
			<li><a href="#jsfiddle"><i class="fa fa-jsfiddle"></i> fa-jsfiddle</a></li>
			<li><a href="#lastfm"><i class="fa fa-lastfm"></i> fa-lastfm</a></li>
			<li><a href="#lastfm-square"><i class="fa fa-lastfm-square"></i> fa-lastfm-square</a></li>
			<li><a href="#linkedin"><i class="fa fa-linkedin"></i> fa-linkedin</a></li>
			<li><a href="#linkedin-square"><i class="fa fa-linkedin-square"></i> fa-linkedin-square</a></li>
			<li><a href="#linux"><i class="fa fa-linux"></i> fa-linux</a></li>
			<li><a href="#maxcdn"><i class="fa fa-maxcdn"></i> fa-maxcdn</a></li>
			<li><a href="#meanpath"><i class="fa fa-meanpath"></i> fa-meanpath</a></li>
			<li><a href="#openid"><i class="fa fa-openid"></i> fa-openid</a></li>
			<li><a href="#pagelines"><i class="fa fa-pagelines"></i> fa-pagelines</a></li>
			<li><a href="#paypal"><i class="fa fa-paypal"></i> fa-paypal</a></li>
			<li><a href="#pied-piper"><i class="fa fa-pied-piper"></i> fa-pied-piper</a></li>
			<li><a href="#pied-piper-alt"><i class="fa fa-pied-piper-alt"></i> fa-pied-piper-alt</a></li>
			<li><a href="#pinterest"><i class="fa fa-pinterest"></i> fa-pinterest</a></li>
			<li><a href="#pinterest-square"><i class="fa fa-pinterest-square"></i> fa-pinterest-square</a></li>
			<li><a href="#qq"><i class="fa fa-qq"></i> fa-qq</a></li>
			<li><a href="#rebel"><i class="fa fa-ra"></i> fa-ra <span class="text-muted">(alias)</span></a></li>
			<li><a href="#rebel"><i class="fa fa-rebel"></i> fa-rebel</a></li>
			<li><a href="#reddit"><i class="fa fa-reddit"></i> fa-reddit</a></li>
			<li><a href="#reddit-square"><i class="fa fa-reddit-square"></i> fa-reddit-square</a></li>
			<li><a href="#renren"><i class="fa fa-renren"></i> fa-renren</a></li>
			<li><a href="#share-alt"><i class="fa fa-share-alt"></i> fa-share-alt</a></li>
			<li><a href="#share-alt-square"><i class="fa fa-share-alt-square"></i> fa-share-alt-square</a></li>
			<li><a href="#skype"><i class="fa fa-skype"></i> fa-skype</a></li>
			<li><a href="#slack"><i class="fa fa-slack"></i> fa-slack</a></li>
			<li><a href="#slideshare"><i class="fa fa-slideshare"></i> fa-slideshare</a></li>
			<li><a href="#soundcloud"><i class="fa fa-soundcloud"></i> fa-soundcloud</a></li>
			<li><a href="#spotify"><i class="fa fa-spotify"></i> fa-spotify</a></li>
			<li><a href="#stack-exchange"><i class="fa fa-stack-exchange"></i> fa-stack-exchange</a></li>
			<li><a href="#stack-overflow"><i class="fa fa-stack-overflow"></i> fa-stack-overflow</a></li>
			<li><a href="#steam"><i class="fa fa-steam"></i> fa-steam</a></li>
			<li><a href="#steam-square"><i class="fa fa-steam-square"></i> fa-steam-square</a></li>
			<li><a href="#stumbleupon"><i class="fa fa-stumbleupon"></i> fa-stumbleupon</a></li>
			<li><a href="#stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i> fa-stumbleupon-circle</a></li>
			<li><a href="#tencent-weibo"><i class="fa fa-tencent-weibo"></i> fa-tencent-weibo</a></li>
			<li><a href="#trello"><i class="fa fa-trello"></i> fa-trello</a></li>
			<li><a href="#tumblr"><i class="fa fa-tumblr"></i> fa-tumblr</a></li>
			<li><a href="#tumblr-square"><i class="fa fa-tumblr-square"></i> fa-tumblr-square</a></li>
			<li><a href="#twitch"><i class="fa fa-twitch"></i> fa-twitch</a></li>
			<li><a href="#twitter"><i class="fa fa-twitter"></i> fa-twitter</a></li>
			<li><a href="#twitter-square"><i class="fa fa-twitter-square"></i> fa-twitter-square</a></li>
			<li><a href="#vimeo-square"><i class="fa fa-vimeo-square"></i> fa-vimeo-square</a></li>
			<li><a href="#vine"><i class="fa fa-vine"></i> fa-vine</a></li>
			<li><a href="#vk"><i class="fa fa-vk"></i> fa-vk</a></li>
			<li><a href="#weixin"><i class="fa fa-wechat"></i> fa-wechat <span class="text-muted">(alias)</span></a></li>
			<li><a href="#weibo"><i class="fa fa-weibo"></i> fa-weibo</a></li>
			<li><a href="#weixin"><i class="fa fa-weixin"></i> fa-weixin</a></li>
			<li><a href="#windows"><i class="fa fa-windows"></i> fa-windows</a></li>
			<li><a href="#wordpress"><i class="fa fa-wordpress"></i> fa-wordpress</a></li>
			<li><a href="#xing"><i class="fa fa-xing"></i> fa-xing</a></li>
			<li><a href="#xing-square"><i class="fa fa-xing-square"></i> fa-xing-square</a></li>
			<li><a href="#yahoo"><i class="fa fa-yahoo"></i> fa-yahoo</a></li>
			<li><a href="#yelp"><i class="fa fa-yelp"></i> fa-yelp</a></li>
			<li><a href="#youtube"><i class="fa fa-youtube"></i> fa-youtube</a></li>
			<li><a href="#youtube-play"><i class="fa fa-youtube-play"></i> fa-youtube-play</a></li>
			<li><a href="#youtube-square"><i class="fa fa-youtube-square"></i> fa-youtube-square</a></li>
			<li><a href="#ambulance"><i class="fa fa-ambulance"></i> fa-ambulance</a></li>
			<li><a href="#h-square"><i class="fa fa-h-square"></i> fa-h-square</a></li>
			<li><a href="#hospital-o"><i class="fa fa-hospital-o"></i> fa-hospital-o</a></li>
			<li><a href="#medkit"><i class="fa fa-medkit"></i> fa-medkit</a></li>
			<li><a href="#plus-square"><i class="fa fa-plus-square"></i> fa-plus-square</a></li>
			<li><a href="#stethoscope"><i class="fa fa-stethoscope"></i> fa-stethoscope</a></li>
			<li><a href="#user-md"><i class="fa fa-user-md"></i> fa-user-md</a></li>
			<li><a href="#wheelchair"><i class="fa fa-wheelchair"></i> fa-wheelchair</a></li>
		</ul>
	</div>
</div>		
<?php
}
 ?>