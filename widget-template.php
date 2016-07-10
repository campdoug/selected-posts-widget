<?php
/**
 * Recent posts widget template.
 */

$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Featured Articles', 'selected-posts-widget' );
$post_ids = ! empty( $instance['posts'] ) ? $instance['posts'] : false;


?>
<div class="widget widget-recent-posts">
	<h3 class="widget-title"><?php echo $title; ?></h3>
	
	<?php if( false === $post_ids ): ?>
		
		<?php _e('No featured posts selected!', 'selected-posts-widget'); ?>
	
	<?php else: ?>

		<?php
			$post_ids = array_unique( explode( ' ', $post_ids) ); 
			$featured = new WP_Query( [ 'post__in' => $post_ids, 'orderby' => 'post__in' ] );
		?>
	    <div class="widget-inner">
			<?php if ( $featured->have_posts() ) :   ?>
	            <ul class="posts-list">
					<?php while ( $featured->have_posts() ) :   $featured->the_post(); ?>
	                    <li>
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="post-title"><?php the_title(); ?></a>
	                        <div class="post-author">
								<?php
									printf(
										esc_html_x( 'By %s', 'post author', 'selected-posts-widget' ),
										'<span class="author"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
									);
								?>
	                        </div>
	                    </li>
					<?php endwhile; ?>
	            </ul>
			<?php endif; ?>
	    </div>
	<?php endif; ?>
</div>

<?php
	// Lets go back in time.
	wp_reset_postdata();
?>
