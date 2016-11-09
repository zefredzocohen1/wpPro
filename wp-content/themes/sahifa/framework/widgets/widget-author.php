<?php
## Author Widget
add_action( 'widgets_init', 'tie_Author_widget_box' );
function tie_Author_widget_box(){
	register_widget( 'tie_author_widget' );
}
class tie_author_widget extends WP_Widget {
	function tie_author_widget() {
		$widget_ops = array( 'classname' => 'widget_author' );
		parent::__construct( 'author_widget',THEME_NAME .' - '.__( 'Post Author' , 'tie' ) , $widget_ops );
	}
	function widget( $args, $instance ) {
		extract( $args );
		if ( is_single() ) :
		
		wp_reset_query();
		
		$avatar = $instance['avatar'];
		$social = $instance['social'];
		
		echo $before_widget;
		echo $before_title;
		printf( __ti( 'About %s' ), get_the_author() );
		echo $after_title; 
		
		tie_author_box( $avatar , $social );
		
		echo $after_widget;
		endif;
	}
	
	
	function update( $new_instance, $old_instance ) {
		$instance 			= $old_instance;
		$instance['avatar'] = strip_tags( $new_instance['avatar'] );
		$instance['social'] = strip_tags( $new_instance['social'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'avatar' => 'true' , 'social' => 'true' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p><em style="color:red;"><?php _e( 'This Widget appears in single post only.' , 'tie') ?></em></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar' ); ?>"><?php _e( "Author's avatar:" , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'avatar' ); ?>" name="<?php echo $this->get_field_name( 'avatar' ); ?>" value="true" <?php if( $instance['avatar'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'social' ); ?>"><?php _e( 'Social icons:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'social' ); ?>" name="<?php echo $this->get_field_name( 'social' ); ?>" value="true" <?php if( $instance['social'] ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>

	<?php
	}
}




## Author posts Widget
add_action( 'widgets_init', 'Author_post_widget_box' );
function Author_post_widget_box(){
	register_widget( 'author_post_widget' );
}
class author_post_widget extends WP_Widget {
	function author_post_widget() {
		$widget_ops = array( 'classname' => 'widget_author_posts'  );
		parent::__construct( 'author_post_widget', THEME_NAME .' - '.__( "Posts By Post's Author" , 'tie' ) , $widget_ops );
	}
	function widget( $args, $instance ) {
		global $post;
		extract( $args );
		wp_reset_query();
		if ( is_single() ) :
		
			$no_of_posts 	= $instance['no_of_posts'];
			$see_all 		= $instance['see_all'];
			
			$authorID 		= get_the_author_meta( 'ID' );
			$args 			= array('author' => $authorID , 'post__not_in' => array($post->ID), 'posts_per_page'=> $no_of_posts, 'no_found_rows' => 1 );
			$my_query 		= new wp_query( $args );
		if( $my_query->have_posts() ) :
			echo $before_widget; 
				echo $before_title;
				printf( __ti( 'By %s' ), get_the_author() );
			echo $after_title; ?>
			<ul>
			<?php while( $my_query->have_posts() ) { $my_query->the_post();?>
				<li><a href="<?php the_permalink()?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
			<?php } ?>
			</ul>
			<?php if($see_all) : ?>
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"> <?php _eti( 'All Posts' ) ?> (<?php echo count_user_posts($authorID) ?>)</a>
			<?php endif; ?>

			<?php
			wp_reset_query();
			echo $after_widget;
		endif;
		endif;

	}
	
	function update( $new_instance, $old_instance ) {
		$instance 					= $old_instance;
		$instance['title'] 			= ' ';
		$instance['no_of_posts'] 	= strip_tags( $new_instance['no_of_posts'] );
		$instance['see_all'] 		= strip_tags( $new_instance['see_all'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'no_of_posts' => '5' , 'see_all' => 'true' );
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		?>
		
		<p><em style="color:red;"><?php _e( 'This Widget appears in single post only.' , 'tie') ?></em></p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'no_of_posts' ); ?>"><?php _e( 'Number of posts to show:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'no_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'no_of_posts' ); ?>" value="<?php if( !empty( $instance['no_of_posts'] ) ) echo $instance['no_of_posts']; ?>" type="text" size="3" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'see_all' ); ?>"><?php _e( 'Display (All) link:' , 'tie') ?></label>
			<input id="<?php echo $this->get_field_id( 'see_all' ); ?>" name="<?php echo $this->get_field_name( 'see_all' ); ?>" value="true" <?php if( !empty( $instance['see_all'] ) ) echo 'checked="checked"'; ?> type="checkbox" />
		</p>

	<?php
	}
}

?>