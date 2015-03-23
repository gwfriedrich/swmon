<?php
require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$bg = $_GET['bg'];
$sys = $_GET['sys'];

if (empty($_GET['anzahl']))
$anzahl = "1";
else
$anzahl = $_GET['anzahl'];

$address = $_GET['address'];
$ort = $_GET['ort'];
$datum = $_GET['datum'];
$uhrzeit = $_GET['uhrzeit'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$type = $_GET['type'];
$kz = $_GET['kz'];
$mitglied = $_GET['mitglied'];
$text1 = $_GET['text1'];
$tname = $_GET['tname'];

// Opens a connection to a MySQL server
$connection = mysql_connect ($sys, $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Insert new row with user data
$query = sprintf("INSERT INTO erleg$bg " .
         " (id, bg, anzahl, address, ort, datum, uhrzeit, lat, lng, type, kz, mitglied, text1, tname ) " .
         " VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s','%s','%s' );",
         mysql_real_escape_string($bg),
         mysql_real_escape_string($anzahl),
         mysql_real_escape_string($address),
		 mysql_real_escape_string($ort),
		 mysql_real_escape_string($datum),
		 mysql_real_escape_string($uhrzeit),
         mysql_real_escape_string($lat),
         mysql_real_escape_string($lng),
         mysql_real_escape_string($type),
		 mysql_real_escape_string($kz),
		 mysql_real_escape_string($mitglied),
		 mysql_real_escape_string($text1),
		 mysql_real_escape_string($tname));

$result = mysql_query($query);

if (!$result) {
  die('Invalid query: ' . mysql_error());
}

?>