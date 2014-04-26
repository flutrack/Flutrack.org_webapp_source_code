<?php
require_once('db.php');

$result = mysql_query("SELECT tweet_text FROM flu_tweets WHERE 1");
while ($row = mysql_fetch_assoc($result)) {
    echo $row["tweet_text"];
    echo '</br>';
}

/*
$result = mysql_query("SELECT user_name, tweet_text, latitude, longitude, tweet_date FROM flu_tweets WHERE 1");
while ($row = mysql_fetch_assoc($result)) {
    echo $row["user_name"];
echo " ` ";
    echo $row["tweet_date"];
echo " ` ";
    echo $row["tweet_text"];
echo " ` ";
    echo "</br>";
}
*/
?>
