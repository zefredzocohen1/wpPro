<?php
/*
	Plugin Name: Taqyeem
	Plugin URI: http://codecanyon.net/item/taqyeem-wordpress-review-plugin/4558799?ref=tielabs
	Description: WordPress Review Plugin -  adding lovely ratings and reviews to your posts, pages, and custom post types.
	Author: TieLabs
	Version: 2.1.5
	Author URI: http://tielabs.com/
*/

require_once ( 'taqyeem-panel.php' );
require_once ( 'taqyeem-posts.php' );
require_once ( 'taqyeem-widgets.php' );

define ('TIE_Plugin' , 'Taqyeem' );
define ('TIE_Plugin_ver' , '2.1.0' );

$taqyeem_default_data = Array(
	"taqyeem_options"	=> Array(
		'allowtorate' 		=> 		'both',
		'rating_image' 		=> 		'stars'
	)
);


/*-----------------------------------------------------------------------------------*/
# Load Text Domain
/*-----------------------------------------------------------------------------------*/
add_action('plugins_loaded', 'taqyeem_init');
function taqyeem_init() {
	load_plugin_textdomain( 'taq' , false, dirname( plugin_basename( __FILE__ ) ).'/languages' ); 
}


/*-----------------------------------------------------------------------------------*/
# Store Defaults settings
/*-----------------------------------------------------------------------------------*/
if ( is_admin() && isset($_GET['activate'] ) && $pagenow == 'plugins.php' ) {
	global $taqyeem_default_data;
	if( !get_option('taq_active') ){
		taqyeem_save_settings( $taqyeem_default_data );
		update_option( 'taq_active' , TIE_Plugin_ver );
	}  
}


/*-----------------------------------------------------------------------------------*/
# Get plugin's Settings
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_option( $name ) {
	$get_options = get_option( 'taqyeem_options' );
	
	if( !empty( $get_options[$name] )){
		return $get_options[$name];
	}
	return false ;
}


/*-----------------------------------------------------------------------------------*/
# Register and Enquee plugin's styles and scripts
/*-----------------------------------------------------------------------------------*/
function taqyeem_scripts_styles(){
	if( !is_admin()){
		wp_register_style ( 'taqyeem-style' , plugins_url('style.css' , __FILE__) ) ;
		wp_register_script( 'taqyeem-main', plugins_url('js/tie.js' , __FILE__), array( 'jquery' ) , false , false );  
		wp_enqueue_script ( 'taqyeem-main' );
		wp_enqueue_style  ( 'taqyeem-style');
	}	
}
add_action( 'init', 'taqyeem_scripts_styles' );


/*-----------------------------------------------------------------------------------*/
# Get Reviews Box 
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_review( $position = "review-top" ){

	if( !is_singular() && taqyeem_get_option('taq_singular') ) return false;
	
	global $post ;
	$post_description 	= '';
	$get_meta 			= get_post_custom($post->ID);

	if( !empty($get_meta['taq_review_criteria'][0]) ){
		$criterias = unserialize( $get_meta['taq_review_criteria'][0] );
	}

	$title 				= $get_meta['taq_review_title'][0] ;
	$summary 			= htmlspecialchars_decode( $get_meta['taq_review_summary'][0] );
	$short_summary 		= $get_meta['taq_review_total'][0] ;
	$style 				= $get_meta['taq_review_style'][0];
	$post_description 	= strip_shortcodes( apply_filters('taqyeem_exclude_content', $post->post_content) );

	$image_style = taqyeem_get_option('rating_image');
	if( empty($image_style) ){
		$image_style ='stars';
	}
	
	$total_score = $total_counter = $score = $ouput = 0;
	
	$users_rate = '';
	if( taqyeem_get_option('allowtorate') != 'none' ){
		$users_rate = taqyeem_get_user_rate();
	}
	
	if( $style == 'percentage' ) $review_class = ' review-percentage'; elseif( $style == 'points' ) $review_class = ' review-percentage'; else $review_class = ' review-stars';
	$ouput = '
<div class="review_wrap" itemscope itemtype="http://schema.org/Review">
	<div style="display:none" itemprop="reviewBody">'. wp_trim_words(  $post_description , 100 ) .'</div>
	<div style="display:none" class="name entry-title" itemprop="name">'. get_the_title() .'</div>
	<div style="display:none" class="entry-title" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing"><span itemprop="name">'. get_the_title() .'</span></div>
	<div style="display:none" class="updated">'. get_the_time( 'Y-m-d' ) .'</div>
	<div style="display:none" class="vcard author" itemprop="author" itemscope itemtype="http://schema.org/Person"><strong class="fn" itemprop="name">'. get_the_author() .'</strong></div>
			
	<meta itemprop="datePublished" content="'. get_the_time( 'Y-m-d' ) .'" />
	
	<div id="review-box" class="review-box '. $position.$review_class.'">';
	if( !empty($title) ){
		$ouput .= '<h2 class="review-box-header">'.$title.'</h2>';
	}
	
	if( !empty($criterias) && is_array($criterias) ){
		foreach( $criterias as $criteria){ 
			if( $criteria['name'] && is_numeric( $criteria['score'] )){
				if( $criteria['score'] > 100 ) $criteria['score'] = 100;
				if( $criteria['score'] < 0 ) $criteria['score'] = 0;
			
			$score += $criteria['score'];
			$total_counter ++;
			if( $style == 'percentage' ): $ouput .= '
		<div class="review-item">
			<span><h5>'. $criteria['name'] .' - '. $criteria['score'] .'%</h5><span data-width="'. $criteria['score'] .'"></span></span>
		</div>';
		elseif( $style == 'points' ):   $point =  $criteria['score']/10;
		$ouput .= '	<div class="review-item">
			<span><h5>'. $criteria['name'] .' - '. $point.'</h5><span data-width="'. $criteria['score'] .'"></span></span>
		</div>';
		else:
		$ouput .= '<div class="review-item">
			<h5>'. $criteria['name'] .'</h5>
			<span class="post-large-rate '.$image_style.'-large"><span style="width:'. $criteria['score'] .'%"></span></span>
		</div>';
		endif;
			}
		}
	}
	
	if( has_filter('tie_taqyeem_before_summary') ) {
		$ouput = apply_filters('tie_taqyeem_before_summary', $ouput, $get_meta );
	}
			
		if( !empty( $score ) && !empty( $total_counter ) )
			$total_score =  $score / $total_counter ;
		$ouput .= '
		<div class="review-summary" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
		<meta itemprop="worstRating" content = "1" />
		<meta itemprop="bestRating" content = "100" />
		<span class="rating points" style="display:none"><span class="rating points" itemprop="ratingValue">'. round($total_score) .'</span></span>';
		if( $style == 'percentage' ): 
		$ouput .= '
			<div class="review-final-score">
				<h3>'. round($total_score) .'<span>%</span></h3>
				<h4>'. $short_summary .'</h4>
			</div>';
		elseif( $style == 'points' ): $total_score = $total_score/10 ;
		$ouput .= '
			<div class="review-final-score">
				<h3>'. round($total_score,1).'</h3>
				<h4>'. $short_summary .' </h4>
			</div>';
		else:
		$ouput .= '
			<div class="review-final-score">
				<span title="'. $short_summary .'" class="post-large-rate '.$image_style.'-large"><span style="width:'. $total_score .'%"></span></span>
				<h4>'. $short_summary .'</h4>
			</div>';
		endif;
		$ouput .= '
		<div class="review-short-summary" itemprop="description">';
		
			if( has_filter('tie_taqyeem_before_summary_text') ) {
				$ouput = apply_filters('tie_taqyeem_before_summary_text', $ouput, $get_meta );
			}
			
		if( !empty( $summary ) ){ 
			$ouput .= '<p>'. $summary .'</p>';
		}
			
		if( has_filter('tie_taqyeem_after_summary_text') ) {
			$ouput = apply_filters('tie_taqyeem_after_summary_text', $ouput, $get_meta );
		}
			
		$ouput .= '</div>';
		
		$ouput .= '
		</div> ';
		
		if( has_filter('tie_taqyeem_before_user_rating') ) {
			$ouput = apply_filters('tie_taqyeem_before_user_rating', $ouput, $get_meta );
		}
		
 		$ouput .= $users_rate;
		
		if( has_filter('tie_taqyeem_after_user_rating') ) {
			$ouput = apply_filters('tie_taqyeem_after_user_rating', $ouput, $get_meta );
		}
		$ouput .='
		
	</div>

</div>';
	
	return $ouput ;
}


/*-----------------------------------------------------------------------------------*/
# Get Reviews Box 
/*-----------------------------------------------------------------------------------*/
function taqyeem_insert_review($content) {
	global $post;

	if( is_feed() ) return $content;
	
	$get_meta = get_post_custom($post->ID);
	if( !empty( $get_meta['taq_review_position'][0] ) ){
		$review_position = $get_meta['taq_review_position'][0];
	}

	$output = $output2 = '';
	if( !empty( $review_position ) &&  $review_position == 'top'){
		$output  = taqyeem_get_review('review-top');
	}

	if( !empty( $review_position ) &&  $review_position == 'bottom' ){
		$output2 = taqyeem_get_review('review-bottom');
	}

	return $output.$content.$output2;
}
add_filter ('the_content', 'taqyeem_insert_review');


/*-----------------------------------------------------------------------------------*/
# Remove the Reviews Box code from the excerpt
/*-----------------------------------------------------------------------------------
function taqyeem_remove_review_excerpt( $text = '' ) {
	remove_filter( 'the_content', 'taqyeem_insert_review' );
}
add_filter( 'get_the_excerpt', 'taqyeem_remove_review_excerpt' );
*/


/*-----------------------------------------------------------------------------------*/
# Users rate posts function
/*-----------------------------------------------------------------------------------*/
add_action('wp_ajax_nopriv_taqyeem_rate_post', 'taqyeem_rate_post');
add_action('wp_ajax_taqyeem_rate_post', 'taqyeem_rate_post');
function taqyeem_rate_post(){
	global $user_ID;
	
	if( taqyeem_get_option('allowtorate') == 'none' || ( !empty($user_ID) && taqyeem_get_option('allowtorate') == 'guests' ) ||	( empty($user_ID) && taqyeem_get_option('allowtorate') == 'users' ) ){ 
		return false ;
	}else{	
		$count 	= $rating = $rate = 0;
		$postID = $_REQUEST['post'];
		$rate 	= abs($_REQUEST['value']);

		if( $rate > 5 ){
			$rate = 5;
		}
		
		if( is_numeric( $postID ) ){
			$rating = get_post_meta($postID, 'tie_user_rate', true);
			$count 	= get_post_meta($postID, 'tie_users_num', true);

			if( empty( $count ) || $count == '' ){
				$count = 0;
			}
			
			$count++;
			$total_rate = $rating + $rate;
			$total = round($total_rate/$count , 2);
			if ( $user_ID ) {
				$user_rated = get_the_author_meta( 'tie_rated', $user_ID  );

				if( empty($user_rated) ){
					$user_rated[$postID] = $rate;
					
					update_user_meta( $user_ID, 'tie_rated', 		$user_rated );
					update_post_meta( $postID, 	'tie_user_rate', 	$total_rate );
					update_post_meta( $postID, 	'tie_users_num', 	$count );
					
					echo $total;
				}
				else{
					if( !array_key_exists($postID , $user_rated) ){
						$user_rated[$postID] = $rate;
						update_user_meta( $user_ID,	'tie_rated', 		$user_rated );
						update_post_meta( $postID, 	'tie_user_rate', 	$total_rate );
						update_post_meta( $postID, 	'tie_users_num', 	$count );
						
						echo $total;
					}
				}
			}else{
				$user_rated = $_COOKIE["tie_rate_".$postID];
				if( empty($user_rated) ){
					setcookie( 'tie_rate_'.$postID , $rate , time()+31104000 , '/');
					update_post_meta( $postID, 'tie_user_rate', $total_rate );
					update_post_meta( $postID, 'tie_users_num', $count );
				}
			}
		}
	}
    die;
}


/*-----------------------------------------------------------------------------------*/
# Get user rate result
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_user_rate(){
	global $post , $user_ID; 
	$disable_rate = false ;

	if( taqyeem_get_option('allowtorate') == 'none' || ( !empty($user_ID) && taqyeem_get_option('allowtorate') == 'guests' ) ||	( empty($user_ID) && taqyeem_get_option('allowtorate') == 'users' ) ){
		$disable_rate = true ;
	}

	if( !empty($disable_rate) ){
		$no_rate_text = __( 'No Ratings Yet !' , 'taq' );
		$rate_active = false ;
	}
	else{
		$no_rate_text = __( 'Be the first one !' , 'taq' );
		$rate_active = ' taq-user-rate-active' ;
	}
		
	$image_style = taqyeem_get_option('rating_image');
	if( empty($image_style) ){
		$image_style ='stars';
	}
	
	$rate 	= get_post_meta( $post->ID , 'tie_user_rate', true );
	$count 	= get_post_meta( $post->ID , 'tie_users_num', true );

	if( !empty($rate) && !empty($count) ){
		$total = (($rate/$count)/5)*100;
		$totla_users_score = round($rate/$count,2);
	}else{
		$totla_users_score = $total = $count = 0;
	}
	
	if ( $user_ID ) {
		$user_rated = get_the_author_meta( 'tie_rated' , $user_ID ) ;
		if( !empty($user_rated) && is_array($user_rated) && array_key_exists($post->ID , $user_rated) ){
			$user_rate = round( ($user_rated[$post->ID]*100)/5 , 2);
			return $output = '<div class="user-rate-wrap"><span class="user-rating-text"><strong>'.__( "Your Rating:" , "taq" ) .' </strong> <span class="taq-score">'.$user_rated[$post->ID].'</span> <small>( <span class="taq-count">'.$count.'</span> '.__( "votes" , "taq" ) .')</small> </span><div data-rate="'. $user_rate .'" class="user-rate rated-done" title=""><span class="user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $user_rate .'%"></span></span></div><div class="taq-clear"></div></div>';
		}
	}elseif( !empty( $_COOKIE["tie_rate_".$post->ID] ) ) {

		$user_rate = $_COOKIE["tie_rate_".$post->ID];
		
		if( !empty($user_rate) ){
			return $output = '<div class="user-rate-wrap"><span class="user-rating-text"><strong>'.__( "Your Rating:" , "taq" ) .' </strong> <span class="taq-score">'.$user_rate.'</span> <small>( <span class="taq-count">'.$count.'</span> '.__( "votes" , "taq" ) .')</small> </span><div class="user-rate rated-done" title=""><span class="user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. (($user_rate*100)/5) .'%"></span></span></div><div class="taq-clear"></div></div>';
		}
		
	}

	if( $total == 0 && $count == 0 ){
		return $output = '<div class="user-rate-wrap"><span class="user-rating-text"><strong>'.__( "User Rating:" , "taq" ) .' </strong> <span class="taq-score"></span> <small>'.$no_rate_text.'</small> </span><div data-rate="'. $total .'" data-id="'.$post->ID.'" class="user-rate'.$rate_active.'"><span class="user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $total .'%"></span></span></div><div class="taq-clear"></div></div>';
	}else{
		return $output = '<div class="user-rate-wrap"><span class="user-rating-text"><strong>'.__( "User Rating:" , "taq" ) .' </strong> <span class="taq-score">'.$totla_users_score.'</span> <small>( <span class="taq-count">'.$count.'</span> '.__( "votes" , "taq" ) .')</small> </span><div data-rate="'. $total .'" data-id="'.$post->ID.'" class="user-rate'.$rate_active.'"><span class="user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $total .'%"></span></span></div><div class="taq-clear"></div></div>';
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Totla Reviews Score
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_score( $post_id = false , $size = 'small' ){
	global $post ;
	
	$total_score 	= 0;
	$rate_size 		= 'small';

	if( empty($post_id) ){
		$post_id = $post->ID;
	}
	
	if( $size == 'large' ){
		$rate_size = 'large';
	}
		
	$image_style = taqyeem_get_option('rating_image');
	if( empty($image_style) ){
		$image_style ='stars';
	}
	
	$get_meta = get_post_custom( $post_id );

	if( !empty( $get_meta['taq_review_position'][0] ) ){
		$short_summary = $get_meta['taq_review_total'][0];

		if( !empty( $get_meta['taq_review_score'][0] ) ){
			$total_score = $get_meta['taq_review_score'][0];
		}
		?>
		<span title="<?php echo $short_summary ?>" class="post-single-rate post-<?php echo $rate_size; ?>-rate <?php echo $image_style ?>-<?php echo $rate_size; ?>"><span style="width:<?php echo $total_score ?>%"></span></span>
		<?php 
	}
}


/*-----------------------------------------------------------------------------------*/
# Get Get Posts Reviews
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_reviews( $num = 5 , $order = 'latest' , $avatar = false , $categories = 'all' ){
	global $post;

	if( $order == 'random'){
		$orderby ="rand";
	}elseif( $order == 'best'){
		$orderby ="meta_value";
	}else{
		$orderby = "date";
	}

	$taq_args = array(
		'posts_per_page'	=> $num,
		'meta_key'			=> 'taq_review_score',
		'orderby'			=> $orderby,
		'post_status'		=> 'publish',
		'meta_query'		=> array(
			array(
				'key'     => 'taq_review_position',
				'value'   => array( 'top', 'bottom', 'custom' ),
				'compare' => 'IN',
			)
		)
	);

	if( $categories != 'all'){
		$taq_args['cat'] = $categories;
	}
	
	$cat_query = new WP_Query( $taq_args ); ?>
	<ul class="reviews-posts">
	<?php
	if( $cat_query->have_posts() ) :
	while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
		<li>
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $avatar != false ) : ?>			
		<div class="review-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'taq' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php echo $image_url = wp_get_attachment_image( get_post_thumbnail_id($post->ID) , $avatar );   ?>
			</a>
		</div><!-- review-thumbnail /-->
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'taq' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a></h3> <?php taqyeem_get_score(); ?></li>
	<?php endwhile;
	else: ?>
	<li><?php _e('No Posts' , 'taq') ?></li>
	<?php
	endif;
	?>
	</ul>
<?php
}


/*-----------------------------------------------------------------------------------*/
# Get Get Post types Reviews
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_types_reviews( $num = 5 , $order = 'latest' , $avatar = false , $types = 'any' ){

	if( $order == 'rand'){
		$orderby ="rand";
	}elseif( $order == 'best'){
		$orderby ="meta_value";
	}else{
		$orderby = "date";
	}
	
	$taq_args = array(
		'posts_per_page'	=> $num,
		'meta_key'			=> 'taq_review_score',
		'orderby'			=> $orderby,
		'post_type'			=> $types, 
		'post_status'		=> 'publish',
		'meta_query'		=> array(
			array(
				'key'     => 'taq_review_position',
				'value'   => array( 'top', 'bottom', 'custom' ),
				'compare' => 'IN',
			)
		)
	);
	
	$cat_query = new WP_Query( $taq_args ); ?>
	<ul class="reviews-posts">
	<?php
	if( $cat_query->have_posts() ) :
	while ( $cat_query->have_posts() ) : $cat_query->the_post()?>
		<li>
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() && $avatar != false ) : ?>			
		<div class="review-thumbnail">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'taq' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
				<?php echo $image_url = wp_get_attachment_image( get_post_thumbnail_id($post->ID) , $avatar );   ?>
			</a>
		</div><!-- review-thumbnail /-->
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'taq' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a></h3> <?php taqyeem_get_score(); ?></li>
	<?php endwhile;
	else: ?>
	<li><?php _e('No Posts' , 'taq') ?></li>
	<?php
	endif;
	?>
	</ul>
<?php
}


/*-----------------------------------------------------------------------------------*/
# Shortcode to disapy the review box
/*-----------------------------------------------------------------------------------*/
function taqyeem_shortcode_review( $atts, $content = null ) {
	$output = taqyeem_get_review( 'review-bottom' );
	return $output; 
}
add_shortcode('taq_review', 'taqyeem_shortcode_review');


/*-----------------------------------------------------------------------------------*/
# Enqueue Fonts From Google Webfonts
/*-----------------------------------------------------------------------------------*/
function taqyeem_enqueue_font ( $got_font) {
	if ($got_font) {
	
		if( taqyeem_get_option('typography_latin_extended') || taqyeem_get_option('typography_cyrillic') ||
		taqyeem_get_option('typography_cyrillic_extended') || taqyeem_get_option('typography_greek') ||
		taqyeem_get_option('typography_greek_extended') ){
		
			$char_set = '&subset=latin';
			if( taqyeem_get_option('typography_latin_extended') ){
				$char_set .= ',latin-ext';
			}
			if( taqyeem_get_option('typography_cyrillic') ){
				$char_set .= ',cyrillic';
			}
			if( taqyeem_get_option('typography_cyrillic_extended') ){
				$char_set .= ',cyrillic-ext';
			}
			if( taqyeem_get_option('typography_greek') ){
				$char_set .= ',greek';
			}
			if( taqyeem_get_option('typography_greek_extended') ){
				$char_set .= ',greek-ext';
			}
		}
		
		$font_pieces 	= explode(":", $got_font);
		$font_name 		= $font_pieces[0];
		$font_name 		= str_replace (" ","+", $font_pieces[0] );
				
		$font_variants 	= $font_pieces[1];
		$font_variants 	= str_replace ("|",",", $font_pieces[1] );
				
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style( $font_name , $protocol.'://fonts.googleapis.com/css?family='.$font_name . ':' . $font_variants.$char_set );
	}
}


/*-----------------------------------------------------------------------------------*/
# Get The Font Name
/*-----------------------------------------------------------------------------------*/
function taqyeem_get_font ( $got_font ) {
	if ($got_font) {
		$font_pieces 	= explode(":", $got_font);
		$font_name 		= $font_pieces[0];
		return $font_name;
	}
}


/*-----------------------------------------------------------------------------------*/
# Typography Elements Array
/*-----------------------------------------------------------------------------------*/
$taqyeem_typography = array(
	"#review-box h2.review-box-header"													=>		"review_typography_title",
	"#review-box .review-item h5,	#review-box.review-percentage .review-item h5"		=>		"review_typography_items",
	"#review-box .review-short-summary, #review-box .review-short-summary p"			=>		"review_typography_summery",
	"#review-box .review-final-score h3"												=>		"review_typography_total",
	"#review-box .review-final-score h4"												=>		"review_typography_final",
	".user-rate-wrap, #review-box strong"												=>		"review_user_rate"
);
	
	
/*-----------------------------------------------------------------------------------*/
# Get Custom Typography
/*-----------------------------------------------------------------------------------*/
add_action('wp_enqueue_scripts', 'taqyeem_typography');
function taqyeem_typography(){
	global $taqyeem_typography;
	foreach( $taqyeem_typography as $selector => $value){
		$option = taqyeem_get_option( $value );
		taqyeem_enqueue_font( $option['font'] ) ;
	}
}


/*-----------------------------------------------------------------------------------*/
# Taqyeem Wp Head
/*-----------------------------------------------------------------------------------*/
add_action('wp_head', 'taqyeem_wp_head');
function taqyeem_wp_head() {
	global $taqyeem_typography; 
	?>
<script type='text/javascript'>
/* <![CDATA[ */
var taqyeem = {"ajaxurl":"<?php echo admin_url('admin-ajax.php'); ?>" , "your_rating":"<?php _e( 'Your Rating:' , 'taq' ) ?>"};
/* ]]> */
</script>
<style type="text/css" media="screen"> 
<?php if( taqyeem_get_option( 'review_bg' ) ): ?>
.review-final-score {border-color: <?php echo taqyeem_get_option( 'review_bg' );?>;}
.review-box  {background-color:<?php echo taqyeem_get_option( 'review_bg' );?> ;}
<?php endif; ?>
<?php if( taqyeem_get_option( 'review_main_color' ) ): ?>
#review-box h2.review-box-header , .user-rate-wrap  {background-color:<?php echo taqyeem_get_option( 'review_main_color' );?> ;}
<?php endif; ?>
<?php if( taqyeem_get_option( 'review_items_color' ) ): ?>
.review-stars .review-item , .review-percentage .review-item span, .review-summary  {background-color:<?php echo taqyeem_get_option( 'review_items_color' );?> ;}
<?php endif; ?>
<?php if( taqyeem_get_option( 'review_secondery_color' ) ): ?>
.review-percentage .review-item span span,.review-final-score {background-color:<?php echo taqyeem_get_option( 'review_secondery_color' );?> ;}
<?php endif; ?>
<?php if( taqyeem_get_option( 'review_links_color' ) || taqyeem_get_option( 'review_links_decoration' )  ): ?>
.review-summary a {
	<?php if( taqyeem_get_option( 'review_links_color' ) ) echo 'color: '.taqyeem_get_option( 'review_links_color' ).';'; ?>
	<?php if( taqyeem_get_option( 'review_links_decoration' ) ) echo 'text-decoration: '.taqyeem_get_option( 'review_links_decoration' ).';'; ?>
}
<?php endif; ?>
<?php if( taqyeem_get_option( 'review_links_color_hover' ) || taqyeem_get_option( 'review_links_decoration_hover' )  ): ?>
.review-summary a:hover {
	<?php if( taqyeem_get_option( 'review_links_color_hover' ) ) echo 'color: '.taqyeem_get_option( 'review_links_color_hover' ).';'; ?>
	<?php if( taqyeem_get_option( 'review_links_decoration_hover' ) ) echo 'text-decoration: '.taqyeem_get_option( 'review_links_decoration_hover' ).';'; ?>
}
<?php endif; ?>
<?php do_action( 'tie_taqyeem_styling_css' ); ?>
<?php
foreach( $taqyeem_typography as $selector => $value){
$option = taqyeem_get_option( $value );
if( $option['font'] || $option['color'] || $option['size'] || $option['weight'] || $option['style'] ):
echo "\n".$selector."{\n"; ?>
<?php if($option['font'] )
	echo "	font-family: '". taqyeem_get_font( $option['font']  )."';\n"?>
<?php if($option['color'] )
	echo "	color :". $option['color'].";\n"?>
<?php if($option['size'] )
	echo "	font-size : ".$option['size']."px;\n"?>
<?php if($option['weight'] )
	echo "	font-weight: ".$option['weight'].";\n"?>
<?php if($option['style'] )
	echo "	font-style: ". $option['style'].";\n"?>
}
<?php endif;
} ?>
<?php echo htmlspecialchars_decode( taqyeem_get_option('css') ) , "\n";?>
<?php if( taqyeem_get_option('css_tablets') ) : ?>
@media only screen and (max-width: 985px) and (min-width: 768px){
<?php echo htmlspecialchars_decode( taqyeem_get_option('css_tablets') ) , "\n";?>
}
<?php endif; ?>
<?php if( taqyeem_get_option('css_wide_phones') ) : ?>
@media only screen and (max-width: 767px) and (min-width: 480px){
<?php echo htmlspecialchars_decode( taqyeem_get_option('css_wide_phones') ) , "\n";?>
}
<?php endif; ?>
<?php if( taqyeem_get_option('css_phones') ) : ?>
@media only screen and (max-width: 479px) and (min-width: 320px){
<?php echo htmlspecialchars_decode( taqyeem_get_option('css_phones') ) , "\n";?>
}
<?php endif; ?>
</style> 
<?php
}
?>