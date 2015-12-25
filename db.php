<?php

require('config.php');
/**********************************************
***					END OF					***
*** import CONFIG MySQL user/pass/db_name	***
***********************************************/

$link = mysql_connect('localhost',$db_user, $db_pass)or die( " Unable to CONNECT to server, administrators will be notified and check this problem soon. We apologize for the inconvenience. ");
//$link=mysqli_connect('localhost', $db_user, $db_pass) or die( " Unable to CONNECT to server, administrators will be notified and check this problem soon. We apologize for the inconvenience. ");


/****************************************************
* Help with fresh installations						*
* usage: URL/db.php?secretinstall=secret-word-here	*
*****************************************************/

//$secret_install_word = $_GET['secretinstall'];
//if ( (isset($secret_install_world)) && (!empty($secret_install_word)) ) {

if (isset( $_GET['secretinstall'])) {
	$secret_install_word = $_GET['secretinstall'];
		if ($correct_secret_word == $secret_install_word) {
		echo "ok!";
		$query = "CREATE DATABASE IF NOT EXISTS $db_name DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
		mysql_query($query);
		mysql_select_db($db_name);
		mysql_query($query); 
		$query = "CREATE TABLE IF NOT EXISTS flu_engine (
			cache_url VARCHAR(250),
			consumer_key VARCHAR(100),
			consumer_secret VARCHAR(100),
			bearer_token VARCHAR(200)
			) 
		ENGINE = MYISAM"; 
		mysql_query($query);
			$query = "CREATE TABLE IF NOT EXISTS flu_tweets (  user_name VARCHAR(20),
			user_id INT UNSIGNED,
			tweet_text VARCHAR(140),
			tweet_id BIGINT UNSIGNED,
			tweet_date INT UNSIGNED,
			latitude DECIMAL(10,6) NOT NULL,
			longitude DECIMAL(10,6) NOT NULL,
			aggravation TINYINT UNSIGNED
			)
		ENGINE = MYISAM";
		mysql_query($query);
		$query = "CREATE TABLE IF NOT EXISTS flu_meta (
			user_id INT UNSIGNED,
			total_count TINYINT UNSIGNED
			)
		ENGINE = MYISAM";
		mysql_query($query);
		
		/*************************************************************
		* Create just one entry on fresh install for cache_url so we *
		* can check if new database and update only					 *
		**************************************************************/
		//mysql_query("INSERT INTO flu_engine (cache_url) VALUES ('?since_id=**********************&q=flu%20OR%20chills%20OR%20sore%20throat%20OR%20headache%20OR%20runny%20nose%20OR%20vomiting%20OR%20sneazing%20OR%20fever%20OR%20diarrhea%20OR%20dry%20cough&result_type=recent&include_entities=1')");
    mysql_query("INSERT INTO flu_engine (cache_url, consumer_key, consumer_secret ) VALUES ('?since_id=**********************&q=flu%20OR%20chills%20OR%20sore%20throat%20OR%20headache%20OR%20runny%20nose%20OR%20sneazing%20OR%20fever%20OR%20dry%20cough&result_type=recent&include_entities=1', '***************', '********************************************')");
		
	}
}
/****************************************************
* END OF Help with fresh installations				*
*****************************************************/


//select a database to work with
$selected = mysql_select_db($db_name,$link) or die("Unable to SELECT database, administrators will be notified and check this problem soon. We apologize for the inconvenience.");


/******************************************/
?>
