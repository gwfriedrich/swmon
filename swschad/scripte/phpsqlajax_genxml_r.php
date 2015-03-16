<?php

require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$bg = $_GET['bg'];
$sys = $_GET['sys'];

// Start XML file, create parent node
$doc = new DomDocument('1.0');
$node = $doc->createElement("reviere");
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

// Select all the rows in the revier table
$query = "SELECT * FROM revier WHERE bg = '$bg'
ORDER BY revier";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $doc->createElement("revier");
  $newnode = $parnode->appendChild($node);

  $newnode->setAttribute("id", $row['id']);
  $newnode->setAttribute("bg", (UTF8_encode($row['bg'])));
  $newnode->setAttribute("revier", (UTF8_encode($row['revier'])));
}

$xmlfile = $doc->saveXML();
echo $xmlfile;

?>
