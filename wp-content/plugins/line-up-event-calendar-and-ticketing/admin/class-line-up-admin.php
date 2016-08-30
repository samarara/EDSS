<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://lineupnow.com
 * @since      1.0.0
 *
 * @package    Line_Up
 * @subpackage Line_Up/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Line_Up
 * @subpackage Line_Up/admin
 * @author     Planvine Ltd <support@lineupnow.com>
 */
class Line_Up_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	}

	public function add_plugin_admin_menu() {

	    // add_options_page( 'Line-Up Setup', 'Line-Up', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
	    // );
	}
	 
	public function add_action_links( $links ) {
	   // $settings_link = array(
	   //  '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   // );
	   // return array_merge(  $settings_link, $links );

	}
	 
	public function display_plugin_setup_page() {
	    // include_once( 'partials/line-up-admin-display.php' );
	}

	public function options_update() {
    	register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	public function validate($input) {
    // All checkboxes inputs        
		$valid = array();
		error_log(print_r($input));

		//Cleanup
		$valid['api_key'] = sanitize_key($input['api_key']);

		return $valid;
	}

}
