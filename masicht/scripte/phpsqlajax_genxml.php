<?php

require("phpsqlajax_dbinfo.php");

// Gets data from URL parameters
$sys = $_GET['sys'];
$vondatum = $_GET['vondatum'];
$anztyp = $_GET['anztyp'];


// Start XML file, create parent node
$doc = new DomDocument('1.0');
$node = $doc->createElement("marlesreuth");
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

// anztyp s Anzeige nur Sichtungen e Anzeige nur Erlegungen r Anzeige nur Reviereinrichtungen leer alles
if ($anztyp == 'r') {
	$query = "SELECT * FROM marlesreuth WHERE datum = '' ORDER BY datum DESC";
} elseif ($anztyp == 'e') {
	$query = "SELECT * FROM marlesreuth WHERE datum >= '$vondatum' AND kz > '' ORDER BY datum DESC";
} elseif ($anztyp == 's') {
	$query = "SELECT * FROM marlesreuth WHERE datum > '' AND datum >= '$vondatum' ORDER BY datum DESC";
} else {
	$query = "SELECT * FROM marlesreuth ORDER BY datum DESC";
}
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
}



$xmlfile = $doc->saveXML();
echo $xmlfile;

?>
