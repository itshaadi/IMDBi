<?php

/**
 * Fired during plugin activation
 *
 * @package    Imdbi
 * @subpackage Imdbi/includes
* @author     mohammad azami <iazami@outlook.com>
 */
class Imdbi_Activator {

	public static function activate() {
		add_option(
			'imdbi',
			array('posters_size' => '9999', 'download_posters' => '1'),
			'',
			'yes'
			);

	}



}
