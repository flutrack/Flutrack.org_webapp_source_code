<?php
/************************************************************
*                  Simple Flutrack API                  *****
*                  Version 1.1                          *****
* USAGE:                                                  ***
* http://api.flutrack.org/?s=keywordORkeyword2ANDkeyword3 ***
*                         &a=TRUE                         ***
*                         &time=7                         ***
*                         &limit=60                     *****
*                                                       *****
*************************************************************/

header('Content-type: application/json; charset=utf-8');
require("db.php");

if ( (!isset( $_GET['s'])) &&  (!isset( $_GET['a'])) &&  (!isset( $_GET['s'])) &&  (!isset( $_GET['time'])) &&  (!isset( $_GET['limit'])) )
{
mysql_close($link);
exit;
}

$query = "SELECT user_name, tweet_text, latitude, longitude, tweet_date, aggravation FROM flu_tweets WHERE ";

if (isset( $_GET['s'])) {
	$search = $_GET['s'];
	mysql_real_escape_string($search);

	$search = str_replace( 'AND', "%') AND (tweet_text LIKE '%", $search ); 
	$search = str_replace( 'OR', "%') OR (tweet_text LIKE '%", $search ); 

	$query .= "(tweet_text LIKE '%".$search."%')";
	}
	
if (isset( $_GET['a'])) {
	$aggravated = filter_input(INPUT_GET, "a", FILTER_VALIDATE_BOOLEAN, array("flags" => FILTER_NULL_ON_FAILURE));
	if ($aggravated){
			if (isset($search)){
				$query .= " AND aggravation = 1";
				}
			else{
				$query .= " aggravation = 1";
				}
		}
	else{
			if (isset($search)){
				$query .= " AND aggravation = 0";
				}
			else{
				$query .= " aggravation = 0";
				}
		}
	}

if (isset( $_GET['time'])) {
	$time = filter_input(INPUT_GET, "time", FILTER_VALIDATE_INT, array("flags" => FILTER_NULL_ON_FAILURE));
	if ($time < 10 AND $time > 0){
		$time = (time() - ($time * 86400));
		if (isset($search)||isset($aggravated)){
				$query .= " AND tweet_date > $time";
				}
			else{
				$query .= " tweet_date > $time";
				}
		}
	else{
		$time = time() - 86400;
			if (isset($search)||isset($aggravated)){
				$query .= " AND tweet_date > $time";
				}
			else{
				$query .= " tweet_date > $time";
				}
		}
	}
else{
	$time = time() - 86400;
		if (isset($search)||isset($aggravated)){
				$query .= " AND tweet_date > $time";
				}
			else{
				$query .= " tweet_date > $time";
				}
	}

if (isset( $_GET['limit'])) {
	$limit = filter_input(INPUT_GET, "limit", FILTER_VALIDATE_INT, array("flags" => FILTER_NULL_ON_FAILURE));
	if (!empty($limit)){
		if ($limit < 101 AND $limit > 0){
			$query .= " LIMIT $limit";
			}
		else {
			$query .= " LIMIT 10";
			}
		}
	}
else{
	$query .= " LIMIT 10";
	}

//$result_json = mysql_query("SELECT user_name, tweet_text, latitude, longitude, tweet_date, aggravation FROM flu_tweets WHERE tweet_text = ". mysql_real_escape_string($search) ." LIMIT = '$limit' " ;
$query .= ";";
$results_json = mysql_query($query);
$rows = array();
while($r = mysql_fetch_assoc($results_json)) {
	$rows[] = $r;
	}
echo json_encode($rows);
//echo "</br>".$query;	
//echo mysql_errno($link) . ": " . mysql_error($link) . "\n";
mysql_close($link);
//exit;
?>
