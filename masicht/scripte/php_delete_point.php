<?php
require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$sys = $_GET['sys'];
$point_id = $_GET['point_id'];

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

// Insert new row with user data		DELETE FROM tabelle WHERE Ort = 'Marlesreuth'
$query = "DELETE FROM marlesreuth WHERE id = $point_id ";
 

$result = mysql_query($query);

if (!$result) {
  die('Invalid query: ' . mysql_error());
}

?>