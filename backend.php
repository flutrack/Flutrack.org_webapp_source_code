<html>
<head>
  <title>flutrack test</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head/>
<body>
<body/>
<html/>

<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

$api_URL = 'https://api.twitter.com/1.1/search/tweets.json';
//NO NEED IT'S STORED TO MySQL//$API_query = 'flu+OR+chills+OR+sore+throat+OR+headache+OR+runny+nose+OR+vomiting+OR+sneazing+OR+fever+OR+diarrhea+OR+dry+cough';
$API_parameters = '&include_entities=true&result_type=recent';

//connect to the database
require('db.php');

/********************************
* FETCH refresh_url stored in MySQL
*********************************/

$result = mysql_query("SELECT cache_url FROM flu_engine");
$field = mysql_fetch_array($result);
$API_query = $field['cache_url'];
$cache = '';

var_dump($API_query);

// Get the first refresh url, because it is the fresher, and the last is the oldest.

$guard=0;
 

/************************
//DO-WHILE LOOP START
************************/

do {



  echo "api_url:$api_URL<br />";
  echo "api_query:$API_query<br />";

  //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

  //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

  //curl_setopt($ch, CURLOPT_CAINFO, "/etc/ssl/certs/ca-certificates.crt");

  include('oauth.php');
  $result = mysql_query("SELECT bearer_token FROM flu_engine");
  $field = mysql_fetch_array($result);
  $bearer = $field['bearer_token'];
  
  $headers = array( 
    "Authorization: Bearer $bearer"
  ); 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "$api_URL"."$API_query");
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, " **************** / mailto:************** ");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  $data = curl_exec($ch);
  $info = curl_getinfo($ch); 
  $http_code = $info['http_code'];
  curl_close($ch);

  //TODO
  // $http_code is int, so IFs ($http_code == 400) 
  // we switch from JSON payload error handling to HTTP Return Headers.


  /* TO INCLUDE
  if ($http_code == 400){
  error or limit
  }
  else if ($http_code == 401){
  auth problem
  }
  else if ($http_code == 403){
  update limit
  }
  404
  error
  406
  wrong request
  420
  we are rate limited
  500
  twitter api is down
  502
  Twitter is under maintenance
  503
  Twitter problem

  END TO INCLUDE*/

  // location bad keywords
  $pattern = '/(hell|heaven|paradise|home|road|mother|fairytail|universe|mars|jupiter|moon|planet|galaxy|house|mom|mercury|venus|saturn|uranus|neptune|pluto|sun|solar|asteroid|comet)/i';
  $aggrav_pattern = '/(getting worse|get worse|weaker|deterioration|deteriorate|worsening|degenerate|regress|exacerbate|relapse|intensify|compound|Become aggravated|get into a decline|go to pot)/i';




  $json = json_decode($data, true);

  /********************************************************
  //Process if no error code is present, else break; !
  **********************************************************/

  if (!isset($json['errors'])) 
  {

    if ($guard==0)
    { 
      $cache = $json['search_metadata']['refresh_url'];
      var_dump($cache);
    }
    $guard+=1;

    //DO-WHILE LOOP CONTROL
    if (isset($json['search_metadata']['next_results'])) {
      $API_query = $json['search_metadata']['next_results'];
      }
    else {
      $API_query = NULL;
      }
    //declarations
    $datetime = '';
    $tweet_text = '';
    $twitter_account_name = '';
    $twitter_account_ID = '';
    $map = '';
    $stripped_tweet = '';

    //DEBUG
    echo '<pre>';
    print_r($json);
    echo '</pre>';

    //process them
    
 
    
    foreach ($json['statuses'] as $tweet) 
    {
      //for ($i=0; $i<$counteras; $i++){    
      //echo "<br />**********************************************<br />**********************************************<br />";
      //echo $tweet['id'];
      //echo "<br />**********************************************<br />**********************************************<br />";
        $datetime = $tweet['created_at'];
        //$date = date(?M d, Y?, strtotime($datetime));
        //$time = date(?g:ia?, strtotime($datetime));
        $tweet_text = $tweet['text'];
        $tweet_ID = $tweet['id_str'];
        $twitter_account_name = $tweet['user']['screen_name'];
        $twitter_account_ID = $tweet['user']['id_str']; //id_string giati polu megala noumera
        $stripped_tweet = preg_replace('/(#|@)\S+|(RT:)|(RT)/', '', $tweet_text); // remove hashtags
        $stripped_tweet = preg_replace('#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#', '', $stripped_tweet);
        //WORKING //   $stripped_tweet = preg_replace('/(RT)|(^|\s)@\S+/', '', $tweet_text); // remove @mentions AND "RT"
      
        if (isset($json['geo'])){
          echo "geolocation found </br>";
          $geo_tweet_location_lat = $tweet['geo']['coordinates']['0'];
          $geo_tweet_location_long = $tweet['geo']['coordinates']['1'];
        }
        else {
          echo "geolocation did not found,location search";
          /********************************************/
          //user is returned in results //
          /********************************************/
          
          //$geoless_tweet_location now contains "location" field from user/lookup function, 
          //some sanitization must be added because location can be "Hello Kitty world", "Heaven", etc
          $geoless_tweet_location = $tweet['user']['location'];
        }

        
        echo "</br>";
        echo " User:  $twitter_account_name (Me ID:$twitter_account_ID) said : $tweet_text </br> Stis: $datetime";
        
        if (isset($json['geo'])) {
          echo "From location with coords: ".'<a href="//maps.google.com/maps?q='.$geo_tweet_location_lat.",".$geo_tweet_location_long.'&z=15" target="_blank">'."$geo_tweet_location_lat , $geo_tweet_location_long</a>";  //removed really long line of comment just saying it's OK
          echo "</br></br>";
        }
        else {
          //check bad location keywords
          $so = preg_match ($pattern , $geoless_tweet_location);
          if ($so) {
            echo "Bad keyword FOUND, SKIPPING"; 
            $result = NULL; //playing safe
          }
          else {
            $result = lookup("$geoless_tweet_location");
            echo "GEO was empty, get location from account API: $geoless_tweet_location"; //do not use null
            //WE Added some more logic
            if ($result == NULL){
              echo "</br>Ask Google for location $geoless_tweet_location, nothing found";
            }
            else{
              echo "</br>Google replied that $geoless_tweet_location, coords are:" .'<a href="//maps.google.com/maps?q='.$result['latitude'].",".$result['longitude'].'&z=15" target="_blank">'.$result['latitude'].",".$result['longitude']."</a>"; //Tricky shit indeed
              $aggravated = preg_match ($aggrav_pattern , $tweet_text); // it will be saved so check for aggravation.
              }		
            echo "</br></br>";
          }
        }
      
        //DEBUG REGEX
        echo "---------------------------------------------------</br>";
        echo "</br></br>PRIN: $tweet_text </br>META $stripped_tweet </br>";
        echo "---------------------------------------------------</br>";
        //sql query in order to save in mysql database
            
        if ( isset($geo_tweet_location_lat) OR isset($result) ) {
          $stripped_tweet = mysql_real_escape_string($stripped_tweet);
          $unix_time = strtotime($datetime);
          
          if (isset($geo_tweet_location_lat)){
            mysql_query("INSERT INTO flu_tweets (user_name, user_id, tweet_text, tweet_id, tweet_date, latitude, longitude, aggravation )
            VALUES ('$twitter_account_name', '$twitter_account_ID', '$stripped_tweet', '$tweet_ID',	'$unix_time', '$geo_tweet_location_lat', '$geo_tweet_location_long', '$aggravated'						
            )") or die (mysql_error()); 
            $geo_tweet_location_lat = NULL;
            $geo_tweet_location_long = NULL;
          }
          else {
            //quick & dirty
            $array_lat = $result['latitude'];
            $array_long = $result['longitude'];
            mysql_query("INSERT INTO flu_tweets (user_name, user_id, tweet_text, tweet_id, tweet_date, latitude, longitude, aggravation ) 
            VALUES ('$twitter_account_name', '$twitter_account_ID', '$stripped_tweet', '$tweet_ID',	'$unix_time', '$array_lat', '$array_long', '$aggravated'						
            )") or die (mysql_error()); 
            $result = NULL;
          }
        }
      // }//for end
    }//foreach end
  }// //END error check in response
  else { 
    echo "</br> Twitter over capacity problems - !ABORTING!";                  //////////////////
    echo "</br></br> DUMPING TWITTER JSON ----------- <br /> --------------- </br>";
    //DEBUG
    echo '<pre>';
    print_r($json);
    echo '</pre>';
    
    $we_have_errors = TRUE; 									//Apopriate actions if error code is present
    break;  													//  (skipping storing the cache refresh url)
    }
} while ($API_query!=NULL);

/************************
//DO-WHILE LOOP END
************************/


if (!isset($we_have_errors)){
  //$cache = $json['search_metadata']['refresh_url'];
  /*
   * UGLY FIX***
   */
  //$cache = $guarded_cache_url;
  mysql_query("UPDATE flu_engine SET cache_url='$cache'"); //SAVE the refresh url
}


// city returned, call function in order to find coords. 

function lookup($string){ 
  $string = str_replace (" ", "+", urlencode($string));
  $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $details_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = json_decode(curl_exec($ch), true);
  // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
  if ($response['status'] != 'OK') {
  return null;
  }
  //print_r($response);
  $geometry = $response['results'][0]['geometry'];
  $longitude = $geometry['location']['lng'];
  $latitude = $geometry['location']['lat'];
  $array = array(
    'latitude' => $geometry['location']['lat'],
    'longitude' => $geometry['location']['lng']
  );
  return $array;
}
 
//Just before exit do the JSON magic
 include('genjson.php');

//close the database connection
	mysql_close($link);
?>
