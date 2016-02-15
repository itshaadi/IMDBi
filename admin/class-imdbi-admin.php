<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Imdbi
 * @subpackage Imdbi/admin
 * @author     mohammad azami <iazami@outlook.com>
 */
class Imdbi_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imdbi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imdbi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 if( is_rtl() ){
			 wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/imdbi-admin-rtl.css', array(), $this->version, 'all' );
		 }
		 else{
			 wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/imdbi-admin.css', array(), $this->version, 'all' );
		 }

	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imdbi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imdbi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/imdbi-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * checkout internet connection
	 */

	private function is_denied($url){

		/**
		* Because of Internet restrictions in Iran, IMDB media servers are restricted.
		*/
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$a = curl_exec($ch);
			return strpos($a , 'http://10.10.34.34')  ? true : false; // 10.10.34.34 refers to peyvandha.ir
	}



	/**
	* Register Admin menu into the wordpress dashboard menu
	*/

	public function imdbi_add_admin_menu(){

		add_menu_page( __( 'imdbi settings', $this->plugin_name ), __( 'imdbi settings', $this->plugin_name ), 'manage_options', $this->plugin_name, array($this,'imdbi_settings_view') );

	}

	/**
	* link to setting page in wordpress plugins page.
	*/

	public function imdbi_action_links( $links ){

	$settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>'
		);

	return array_merge($settings_link, $links);
}


	/**
	* Render the settings page
	*/

	public function imdbi_settings_view(){

		include_once( 'partials/imdbi-admin-view.php' );

	}


	/**
	* Validate option enteries
	*/

	public function imdbi_validate_options($input){
		$valid = array();
		$valid['posters_size'] = $input['posters_size'];
		if( empty($valid['posters_size']) || !is_numeric($valid['posters_size']) ){
			add_settings_error(
					'poster_size',                     // Setting title
					'poster_size_texterror',            // Error ID
					__('poster size must be a number.',$this->plugin_name),     // Error message
					'error'                         // Type of message
				);
		}
		else{
			return $valid;
		}
	}


	/**
	* Updating options
	*/

	public function imdbi_update_options(){
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'imdbi_validate_options'));
	}


	/**
	* Metabox setup function
	*/

	public function imdbi_post_metabox_setup(){

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array($this,'imdbi_add_post_metabox') );

		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array($this,'imdbi_save_post_metabox'), 10, 2 );
	}


	/**
	* Creating Metabox to be displayed on post editor screen.
	*/

	public function imdbi_add_post_metabox(){

	  add_meta_box(
	    'imdbi_metabox',      // Unique ID
	    esc_html__( 'Search movies and TV series', $this->plugin_name ),    // Title
	    array($this,'imdbi_metabox_callback'),   // Callback function
	    'post',         // Admin page (or post type)
	    'advanced',         // Context
	    'default'         // Priority
	  );

	}


	/**
	* Save and Update Fields
	*/

	public function imdbi_save_post_metabox($post_id, $post){

		/* Verify the nonce before proceeding. */
	  if ( !isset( $_POST['imdbi_metabox_nonce'] ) || !wp_verify_nonce( $_POST['imdbi_metabox_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	  /* Get the post type object. */
	  $post_type = get_post_type_object( $post->post_type );

	  /* Check if the current user has permission to edit the post. */
	  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

		/* List of meta box fields (name => meta_key) */
		$fields = array(
			'imdbi-imdbid-value'		 => 'imdbID',
			'imdbi-title-value'	 		 => 'IMDBI_Title',
			'imdbi-year-value'	 		 => 'IMDBI_Year',
			'imdbi-type-value'	 		 => 'IMDBI_Type',
			'imdbi-trailer-value' 	 => 'IMDBI_Trailer',
			'imdbi-poster-value' 		 => 'IMDBI_Poster',
			'imdbi-budget-value' 		 => 'IMDBI_Budget',
			'imdbi-gross-value'			 => 'IMDBI_Gross',
			'imdbi-imdbvotes-value'  => 'IMDBI_imdbVotes',
			'imdbi-imdbrating-value' => 'IMDBI_imdbRating',
			'imdbi-metascore-value'	 => 'IMDBI_Metascore',
			'imdbi-actors-value'		 => 'IMDBI_Actors',
			'imdbi-writer-value'		 => 'IMDBI_Writer',
			'imdbi-director-value'	 => 'IMDBI_Director',
			'imdbi-runtime-value' 	 => 'IMDBI_Runtime',
			'imdbi-released-value'	 => 'IMDBI_Released',
			'imdbi-rated-value' 		 => 'IMDBI_Rated',
			'imdbi-plot-value' 			 => 'IMDBI_Plot',
			'imdbi-awards-value'		 => 'IMDBI_Awards',
			'imdbi-language-value'	 => 'IMDBI_Language',
			'imdbi-country-value' 	 => 'IMDBI_Counetry',
			'imdbi-genre-value'			 => 'IMDBI_Genre'
		);

		foreach($fields as $name => $meta_key){

			/* Check if meta box fields has a proper value */
			if( isset($_POST[$name]) && $_POST[$name] != 'N/A' ){
				/* Set thumbnail */
				if($name == "imdbi-poster-value"){
					global $wpdb;
					$image_src = $_POST[$name];
					$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
					$attachment_id = $wpdb->get_var($query);
					set_post_thumbnail($post_id, $attachment_id);
				}

				$new_meta_value = $_POST[$name];
			}
			else{
				$new_meta_value = '';
			}

			/* Get the meta value of the custom field key */
			$meta_value = get_post_meta($post_id, $meta_key, true);

			/* If a new meta value was added and there was no previous value, add it. */
		  if ( $new_meta_value && '' == $meta_value )
		    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		  /* If the new meta value does not match the old value, update it. */
		  elseif ( $new_meta_value && $new_meta_value != $meta_value )
		    update_post_meta( $post_id, $meta_key, $new_meta_value );

		  /* If there is no new meta value but an old value exists, delete it. */
		  elseif ( '' == $new_meta_value && $meta_value )
		    delete_post_meta( $post_id, $meta_key, $meta_value );

		}
	}

	/**
	* Meta box html view
	*/

	public function imdbi_metabox_callback($object, $box){

		wp_nonce_field(basename( __FILE__ ), 'imdbi_metabox_nonce');

		include_once( dirname( __FILE__ ) . '/partials/imdbi-metabox-view.php' );
	}



	/**
	* Send meta box parameters to OMDbAPI class
	*/

	public function imdbi_run_crawler(){

		isset($_REQUEST['imdbQuery']) ? $query = urlencode($_REQUEST['imdbQuery']): $query = '';
		isset($_REQUEST['imdbYear']) ? $year = urlencode($_REQUEST['imdbYear']): $year = '';
		isset($_REQUEST['imdbID']) ? $imdbID = urlencode($_REQUEST['imdbID']): $imdbID = '';

		/* prevent leaking out data */
		if( !isset($_POST['action']) || $_POST['action'] != 'editpost' ){

			if( !empty($imdbID) ){
				$omdb = new OMDbAPI();
				$data = $omdb->fetch('i', $imdbID);
				$this->omdb_view('fetch', $data);
				die();
			}
			elseif ( !empty($query) ) {
				$omdb = new OMDbAPI();
				$data = $omdb->search($query,$year);
				$this->omdb_view('search', $data);
				die();
			}

		}

	}


	/**
	* translating common fields
	*/

	public function imdbi_translate($string, $type = ""){

		if($string == "N/A" || $string == "n/a"){
			return 'N/A';
		}

		elseif($type == "genre"){

			$string = strtolower($string);

			$words = array(
			'action' 				=> __('Action',$this->plugin_name),
			'animation' 		=> __('Animation', $this->plugin_name),
			'comedy' 				=> __('Comedy',$this->plugin_name),
			'documentary' 	=> __('Documentary',$this->plugin_name),
			'family'				=> __('Family', $this->plugin_name),
			'film-noir'			=> __('Film-Noir',$this->plugin_name),
			'horror'				=> __('Horror',$this->plugin_name),
			'musical'				=> __('Musical',$this->plugin_name),
			'romance'				=> __('Romance',$this->plugin_name),
			'sport'					=> __('Sport',$this->plugin_name),
			'war'						=> __('War',$this->plugin_name),
			'adventure'			=> __('Adventure',$this->plugin_name),
			'biography'			=> __('Biography',$this->plugin_name),
			'crime'					=> __('Crime',$this->plugin_name),
			'drama'					=> __('Drama',$this->plugin_name),
			'fantasy'				=> __('Fantasy',$this->plugin_name),
			'history'				=> __('History',$this->plugin_name),
			'music'					=> __('Music',$this->plugin_name),
			'mystery'				=> __('Mystery',$this->plugin_name),
			'sci-fi'				=> __('Sci-Fi',$this->plugin_name),
			'thriller'			=> __('Thriller',$this->plugin_name),
			'western'				=> __('Western',$this->plugin_name),
			'news'					=> __('News',$this->plugin_name),
			'sitcom'				=> __('Sitcom',$this->plugin_name),
			'Reality-TV'		=> __('Reality-TV',$this->plugin_name),
			'game-show'			=> __('Game-Show',$this->plugin_name),
			'talk-show'			=> __('Talk-Show',$this->plugin_name),
			'short'					=> __('short',$this->plugin_name),
			);

			return empty($words[$string]) ? $string : $words[$string];

		}
		elseif($type == "country"){

			$string = strtolower($string);

			$words = array(
				'argentina'				=>__('Argentina',$this->plugin_name),
				'brazil'					=>__('Brazil',$this->plugin_name),
				'colombia'				=>__('Colombia',$this->plugin_name),
				'hong kong'				=>__('Hong Kong',$this->plugin_name),
				'iran'						=>__('Iran',$this->plugin_name),
				'malaysia'				=>__('Malaysia',$this->plugin_name),
				'pakistan'				=>__('Pakistan',$this->plugin_name),
				'russia'					=>__('Russia',$this->plugin_name),
				'sweden'					=>__('Sweden',$this->plugin_name),
				'usa'							=>__('USA',$this->plugin_name),
				'australia'				=>__('Australia',$this->plugin_name),
				'bulgaria'				=>__('Bulgaria',$this->plugin_name),
				'costa rica'			=>__('Costa Rica',$this->plugin_name),
				'france'					=>__('France',$this->plugin_name),
				'hungary'					=>__('Hungary',$this->plugin_name),
				'ireland'					=>__('Ireland',$this->plugin_name),
				'mexico'					=>__('Mexico',$this->plugin_name),
				'poland'					=>__('Poland',$this->plugin_name),
				'singapore'				=>__('Singapore',$this->plugin_name),
				'switzerland'			=>__('Switzerland',$this->plugin_name),
				'austria'					=>__('Austria',$this->plugin_name),
				'canada'					=>__('Canada',$this->plugin_name),
				'czech republic'	=>__('Czech Republic',$this->plugin_name),
				'germany'					=>__('Germany',$this->plugin_name),
				'iceland'					=>__('Iceland',$this->plugin_name),
				'italy'						=>__('Italy',$this->plugin_name),
				'netherlands'			=>__('Netherlands',$this->plugin_name),
				'portugal'				=>__('Portugal',$this->plugin_name),
				'south africa'		=>__('South Africa',$this->plugin_name),
				'thailand'				=>__('Thailand',$this->plugin_name),
				'belgium'					=>__('Belgium',$this->plugin_name),
				'china'						=>__('China',$this->plugin_name),
				'denmark'					=>__('Denmark',$this->plugin_name),
				'greece'					=>__('Greece',$this->plugin_name),
				'india'						=>__('India',$this->plugin_name),
				'japan'						=>__('Japan',$this->plugin_name),
				'new zealand'			=>__('New Zealand',$this->plugin_name),
				'romania'					=>__('Romania',$this->plugin_name),
				'spain'						=>__('Spain',$this->plugin_name),
				'uk'							=>__('UK',$this->plugin_name)
				);

				return empty($words[$string]) ? $string : $words[$string];

		} // EndIF
		else{
			return $string;
		}
	}


	/**
	* editing results (cleaning Strings and Arrays)
	*/

	private function omdb_serialize($fields){

		$serialize = array();
		$temp;
		$temp_str = "";
		$c = 0;
		$y = 0;

		foreach ($fields as $name => $value) {
			switch ($name) {
				case "Year":
				$serialize["Year"] = rtrim($value,'–');
					break;

				case "Runtime":
				$serialize["Runtime"] = trim(rtrim($value, 'min'));
				break;

				case "Genre":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y], "genre").', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Genre"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;

				case "Director":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y]).', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Director"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;

				case "Writer":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y]).', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Writer"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;

				case "Actors":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y]).', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Actors"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;

				case "Language":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y]).', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Language"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;


				case "Country":
				$temp = trim(strtolower($value));
				$c = substr_count($temp, ',');
				$temp = explode(", ", $temp);
				do{
					$temp_str .= $this->imdbi_translate($temp[$y], "country").', ';
					$y++;
					$c--;
					if($c == -1){
						break;
					}
				} while(true);
				$serialize["Country"] = rtrim($temp_str, ', ');
				$c = 0;
				$y = 0;
				$temp="";
				$temp_str="";
				break;

				default:
					$serialize[$name] = $value;
					break;

			}//End Switch
		}// End foreach

		return $serialize;

	}// End function



	/**
	* uploading poster via url and save it as thumbnail
	*/


	public function imdbi_save_poster(){

		isset($_REQUEST['poster_url']) ? $poster_url = $_REQUEST['poster_url']: $poster_url = NULL;

		global $wpdb;

		$wp_upload_dir = wp_upload_dir();

		if($poster_url !== NULL){

			// let's assume that poster already exist (uploaded once before).
			$file_name =  rtrim(basename($poster_url), '.jpg');
			//Searching
			$query = "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_title='$file_name'";
			$count = $wpdb->get_var($query);


			if($count == 0){

				/*
				* so poster wasn’t uploaded before.
				*/

				$tmp = download_url($poster_url, 0);

				$file_array = array(

					"name" 		=> basename($poster_url),
					"tmp_name"  => $tmp

					);

				//Check for download errors.

				if( is_wp_error($tmp) ){
					@chown($file_array['tmp_name'],465);
					@unlink( $file_array['tmp_name'] );
					return $tmp;
				}

				$id = media_handle_sideload($file_array, 0);

				// Check for handle sideload errors.

				if( is_wp_error( $id ) ){
					@chown($file_array['tmp_name'],465);
					@unlink( $file_array['tmp_name'] );
					return $id;
				}

				$attachment_url = wp_get_attachment_url( $id );

				echo $attachment_url;

				die();
			}
			else{
				$query = "SELECT guid FROM {$wpdb->posts} WHERE post_title='$file_name'";
				$poster_path = $wpdb->get_var($query);
				echo $poster_path;
				die();
			}
		}
	}


	public function omdb_view($type, $handle){
		include_once( 'partials/omdb_view.php' );
	}


/**
* display a warning to checkout new documentation.
*/
	public function imdbi_warning(){
		?>
		<div class="update-nag imdbi-warning">
			<p>
				<a href="#" id="imdbi-warning-close"><span class="dashicons dashicons-dismiss"></span></a>
			<b><?php _e( 'warning:', $this->plugin_name ); ?></b>
			 <?php _e( 'if you updated imdbi plugin to newer version, please read', $this->plugin_name ); ?>
			 <a href="https://github.com/iazami/imdbi/wiki/Function-Reference" target="_blank"><?php _e('Function Reference',$this->plugin_name); ?></a>
			</p>
			 </div>
		<?php
	}


}
