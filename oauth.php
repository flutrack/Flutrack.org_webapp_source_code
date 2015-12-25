<?php

require('config.php');

// url encode the consumer_key and consumer_secret in accordance with RFC 1738
$encoded_consumer_key = urlencode($consumer_key);
$encoded_consumer_secret = urlencode($consumer_secret);
// concatinate encoded consumer, a colon character and the encoded consumer secret
$bearer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
// base64-encode bearer token
$base64_encoded_bearer_token = base64_encode($bearer_token);
// step 2
$url = "https://api.twitter.com/oauth2/token"; // url to send data to for authentication
$headers = array( 
    "POST /oauth2/token HTTP/1.1", 
    "Host: api.twitter.com", 
    "User-Agent:  flutrack.org Application / mailto:***************************",
    "Authorization: Basic ".$base64_encoded_bearer_token."",
    "Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
    "Content-Length: 29",
    "grant_type=client_credentials"
); 

$ch = curl_init();  // setup a curl
curl_setopt($ch, CURLOPT_URL,$url);  // set url to send to
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set our custom headers
curl_setopt($ch, CURLOPT_POST, 1); // send as post
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Only return the response, set 0 to debug output of curl headers
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); // post body/fields to be sent
//$header = curl_setopt($ch, CURLOPT_HEADER, 1); // send custom headers
//$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$result = curl_exec($ch); // run the curl
$info = curl_getinfo($ch); 
$http_code = $info['http_code'];


$json = json_decode($result);
$token = $json->access_token;

if ($http_code == 200){ //TO-DO: Error handling
  if ($json->token_type == "bearer"){
   mysql_query("UPDATE flu_engine SET bearer_token='$token'");
//   echo "DEBUG: we successfully acquired or have already valid bearer token<br />";
//   echo "DEBUG: this was: $token<br />";
  }
  else {
    echo "ERROR: token type not bearer";
  }
}
else {
  echo "ERROR :something went wrong";
}

curl_close($ch); 
?>
