<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Imdbi
 * @subpackage Imdbi/public
 * @author     mohammad azami <iazami@outlook.com>
 */
class Imdbi_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/* loading public functions */
	public function imdbi_function_loader(){
		include_once( 'imdbi-public-functions.php' );
	}

}
