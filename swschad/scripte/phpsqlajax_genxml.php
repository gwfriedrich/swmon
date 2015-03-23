<?php

require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$vondatum = $_GET['vondatum'];
$bisdatum = $_GET['bisdatum'];
$bg = $_GET['bg'];
$sys = $_GET['sys'];

// Start XML file, create parent node
$doc = new DomDocument('1.0');
$node = $doc->createElement("schaeden");
$parnode = $doc->appendChild($node);

// Opens a connection to a mySQL server
$connection=mysql_connect ($sys, $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active mySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the schaeden table
$query = "SELECT * FROM schad$bg WHERE datum >= '$vondatum' AND datum <= '$bisdatum'
ORDER BY anzahl, datum DESC";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $doc->createElement("marker");
  $newnode = $parnode->appendChild($node);

  $newnode->setAttribute("id", $row['id']);
  $newnode->setAttribute("bg", (UTF8_encode($row['bg'])));
  $newnode->setAttribute("anzahl", (UTF8_encode($row['anzahl'])));
  $newnode->setAttribute("address", (UTF8_encode($row['address'])));
  $newnode->setAttribute("ort", (UTF8_encode($row['ort'])));
  $newnode->setAttribute("datum", (UTF8_encode($row['datum'])));
  $newnode->setAttribute("uhrzeit", (UTF8_encode($row['uhrzeit'])));
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("type", (UTF8_encode($row['type'])));
  $newnode->setAttribute("kz", (UTF8_encode($row['kz'])));
  $newnode->setAttribute("mitglied", (UTF8_encode($row['mitglied'])));
  $newnode->setAttribute("text1", (UTF8_encode($row['text1'])));
  $newnode->setAttribute("tname", (UTF8_encode($row['tname'])));
}

$xmlfile = $doc->saveXML();
echo $xmlfile;

?>
