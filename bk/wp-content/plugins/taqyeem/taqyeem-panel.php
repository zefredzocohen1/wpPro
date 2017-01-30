<?php
/*-----------------------------------------------------------------------------------*/
# Register main Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function taq_admin_register() {
    wp_register_script( 'taqyeem-admin-checkbox', plugins_url('admin/js/checkbox.min.js' , __FILE__) , array( 'jquery' ) , false , false );  
    wp_register_script( 'taqyeem-admin-main', plugins_url('admin/js/tie.js' , __FILE__), array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-sortable' ) , false , false );  
    
	wp_register_script( 'taqyeem-admin-colorpicker',  plugins_url('admin/js/colorpicker.js' , __FILE__), array( 'jquery' ) , false , false );  

	wp_register_style( 'taqyeem-admin-style', plugins_url('admin/style.css' , __FILE__), array(), false , 'all' ); 

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'taqyeem' ) {
		wp_enqueue_script( 'taqyeem-admin-checkbox' ); 
	}
	wp_enqueue_script( 'taqyeem-admin-colorpicker');  
	wp_enqueue_script( 'taqyeem-admin-main' );
	wp_enqueue_style( 'taqyeem-admin-style' );
	?>
	<script type='text/javascript'>
	/* <![CDATA[ */
		var taqyeem_lang = {"review_criteria":"<?php _e( 'Review Criteria' , 'taq' ) ?>", "criteria_score":"<?php  _e( 'Criteria Score' , 'taq' ) ?>", "del":"<?php  _e( 'Delete' , 'taq' ) ?>"};
		/* ]]> */	
	</script>
	<?php
}
add_action( 'admin_enqueue_scripts', 'taq_admin_register' ); 


/*-----------------------------------------------------------------------------------*/
# get Google Fonts
/*-----------------------------------------------------------------------------------*/
require ('google-fonts.php');
$google_font_array = json_decode ($google_api_output,true) ;
	
$items = $google_font_array['items'];
	
$options_fonts=array();
$options_fonts[''] = "Default Font" ;
$fontID = 0;
foreach ($items as $item) {
	$fontID++;
	$variants='';
	$variantCount=0;
	foreach ($item['variants'] as $variant) {
		$variantCount++;
		if ($variantCount>1) { $variants .= '|'; }
		$variants .= $variant;
	}
	$variantText = ' (' . $variantCount . ' Varaints' . ')';
	if ($variantCount <= 1) $variantText = '';
	$options_fonts[ $item['family'] . ':' . $variants ] = $item['family']. $variantText;
}


/*-----------------------------------------------------------------------------------*/
# Clean options before store it in DB
/*-----------------------------------------------------------------------------------*/
function taqyeem_clean_options(&$value) {
  $value = htmlspecialchars(stripslashes($value));
}


/*-----------------------------------------------------------------------------------*/
# Save plugin Settings
/*-----------------------------------------------------------------------------------*/	
function taqyeem_save_settings ( $data , $refresh = 0 ) {
		
	if( isset( $data['taqyeem_options'] )){
		array_walk_recursive( $data['taqyeem_options'] , 'taqyeem_clean_options');
		update_option( 'taqyeem_options' ,  $data['taqyeem_options']   );
	}
	
	if( $refresh == 2 )  die('2');
	elseif( $refresh == 1 )	die('1');
}
	
	
/*-----------------------------------------------------------------------------------*/
# Save Options
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_test_taqyeem_data_save', 'taqyeem_save_ajax');
function taqyeem_save_ajax() {
	
	check_ajax_referer('test-taqyeem-data', 'taq_security');
	$data = $_POST;
	$refresh = 1;

	if( $data['taqyeem_import'] ){
		$data = unserialize(base64_decode( $data['taqyeem_import'] ));
		array_walk_recursive( $data , 'taqyeem_clean_options');
		update_option( 'taqyeem_options' ,  $data   );
		die('2');
	}
	
	taqyeem_save_settings ($data , $refresh );
}


/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_addons_rss( $number = false ){
	include_once(ABSPATH . WPINC . '/feed.php');
	$rss = fetch_feed('http://themes.tielabs.com/xml/taqyeem-addons.xml');
	if ( !is_wp_error( $rss ) ){
		if( !empty( $number ) )
			return $rss->get_item_quantity(  );

	    return $rss->get_items(); 
		
	}
	return false;
}

/*-----------------------------------------------------------------------------------*/
# Add Panel Page
/*-----------------------------------------------------------------------------------*/
function taqyeem_add_admin() {

	$current_page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';

	$icon = plugins_url('admin/images/general.png' , __FILE__);
	add_menu_page(TIE_Plugin.' '.__( "Settings" , "taq" ), TIE_Plugin ,'activate_plugins', 'taqyeem' , 'taqyeem_options', ''  );
	$theme_page = add_submenu_page('taqyeem',TIE_Plugin.' '.__( "Settings" , "taq" ), TIE_Plugin.' '.__( "Settings" , "taq" ) ,'activate_plugins', 'taqyeem' , 'taqyeem_options');
	add_submenu_page('taqyeem', __( "Documentation" , "taq" ), __( "Documentation" , "taq" ),'activate_plugins', 'taq_docs' , 'taqyeem_redirect_docs');

	function taqyeem_redirect_docs(){
		$taq_docs_url = "http://plugins.tielabs.com/docs/taqyeem";
		echo "<script type='text/javascript'>window.location='".$taq_docs_url."';</script>";
	}
	
	add_action( 'admin_head-'. $theme_page, 'taqyeem_admin_head' );
	function taqyeem_admin_head(){
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

		  jQuery('.on-of').checkbox({empty:'<?php echo plugins_url('admin/images/empty.png' , __FILE__) ?>'});

		 // jQuery('form#taqyeem_form').submit(function() {
		  jQuery('form#taqyeem_form .taqyeem-save').click(function() {
		  
		  	//Disable Empty options
			  jQuery('form#taqyeem_form input, form#taqyeem_form textarea, form#taqyeem_form select').each(function() {
					if (!jQuery(this).val()) jQuery(this).attr("disabled", true );
			  });
			  
			  //var data = jQuery(this).serialize();
			  var data = jQuery('form#taqyeem_form').serialize();
			  
			//Enable Empty options
			  jQuery('form#taqyeem_form input:disabled, form#taqyeem_form textarea:disabled, form#taqyeem_form select:disabled').attr("disabled", false );

			  jQuery.post(ajaxurl, data, function(response) {
				  if(response == 1) {
					  jQuery('#save-alert').addClass('save-done');
					  t = setTimeout('fade_message()', 1000);
				  }
				else if( response == 2 ){
					location.reload();
				}
				else {
					 jQuery('#save-alert').addClass('save-error');
					  t = setTimeout('fade_message()', 1000);
				  }
			  });
			  return false;
		  });
		  
		});
		
		function fade_message() {
			jQuery('#save-alert').fadeOut(function() {
				jQuery('#save-alert').removeClass('save-done');
			});
			clearTimeout(t);
		}
	
	</script>
	<?php
		wp_print_scripts('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		do_action('admin_print_styles');
		
	}
	if( isset( $_REQUEST['action'] ) ){
		if( 'reset' == $_REQUEST['action']  && $current_page == 'taqyeem' && check_admin_referer('reset-action-taqyeem' , 'resetnonce') ) {
			global $taqyeem_default_data;
			taqyeem_save_settings( $taqyeem_default_data );
			header("Location: admin.php?page=taqyeem&reset=true");
			die;
		}
	}
}


/*-----------------------------------------------------------------------------------*/
# Options funtion
/*-----------------------------------------------------------------------------------*/
add_action('admin_menu', 'taqyeem_add_admin'); 
function taqyeem_option($value){
	global $options_fonts;
?>
	<div class="taqyeem-option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php echo $value['name']  ?></span>
	<?php
	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input  name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php  echo $value['id']; ?>" type="text" value="<?php echo taqyeem_get_option( $value['id'] ); ?>" />
			<?php if( isset( $value['extra_text'] ) ) : ?><span class="extra-text"><?php echo $value['extra_text'] ?></span><?php endif; ?>		
		<?php 
		break;
		
		case 'checkbox':
			if(taqyeem_get_option($value['id'])){$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input class="on-of" type="checkbox" name="taqyeem_options[<?php echo $value['id'] ?>]" id="<?php echo $value['id'] ?>" value="true" <?php echo $checked; ?> />			
		<?php	
		break;

		case 'radio':
		?>
			<div class="radio-contnet">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<label style="display:block; margin-bottom:8px;"><input name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( taqyeem_get_option( $value['id'] ) == $key) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
				<?php } ?>
			</div>
		<?php
		break;
		
		case 'select':
		?>
			<select name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( taqyeem_get_option( $value['id'] ) == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		<?php
		break;
		case 'textarea':
		?>
			<textarea style="direction:ltr; text-align:left" name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php echo taqyeem_get_option( $value['id'] );  ?></textarea>
		<?php
		break;
		case 'color':
		?>
			<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo taqyeem_get_option($value['id']) ; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="text" value="<?php echo taqyeem_get_option($value['id']) ; ?>" />
							
			<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php echo taqyeem_get_option($value['id']) ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>').val('#'+hex);
					}
				});
			</script>
		<?php
		break;
		
		case 'typography':
			$current_value = taqyeem_get_option($value['id']);
		?>
				<div style="clear:both;"></div>
				<div style="clear:both; padding:10px 14px; margin:0 -15px;">
					<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo $current_value['color'] ; ?>"></div></div>
					<input style="width:80px;;"  name="taqyeem_options[<?php echo $value['id']; ?>][color]" id="<?php  echo $value['id']; ?>color" type="text" value="<?php echo $current_value['color'] ; ?>" />
					
					<select name="taqyeem_options[<?php echo $value['id']; ?>][size]" id="<?php echo $value['id']; ?>[size]" style="width:55px;">
						<option value="" <?php if (!$current_value['size'] ) { echo ' selected="selected"' ; } ?>></option>
					<?php for( $i=1 ; $i<101 ; $i++){ ?>
						<option value="<?php echo $i ?>" <?php if (( $current_value['size']  == $i ) ) { echo ' selected="selected"' ; } ?>><?php echo $i ?></option>
					<?php } ?>
					</select>

					<select name="taqyeem_options[<?php echo $value['id']; ?>][font]" id="<?php echo $value['id']; ?>[font]" style="width:150px;">
					<?php foreach( $options_fonts as $font => $font_name ){ ?>
						<option value="<?php echo $font ?>" <?php if ( $current_value['font']  == $font ) { echo ' selected="selected"' ; } ?>><?php echo $font_name ?></option>
					<?php } ?>
					</select>
					
					<select name="taqyeem_options[<?php echo $value['id']; ?>][weight]" id="<?php echo $value['id']; ?>[weight]" style="width:96px;">
						<option value="" <?php if ( !$current_value['weight'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['weight']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
						<option value="bold" <?php if ( $current_value['weight']  == 'bold') { echo ' selected="selected"' ; } ?>>Bold</option>
						<option value="lighter" <?php if ( $current_value['weight'] == 'lighter') { echo ' selected="selected"' ; } ?>>Lighter</option>
						<option value="bolder" <?php if ( $current_value['weight'] == 'bolder') { echo ' selected="selected"' ; } ?>>Bolder</option>
						<option value="100" <?php if ( $current_value['weight'] == '100') { echo ' selected="selected"' ; } ?>>100</option>
						<option value="200" <?php if ( $current_value['weight'] == '200') { echo ' selected="selected"' ; } ?>>200</option>
						<option value="300" <?php if ( $current_value['weight'] == '300') { echo ' selected="selected"' ; } ?>>300</option>
						<option value="400" <?php if ( $current_value['weight'] == '400') { echo ' selected="selected"' ; } ?>>400</option>
						<option value="500" <?php if ( $current_value['weight'] == '500') { echo ' selected="selected"' ; } ?>>500</option>
						<option value="600" <?php if ( $current_value['weight'] == '600') { echo ' selected="selected"' ; } ?>>600</option>
						<option value="700" <?php if ( $current_value['weight'] == '700') { echo ' selected="selected"' ; } ?>>700</option>
						<option value="800" <?php if ( $current_value['weight'] == '800') { echo ' selected="selected"' ; } ?>>800</option>
						<option value="900" <?php if ( $current_value['weight'] == '900') { echo ' selected="selected"' ; } ?>>900</option>
					</select>
					
					<select name="taqyeem_options[<?php echo $value['id']; ?>][style]" id="<?php echo $value['id']; ?>[style]" style="width:100px;">
						<option value="" <?php if ( !$current_value['style'] ) { echo ' selected="selected"' ; } ?>></option>
						<option value="normal" <?php if ( $current_value['style']  == 'normal' ) { echo ' selected="selected"' ; } ?>>Normal</option>
						<option value="italic" <?php if ( $current_value['style'] == 'italic') { echo ' selected="selected"' ; } ?>>Italic</option>
						<option value="oblique" <?php if ( $current_value['style']  == 'oblique') { echo ' selected="selected"' ; } ?>>oblique</option>
					</select>
				</div>

				<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '#<?php echo $current_value['color'] ; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						jQuery('#<?php echo $value['id']; ?>colorSelector div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $value['id']; ?>color').val('#'+hex);
					}
				});
				</script>
		<?php
		break;
	}
	if( isset( $value['help'] ) ) : ?>
		<a class="taqyeem-help tooltip"  title="<?php echo $value['help'] ?>"></a>
		<?php endif; ?>
	</div>
			
<?php
}

function taq_save_button(){ ?>
		<div class="taqyeem-submit">
			<input type="hidden" name="action" value="test_taqyeem_data_save" />
			<input type="hidden" name="taq_security" value="<?php echo wp_create_nonce("test-taqyeem-data") ?>" />
			<input name="save" class="taqyeem-save button button-primary button-large" type="submit" value="<?php _e("Save Settings","taq") ?>" />    
		</div>
	<?php
}	

/*-----------------------------------------------------------------------------------*/
# Taqyeem Panel
/*-----------------------------------------------------------------------------------*/
function taqyeem_options() { 
?>
<div id="save-alert"></div>
<div class="taqyeem-panel">
	
	<div class="taqyeem-panel-tabs">
		<a href="http://tielabs.com/" target="_blank" class="taqyeem-logo"></a>
		<ul>
			<li class="tie-tabs start"><a href="#tab0"><span class="dashicons-before dashicons-dashboard taq-icon-menu"></span><?php _e('Start','taq'); ?></a> </li>
			<li class="tie-tabs general"><a href="#tab1"><span class="dashicons-before dashicons-admin-settings taq-icon-menu"></span><?php _e('General Settings','taq'); ?></a></li>
			<li class="tie-tabs styling"><a href="#tab3"><span class="dashicons-before dashicons-admin-appearance taq-icon-menu"></span><?php _e('Styling','taq'); ?></a></li>
			<li class="tie-tabs typography"><a href="#tab4"><span class="dashicons-before dashicons-editor-italic taq-icon-menu"></span><?php _e('Typography','taq'); ?></a></li>

			<?php do_action( 'tie_taqyeem_panel_tabs' ); ?>

			<li class="tie-tabs advanced"><a href="#tab5"><span class="dashicons-before dashicons-admin-tools taq-icon-menu"></span><?php _e('Advanced Settings','taq'); ?></a></li>
		</ul>
		<div class="clear"></div>
	</div> <!-- .taqyeem-panel-tabs -->
	
	<div class="taqyeem-panel-content">
	<form action="/" name="taqyeem_form" id="taqyeem_form">

	
		<div id="tab0" class="tab_content taq-tabs-wrap">
			<h2><?php _e('Welcome to TAQYEEM!','taq'); ?></h2>	<?php //taq_save_button() ?>	
			<div class="clear"></div>
			<h4><?php _e('Thank you for purchasing the TAQYEEM plugin, a WordPress plugin by <a href="http://tielabs.com/" target="_blank">TieLabs</a>.','taq'); ?></h4>
			<div class="clear"><br /></div>
			<div class="taqyeem_message_hint" style="float:left; width:42%;">
				<h3><?php _e('Need Help?','taq'); ?></h3>
				<ul>
					<li><a href="http://plugins.tielabs.com/docs/taqyeem/" target="_blank"><?php _e('Plugin Docs','taq'); ?></a></li>
					<li><a href="http://support.tielabs.com/forums/forum/wordpress-plugins/taqyeem" target="_blank"><?php _e('Support','taq'); ?></a></li>
				</ul>		
			</div>
			
			<div class="taqyeem_message_hint" style="float:left; width:42%;">
				<h3><?php _e('Rate Taqyeem','taq'); ?></h3>
				<?php _e('If you like Taqyeem, please don’t forget to <a target="_blank" href="http://themeforest.net/downloads?ref=tielabs">rate it</a>','taq'); ?>			
			</div>
			
			<div class="clear"></div>
			<div class="taqyeem-item more-addons">
				<h3><?php _e('Taqyeem Add-ons','taq'); ?></h3>
				<?php
				if( $addons_items = taqyeem_get_addons_rss( ) ){
					foreach ( $addons_items as $item ){
					?>
				<div class="taqyeem-option-item">
					<a href="<?php echo $item->get_link(); ?>" target="_blank"><img src="<?php echo html_entity_decode($item->get_description()); ?>"  alt="" /></a>
					<h4><a href="<?php echo $item->get_link(); ?>" target="_blank"><?php echo $item->get_title(); ?></a></h4>
					<div class="addon-desc"><?php echo html_entity_decode($item->get_content()); ?></div>
					<a href="<?php echo $item->get_link(); ?>" class="button button-primary button-small" target="_blank"><?php _e('GET IT NOW','taq'); ?></a>
				</div>
				<?php
					}
				} else {
					echo '<div class="taqyeem-option-item">Error: Unable to fetch the data.</div>';
				}
				?>
			</div>
			

		</div> <!-- Start -->
		
		<div id="tab1" class="taq-tabs-wrap">
			<h2><?php _e('General Settings','taq'); ?></h2> <?php taq_save_button() ?>

			<div class="taqyeem-item">
				<h3><?php _e('Show Review Boxes in the singular pages only ?','taq'); ?></h3>
				<?php
					taqyeem_option(
						array(	"name" => __('Enable in Single page only ?','taq'),
								"id" => "taq_singular",
								"type" => "checkbox"));
				?>
				<p class="taqyeem_message_hint"><?php _e("Enable it if your theme uses ' the_content() ' in homepage and archives pages and you want to show post review box in the single post page only !","taq"); ?></p>
			</div>
			
			<div class="taqyeem-item">
				<h3><?php _e('Who Is Allowed To Rate ?','taq'); ?></h3>
				<?php
					taqyeem_option(
						array( 	"name" => __('Who Is Allowed To Rate ?','taq'),
								"id" => "allowtorate",
								"type" => "radio",
								"options" => array( "none"=> __( 'No One !' ,'taq'),
													"both"=> __( 'Registered Users And Guests' ,'taq'),
													"guests"=> __( 'Guests Only' ,'taq'),
													"users"=> __( 'Registered Users Only' ,'taq') )));
				?>									
			</div>
			<div class="taqyeem-item">
				<h3><?php _e('Ratings Image','taq'); ?></h3>
				<?php
					taqyeem_option(
						array( 	"name" => __('Ratings Image','taq'),
								"id" => "rating_image",
								"type" => "radio",
								"options" => array( "stars"=>"<img src='".  plugins_url('admin/images/stars.png' , __FILE__) ."' alt='' />",
													"hearts"=>"<img src='".  plugins_url('admin/images/hearts.png' , __FILE__) ."' alt='' />",
													"thumbs"=>"<img src='".  plugins_url('admin/images/thumbs.png' , __FILE__) ."' alt='' />")));
				?>									
			</div>
		</div>
	
		<div id="tab3" class="tab_content taq-tabs-wrap">
			<h2><?php _e('Styling','taq'); ?></h2><?php taq_save_button() ?>	
		
			<div class="taqyeem-item">
				<h3><?php _e('Styling','taq'); ?></h3>
				<?php
					taqyeem_option(
						array(	"name" => __('Review Box Outer Border','taq'),
								"id" => "review_bg",
								"type" => "color"));
			
					taqyeem_option(
						array(	"name" => __('Review Box Header & Footer Background','taq'),
								"id" => "review_main_color",
								"type" => "color"));
								
					taqyeem_option(
						array(	"name" => __('Review items Background','taq'),
								"id" => "review_items_color",
								"type" => "color"));
						
					taqyeem_option(
						array(	"name" => __('Final Score and percentage bar Background','taq'),
								"id" => "review_secondery_color",
								"type" => "color"));
											
					taqyeem_option(
						array(	"name" => __('Links Color','taq'),
								"id" => "review_links_color",
								"type" => "color"));
			
					taqyeem_option(
						array(	"name" => __('Links Decoration','taq'),
								"id" => "review_links_decoration",
								"type" => "select",
								"options" => array( ""=> "Default" ,
													"none"=>"none",
													"underline"=>"underline",
													"overline"=>"overline",
													"line-through"=>"line-through" )));
		
					taqyeem_option(
						array(	"name" => __('Links Color on mouse over','taq'),
								"id" => "review_links_color_hover",
								"type" => "color"));
			
					taqyeem_option(
						array(	"name" => __('Links Decoration on mouse over','taq'),
								"id" => "review_links_decoration_hover",
								"type" => "select",
								"options" => array( ""=>"Default" ,
													"none"=>"none",
													"underline"=>"underline",
													"overline"=>"overline",
													"line-through"=>"line-through" )));
													
					do_action( 'tie_taqyeem_panel_styling' );
				?>
			</div>
		
			<div class="taqyeem-item">
				<h3><?php _e('Custom CSS','taq'); ?></h3>	
				<div class="taqyeem-option-item">
					<p><strong><?php _e('Global CSS :','taq'); ?></strong></p>
					<textarea id="tie_css" name="taqyeem_options[css]" style="width:100%" rows="7"><?php echo taqyeem_get_option('css');  ?></textarea>
				</div>	
				<div class="taqyeem-option-item">
					<p><strong><?php _e('Tablets CSS :','taq'); ?></strong> <?php _e('Width from 768px to 985px','taq'); ?></p>
					<textarea id="tie_css" name="taqyeem_options[css_tablets]" style="width:100%" rows="7"><?php echo taqyeem_get_option('css_tablets');  ?></textarea>
				</div>
				<div class="taqyeem-option-item">
					<p><strong><?php _e('Wide Phones CSS :','taq'); ?></strong> <?php _e('Width from 480px to 767px','taq'); ?></p>
					<textarea id="tie_css" name="taqyeem_options[css_wide_phones]" style="width:100%" rows="7"><?php echo taqyeem_get_option('css_wide_phones');  ?></textarea>
				</div>
				<div class="taqyeem-option-item">
					<p><strong><?php _e('Phones CSS :','taq'); ?></strong><?php _e('Width from 320px to 479px','taq'); ?></p>
					<textarea id="tie_css" name="taqyeem_options[css_phones]" style="width:100%" rows="7"><?php echo taqyeem_get_option('css_phones');  ?></textarea>
				</div>	
			</div>	

		</div> <!-- Styling -->

		<?php do_action( 'tie_taqyeem_panel_tabs_content' ); ?>

					
		<div id="tab4" class="tab_content taq-tabs-wrap">
			<h2><?php _e('Typography','taq'); ?></h2>	<?php taq_save_button() ?>	
			
			<div class="taqyeem-item">
				<h3><?php _e('Character sets','taq'); ?></h3>
				<p class="taqyeem_message_hint"><?php _e("<strong>Tip:</strong> If you choose only the languages that you need, you'll help prevent slowness on your webpage.","taq"); ?></p>
				<?php
					taqyeem_option(
						array(	"name" => __('Latin Extended','taq'),
								"id" => "typography_latin_extended",
								"type" => "checkbox"));

					taqyeem_option(
						array(	"name" => __('Cyrillic','taq'),
								"id" => "typography_cyrillic",
								"type" => "checkbox"));

					taqyeem_option(
						array(	"name" => __('Cyrillic Extended','taq'),
								"id" => "typography_cyrillic_extended",
								"type" => "checkbox"));
								
					taqyeem_option(
						array(	"name" => __('Greek','taq'),
								"id" => "typography_greek",
								"type" => "checkbox"));
								
					taqyeem_option(
						array(	"name" => __('Greek Extended','taq'),
								"id" => "typography_greek_extended",
								"type" => "checkbox"));
				?>
			</div>
			
			<div class="taqyeem-item">
				<h3><?php _e('Typography','taq'); ?></h3>
				<?php
					taqyeem_option(
						array( 	"name" => __('Review Box Title','taq'),
								"id" => "review_typography_title",
								"type" => "typography"));
	
					taqyeem_option(
						array( 	"name" => __('Review Items','taq'),
								"id" => "review_typography_items",
								"type" => "typography"));

					taqyeem_option(
						array( 	"name" => __('Review Summary','taq'),
								"id" => "review_typography_summery",
								"type" => "typography"));

					taqyeem_option(
						array( 	"name" => __('Total Score','taq'),
								"id" => "review_typography_total",
								"type" => "typography"));

					taqyeem_option(
						array( 	"name" => __('Final opinion Text','taq'),
								"id" => "review_typography_final",
								"type" => "typography"));

					taqyeem_option(
						array( 	"name" => __('User Rating','taq'),
								"id" => "review_user_rate",
								"type" => "typography"));
				?>
			</div>

		</div> <!-- Typography -->
		
		<div id="tab5" class="tab_content taq-tabs-wrap">
			<h2><?php _e('Advanced Settings','taq'); ?></h2>	<?php taq_save_button() ?>	

			<?php
				$current_taqyeem_options =  get_option( 'taqyeem_options' ) ;
			?>
			
			<div class="taqyeem-item">
				<h3><?php _e('Export','taq'); ?></h3>
				<div class="taqyeem-option-item">
					<textarea style="width:100%" rows="7"><?php echo $currentsettings = base64_encode( serialize( $current_taqyeem_options )); ?></textarea>
				</div>
			</div>
			<div class="taqyeem-item">
				<h3><?php _e('Import','taq'); ?></h3>
				<div class="taqyeem-option-item">
					<textarea id="taqyeem_import" name="taqyeem_import" style="width:100%" rows="7"></textarea>
				</div>
			</div>
	
		</div> <!-- Advanced -->

		
		<div class="taqyeem-footer">
			<?php taq_save_button() ?>
		</form>

			<form method="post">
				<div class="taqyeem-reset">
					<input type="hidden" name="resetnonce" value="<?php echo wp_create_nonce('reset-action-taqyeem'); ?>" />
					<input name="reset" class="taqyeem-reset-button button button-primary button-large" type="submit" onClick="if(confirm('<?php _e('All settings will be reset .. Are you sure ?','taq'); ?>')) return true ; else return false; " value="<?php _e('Reset Settings','taq'); ?>" />
					<input type="hidden" name="action" value="reset" />
				</div>
			</form>
		</div>

	</div><!-- .taqyeem-panel-content -->
</div><!-- .taqyeem-panel -->

<?php
}
?>