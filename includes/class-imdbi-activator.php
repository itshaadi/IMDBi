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

		// general options
		add_option(
			'imdbi',
			array('posters_size' => '9999', 'download_posters' => '1'),
			'',
			'yes'
			);

			// imdb top list
			add_option(
			'imdbi_top_list',
			null,
			'',
			'yes'
			);

			//schedule a monthly event for extracting imdb top 250.
			wp_schedule_event( time(), 'monthly', 'imdbi_top_list' );

	}



}
