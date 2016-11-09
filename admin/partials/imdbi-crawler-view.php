<?php
/**
*
* Display OMDbAPI result in metabox area
*
* @package imdbi
* @subpackage imdbi/admin/partials
*
*
*/

$options = get_option($this->plugin_name);

$media_server = "http://ia.media-imdb.com";
$no_block = "http://nfqq.nvswi2lbfvuw2zdcfzrw63i.nblu.ru"; // media server miror
$imdb = "http://o53xo.nfwwiyromnxw2.nblu.ru/title/"; // imdbi.com/title mirror
$options = get_option($this->plugin_name);
set_time_limit ( 0 );

?>

<?php
  if($handle->code == "400" && $handle->message == "Movie not found!"){
    ?>
    <div class="crawler-error">
    <center>
    <h1><?php _e('Uh-oh Nothing Found.', $this->plugin_name); ?></h1>
    <br/>
    <a class="button-secondary crawler-error-btn" href="#" title="<?php esc_attr_e( "let's try again" ); ?>"><?php esc_attr_e( "let's try again", $this->plugin_name ); ?></a>
    </center>
    <br/><br/>
    </div>
    <?php
    die();
  }
?>

<div class="omdb-search-results">
<?php
if($type == "search"){
  ?>
  <b><a href="#" id="back1"><?php _e('← Back', $this->plugin_name); ?></a></b>
  <?php
  $output = "";
  foreach ($handle->data as $index => $values) {
    $output .="<ul><li class='omdb-ltr'><a href='#' class='omdb-result-link' title=".$values->imdbID."><b>";
    $output .= $values->Title;
    $output .= " (".rtrim($values->Year, '–')." - ".$values->Type.")</b></a></li>";
    $output .="<li>";
    $output .= "<i>http://www.imdb.com/title/".$values->imdbID."</i>";
    $output .= "</li></ul>";
  }
  echo $output;
}// Endif Seach
else{
  ?>
  <b style="display:block;"><a href="#" id="back2"><?php _e('← Back', $this->plugin_name); ?></a></b>
  <br/>

<?php

$src = "http://www.imdb.com/title/" . urlencode($handle->data->imdbID);

$crawler = file_get_html($src);

    foreach ($crawler->find("div[id=titleDetails] div[class=txt-block]") as $element) {

      foreach ($element->find("h4") as $subelement) {

        if(preg_match('/Budget/i', $subelement->innertext)){
            preg_match_all('/[\d](,)?\d*/', $element->plaintext, $matches);
            $budget = implode(',', $matches[0]);
        }

        if(preg_match('/Gross/i', $subelement->innertext)){
          $var =  str_replace(array(' ','Gross:','$'), '', $element->plaintext);
          $var = preg_replace('/\(([^\)]+)\)/', '', $var);
          preg_match_all('/[\d](,)?\d+/', $var, $matches);
          $gross = implode(',', $matches[0]);
        }

      }

    }

    if(!isset($budget)){
      $budget = "N/A";
    }
    if(!isset($gross)){
      $gross = "N/A";
    }

$crawler = file_get_html($imdb.urlencode($handle->data->imdbID));

  /**
  * looking for trailer ID
  *
  */

foreach($crawler->find("div[class=slate] a[itemprop=trailer]") as $element){

  $trailerID = trim($element->getAttribute("href"));

}
if(!isset($trailerID) OR empty($trailerID)){
  $trailer = 'N/A';
}
else{
  /**
  * now that trailer ID exists let's find trailer URL
  *
  */

  preg_match_all("/vi\\d+/i", $trailerID, $matches);

  $trailerID = implode('', $matches[0]);

  $src = "http://www.imdb.com/video/imdb/".$trailerID."/imdb/single?vPage=1";

  $crawler = file_get_html($src);

  preg_match_all('/<script class=\"imdb-player-data\" type=\"text\/imdb-video-player-json\">(.*?)<\/script>/', $crawler, $matches);

  $content = json_decode(trim($matches[1][0]));


  foreach($content as $element => $value){ // main foreach

    if(preg_match('/videoPlayerObject/i', $element)){ //if one

      foreach($value as $element => $value){ // foreach one

        foreach ($value as $element => $value) { // foreach tow

          if(preg_match('/videoInfoList/', $element)){ // if two

            foreach ($value as $element => $value) { // foreach three

              foreach ($value as $element => $value) { // foreach four
                $values[] = $value;
              }

            } // end foreach three

          } // end if two

        } //end foreach two

      } // end foreach one

    } // end if one

  } // end main foreach

  $c = 0;

  while(TRUE){
    if(preg_match('/video\/(.*?)/', $values[$c])){
      $trailer = $values[$c+1];
      break;
    }
    $c++;
  }

}// end Else

$fields = array(
  "Title" => $handle->data->Title,
  "Year" => $handle->data->Year,
  "Rated" => $handle->data->Rated,
  "Released" => $handle->data->Released,
  "Runtime" => $handle->data->Runtime,
  "Genre" => $handle->data->Genre,
  "Director" => preg_replace("/\(([^\)]+)\)/", '', $handle->data->Director), // this regex will remove Parenthesis with anything in it.
  "Writer" => preg_replace("/\(([^\)]+)\)/", '', $handle->data->Writer),
  "Actors" => preg_replace("/\(([^\)]+)\)/", '', $handle->data->Actors),
  "Plot" => $handle->data->Plot,
  "Language" => $handle->data->Language,
  "Country" => $handle->data->Country,
  "Awards" => $handle->data->Awards,
  "Metascore" => $handle->data->Metascore,
  "imdbRating" => $handle->data->imdbRating,
  "imdbVotes" => $handle->data->imdbVotes,
  "imdbID" => $handle->data->imdbID,
  "Poster" => $handle->data->Poster,
  "Type" => $handle->data->Type,
  "Gross" => $gross,
  "Budget" => $budget,
  "Trailer" => $trailer,
);

$fields = $this->omdb_serialize($fields);

  if($fields["Poster"] !== "N/A"){
    ?>
    <div class="omdb-wrapper">
      <div id="omdb-poster">
        <?php

          $poster = str_replace($media_server,$no_block,$fields["Poster"]); //replace no_block adress if the connection is restricted

          echo "<img src=".$poster." alt=".$fields["Title"]."/>" ;
         ?>
      </div>
    </div>
<?php

	$poster = preg_replace("/(SX)([0-9])+/", "SX".$options["posters_size"], $poster); // changing poster size

  }
  else{
    $poster = "N/A";
  }
  ?>
  <div class="omdb-wrapper">
    <div class="omdb-info">
      <ul>
        <li>
          <h1 class="omdb-ltr">
            <?php
            if($handle->data->Year !== "N/A")
                  echo $fields["Title"]." (".rtrim($handle->data->Year, '–').")";
            else
                  echo $fields["Title"];
             ?>
          </h1>
        </li>

        <li>
          <b><?php _e('Release Date:', $this->plugin_name); ?> </b><span class="omdb-ltr"><?php echo $fields["Released"]; ?></span>
        </li>

        <li>

           <b><?php _e('Genre:',$this->plugin_name); ?> </b><?php echo $fields["Genre"]; ?>

        </li>

        <li>
          <b><?php _e('Rating:',$this->plugin_name); ?> </b><?php echo $fields["imdbRating"].'/10'; ?><small><?php printf(__('from %s users',$this->plugin_name),$fields["imdbVotes"]); ?></small>
        </li>

        <li>
          <b><?php _e('Metascore:',$this->plugin_name); ?> </b><?php echo $fields["Metascore"].'/100'; ?>
        </li>

        <li>

           <b><?php _e('Director:',$this->plugin_name); ?> </b><span class="omdb-ltr"><?php echo $fields["Director"]; ?></span>

        </li>


        <li>

           <b><?php _e('Stars:',$this->plugin_name); ?> </b><span class="omdb-ltr"><?php echo $fields["Actors"]; ?></span>

        </li>

        <li>
        <span class="omdb-ltr">
           <?php
           if( $fields["Plot"] !== "N/A" && !is_rtl() )
              echo $fields["Plot"];
            ?>
      </span>
        </li>

        <li>
          <input class="button-primary imdbi-submit-info" type="button" name="imdbi-submit-info" value="<?php esc_attr_e( 'Submit Information',$this->plugin_name ); ?>" />
        </li>

      </ul>
    </div>


    <?php
    /**
    * Translate Languages (en_fa)
    * @since 2.1.0
    */

      if(get_locale() == 'fa_IR'){

        $translate = 'http://translate.parsijoo.ir/translate?mode=en_fa&text=' . urlencode($fields['Language']) . ',';
        $html = file_get_html($translate);
        $html = $html->find('div[class=translation]',0);
        $html = str_replace(array('زبان',' '), '', trim($html->plaintext));
        $html = substr(ltrim($html), 0, -1);
        $fields['Language'] = str_replace(',',', ',$html);

      }

    ?>


    <!--
    this is so important this div will save the crawler result
     and by using jquery set the results into metabox fields
    in order to avoid sending another request to recive data. -->

    <div id="imdbi-crawler-result" style="display:none !important;">
      <ul>
        <li id="imdbi-crawler-title"><?php echo $fields["Title"]; ?></li>
        <li id="imdbi-crawler-year"><?php echo $fields["Year"]; ?></li>
        <li id="imdbi-crawler-rated"><?php echo $fields["Rated"]; ?></li>
        <li id="imdbi-crawler-released"><?php echo $fields["Released"]; ?></li>
        <li id="imdbi-crawler-runtime"><?php echo $fields["Runtime"]; ?></li>
        <li id="imdbi-crawler-genre"><?php echo $fields["Genre"]; ?></li>
        <li id="imdbi-crawler-director"><?php echo $fields["Director"]; ?></li>
        <li id="imdbi-crawler-writer"><?php echo $fields["Writer"]; ?></li>
        <li id="imdbi-crawler-actors"><?php echo $fields["Actors"]; ?></li>
        <li id="imdbi-crawler-plot"><?php echo $fields["Plot"]; ?></li>
        <li id="imdbi-crawler-language"><?php echo $fields["Language"]; ?></li>
        <li id="imdbi-crawler-country"><?php echo $fields["Country"]; ?></li>
        <li id="imdbi-crawler-awards"><?php echo $fields["Awards"]; ?></li>
        <li id="imdbi-crawler-poster"><?php if($options['download_posters'] == '1'){echo $poster;}else{echo 'N/A';} ?></li>
        <li id="imdbi-crawler-metascore"><?php echo $fields["Metascore"]; ?></li>
        <li id="imdbi-crawler-imdbrating"><?php echo $fields["imdbRating"]; ?></li>
        <li id="imdbi-crawler-imdbvotes"><?php echo $fields["imdbVotes"]; ?></li>
        <li id="imdbi-crawler-imdbid"><?php echo $fields["imdbID"]; ?></li>
        <li id="imdbi-crawler-gross"><?php echo $fields["Gross"]; ?></li>
        <li id="imdbi-crawler-budget"><?php echo $fields["Budget"]; ?></li>
        <li id="imdbi-crawler-trailer"><?php echo $fields["Trailer"]; ?></li>
        <li id="imdbi-crawler-type"><?php echo $fields["Type"]; ?></li>
      </ul>
    </div>

  </div>
  <?php
} //End Else
?>

</div> <!-- End .omdb-search-results -->
