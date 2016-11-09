<?php
/**
* Public functions
*
* @package    Imdbi
* @subpackage Imdbi/public
* @author     mohammad azami <iazami@outlook.com>
*/

/* this function just checkout the meta box value */
function imdbi_check($name){
  global $post;
  $name = trim(strtolower($name));

  if($name == "poster" ){
    if( get_post_meta($post->ID, 'IMDBI_Poster', true) != ''  ){
      return true;
    }
    elseif( wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) != false ){
      return true;
    }
    else{
      return false;
    }
  }

  if($name == "rank" && get_post_meta( $post->ID, 'imdbID', true ) != ''){
    $id = get_post_meta( $post->ID, 'imdbID', true );
    $top_list = get_option('imdbi_top_list');
    $is_top = (isset($top_list[$id]) ? true : false );
    return $is_top;
  }

  /* List of meta box fields (meta_name => meta_key) */
  $fields = array(
    'imdbid'		 => 'imdbID',
    'title'	 		 => 'IMDBI_Title',
    'year'	 		 => 'IMDBI_Year',
    'type'	 		 => 'IMDBI_Type',
    'trailer' 	 => 'IMDBI_Trailer',
    'budget' 		 => 'IMDBI_Budget',
    'gross'			 => 'IMDBI_Gross',
    'imdbvotes'  => 'IMDBI_imdbVotes',
    'imdbrating' => 'IMDBI_imdbRating',
    'metascore'	 => 'IMDBI_Metascore',
    'actors'		 => 'IMDBI_Actors',
    'writer'		 => 'IMDBI_Writer',
    'director'	 => 'IMDBI_Director',
    'runtime' 	 => 'IMDBI_Runtime',
    'released'	 => 'IMDBI_Released',
    'rated' 		 => 'IMDBI_Rated',
    'plot' 			 => 'IMDBI_Plot',
    'awards'		 => 'IMDBI_Awards',
    'language'	 => 'IMDBI_Language',
    'country' 	 => 'IMDBI_Counetry',
    'genre'			 => 'IMDBI_Genre'
  );

  /* Indolence is the key :) who need switch cases ? */
  foreach($fields as $meta_name => $meta_key){
    if( $name == $meta_name && get_post_meta( $post->ID, $meta_key, true ) != '' ){
      return true;
    }
    else{
      continue;
    }
  }
  return false;
}



/* this function will print informations that saved in meta box */
function imdbi($name){
  global $post;
  $name = trim(strtolower($name));

  if($name == "poster" ){
    if( get_post_meta($post->ID, 'IMDBI_Poster', true) != '' ){
    return get_post_meta( $post->ID, 'IMDBI_Poster', true );
    }
    elseif(  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) != false ){
      return wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); // if there is no POSTER it will print thumbnail url
    }
    else{
      return __('N/A','imdbi');
    }
  }

  if($name == "rank" && get_post_meta( $post->ID, 'imdbID', true ) != ''){
    $id = get_post_meta( $post->ID, 'imdbID', true );
    $top_list = get_option('imdbi_top_list');
    $rank = (isset($top_list[$id]) ? $top_list[$id] : 0 );
    return $rank;
  }

  /* List of meta box fields (meta_name => meta_key) */
  $fields = array(
    'imdbid'		 => 'imdbID',
    'title'	 		 => 'IMDBI_Title',
    'year'	 		 => 'IMDBI_Year',
    'type'	 		 => 'IMDBI_Type',
    'trailer' 	 => 'IMDBI_Trailer',
    'budget' 		 => 'IMDBI_Budget',
    'gross'			 => 'IMDBI_Gross',
    'imdbvotes'  => 'IMDBI_imdbVotes',
    'imdbrating' => 'IMDBI_imdbRating',
    'metascore'	 => 'IMDBI_Metascore',
    'actors'		 => 'IMDBI_Actors',
    'writer'		 => 'IMDBI_Writer',
    'director'	 => 'IMDBI_Director',
    'runtime' 	 => 'IMDBI_Runtime',
    'released'	 => 'IMDBI_Released',
    'rated' 		 => 'IMDBI_Rated',
    'plot' 			 => 'IMDBI_Plot',
    'awards'		 => 'IMDBI_Awards',
    'language'	 => 'IMDBI_Language',
    'country' 	 => 'IMDBI_Counetry',
    'genre'			 => 'IMDBI_Genre'
  );

  /* Indolence is the key :) who need switch cases ? */
  foreach($fields as $meta_name => $meta_key){
    if( $name == $meta_name && get_post_meta( $post->ID, $meta_key, true ) != '' ){
      return get_post_meta( $post->ID, $meta_key, true );
    }
    else{
      continue;
    }
  }
  return __('N/A','imdbi'); // so instead of NULL you can return a proper message in any language.
}




/* this function will return informations that saved in meta box */
function imdbi_fetch_meta($name){
  global $post;
  $name = trim(strtolower($name));
  $alt_name = get_option('imdbi_alternative_fields');

  if($name == "poster" ){
    if( get_post_meta($post->ID, 'IMDBI_Poster', true) != '' ){
    return get_post_meta( $post->ID, 'IMDBI_Poster', true );
    }
    elseif(  wp_get_attachment_url( get_post_thumbnail_id($post->ID) ) != false ){
      return wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); // if there is no POSTER it will print thumbnail url
    }
    else{
      return __('N/A','imdbi');
    }
  }

  if($name == "rank" && get_post_meta( $post->ID, 'imdbID', true ) != ''){
    $id = get_post_meta( $post->ID, 'imdbID', true );
    $top_list = get_option('imdbi_top_list');
    $rank = (isset($top_list[$id]) ? $top_list[$id] : 0 );
    return $rank;
  }

  /* List of meta box fields (meta_name => meta_key) */
  $fields = array(
    'imdbid'		 => 'imdbID',
    'title'	 		 => 'IMDBI_Title',
    'year'	 		 => 'IMDBI_Year',
    'type'	 		 => 'IMDBI_Type',
    'trailer' 	 => 'IMDBI_Trailer',
    'budget' 		 => 'IMDBI_Budget',
    'gross'			 => 'IMDBI_Gross',
    'imdbvotes'  => 'IMDBI_imdbVotes',
    'imdbrating' => 'IMDBI_imdbRating',
    'metascore'	 => 'IMDBI_Metascore',
    'actors'		 => 'IMDBI_Actors',
    'writer'		 => 'IMDBI_Writer',
    'director'	 => 'IMDBI_Director',
    'runtime' 	 => 'IMDBI_Runtime',
    'released'	 => 'IMDBI_Released',
    'rated' 		 => 'IMDBI_Rated',
    'plot' 			 => 'IMDBI_Plot',
    'awards'		 => 'IMDBI_Awards',
    'language'	 => 'IMDBI_Language',
    'country' 	 => 'IMDBI_Counetry',
    'genre'			 => 'IMDBI_Genre'
  );

  /* Indolence is the key :) who need switch cases ? */
  foreach($fields as $meta_name => $meta_key){
    if( $name == $meta_name && get_post_meta( $post->ID, $meta_key, true ) != '' ){
      return get_post_meta( $post->ID, $meta_key, true );
    }
    else{
      continue;
    }
  }
  return __('N/A','imdbi'); // so instead of NULL you can return a proper message in any language.
}




/* generating shortcodes */

function imdbi_shortcode_generator($atts){
  extract(
  shortcode_atts(
  array(
    'meta_name' => ''
  )
  ,$atts)
);
  return imdbi_fetch_meta($meta_name);
}

add_shortcode('imdbi','imdbi_shortcode_generator');
add_shortcode('IMDBI','imdbi_shortcode_generator');

?>
