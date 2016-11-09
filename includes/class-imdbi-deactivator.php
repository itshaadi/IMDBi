<?php

/**
 * Fired during plugin deactivation
 *
 * @package    Imdbi
 * @subpackage Imdbi/includes
* @author     mohammad azami <iazami@outlook.com>
 */
class Imdbi_Deactivator {

	public static function deactivate() {
		delete_option( 'imdbi' );
		delete_option( 'imdbi_top_list' );
		wp_clear_scheduled_hook( 'imdbi_top_list' );
	}

}
