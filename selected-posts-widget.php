<?php
/**
 * Plugin Name: Selected Posts Widget
 * Plugin URI:  http://campdoug.com
 * Description: Show selected posts using a widget.
 * Version:     0.0.2
 * Author:      campdoug, nerdaryan
 * Author URI:  http://campdoug.com
 * Donate link: http://campdoug.com
 * License:     GPLv3
 * Text Domain: selected-posts-widget
 * Domain Path: /languages
 *
 * @link http://campdoug.com
 *
 * @package Selected Posts Widget
 * @version 0.0.2
 */

/**
 * Copyright (c) 2016 campdoug, nerdaryan (email : hello@campdoug.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Main initiation class
 *
 * @since  0.0.2
 */
final class Selected_Posts_Widget {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  0.0.2
	 */
	const VERSION = '0.0.2';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  0.0.2
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  0.0.2
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  0.0.2
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var Selected_Posts_Widget
	 * @since  0.0.2
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  0.0.2
	 * @return Selected_Posts_Widget A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  0.0.2
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Add hooks and filters
	 *
	 * @since  0.0.2
	 * @return void
	 */
	public function hooks() {
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_spw_posts_suggestion', array( $this, 'posts_suggestion' ) );
	}

	/**
	 * widgets_init hooks.
	 *
	 * @since  0.0.2
	 * @return void
	 */
	public function widgets_init() {
		require_once $this->path.'widget.php';
		register_widget( 'Selected_Posts_Widget_Widget' );
	}

	public function admin_enqueue_scripts(){
		$screen = get_current_screen();
		if( 'widgets' == $screen->base ){
			wp_enqueue_script( 'spw-admin-js', $this->url .'/assets/js/spw-admin.js', array('jquery-ui-sortable') );
			wp_enqueue_style( 'spw-admin-css', $this->url .'/assets/css/spw-admin.css' );
		}
	}

	public function posts_suggestion() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die('Trying to cheat? huh!' );
		}

		$query = sanitize_text_field( wp_unslash( $_GET['q'] ) );

		$posts = get_posts( [ 'posts_per_page' => 10, 's' => $query ] );

		if ( $posts ) {
			$output = '<ul class="chef-posts-suggestions">';

			foreach ( $posts as $post ) {
				$output .= '<li data-id="'. $post->ID .'">';
				$output .= $post->post_title;
				$output .= '</li>';
			}

			$output .= '</ul>';

			wp_die($output );
		}

		wp_die('Nothing found!');
	}

}

/**
 * Grab the Selected_Posts_Widget object and return it.
 * Wrapper for Selected_Posts_Widget::get_instance()
 *
 * @since  0.0.2
 * @return Selected_Posts_Widget  Singleton instance of plugin class.
 */
function cd_selected_posts_widget() {
	return Selected_Posts_Widget::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( cd_selected_posts_widget(), 'hooks' ) );

