<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    Imdbi
 * @subpackage Imdbi/admin/partials
 */
?>

<div class="wrap">
	<?php settings_errors(); ?>
	<br>

	<form method="post" name="imdbi_options" action="options.php"></label>
	<?php

		//Grab all options
		$options = get_option($this->plugin_name);

		//var_dump($options);
		$args = array(
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'category',
		'pad_counts'               => false

	);
	?>


	<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
	?>

	<div class="wrap">
		<div id="col-container">
			<div class="col-wrap">

				<!-- General Settings Contents Goes Here. -->
				<div class="imdbi-setting-general">

					<h3>
						<?php _e('Poster Settings', $this->plugin_name); ?>
					</h3>
					<table class="form-table">
						<tbody>

							<tr>
								<th><b><?php _e('Posters Width',$this->plugin_name) ?></b></th>
								<td>
									<fieldset>
										<input type="text" class="regular-text" style="width:100px;" id="<?php echo $this->plugin_name; ?>-posters_size" name="<?php echo $this->plugin_name; ?>[posters_size]"
										<?php if(isset($options['posters_size'])){echo 'value='.$options['posters_size'].'';} else{echo 'value=""';} ?>>
									</fieldset>
									<i><?php _e('(enter 9999 means you choise the largest resulation that is available on media server.)',$this->plugin_name); ?></i>
								</td>
							</tr>
							<tr>
								<th><b><?php _e('Automatic Download',$this->plugin_name) ?></b></th>
								<td>
									<fieldset>
										<label for="<?php echo $this->plugin_name; ?>[download_posters]">
											<input name="<?php echo $this->plugin_name; ?>[download_posters]" type="checkbox" id="<?php echo $this->plugin_name; ?>-download_posters" value="1" <?php checked( '1', $options['download_posters'] ); ?> />
											<span><?php esc_attr_e( 'Download posters automatically.', $this->plugin_name ); ?></span>
										</label>
									</fieldset>
								</td>
							</tr>
						</tbody>
					</table>

				</div> <!-- End of General Settings Contents -->


			</div>
		</div>
	</div>

        <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>
	</form>
</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
