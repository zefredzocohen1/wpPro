<?php
add_action( 'widgets_init', 'tie_timeline_posts_widget' );
function tie_timeline_posts_widget() {
	register_widget( 'tie_timeline_widget' );
}
class tie_timeline_widget extends WP_Widget {

	public function __construct(){
		$widget_ops 	= array( 'classname' => 'timeline-posts' );
		$control_ops 	= array( 'width' => 250, 'height' => 350, 'id_base' => 'timeline-posts-widget' );
		parent::__construct( 'timeline-posts-widget', THEME_NAME .' - '.__( 'Timeline' , 'tie') , $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title 			= apply_filters('widget_title', $instance['title'] );
		$no_of_posts 	= $instance['no_of_posts'];
		$cats_id		 = $instance['cats_id'];

		echo $before_widget;
			echo $before_title;
			echo $title ; ?>
		<?php echo $after_title; ?>
				<ul>
					<?php tie_last_posts_cat_timeline($no_of_posts , $cats_id)?>
				</ul>
		<div class="clear"></div>
	<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance 					= $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['no_of_posts'] 	= strip_tags( $new_instance['no_of_posts'] );
		$instance['cats_id'] 		= implode(',' , $new_instance['cats_id']  );

		return $instance;
	}

	public function form( $instance ) {
		$defaults = array( 'title' =>__( 'Timeline' , 'tie'), 'no_of_posts' => '5' , 'cats_id' => '1' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		$categories_obj = get_categories();
		$categories 	= array();

		foreach ($categories_obj as $pn_cat) {
			$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_posts' ); ?>"><?php _e( 'Number of posts to show:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'no_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'no_of_posts' ); ?>" value="<?php echo $instance['no_of_posts']; ?>" type="text" size="3" />
		</p>
		<p>
			<?php $cats_id = explode ( ',' , $instance['cats_id'] ) ; ?>
			<label for="<?php echo $this->get_field_id( 'cats_id' ); ?>"><?php _e( 'Category :' , 'tie') ?></label>
			<select multiple="multiple" id="<?php echo $this->get_field_id( 'cats_id' ); ?>[]" name="<?php echo $this->get_field_name( 'cats_id' ); ?>[]">
				<?php foreach ($categories as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( in_array( $key , $cats_id ) ) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
				<?php } ?>
			</select>
		</p>

	<?php
	}
}
?>