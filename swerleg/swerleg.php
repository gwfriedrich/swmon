<?php
session_start();
if(!$_SESSION['logged_in'])
	header('Location: ../index.html');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 

<meta name="description" content="Google Maps Schwarzwild Monitoring" />
<meta name="keywords" content="Schwarzwild Monitoring" />
<meta name="author" content="Guenter Friedrich" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />

    <title>Schwarzwildmonitoring</title> 

	<base href="http://localhost/swmon/" />
<!-- <base href="http://www.jagenfischennaila.de" />   -->

	<link rel="stylesheet" type="text/css" href="./style/style.css" />

<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.7&amp;sensor=false&amp;libraries=drawing"></script>
<script type="text/javascript" src="./mapscript/v3/downloadxml.js"></script>
<script type="text/javascript" src="./mapscript/v3/tooltip.js"></script>
<script type="text/javascript" src="./jquery/jquery-1.8.3.min.js"></script>

<script type="text/javascript">
    var datefield=document.createElement("input")
    datefield.setAttribute("class", ".dat1")
    if (datefield.type!=".dat1"){ //eigenes Datumsformat date1, load files for jQuery UI Date Picker
          document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
//        funktioniert mit google maps nicht
//        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
          document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n')
    }
</script>

<style type="text/css">	
	#inhalt {
	width: 980px;
	margin: 0 auto; 
	}
	#Kartentitel {
	font-size:160%;
	font-weight:bold;
	color: green;
	margin-bottom:8px;
	text-align:center;
	}
	#map_canvas {
	font-family:Helvetica,Verdana,Arial,sans-serif;
	width:724px;
	height:510px;
	}
	#side_bar {
	font-family:Helvetica,Verdana,Arial,sans-serif;
	height:355px;
	width:240px;
	overflow:auto;
	margin-left:0px;
	margin-top:3px;
	text-decoration: none;
	}
	#side_bar_titel {
	color: black;
	margin-left:8px;
	margin-top:3px;
	font-size:15px;
	text-decoration: underline;
	}
	#side_bar a {
	text-decoration: none;
	margin-left:5px;
	font-weight:normal;
	}
	#liste_unten {
	padding-left: 1.5em;
	padding-right: 1em;
	padding-bottom: 1.5em;
	list-style-type:disc;
	list-style-position:outside;
	font-weight:normal;
	line-height: 1.4em;
	}
	#infofenster {
	width:310px;
	border: none;
	color: black;
	line-height:1.5;
	overflow:hidden;
	margin:0px;
	}
	#infofenster_titel { 
	width:75px;
	font-size:15px;
	font-weight:bold;
	border: none;
	background-color: #CCD9CC;
	color: black;
	line-height:1.5;
	padding-left:5px;
	margin-bottom:2px;
	}
	#tt {
	font-family: Arial,Verdana,sans-serif;
	font-size:15px;
	font-weight:bold;
	position:absolute; 
	display:block; 
	}
	#ttcont {
	display:block; 
	padding:2px 4px 2px 4px; 
	margin-left:5px;
	background:#666; 
	border:2px black solid;
	color:#FFF
	}
	.delete_button {
	font-size:14px;
	font-weight:normal;
	cursor:pointer;
	border-width:2px;
	margin-top:5px;
	padding:3px;
	background:#FF3300;
	float:left;
	}
	.edit_button {
	font-size:14px;
	font-weight:normal;
	cursor:pointer;
	border-width:2px;
	margin-top:5px;
	margin-right:10px;
	padding:3px;
	background:#80ff00;
	float:right;
	}
	img.klein {
	border:none;
	vertical-align:middle;
	margin-bottom: 2px;
	width: 16px;
	height: 17px;
	}
	img.xyz {
	border:none;
	vertical-align:middle;
	margin-bottom: 2px;
	margin-right: 2px;
	height: 24px;
	}
	#Inzoom {
	position:absolute;
	right:5px;
	top: 2px;
	font-size:11px;
	padding:0;
	}
	#Outzoom {
	position:absolute;
	right:5px;
	top: 2px;
	font-size:11px;
	padding:0;
	}
</style>

<script type="text/javascript">
//<![CDATA[
	var map;
	var i;
	var d;
	var tname;
	var drawingManager;
	var geocoder;
	var new_marker;
	var address_marker;
	var gmarkers = []; 
	var side_bar_html = "";
	var greviere = [];
	var reviere1_html = "";
	var reviere2_html = "";
	var reviere3_html = "";
	var input_infowindow;
	var infoWindow = new google.maps.InfoWindow;
	var address_infowindow = new google.maps.InfoWindow();
	
	var usr = '<?php echo $_SESSION['usr']; ?>';
	var bg = '<?php echo $_SESSION['bg']; ?>';
	
	var sys = 'localhost';
//	var sys = 'rdbms.strato.de';

    var customIcons = {
      Keiler: {
        icon: "./images/googlemaps/keiler.png"},
      Bache: {
        icon: "./images/googlemaps/bache.png"},
      mUeberl: {
        icon: "./images/googlemaps/mueberl.png"},
      wUeberl: {
        icon: "./images/googlemaps/wueberl.png"},
      mFrischl: {
        icon: "./images/googlemaps/mfrischl.png"},
      wFrischl: {
        icon: "./images/googlemaps/wfrischl.png"},
      unbekannt: {
        icon: "./images/googlemaps/unbekannt.png"}
    };

	var osmMapType = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			return "http://tile.openstreetmap.org/" +
			zoom + "/" + coord.x + "/" + coord.y + ".png";
		},
		tileSize: new google.maps.Size(256, 256),
		isPng: true,
		alt: "OpenStreetMap Layer",
		name: "OSM",
		maxZoom: 19
	});

	if (datefield.type!=".dat1"){ //eigens Datumsformat für alle Browser 
		jQuery(function($){ //on document.ready
			$('.dat1').datepicker( {dateFormat: "yy-mm-dd"} );
		})
	};

 	function HomeControl(controlDiv, map) {
	  controlDiv.style.padding = '5px';
	
	  var controlUI = document.createElement('DIV');
	  controlUI.style.backgroundColor = '#9eff80';
	  controlUI.style.borderStyle = 'solid';
	  controlUI.style.borderWidth = '1px';
	  controlUI.style.cursor = 'pointer';
	  controlUI.style.textAlign = 'center';
	  controlUI.title = 'zurück zur Startkarte';
	  controlDiv.appendChild(controlUI);
	
	  var controlText = document.createElement('DIV');
	  controlText.style.fontFamily = 'Arial,sans-serif';
	  controlText.style.fontSize = '12px';
	  controlText.style.paddingLeft = '4px';
	  controlText.style.paddingRight = '4px';
	  controlText.innerHTML = '<b>Home</b>';
	  controlUI.appendChild(controlText);
	
	  google.maps.event.addDomListener(controlUI, 'click', function() {
	    location.reload()
	  });
	}
	
	function createMarker(point,point_id,datum,anzahl,kz,type,html) {
		var icon = customIcons[type] || {};
	    var marker = new google.maps.Marker({
	        map: map,
	        position: point,
			flat: true, 
			draggable:true,
			icon: icon.icon
 		});
		
		marker.my_id = point_id; 


		google.maps.event.addListener(marker, 'click', function() {
			gmarkers[i] = marker;
			if (map.getZoom() > 15) {
				xxx = '<div id="Outzoom"><img src="./images/googlemaps/icon_lupe.png" width="18" height="18" alt="" /><a  href="javascript: zoomOut('+i+');">Zoom out</a></div>';
			} else {
				xxx = '<div id="Inzoom"><img src="./images/googlemaps/icon_lupe.png" width="18" height="18" alt="" /><a  href="javascript: zoomIn('+i+');">Zoom in</a></div>';
			}
			var contentString = html + " " + xxx; 
			if (input_infowindow) {input_infowindow.close();}
			infoWindow.setContent(contentString);
			infoWindow.open(map, marker);
		});

		google.maps.event.addListener(infoWindow, "domready", function() { 
			var newLat = marker.getPosition().lat().toFixed(6);
			var newLng = marker.getPosition().lng().toFixed(6);
			var newCoords = newLat+ ', ' + newLng;
			document.getElementById("Koords_marker").innerHTML = newCoords;
		});
		
		google.maps.event.addListener(marker, "drag", function() { 
			if (infoWindow.getMap()) {
				var newLat = marker.getPosition().lat().toFixed(6);
				var newLng = marker.getPosition().lng().toFixed(6);
				var newCoords = newLat+ ', ' + newLng;
				document.getElementById("Koords_marker").innerHTML = newCoords;
			}
		});

		google.maps.event.addListener(marker, 'mouseover', function() {
		var mouse_anz = anzahl+ ' ' + datum;
		  tooltip.show(mouse_anz);
		});

		google.maps.event.addListener(marker, 'mouseout', function() {
		  tooltip.hide();
		});

		gmarkers.push(marker);

		side_bar_html += '<tr> <td  width="40" align="center" > '+ anzahl +' </td> <td> <a href="javascript:myclick(' + (gmarkers.length-1) + ')" > '+ datum +' </td> <td width="60" align="center" > '+ kz +' </td> </tr>';
		}

//  Foto
	function upload (tname) {
	fenster = window.open('./uploadf.php?tname='+ tname + '', "Upload", "width=350,height=150,resizable=yes");
	fenster.focus();
	return false;
	}

	function displayf (tname) {
	fenster = window.open('./displayf.php?tname='+ tname + '.JPG', "Display", "width=500,height=375,resizable=yes");
	fenster.focus();
	return false;
	}

//  Liste Reviere
	function createRevier(point_id,bg,revier) {
		greviere.push(revier);
		reviere1_html +=  'address_html += "<option value=' + revier + '>' + revier +'</option>";';
		reviere2_html +=  'html += "<option value=' + revier + '>' + revier + '</option>";';
		reviere3_html +=  'html_edit += "<option value=' + revier + '>' + revier + '</option>";';
	}

	function myclick(i) {
		google.maps.event.trigger(gmarkers[i], "click");
	}
	
  function codeAddress() {

	d = new Date();
	tname = d.getTime();

	if (address_marker) {
		address_marker.setMap(null);
		address_infowindow.close();
	}
    var address = document.getElementById("address").value;
    if (geocoder) {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          map.setCenter(results[0].geometry.location);
//		  map.setMapTypeId(google.maps.MapTypeId.HYBRID);
		  map.setMapTypeId("OSM");
		  map.setZoom(17);

            var address_icon = './images/googlemaps/house.png';
			address_marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map, 
				draggable:true,
                icon: address_icon
            }); 

			for (var i = 0; i < results[0].address_components.length; i++)	{
				var addr = results[0].address_components[i];
				if (addr.types[0] == "route") {var street = addr.long_name;} 
				if (addr.types[0] == "postal_code") {var postelcode = addr.long_name;} 
				if (addr.types[0] == "street_number") {var housenumber = addr.long_name;} 
				if (addr.types[0] == "locality") {var city = addr.long_name;} 
				if (addr.types[0] == "sublocality") {var ortteil = addr.long_name;} 
				if (addr.types[0] == "establishment" || addr.types[0] == "point_of_interest"  ) {var geocoded_anzahl = addr.long_name;} 
			}

			if (!street) {street = ''}
			if (!postelcode) {postelcode = ''}
			if (!housenumber) {housenumber = ''}
			if (!city) {city = ''}
			if (!ortteil) {ortteil = ''}
			if (!geocoded_anzahl) {geocoded_anzahl = ''}
			
			var address_html = "<div id='infofenster'><table>";
			address_html += "<tr><td>Anzahl:</td> <td><input type='text' size='5' id='anzahl' value=''/> </td> </tr>";
			address_html += "<tr><td>Revier:</td> <td><select id='address'>";
//				address_html += "<option SELECTED>???</option>";
				eval(reviere1_html);
				address_html += "<option value='unbekannt' SELECTED>unbekannt</option>";
				address_html += "</select> </td></tr>";
			address_html += "<tr><td>Datum:</td> <td><input class='dat1' type='date' size='10' id='datum' value='<?php $datum = date("Y-m-d"); echo date("Y-m-d", strtotime ($datum)-0); ?>' </td> (JJJJ-MM-TT)</td> </tr>";
			address_html += "<tr><td>Uhrzeit:</td> <td><input type='time' size='3' id='uhrzeit' value='' </td>  (HH:MM)</td> </tr>";
			address_html += "<tr><td>Ort:</td> <td><select id='ort'>";
//				address_html += "<option SELECTED>???</option>";
				address_html += "<option value='Feld'>Feld</option>";
				address_html += "<option value='Wald'>Wald</option>";
				address_html += "<option value='Wiese'>Wiese</option>";
				address_html += "<option value='Kirrung'>Kirrung</option>";
				address_html += "<option value='Pirsch'>Pirsch</option>";
				address_html += "<option value='Drueckjagd'>Drueckjagd</option>";
				address_html += "<option value='unbekannt' SELECTED>unbekannt</option>";
				address_html += "</select> </td></tr>";
			address_html += "<tr><td>Typ:</td> <td><select id='type'>";
//				address_html += "<option SELECTED>???</option>";
				address_html += "<option value='Keiler'>Keiler</option>";
				address_html += "<option value='Bache'>Bache</option>";
				address_html += "<option value='mUeberl'>mUeberl</option>";
				address_html += "<option value='wUeberl'>wUeberl</option>";
				address_html += "<option value='mFrischl'>mFrischl</option>";
				address_html += "<option value='wFrischl'>wFrischl</option>";
				address_html += "<option value='unbekannt' SELECTED>unbekannt</option>";
				address_html += "</select> </td></tr>";
			address_html += "<tr><td>Gewicht:</td> <td><input type='text' size='3' id='kz' value='' </td>  kg </td> </tr>";
			address_html += "<table><tr><td>Foto:</td> <td><input type='button' class='edit_button' id='tname' value='" + tname + "' onclick='upload(" + tname + ")'/></td> </tr>";
			address_html += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' ></textarea></p></form>";
				var lat_koord = results[0].geometry.location.lat().toFixed(6);
				var lng_koord = results[0].geometry.location.lng().toFixed(6);
				var lat_lng = lat_koord + ', ' + lng_koord;
			address_html += "<tr><td>Koords:</td> <td><div id='Listing' style='font-size:12px'>" + lat_lng + "</div></td> </tr></table>";
			address_html += "<table><tr><td><input type='button' class='edit_button' value='Speichern & Schließen'  onclick='saveAddressData()'/></td> </tr></table>";
			address_html += "</div>";

			google.maps.event.addListener(address_marker, 'click', function() {
				address_infowindow.setContent (address_html);
                address_infowindow.open(map, address_marker);
            });
			
			google.maps.event.addListener(address_marker, "drag", function() { 
			if (address_infowindow.getMap()) {
				var newLat = address_marker.getPosition().lat().toFixed(6);
				var newLng = address_marker.getPosition().lng().toFixed(6);
				var newCoords = newLat+ ', ' + newLng;
				document.getElementById("Listing").innerHTML = newCoords;
			}
		});

			address_infowindow.setContent (address_html);
			address_infowindow.open(map, address_marker);

          } else {
            alert("Keine Ergebnisse gefunden");
          }
        } else {
          alert("Das Geocodieren war nicht erfolgreich. Grund: " + status);
        }
      });
    }
  }

function initialize() {
	geocoder = new google.maps.Geocoder();

	d = new Date();
	tname = d.getTime();

	var myOptions = {
		center: new google.maps.LatLng(50.308557,11.711553),
		zoom: 12,
		mapTypeControl: true,
				mapTypeControlOptions: { mapTypeIds: ['OSM', google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN],
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR},
	    navigationControl: true,
				navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},
		scaleControl: true,
				scaleControlOptions: {position: google.maps.ControlPosition.BOTTOM_LEFT},
		overviewMapControl: true,
		mapTypeId: 'OSM'
	};

//  Liste Reviere 
	var url = "./swsicht/scripte/phpsqlajax_genxml_r.php?bg=" + bg + "&sys=" + sys;
	downloadUrl(url, function(doc) {
		var xml = xmlParse(doc);
		var reviere = xml.documentElement.getElementsByTagName("revier");
		for (var i = 0; i < reviere.length; i++) {
			var point_id = parseFloat(reviere[i].getAttribute("id"));
			var bg = reviere[i].getAttribute("bg");
			var revier = reviere[i].getAttribute("revier");
			var revier = createRevier(point_id,bg,revier);
			}
	});

	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	map.mapTypes.set('OSM',osmMapType);
	loadData ();
	
//======== Verhindert das Scrollen der Seite, wenn mit Scrollrad in der karte gezoomt wird (nur Firefox)		
		jQuery("#map_canvas").on(
  "MozMousePixelScroll", function (event) { event.preventDefault(); }, false);	
	
	
	google.maps.event.addListener(map, 'click', function() {
		if (infoWindow) {infoWindow.close();}
		if (input_infowindow) {input_infowindow.close();}
		if (address_infowindow) {address_infowindow.close();}
	});

	drawingManager = new google.maps.drawing.DrawingManager({
		drawingControl: true,
		drawingControlOptions: {
			position: google.maps.ControlPosition.RIGHT_TOP, drawingModes: [
				google.maps.drawing.OverlayType.MARKER,
			]
		},
		markerOptions: {draggable: true}
	});
	drawingManager.setMap(map);
	
		var homeControlDiv = document.createElement('DIV');
		var homeControl = new HomeControl(homeControlDiv, map);
		homeControlDiv.index = 1;
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(homeControlDiv);


	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
		if (new_marker) {new_marker.setMap(null);}
		if (input_infowindow) {input_infowindow.close();}
		if (infoWindow) {infoWindow.close();}
		drawingManager.setDrawingMode(null);
		
		new_marker = event.overlay;
		
		var html = "<div id='infofenster'><table>";
		html += "<tr><td>Anzahl:</td> <td><input type='text' size='5' id='anzahl'/> </td> </tr>";
		html += "<tr><td>Revier:</td> <td><select id='address'>";
//			html += "<option SELECTED>???</option>";
			eval(reviere2_html);
			html += "<option value='unbekannt' SELECTED>unbekannt</option>";
			html += "</select> </td></tr>";
		html += "<tr><td>Datum:</td> <td><input class='dat1' type='date' size='10' id='datum' value='<?php $datum = date("Y-m-d"); echo date("Y-m-d", strtotime ($datum)-0); ?>' </td>  (JJJJ-MM-TT)</td </tr>";
		html += "<tr><td>Uhrzeit:</td> <td><input type='time' size='3' id='uhrzeit' </td>   (HH:MM)</td> </tr>";
		html += "<tr><td>Ort:</td> <td><select id='ort'>";
//			html += "<option SELECTED>???</option>";
			html += "<option value='Feld'>Feld</option>";
			html += "<option value='Wald'>Wald</option>";
			html += "<option value='Wiese'>Wiese</option>";
			html += "<option value='Kirrung'>Kirrung</option>";
			html += "<option value='Pirsch'>Pirsch</option>";
			html += "<option value='Drueckjagd'>Drueckjagd</option>";
			html += "<option value='unbekannt' SELECTED>unbekannt</option>";
			html += "</select> </td></tr>";
		html += "<tr><td>Typ:</td> <td><select id='type'>";
//			html += "<option SELECTED>???</option>";
			html += "<option value='Keiler'>Keiler</option>";
			html += "<option value='Bache'>Bache</option>";
			html += "<option value='mUeberl'>mUeberl</option>";
			html += "<option value='wUeberl'>wUeberl</option>";
			html += "<option value='mFrischl'>mFrischl</option>";
			html += "<option value='wFrischl'>wFrischl</option>";
			html += "<option value='unbekannt' SELECTED>unbekannt</option>";
			html += "</select> </td></tr>";
		html += "<tr><td>Gewicht:</td> <td><input type='text' size='3' id='kz' </td>  kg </td> </tr>";
		html += "<table><tr><td>Foto:<input type='button' class='edit_button' value='" + tname + "' id='tname' onclick='upload(" + tname + ")'/></td> </tr>";
		html += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' ></textarea></p></form>";
		html += "<table><tr><td>Koords:</td> <td><div id='Listing' style='font-size:12px'></div></td> </tr></table>";
		html += "<table><tr><td><input type='button' class='edit_button' value='Speichern & Schließen' onclick='saveData()'/></td> </tr></table>";

		input_infowindow = new google.maps.InfoWindow({
			content: html
		});

		google.maps.event.addListener(new_marker, "drag", function() { 
			if (input_infowindow.getMap()) {
				var newLat = new_marker.getPosition().lat().toFixed(6);
				var newLng = new_marker.getPosition().lng().toFixed(6);
				var newCoords = newLat+ ', ' + newLng;
				document.getElementById("Listing").innerHTML = newCoords;
			}
		});

		google.maps.event.addListener(input_infowindow, "domready", function() { 
			var newLat = new_marker.getPosition().lat().toFixed(6);
			var newLng = new_marker.getPosition().lng().toFixed(6);
			var newCoords = newLat+ ', ' + newLng;
			document.getElementById("Listing").innerHTML = newCoords;
		});
		
		google.maps.event.addListener(new_marker, "click", function() {
			if (input_infowindow) {input_infowindow.close();}
			if (infoWindow) {infoWindow.close();}
			input_infowindow.open(map, new_marker);
		});

		input_infowindow.open(map, new_marker);
		
	});
}

	function loadData() {
		if (gmarkers.length > 0) {		
			for (var i = 0; i < gmarkers.length; i++) {
				gmarkers[i].setMap(null);
			}
		}
		var vondatum = document.getElementById("vondatum").value;
		var bisdatum = document.getElementById("bisdatum").value;
		side_bar_html = '<table>';
		var bounds = new google.maps.LatLngBounds();
		
			var url = "./swerleg/scripte/phpsqlajax_genxml.php?vondatum=" + vondatum + "&bisdatum=" + bisdatum + "&bg=" + bg + "&sys=" + sys;
			downloadUrl(url, function(doc) {
			var xml = xmlParse(doc);
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				var point_id = parseFloat(markers[i].getAttribute("id"));
				var anzahl = markers[i].getAttribute("anzahl");
				var address = markers[i].getAttribute("address");
				var mitglied = markers[i].getAttribute("mitglied");
				var ort = markers[i].getAttribute("ort");
				var datum = markers[i].getAttribute("datum");
				var uhrzeit = markers[i].getAttribute("uhrzeit");
				var type = markers[i].getAttribute("type");
				var kz = markers[i].getAttribute("kz");
				var fname = markers[i].getAttribute("tname");
				var text1 = markers[i].getAttribute("text1");
				var point = new google.maps.LatLng(
					parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng")));
				var html = "<div id='infofenster'><div id='infofenster_titel'>" + anzahl + "</div>";
					html += "<u></u> " + type + "&nbsp;&nbsp;&nbsp;Gewicht:" + kz;
					html += "<br/><u></u> " + datum + "&nbsp;&nbsp;&nbsp;" + uhrzeit +"&nbsp;&nbsp;&nbsp;" + ort;
					html += "<br/<u></u> " + address;
					html += "<br/<u></u> " + mitglied;
					html += "</table><table><form action='text1'><p>Anmerkung:<br><textarea name='text1' cols='30' rows='3' readonly>"+ text1 +"</textarea></p></form></table>";
					html += "<table><tr><td>Foto: <input type='button' class='edit_button' value='" + fname + "' onclick='displayf(" + fname + ")'</td></tr></table>";
					html += "<table><tr><td>ID: " + point_id + "</td><td>";
					html += "&nbsp;&nbsp;&nbsp;Koords:<span id='Koords_marker' style='font-size:12px'></span></td></tr></table>";
				if (mitglied == usr || mitglied == '') {
					html += "<table><tr><td><form action='#'><input type='button' class='delete_button'  id='" + point_id + "' anzahl='Auswahlmarkername' value='Markierung löschen' onclick='javascript:deletePoint(id)' /></td></form>";
					if (fname == '') {
					d = new Date();
					tname = d.getTime();
					fname=tname;
					};
				};
				var html_edit = "<div id='infofenster'><table>";
					html_edit += "<tr><td>Anzahl:</td> <td><input type='text' size='5' value='" +anzahl+ "' id='anzahl'/> </td> </tr>";
					html_edit += "<tr><td>Datum:</td> <td><input class='dat1' type='date' size='10' value='" +datum+ "' id='datum'  </td>  (JJJJ-MM-TT)</td> </tr>";
					html_edit += "<tr><td>Uhrzeit:</td> <td><input type='time' size='3' value='" +uhrzeit+ "' id='uhrzeit'  </td>  (HH:MM)</td> </tr>";
					html_edit += "<tr><td>Ort:</td> <td><select id='ort'>";
						html_edit += "<option value='" +ort+ "' SELECTED>" +ort+ "</option>";
						html_edit += "<option value='Feld'>Feld</option>";
						html_edit += "<option value='Wald'>Wald</option>";
						html_edit += "<option value='Wiese'>Wiese</option>";
						html_edit += "<option value='Kirrung'>Kirrung</option>";
						html_edit += "<option value='Pirsch'>Pirsch</option>";
						html_edit += "<option value='Drueckjagd'>Drueckjagd</option>";
						html_edit += "<option value='unbekannt'>unbekannt</option>";
						html_edit += "</select> </td></tr>";
					html_edit += "<tr><td>Revier:</td> <td><select id='address'>";
						html_edit += "<option value='" +address+ "' SELECTED>" +address+ "</option>";
						eval(reviere3_html);
						html_edit += "<option value='unbekannt'>unbekannt</option>";
						html_edit += "</select> </td></tr>";
					html_edit += "<tr><td>Typ:</td> <td><select id='type'>";
						html_edit += "<option value='" +type+ "' SELECTED>" +type+ "</option>";
						html_edit += "<option value='Keiler'>Keiler</option>";
						html_edit += "<option value='Bache'>Bache</option>";
						html_edit += "<option value='mUeberl'>mUeberl</option>";
						html_edit += "<option value='wUeberl'>wUeberl</option>";
						html_edit += "<option value='mFrischl'>mFrischl</option>";
						html_edit += "<option value='wFrischl'>wFrischl</option>";
						html_edit += "<option value='unbekannt'>unbekannt</option>";
						html_edit += "</select> </td></tr>";
					html_edit += "<tr><td>Gewicht:</td> <td><input type='text' size='3' value='" +kz+ "' id='kz' </td>  kg </td> </tr>";
					html_edit += "<table><tr><td>Foto:</td> <td><input type='button' class='edit_button' id='tname' value='" + fname + "' onclick='upload(" + fname + ")'/></td> </tr>";
					html_edit += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' >"+ text1 +"</textarea></p></form>";
					html_edit += "<tr><td>Koords:</td> <td><div id='Koords_marker' style='font-size:12px'></div></td></tr></table>";
					html_edit += "<table><tr><td><div<form action='#'><input type='button' id='" + point_id + "' anzahl='XXXanzahl' value='Änderung speichern' class='edit_button' onclick='editData(id)' /></form></div></td></tr></table>";
				if (mitglied == usr || mitglied == '') {
					html += '<td><form action="#"><input type="button" class="edit_button"  id="' + point_id + '" name="' + html_edit + '" value="Bearbeiten" onclick="javascript:change_content(id,name)"/></form>';
				}
					html += "</td></tr></table></div>";
				var marker = createMarker(point,point_id,datum,anzahl,kz,type,html);
				bounds.extend(point);
			}
			map.fitBounds(bounds);
			map.setMapTypeId('OSM');
			
			var ctaLayer = new google.maps.KmlLayer({url: "http://www.jagenfischennaila.de/map/" + bg + ".kml"});
			ctaLayer.setMap(map);
			
			side_bar_html += '</table>';
			document.getElementById("side_bar").innerHTML = side_bar_html;

			d = new Date();
			tname = d.getTime();

		});
	}
	
    function saveData() {
      var anzahl = escape(document.getElementById("anzahl").value);
      var address = escape(document.getElementById("address").value);
	  var ort = escape(document.getElementById("ort").value);
	  var datum = escape(document.getElementById("datum").value);
	  var uhrzeit = escape(document.getElementById("uhrzeit").value);
      var type = escape(document.getElementById("type").value);
      var kz = escape(document.getElementById("kz").value);
      var text1 = escape(document.getElementById("text1").value);
	  var tname = escape(document.getElementById("tname").value);
      var latlng = new_marker.getPosition();
	  
	  var anzahl_tmp = document.getElementById("anzahl").value;

      var url = "./swerleg/scripte/phpsqlinfo_addrow_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 + "&tname=" + tname + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();
      downloadUrl(url, function(data, responseCode) {
        if (responseCode == 200 && data.length <= 1) {
          input_infowindow.close();
		  new_marker.setMap(null);
		  loadData ();
          document.getElementById("message").innerHTML += "Markierung <b>" + anzahl_tmp + "</b> hinzugefügt<br/>";
        }
      });
    }

    function saveAddressData() {
      var anzahl = escape(document.getElementById("anzahl").value);
      var address = escape(document.getElementById("address").value);
	  var ort = escape(document.getElementById("ort").value);
	  var datum = escape(document.getElementById("datum").value);
	  var uhrzeit = escape(document.getElementById("uhrzeit").value);
      var type = escape(document.getElementById("type").value);
      var kz = escape(document.getElementById("kz").value);
      var text1 = escape(document.getElementById("text1").value);
      var tname = escape(document.getElementById("tname").value);
      var latlng = address_marker.getPosition();
	  
	  var anzahl_tmp = document.getElementById("anzahl").value;

      var url = "./swerleg/scripte/phpsqlinfo_addrow_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 + "&tname=" + tname + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();
      downloadUrl(url, function(data, responseCode) {
        if (responseCode == 200 && data.length <= 1) {
          address_infowindow.close();
		  address_marker.setMap(null);
		  loadData ();
          document.getElementById("message").innerHTML += "Markierung <b>" + anzahl_tmp + "</b> hinzugefügt<br/>";
        }
      });
    }	
	
    function editData(id) {
		var anzahl = escape(document.getElementById("anzahl").value);
		var address = escape(document.getElementById("address").value);
		var ort = escape(document.getElementById("ort").value);
		var datum = escape(document.getElementById("datum").value);
		var uhrzeit = escape(document.getElementById("uhrzeit").value);
		var type = escape(document.getElementById("type").value);
		var kz = escape(document.getElementById("kz").value);
		var text1 = escape(document.getElementById("text1").value);
		var tname = escape(document.getElementById("tname").value);
	  
	  if (gmarkers[i].my_id == id) {
		var latlng_marker = gmarkers[i].getPosition();
	  }
	
	  var anzahl_tmp = document.getElementById("anzahl").value;

      var url = "./swerleg/scripte/php_edit_point_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 + "&tname=" + tname + "&lat=" + latlng_marker.lat() + "&lng=" + latlng_marker.lng() + "&id=" + id;
      downloadUrl(url, function(data, responseCode) {
        if (responseCode == 200 && data.length <= 1) {
          infoWindow.close();
		  loadData ();
          document.getElementById("message").innerHTML += "Markierung <b>" + anzahl_tmp + "</b> geändert<br/>";
        }
      });
    }

	function deletePoint(id) {
		var url = "./swerleg/scripte/php_delete_point.php?point_id=" + id + "&bg=" + bg + "&sys=" + sys;
		downloadUrl(url, function(data, responseCode) {
			if (responseCode == 200 && data.length <= 1) {
			  infoWindow.close();
			  loadData ();
			  document.getElementById("message").innerHTML += "Markierung mit ID: <b>" + id + "</b> gelöscht<br/>";
			}
		});
	}

	function change_content(id,name) {
		infoWindow.setContent(name);
	}

	function zoomIn(i){
		last_zoom = map.getZoom();
		map.setMapTypeId(google.maps.MapTypeId.HYBRID);
		setTimeout('infoWindow.close()', 5);
		setTimeout('map.setZoom(16)', 20);
		setTimeout( function() {google.maps.event.trigger(gmarkers[i], "click")},25);
	}
	function zoomOut(i){
		map.setMapTypeId('OSM');
		if (input_infowindow) {setTimeout('infoWindow.close()', 5);}
		if (last_zoom > 15) {
			setTimeout('map.setZoom(9)', 20);
		} else {
			setTimeout('map.setZoom(last_zoom)', 20);
		}
		setTimeout( function() {google.maps.event.trigger(gmarkers[i], "click")},25);
	}
	
    function doNothing() {}

	google.maps.event.addDomListener(window, 'load', initialize);
//]]>
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'U1971299']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  </head> 
<body style="margin:0px; padding:0px;"> 

<div id="content_beispiele">
    <div id="inhalt">
        <div id="Kartentitel">Jägerschaft Naila &nbsp;&nbsp;&nbsp; Erlegungen &nbsp;&nbsp;&nbsp;<?php echo $_SESSION['usr']; ?></div>
		<div style="text-align:left;margin-bottom:3px">Um Erlegungsdaten zu erfassen, geben Sie entweder eine Adresse (bzw. ein bekanntes Objekt wie 'Naila') ein oder starten den <b>Zeichenmodus</b> durch Klick auf das <span><img class='klein' src="./images/googlemaps/drawing_point.png" alt="Punktsymbol" />-Symbol</span> rechts oben in der Karte und klicken dann mit der Maus in die Karte. Es öffnet sich ein Infofenster mit der Möglichkeit zum Hinzufügen von Attributen. Sie können die Position ändern/optimieren, indem Sie den Marker mit gedrückter linker Maustaste verschieben. Erfasste Objekte lassen sich im Nachhinein bearbeiten oder auch wieder löschen, wenn Sie das Infofenster eines 'normalen' Markers öffnen.</div>

<table border="0" width="800" style="margin-left:auto; margin-right:auto; margin-top:3px;">
    <tr style="font-size:12px">
        <td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/keiler.png" alt="Keiler" /> Keiler</td>
        <td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/bache.png" alt="Bache" /> Bache</td>
        <td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/mueberl.png" alt="mUeberl" /> mUeberl</td>
        <td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/wueberl.png" alt="wUeberl" /> wUeberl</td>
		<td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/mfrischl.png" alt="mFrischl" /> mFrischl</td>
		<td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/wfrischl.png" alt="wFrischl" /> wFrischl</td>
		<td align="center" valign="middle"><img class='xyz' src="./images/googlemaps/unbekannt.png" alt="unbekannt" /> unbekannt</td>
    </tr>
</table> 


	<table border="1"> 
      <tr> 
        <td> 
           <div id="map_canvas"></div> 
        </td> 
        <td style="color: #4444ff; vertical-align: top"> 
			<div style="background-color:#9eff80; padding-bottom:5px; padding-top:3px">
				<div style="float:left">
					<p style="font-size:14px; font-weight:bold; margin:0">Ortssuche</p>
					<p style="font-size: 11px; font-weight:normal; margin:0"> (z.B. <i>"Schottenhammer"</i>) </p>
				</div>
				<div style="float:right; margin-top:3px">
					<form action="get" method="post" onsubmit="codeAddress(); return false">
						<p style="margin:0">
						<input type="submit" value="Los" style="font-weight:bold;font-size:14px" /><br/>
						</p>
					</form>
				</div>
					
				<form action="get" method="post" onsubmit="codeAddress(); return false" style="clear:both">
					<p style="margin:0">
					<input id="address" size="30" type="text" value="" />
					</p>
			    </form>
			</div>
			<div style="background-color:#9eff80; padding-bottom:5px; padding-top:3px">
				<div style="float:left">
					<p style="font-size:14px; font-weight:bold; margin:0">Datum von &nbsp;&nbsp;&nbsp;&nbsp; bis</p>
				</div>
			<div style="float:right; margin-top:3px">
				<form action="get" method="post" onsubmit="loadData(); return false">
					<p style="margin:0">
					<input type="submit" value="Los" style="font-weight:bold;font-size:14px" /><br/>
					</p>
				</form>
			</div>
				<form action="get" method="post" onsubmit="loadData(); return false" style="clear:both">
					<p style="margin:0">
					<input id="vondatum" size="9" class="dat1" type="text" value="<?php $datum = date("Y-m-d"); echo date("Y-m-d", strtotime ($datum)-2419200); ?>" />
					<input id="bisdatum" size="9" class="dat1" type="text" value="<?php echo date("Y-m-d", strtotime ($datum)); ?>" />
					</p>
				</form>
			</div>
			<div id="side_bar_titel">
				<table border="0">
					<tr>
						<td width="45" align="left" >Anz</td>
						<td width="70" align="left" >Datum</td>
						<td width="60" align="left" >Gewicht</td>
					</tr>
				</table>
			</div> 
			<div id="side_bar"></div> 
        </td> 
      </tr> 
    </table> 
	<div id="message"></div>
	</div>
</div>

<div id="footer">
    <p> Version 1.3 <span style="color: black;">•</span> Günter Friedrich <span style="color: black;">•</span> Tel.:09282-3653 <span style="color: black;">•</span>Mobil: 0174-3472600 <span style="color: black;">•</span>
	Email:<a href="mailto:gfriedrich@dv-tech.de">gfriedrich@dv-tech.de.de</a></p>
</div>
	
<div id="w3c_logo">
    <div class="w3c">
		<img src="style/valid-xhtml10.png" alt="Valid XHTML 1.0 Strict" height="23" width="65" />
    </div>
</div>

  </body> 
</html> 