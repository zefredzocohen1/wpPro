<?php
/*-----------------------------------------------------------------------------------*/
# Reviews Posts Options
/*-----------------------------------------------------------------------------------*/
add_action("admin_init", "taqyeem_post_init");
function taqyeem_post_init(){
	add_meta_box("taqyeem_post_options", TIE_Plugin ." - ". __( 'Review Options' , 'taq' ), "taqyeem_post_options", "post", "normal", "high");
	add_meta_box("taqyeem_post_options", TIE_Plugin ." - ". __( 'Review Options' , 'taq' ), "taqyeem_post_options", "page", "normal", "high");
	
	//Support Custom Post Types
	$post_types= get_post_types( array( '_builtin' => false, 'public' => true ) ,'names'); 

	foreach ($post_types as $post_type ) {
		add_meta_box("taqyeem_post_options", TIE_Plugin ." - ". __( 'Review Options' , 'taq' ), "taqyeem_post_options", $post_type , "normal", "high");
	}
}

function taqyeem_post_options(){
	global $post ;
	$get_meta = get_post_custom($post->ID);
		
	if(isset( $get_meta["taq_review_criteria"][0] ))	
	$taq_review_criteria = unserialize($get_meta["taq_review_criteria"][0]);
	
?>
	<div class="taqyeem-item" style="border:0 none; box-shadow: none;">
	
		<input type="hidden" name="taqyeem_hidden_flag" value="true" />
		
		<script type="text/javascript">
			jQuery(function() {
				jQuery( "#taqyeem-reviews-list" ).sortable({placeholder: "taqyeem-state-highlight"});
			});
		</script>
<?php

			taqyeem_options_items(				
				array(	"name" => __('Review Box Position','taq'),
						"id" => "taq_review_position",
						"type" => "select",
						"options" => array( "" => __('Disable','taq') ,
											"top" => __('Top of the post','taq') ,
											"bottom" => __('Bottom of the post','taq'),
											"custom" => __('Custom position','taq'))));
			?>
			<p id="taqyeem_custom_position_hint" class="taqyeem_message_hint">
			<?php printf( __( 'Use <strong>[taq_review]</strong> shortcode to place the review box in any place within post content or use <strong> %s </strong> Widget .', 'taq' ),  TIE_Plugin .' - Review Box' );	?>
			</p>
			<div id="taq-reviews-options">
			<?php do_action( 'tie_taqyeem_before_review_options' ); ?>
			<?php
			taqyeem_options_items(				
				array(	"name" => __('Review Style','taq'),
						"id" => "taq_review_style",
						"type" => "radio",
						"options" => array( "stars" => __('Image','taq') ,"percentage" => __('Percentage','taq'), "points" => __('Points','taq'))));
											
			taqyeem_options_items(				
				array(	"name" => __('Review Box Title','taq'),
						"id" => "taq_review_title",
						"type" => "text"));
						
			taqyeem_options_items(				
				array(	"name" => __('Text appears under the total score','taq'),
						"id" => "taq_review_total",
						"type" => "text"));
				
			do_action( 'tie_taqyeem_before_review_summary_option' ); 
			
			taqyeem_options_items(				
				array(	"name" => __('Review Summary','taq'),
						"id" => "taq_review_summary",
						"type" => "textarea"));
			?>
			
			<?php do_action( 'tie_taqyeem_after_review_options' ); ?>

			<input id="add_review_criteria" type="button" class="button" value="<?php  _e( 'Add New Review Criteria' , 'taq' ) ?>">
		
			<?php do_action( 'tie_taqyeem_before_review_criteria' ); ?>
		
		<div class="clear"></div>
			
		<ul id="taqyeem-reviews-list">
		<?php $i = 0;
		if(!empty($taq_review_criteria) && is_array($taq_review_criteria) ){
			foreach( $taq_review_criteria as $taq_review ){  ; $i++; ?>
			<li class="taqyeem-option-item taqyeem-review-item">				
				<span class="label"><?php  _e( 'Review Criteria' , 'taq' ) ?></span>
				<input name="taq_review_criteria[<?php echo $i ?>][name]" type="text" value="<?php if( !empty($taq_review['name'] ) ) echo $taq_review['name'] ?>" />
				<div class="clear"></div>
				<span class="label"><?php  _e( 'Criteria Score' , 'taq' ) ?></span>
				<div id="criteria<?php echo $i ?>-slider"></div>
				<input type="text" id="criteria<?php echo $i ?>" value="<?php if( !empty($taq_review['score']) ) echo $taq_review['score']; else echo 0; ?>" name="taq_review_criteria[<?php echo $i ?>][score]" style="width:40px; opacity: 0.7;" />
				<a class="del-review" title="<?php  _e( 'Delete' , 'taq' ) ?>"><?php  _e( 'Delete' , 'taq' ) ?></a>
				<script>
				  jQuery(document).ready(function() {
					jQuery("#criteria<?php echo $i ?>-slider").slider({
						range: "min",
						min: 0,
						max: 100,
						value: <?php if( !empty($taq_review['score']) ) echo $taq_review['score']; else echo 0; ?>,
						slide: function(event, ui) {
							jQuery('#criteria<?php echo $i ?>').attr('value', ui.value );
						}
						});
					});
				</script>
			</li>	

				<?php
			}
		}
			?>
		</ul>
			<script>var taqNextReview = <?php echo $i+1 ?> ;</script>
		</div>	
	</div>	

  <?php
}


add_action('save_post', 'taqyeem_save_post');
function taqyeem_save_post( $post_id ){
	global $post;
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;
		
	if (isset($_POST['taqyeem_hidden_flag'])) {
	
		$custom_meta_fields = array(
			'taq_review_title',
			'taq_review_position',
			'taq_review_style',
			'taq_review_summary',
			'taq_review_total');
		
		if( has_filter('tie_taqyeem_save_review_fields') ) {
			$custom_meta_fields = apply_filters('tie_taqyeem_save_review_fields', $custom_meta_fields );
		}
	
		foreach( $custom_meta_fields as $custom_meta_field ){
			if(isset($_POST[$custom_meta_field]) )
				update_post_meta($post_id, $custom_meta_field, htmlspecialchars(stripslashes($_POST[$custom_meta_field])) );
			else
				delete_post_meta($post_id, $custom_meta_field);
		}
		
		if( isset($_POST['taq_review_criteria']) )
			update_post_meta($post_id, 'taq_review_criteria', $_POST['taq_review_criteria']);
		else
			delete_post_meta($post_id, 'taq_review_criteria');
		
		$get_meta = get_post_custom($post_id);

		$total_counter = $score = 0;
		if( isset( $get_meta['taq_review_criteria'][0] ))
		$criterias = unserialize( $get_meta['taq_review_criteria'][0] );
		
		if( !empty($criterias) ){
			foreach( $criterias as $criteria){ 
				if( $criteria['name'] && $criteria['score'] && is_numeric( $criteria['score'] )){
					if( $criteria['score'] > 100 ) $criteria['score'] = 100;
					if( $criteria['score'] < 0 ) $criteria['score'] = 1;
						
				$score += $criteria['score'];
				$total_counter ++;
				}
			}
			if( !empty( $score ) && !empty( $total_counter ) )
				$total_score =  $score / $total_counter ;
			
			update_post_meta($post_id, 'taq_review_score', $total_score);
		}
	}
}


/*********************************************************/
function taqyeem_options_items($value){
	global $post;
?>
	<div class="taqyeem-option-item" id="<?php echo $value['id'] ?>-item">
		<span class="label"><?php echo $value['name']  ?></span>
	<?php
		$id = $value['id'];
		$get_meta = get_post_custom($post->ID);
		if( isset( $get_meta[$id][0] ) )
			$current_value = $get_meta[$id][0];
			
	switch ( $value['type'] ) {
	
		case 'text': ?>
			<input  name="<?php echo $value['id']; ?>" id="<?php  echo $value['id']; ?>" type="text" value="<?php if( !empty( $current_value ) ) echo $current_value ?>" />
		<?php 
		break;
		case 'select':
		?>
			<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php foreach ($value['options'] as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( isset($current_value) && $current_value == $key) { echo ' selected="selected"' ; } ?>><?php _e( $option , 'taq' ) ?></option>
				<?php } ?>
			</select>
		<?php
		break;
		case 'checkbox':
			if( isset($current_value) ) {$checked = "checked=\"checked\"";  } else{$checked = "";} ?>
				<input class="on-of" type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />			
		<?php	
		break;

		case 'radio':
		?>
			<div class="radio-contnet">
				<?php $i = 0;
				foreach ($value['options'] as $key => $option) { ?>
				<label style="display:block; margin-bottom:8px;"><input name="<?php echo $value['id']; ?>" type="radio" value="<?php echo $key ?>" <?php if ( ( !empty($current_value) && $current_value == $key ) ||  ( empty($current_value) && $i == 0 ) ) { echo ' checked="checked"' ; } ?>> <?php echo $option; ?></label>
				<?php $i++; } ?>
			</div>
		<?php
		break;
		case 'textarea':
		?>
			<textarea style="width:430px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="textarea" cols="100%" rows="3" tabindex="4"><?php if( !empty( $current_value ) ) echo $current_value  ?></textarea>
		<?php
		break;
		case 'color':
		?>
			<div id="<?php echo $value['id']; ?>colorSelector" class="color-pic"><div style="background-color:<?php echo taqyeem_get_option($value['id']) ; ?>"></div></div>
			<input style="width:80px; margin-right:5px;"  name="taqyeem_options[<?php echo $value['id']; ?>]" id="<?php echo $value['id']; ?>" type="text" value="<?php if( !empty( $current_value ) ) echo $current_value  ?>" />
							
			<script>
				jQuery('#<?php echo $value['id']; ?>colorSelector').ColorPicker({
					color: '<?php if( !empty( $current_value ) ) echo $current_value  ?>',
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
	} ?>
	</div>
<?php
}