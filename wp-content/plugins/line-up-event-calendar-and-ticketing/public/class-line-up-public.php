<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://lineupnow.com
 * @since      1.0.0
 *
 * @package    Line_Up
 * @subpackage Line_Up/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Line_Up
 * @subpackage Line_Up/public
 * @author     Planvine Ltd <support@lineupnow.com>
 */
class Line_Up_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'lineup_calendar', 'https://platform.lineupnow.com/v2/sdk-loader.bundle.js"', array(), $this->version, false );
		wp_enqueue_script( 'lineup_event', 'https://platform.lineupnow.com/v2/ticketing-loader.bundle.js', array(), $this->version, false );
		
	}


	/**
	 * The HTML generated from rendering a plugin view with the specified arguments.
	 *
	 * @param string $view The PHP file name without the extension.
	 * @param array $vars An associative array of variables made available.
	 * @return string The generated HTML.
	 */
	function render( $view, $vars = array() ) {
		$path = untrailingslashit( dirname( __FILE__ ) ) . "/partials/{$view}.php";
		ob_start();
		require $path;

		return ob_get_clean();
	}

	protected function get_option( $name ) {
		$options = get_option($this->plugin_name);
		return isset($options[$name]) ? $options[$name] : null;
	}

	/**
	 * the handler for the line-up calendar shortcode
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 */

	public function lineup_calendar_func( $attributes ) {
		if ( empty( $attributes ) ) {
			return null;
		}
		$defaults = array(
			'width' => '100%',
			'height' => '800'
		);

		$arguments = wp_parse_args( $attributes, $defaults );

		if ( empty( $arguments['plugin_id'] ) ) {
			return null;
		}

		return $this->render( 'line-up-calendar-embed-code', $arguments );
	}

	/**
	 * the handler for the line-up event shortcode
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 */

	public function lineup_event_func( $attributes ) {

		if ( empty( $attributes ) ) {
			return null;
		}

		$defaults = array(
			'background_color' => '',
			'main_color' => '',
			'event_id' => '',
			'api_key' => '',
			'width' => '',
			'event_card' => 'false'
		);

		$arguments = wp_parse_args( $attributes, $defaults );

		if ( empty( $arguments['event_id'] ) || empty( $arguments['api_key'] )) {
			return null;
		}
		return $this->render( 'line-up-event-embed-code', $arguments );
	}
}
