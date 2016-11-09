<?php
/**
 * Provide a meta box area view for the plugin
 *
 * @package    Imdbi
 * @subpackage Imdbi/admin/partials
 */
?>

<div class="wrap">
	<div id="col-container">
		<div class="col-wrap imdbi-metabox-slide1">
		<h1><?php esc_attr_e( 'Wellcome To IMDBI Explorer', $this->plugin_name ); ?></h1>
			<div class="inside">
				<p><?php esc_attr_e("You can use one of the following methods for find your favorite movie and series, then add it to the post", $this->plugin_name); ?></p>
				<fieldset>
						<div class="searchType-wrap">
						<label>
						<input type="radio" name="searchType" class="imdbi-searchType" id="imdbi-bytitle" value="bytitle" />
						<span><?php esc_attr_e( 'Search By Title', $this->plugin_name ); ?></span>
						</label>
						</div>
						<div class="searchType-wrap">
						<label>
						<input type="radio" name="searchType" class="imdbi-searchType" id="imdbi-byid" value="byid" />
						<span><?php esc_attr_e( 'Search By IMDB ID', $this->plugin_name ); ?></span>
						</label>
						</div>
				</fieldset>
			</div>
		</div>


		<!-- slide2 -->

		<div class="col-wrap imdbi-metabox-slide2">
		<b><a href="#" id="goto1">← <?php esc_attr_e( 'Change Search Type', $this->plugin_name ); ?></a></b>
			<div class="inside">
				<fieldset>
						<div class="searchType-wrap">
						<span><b><?php esc_attr_e( 'Enter Name of Movie/Series', $this->plugin_name ); ?></b></span>
						<input type="text" value="" name="imdbQuery" id="imdbi-query" class="all-options" />
						</div>
						<div class="searchType-wrap">
						<span><b><?php esc_attr_e( 'Year', $this->plugin_name ); ?></b> <i><?php esc_attr_e("(optional)", $this->plugin_name); ?></i></span>
						<input type="text" value="" name="imdbYear" id="imdbi-year" class="small-text" />
						</div>
						<div class="searchType-wrap">
						<input class="button-secondary" type="button" id="imdbi-search-submit" value="<?php esc_attr_e( 'Search', $this->plugin_name ); ?>" />
						</div>
						<div class="imdbi-empty-title error" style="display:none;">
						<p><?php _e('title field cannot be empty.', $this->plugin_name) ?></p>
						</div>
				</fieldset>
			</div>
		</div>


		<!-- slide3 -->

		<div class="col-wrap imdbi-metabox-slide3">
		<b><a href="#" id="goto2">← <?php esc_attr_e( 'Change Search Type', $this->plugin_name ); ?></a></b>
			<div class="inside">
				<fieldset>
						<div class="searchType-wrap">
						<span><b><?php esc_attr_e( 'Enter IMDB ID', $this->plugin_name ); ?></b> <i>(E.g tt1234567)</i></span>
						<input type="text" value="" name="imdbID" id="imdbi-id" class="small" />
						</div>
						<div class="searchType-wrap">
						<input class="button-secondary" type="button" id="imdbi-id-submit" value="<?php esc_attr_e( 'Retrieve Information', $this->plugin_name ); ?>" />
						</div>
						<div class="imdbi-empty-id error" style="display:none;">
						<p><?php _e('IMDB ID field cannot be empty.', $this->plugin_name) ?></p>
						</div>
						<div class="imdbi-invalid-id error" style="display:none;">
						<p><?php _e('invalid IMDB ID pattern.', $this->plugin_name) ?></p>
						</div>
				</fieldset>
			</div>
		</div>


		<div class="col-wrap imdbi-metabox-slide4">
			<div class="inside">
			</div>
		</div>

		<div class="col-wrap imdbi-metabox-loader">
		<div class="inside">
		<center>
			<img src="<?php echo untrailingslashit( plugins_url( '' ) ); ?>/imdbi/admin/partials/pacman.gif" alt="pacman loader">
			<h2><?php _e('Loading ...', $this->plugin_name); ?></h2>
		</center>
		<br/>
		</div>
		</div>

		<div class="col-wrap omdb-temp-results">
		</div>


		<div class="imdbi-metabox-notify">
			<div class="inside">
				<center>
				<h1><?php _e("Information was received successfully", $this->plugin_name); ?></h1>
				<br/>
				<a class="button-secondary imdbi-metabox-edit imdbi-metabox-btn" href="#" title="<?php _e( 'Translate common fields', $this->plugin_name ); ?>"><?php _e( 'Translate common fields', $this->plugin_name ); ?></a>
				<a class="button-secondary imdbi-metabox-remove imdbi-metabox-btn" href="#" title="<?php _e( 'Remove', $this->plugin_name ); ?>"><?php _e( 'Remove', $this->plugin_name ); ?></a>
				<br/><br/><br/>
			</center>
			</div>
		</div>

		<div class="imdbi-tab-wrapper">

			<h2 class="nav-tab-wrapper">
				<a href="#" class="imdbi-tab1 nav-tab nav-tab-active"><?php _e("Common Fields", $this->plugin_name); ?></a>
				<a href="#" class="imdbi-tab2 nav-tab"><?php _e("Other Fields", $this->plugin_name); ?></a>
			</h2>

		</div>

		<!-- tab1 common metabox fields -->

		<div class="imdbi-metabox-common-fields">
			<div class="inside">
				<table>
					<tbody>

						<tr>
							<th>
								<b><?php _e("Genre:" ,$this->plugin_name); ?></b>
							</th>
							<td>
								<fieldset>
									<input type="text" class="all-options" name="imdbi-genre-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Genre', true ) ); ?>" />
								</fieldset>
							</td>
					</tr>

					<tr>
						<th>
							<b><?php _e("Country:" ,$this->plugin_name); ?></b>
						</th>
						<td>
							<fieldset>
								<input type="text" class="all-options" name="imdbi-country-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Counetry', true ) ); ?>" />
							</fieldset>
						</td>
				</tr>

				<tr>
					<th>
						<b><?php _e("Language:" ,$this->plugin_name); ?></b>
					</th>
					<td>
						<fieldset>
							<input type="text" class="all-options" name="imdbi-language-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Language', true ) ); ?>" />
						</fieldset>
					</td>
			</tr>


			<tr>
				<th>
					<b><?php _e("Awards:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-awards-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Awards', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Plot:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<textarea name="imdbi-plot-value" cols="70" rows="10"><?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Plot', true ) ); ?></textarea>
					</fieldset>
				</td>
		</tr>

				</tbody>
			</table>
							<center><i><?php _e("metabox values will change after publish/update the post.", $this->plugin_name); ?></i></center>
			</div>
			<br/>
		</div>


		<!-- tab2 other metabox fields -->


		<div class="imdbi-metabox-other-fields">
			<div class="inside">
				<table>
					<tbody>

						<tr>
							<th>
								<b><?php _e("MPAA Rating (age):" ,$this->plugin_name); ?></b>
							</th>
							<td>
								<fieldset>
									<input type="text" class="all-options" name="imdbi-rated-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Rated', true ) ); ?>" />
								</fieldset>
							</td>
					</tr>

					<tr>
						<th>
							<b><?php _e("Relase Date:" ,$this->plugin_name); ?></b>
						</th>
						<td>
							<fieldset>
								<input type="text" class="all-options" name="imdbi-released-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Released', true ) ); ?>" />
							</fieldset>
						</td>
				</tr>

				<tr>
					<th>
						<b><?php _e("Runtime (minute):" ,$this->plugin_name); ?></b>
					</th>
					<td>
						<fieldset>
							<input type="text" class="all-options" name="imdbi-runtime-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Runtime', true ) ); ?>" />
						</fieldset>
					</td>
			</tr>


			<tr>
				<th>
					<b><?php _e("Director:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-director-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Director', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Writer:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-writer-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Writer', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Actors:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-actors-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Actors', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Metascore:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-metascore-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Metascore', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Rating:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-imdbrating-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_imdbRating', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Votes:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-imdbvotes-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_imdbVotes', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Gross:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-gross-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Gross', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>



			<tr>
				<th>
					<b><?php _e("Budget:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-budget-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Budget', true ) ); ?>" />
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Poster Url:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<textarea name="imdbi-poster-value" cols="70" rows="10"><?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Poster', true ) ); ?></textarea>
					</fieldset>
				</td>
		</tr>


			<tr>
				<th>
					<b><?php _e("Trailer Url:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<textarea name="imdbi-trailer-value" cols="70" rows="10"><?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Trailer', true ) ); ?></textarea>
					</fieldset>
				</td>
		</tr>

			<!-- these fields are not editable and hidden from user view -->

				<tr style="display:none !important;">
					<th>
						<b><?php _e("imdbID:" ,$this->plugin_name); ?></b>
					</th>
					<td>
						<fieldset>
							<input type="text" class="all-options" name="imdbi-imdbid-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'imdbID', true ) ); ?>" />
						</fieldset>
					</td>
				</tr>

				<tr style="display:none !important;">
					<th>
						<b><?php _e("Title:" ,$this->plugin_name); ?></b>
					</th>
					<td>
						<fieldset>
							<input type="text" class="all-options" name="imdbi-title-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Title', true ) ); ?>" />
						</fieldset>
					</td>
				</tr>

				<tr style="display:none !important;">
					<th>
						<b><?php _e("Year:" ,$this->plugin_name); ?></b>
					</th>
					<td>
						<fieldset>
							<input type="text" class="all-options" name="imdbi-year-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Year', true ) ); ?>" />
						</fieldset>
					</td>
				</tr>

			<tr style="display:none !important;">
				<th>
					<b><?php _e("Type:" ,$this->plugin_name); ?></b>
				</th>
				<td>
					<fieldset>
						<input type="text" class="all-options" name="imdbi-type-value" value="<?php echo esc_attr( get_post_meta( $object->ID, 'IMDBI_Type', true ) ); ?>" />
					</fieldset>
				</td>
			</tr>

				</tbody>
			</table>
							<center><i><?php _e("metabox values will change after publish/update the post.", $this->plugin_name); ?></i></center>
			</div>
			<br/>
		</div>
	</div>
</div> <!-- .wrap -->
		<footer id="imdbi-metabox-footer">
			<i id="imdbi-plugin-url" style="display:none;"><?php echo untrailingslashit( plugins_url( '' ) ); ?></i>
		</footer>
