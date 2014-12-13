<?php
if (file_exists("results.json")){
	if (copy("results.json","results_old.json")) {
		unlink("results.json");
		require_once('db.php');
		$result_json = mysql_query("SELECT user_name, tweet_text, latitude, longitude, tweet_date, aggravation FROM flu_tweets WHERE UNIX_TIMESTAMP(NOW()) - tweet_date < 864000;");
                //$result_json = mysql_query("SELECT user_name, tweet_text, latitude, longitude, tweet_date, aggravation FROM DB_table.flu_tweets WHERE tweet_date > 1362873600 AND tweet_date < 1363737600;");
		// UNIX_TIMESTAMP(NOW()) - tweet_date < 604800;
		$rows = array();
		while($r = mysql_fetch_assoc($result_json)) {
			$rows[] = $r;
		}
		$fp = fopen('results.json', 'w');
		fwrite($fp, json_encode($rows));
		fclose($fp);
		}
	else {
		echo "JSON ERROR: couldnt rename/move";
		}
	}
else {
	echo "JSON ERROR: file not found";
	}
?>
