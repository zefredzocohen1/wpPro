<?php
## Reviews Widget
add_action( 'widgets_init', 'taqyeem_review_widget_box' );
function taqyeem_review_widget_box() {
	register_widget( 'taqyeem_review_widget' );
}
class taqyeem_review_widget extends WP_Widget {

	function taqyeem_review_widget() {
		$widget_ops = array( 'classname' => 'taqyeem-review-widget'  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'taqyeem-review-widget' );
		parent::__construct( 'taqyeem-review-widget',TIE_Plugin .' - Review Box', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		global $post ;
		$get_meta = get_post_custom($post->ID);
		if ( is_singular() && !empty( $get_meta['taq_review_position'][0] )) :
				
			$title = apply_filters('widget_title', $instance['title'] );
			$page_url = $instance['page_url'];

			echo $before_widget;
			if ( $title )
				echo $before_title;
			echo $title ;
			echo $after_title;
			echo taqyeem_get_review( 'review-bottom' );
			echo $after_widget;
		endif;
	}

	function update( $new_instance, $old_instance ) {
		$instance 				= $old_instance;
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['page_url'] 	= strip_tags( $new_instance['page_url'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' =>__( 'Review Overview' , 'taq') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p><em style="color:red;"><?php _e( 'This Widget appears in single post only .' , 'taq') ?></em></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title : ' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>

	<?php
	}
}


/** Best, latest & random Review Widget **/
add_action( 'widgets_init', 'taqyeem_reviews_widget' );
function taqyeem_reviews_widget() {
	register_widget( 'taqyeem_get_reviews' );
}
class taqyeem_get_reviews extends WP_Widget {

	function taqyeem_get_reviews() {
		$widget_ops 	= array( 'classname' => 'reviews-posts-widget' );
		$control_ops 	= array( 'width' => 250, 'height' => 350, 'id_base' => 'reviews-posts-widget' );
		parent::__construct( 'reviews-posts-widget',TIE_Plugin .' - Reviews Posts', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title 			= apply_filters('widget_title', $instance['cat_posts_title'] );
		$num_posts 		= $instance['num_posts'];
		$cats_id 		= $instance['cats_id'];
		$thumb 			= $instance['thumb'];
		$thumb_size 	= $instance['thumb_size'];
		$reviews_order 	= $instance['reviews_order'];
		
		if( empty($thumb) ){ $thumb_size = false; }
		else{
			if(empty($thumb_size) || !is_numeric($thumb_size)){
				$thumb_size = array(50,50);
			}else{
				$thumb_size = array($thumb_size,$thumb_size);
			}
		}
		
		echo $before_widget;
		echo $before_title;
		echo $title ; 
		echo $after_title;
		taqyeem_get_reviews($num_posts , $reviews_order , $thumb_size , $cats_id);
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['cat_posts_title'] 	= strip_tags( $new_instance['cat_posts_title'] );
		$instance['num_posts'] 			= strip_tags( $new_instance['num_posts'] );
		$instance['cats_id'] 			= implode(',' , $new_instance['cats_id']  );
		$instance['thumb'] 				= strip_tags( $new_instance['thumb'] );
		$instance['thumb_size'] 		= strip_tags( $new_instance['thumb_size'] );
		$instance['reviews_order'] 		= strip_tags( $new_instance['reviews_order'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'cat_posts_title' =>__( 'Best Reviews' , 'taq'), 'num_posts' => '5' , 'reviews_order' => 'best' , 'cats_id' => '1' , 'thumb' => 'true', 'thumb_size' => 50 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$categories_obj = get_categories();
		$categories 	= array();

		foreach ($categories_obj as $pn_cat) {
			$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_posts_title' ); ?>"><?php _e( 'Title : ' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'cat_posts_title' ); ?>" name="<?php echo $this->get_field_name( 'cat_posts_title' ); ?>" value="<?php echo $instance['cat_posts_title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e( 'Number of posts to show :' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" type="text" size="3" />
		</p>
		<p>
			<?php $cats_id = explode ( ',' , $instance['cats_id'] ) ; ?>
			<label for="<?php echo $this->get_field_id( 'cats_id' ); ?>"><?php _e( 'Categories :' , 'taq') ?></label>
			<select multiple="multiple" id="<?php echo $this->get_field_id( 'cats_id' ); ?>[]" name="<?php echo $this->get_field_name( 'cats_id' ); ?>[]">
				<?php foreach ($categories as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( in_array( $key , $cats_id ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'reviews_order' ); ?>"><?php _e( 'Posts Order :' , 'taq') ?></label>
			<select id="<?php echo $this->get_field_id( 'reviews_order' ); ?>" name="<?php echo $this->get_field_name( 'reviews_order' ); ?>" >
				<option value="latest" <?php if( $instance['reviews_order'] == 'latest' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Recent Reviews' , 'taq') ?></option>
				<option value="random" <?php if( $instance['reviews_order'] == 'random' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Random Reviews' , 'taq') ?></option>
				<option value="best" <?php if( $instance['reviews_order'] == 'best' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Best Reviews' , 'taq') ?></option>
			</select>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="true" <?php if( $instance['thumb'] ) echo 'checked="checked"'; ?> type="checkbox" />
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"> <?php _e( 'Display Thumbinals' , 'taq') ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><?php _e( 'Thumbinals Size :' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_size' ); ?>" name="<?php echo $this->get_field_name( 'thumb_size' ); ?>" value="<?php echo $instance['thumb_size']; ?>" type="text" size="3" />
		</p>
	<?php
	}
}


/** Post Types Review Widget **/
add_action( 'widgets_init', 'taqyeem_types_reviews_widget' );
function taqyeem_types_reviews_widget() {
	register_widget( 'taqyeem_types_get_reviews' );
}
class taqyeem_types_get_reviews extends WP_Widget {

	function taqyeem_types_get_reviews() {
		$widget_ops 	= array( 'classname' => 'reviews-posts-widget' );
		$control_ops 	= array( 'width' => 250, 'height' => 350, 'id_base' => 'reviews-types-widget' );
		parent::__construct( 'reviews-types-widget',TIE_Plugin .' - Reviews Post Types', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title 			= apply_filters('widget_title', $instance['cat_posts_title'] );
		$num_posts 		= $instance['num_posts'];
		$types 			= $instance['types'];
		$thumb 			= $instance['thumb'];
		$thumb_size 	= $instance['thumb_size'];
		$reviews_order	= $instance['reviews_order'];
		
		if( empty($thumb) ){ $thumb_size = false; }
		else{
			if(empty($thumb_size) || !is_numeric($thumb_size)){
				$thumb_size = array(50,50);
			}else{
				$thumb_size = array($thumb_size,$thumb_size);
			}
		}
		
		echo $before_widget;
		echo $before_title;
		echo $title ; 
		echo $after_title;
		taqyeem_get_types_reviews($num_posts , $reviews_order , $thumb_size , $types);
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['cat_posts_title'] 	= strip_tags( $new_instance['cat_posts_title'] );
		$instance['num_posts'] 			= strip_tags( $new_instance['num_posts'] );
		$instance['types'] 				= $new_instance['types'];
		$instance['thumb'] 				= strip_tags( $new_instance['thumb'] );
		$instance['thumb_size'] 		= strip_tags( $new_instance['thumb_size'] );
		$instance['reviews_order'] 		= strip_tags( $new_instance['reviews_order'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'cat_posts_title' =>__( 'Best Reviews' , 'taq'), 'num_posts' => '5' , 'reviews_order' => 'best' , 'thumb' => 'true', 'thumb_size' => 50 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$post_types = array();
		$post_types = get_post_types( array('_builtin' => false ) ,'names'); 
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_posts_title' ); ?>"><?php _e( 'Title : ' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'cat_posts_title' ); ?>" name="<?php echo $this->get_field_name( 'cat_posts_title' ); ?>" value="<?php echo $instance['cat_posts_title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_posts' ); ?>"><?php _e( 'Number of posts to show :' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'num_posts' ); ?>" name="<?php echo $this->get_field_name( 'num_posts' ); ?>" value="<?php echo $instance['num_posts']; ?>" type="text" size="3" />
		</p>
		<p>
			<?php $types = $instance['types'] ; ?>
			<label for="<?php echo $this->get_field_id( 'types' ); ?>"><?php _e( 'Post Types :' , 'taq') ?></label>
			<select multiple="multiple" id="<?php echo $this->get_field_id( 'types' ); ?>[]" name="<?php echo $this->get_field_name( 'types' ); ?>[]">
				<?php foreach ($post_types as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( !empty($types) && in_array( $key , $types ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'reviews_order' ); ?>"><?php _e( 'Posts Order :' , 'taq') ?></label>
			<select id="<?php echo $this->get_field_id( 'reviews_order' ); ?>" name="<?php echo $this->get_field_name( 'reviews_order' ); ?>" >
				<option value="latest" <?php if( $instance['reviews_order'] == 'latest' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Recent Reviews' , 'taq') ?></option>
				<option value="random" <?php if( $instance['reviews_order'] == 'random' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Random Reviews' , 'taq') ?></option>
				<option value="best" <?php if( $instance['reviews_order'] == 'best' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Best Reviews' , 'taq') ?></option>
			</select>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" value="true" <?php if( $instance['thumb'] ) echo 'checked="checked"'; ?> type="checkbox" />
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"> <?php _e( 'Display Thumbinals' , 'taq') ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb_size' ); ?>"><?php _e( 'Thumbinals Size :' , 'taq') ?></label>
			<input id="<?php echo $this->get_field_id( 'thumb_size' ); ?>" name="<?php echo $this->get_field_name( 'thumb_size' ); ?>" value="<?php echo $instance['thumb_size']; ?>" type="text" size="3" />
		</p>

	<?php
	}
}
?>