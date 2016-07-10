<?php
/**
 * Extend widget CLass.
 */
class Selected_Posts_Widget_Widget extends WP_Widget {
	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, __('Selected Posts Widget', 'selected-posts-widget' ) );
	}

	function widget( $args, $instance ) {
		include plugin_dir_path( __FILE__ ) . '/widget-template.php';
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Selected Posts', 'selected-posts-widget' );
		$posts = ! empty( $instance['posts'] ) ? $instance['posts'] : '';
		$post_arr = array_unique( explode( ' ', $posts ) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>"><?php _e( esc_attr( 'Select Post', 'selected-posts-widget' ) ); ?></label>
			<div class="post-suggestion-wrap">
				<input class="widefat post-suggestion" id="<?php echo esc_attr( $this->get_field_id( 'posts' ) ); ?>" type="text" placeholder="<?php _e('Slowly type for suggestions', 'selected-posts-widget'); ?>" autocomplete="off" />
				<input type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'posts' ) ); ?>" value="<?php echo $posts; ?>" />
				<div class="selected-posts">
					<div data-template style="display:none">
						<div data-id="{id}">
							<span>{title}</span>
							<span class="remove">&times;</span>
						</div>
					</div>
					<?php foreach( (array) $post_arr as $id ): ?>
						<?php if( !empty( $id) ): ?>
							<div data-id="<?php echo $id; ?>">
								<span><?php echo get_the_title( $id ); ?></span>
								<span class="remove">&times;</span>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</p>

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts'] = ( ! empty( $new_instance['posts'] ) ) ? trim(strip_tags( $new_instance['posts'] )) : '';

		return $instance;
	}
}
