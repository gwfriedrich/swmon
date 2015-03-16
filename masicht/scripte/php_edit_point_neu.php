<?php
require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$sys = $_GET['sys'];
$id = $_GET['id'];
$anzahl = $_GET['anzahl'];
$address = $_GET['address'];
$ort = $_GET['ort'];
$datum = $_GET['datum'];
$uhrzeit = $_GET['uhrzeit'];
$type = $_GET['type'];
$kz = $_GET['kz'];
$mitglied = $_GET['mitglied'];
$text1 = $_GET['text1'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];

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

// Edit data with user data
$query = sprintf("UPDATE marlesreuth SET anzahl = '%s', address = '%s', ort = '%s', datum = '%s', uhrzeit = '%s' ,type = '%s',kz = '%s',mitglied = '%s',text1 = '%s', lat = '%s', lng = '%s' WHERE id = '%s';",
		mysql_real_escape_string($anzahl),
		mysql_real_escape_string($address),
		mysql_real_escape_string($ort),
		mysql_real_escape_string($datum),
		mysql_real_escape_string($uhrzeit),
		mysql_real_escape_string($type),
		mysql_real_escape_string($kz),
		mysql_real_escape_string($mitglied),
		mysql_real_escape_string($text1),
		mysql_real_escape_string($lat),
		mysql_real_escape_string($lng),
		mysql_real_escape_string($id));

$result = mysql_query($query);

if (!$result) {
  die('Invalid query: ' . mysql_error());
}

?>