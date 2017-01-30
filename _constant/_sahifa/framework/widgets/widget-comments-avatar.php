<?php
add_action( 'widgets_init', 'tie_comments_avatar_widget' );
function tie_comments_avatar_widget() {
	register_widget( 'tie_comments_avatar' );
}
class tie_comments_avatar extends WP_Widget {

	public function __construct(){
		$widget_ops 	= array( 'classname' => 'comments-avatar' );
		$control_ops 	= array( 'width' => 250, 'height' => 350, 'id_base' => 'comments_avatar-widget' );
		parent::__construct( 'comments_avatar-widget', THEME_NAME .' - '.__( 'Recent Comments with avatar' , 'tie' ) , $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title 			= apply_filters('widget_title', $instance['title'] );
		$no_of_comments = $instance['no_of_comments'];
		$avatar_size	= $instance['avatar_size'];

		echo $before_widget;
		if ( $title )
			echo $before_title;
			echo $title ; ?>
		<?php echo $after_title; ?>
			<ul>
		<?php tie_most_commented( $no_of_comments , $avatar_size); ?>
		</ul>
	<?php
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance 					= $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['no_of_comments'] = strip_tags( $new_instance['no_of_comments'] );
		$instance['avatar_size'] 	= strip_tags( $new_instance['avatar_size'] );
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array( 'title' =>__( 'Recent Comments' , 'tie'), 'no_of_comments' => '5' , 'avatar_size' => '55' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Avatar Size:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php echo $instance['avatar_size']; ?>"  type="text" size="3" /> px
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_comments' ); ?>"><?php _e( 'Number of comments to show:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'no_of_comments' ); ?>" name="<?php echo $this->get_field_name( 'no_of_comments' ); ?>" value="<?php echo $instance['no_of_comments']; ?>" type="text" size="3" />
		</p>


	<?php
	}
}
?>