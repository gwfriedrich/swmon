
<script type="text/javascript">
//<![CDATA[


	var d;
	var tname;


//  Foto
	function upload (tname) {
	fenster = window.open('/uploadf.php?tname='+ tname + '', "Upload", "width=350,height=150,resizable=yes");
	fenster.focus();
	return false;
	}
	
	function displayf (tname) {
	fenster = window.open('/displayf.php?tname='+ tname + '.JPG', "Display", "width=500,height=375,resizable=yes");
	fenster.focus();
	return false;
	}
	
//  Liste Reviere



  function codeAddress() {

	d = new Date();
	tname = d.getTime();


			address_html += "<tr><td>Rotte:</td> <td><input type='text' size='8' id='kz' value=''/></td> </tr></table>";
			address_html += "<table><tr><td>Foto:</td> <td><input type='button' class='edit_button' id='tname' value='" + tname + "' onclick='upload(" + tname + ")'/></td> </tr>";
			address_html += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' ></textarea></p></form>";

			address_html += "<tr><td>Koords:</td> <td><div id='Listing' style='font-size:12px'>" + lat_lng + "</div></td> </tr></table>";
			address_html += "<table><tr><td><input type='button' class='edit_button' value='Speichern & Schließen'  onclick='saveAddressData()'/></td> </tr></table>";
			address_html += "</div>";


function initialize() {
	geocoder = new google.maps.Geocoder(); 

	d = new Date();
	tname = d.getTime();


		html += "<tr><td>Rotte:</td> <td><input type='text' size='8' id='kz'/></td> </tr></table>";
		html += "<table><tr><td>Foto:<input type='button' class='edit_button' value='" + tname + "' id='tname' onclick='upload(" + tname + ")'/></td> </tr>";
		html += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' ></textarea></p></form></table>";
		html += "<table><tr><td>Koords:</td> <td><div id='Listing' style='font-size:12px'></div></td> </tr></table>";
		html += "<table><tr><td><input type='button' class='edit_button' value='Speichern & Schließen' onclick='saveData()'/></td> </tr></table>";



	function loadData() {

				var fname = markers[i].getAttribute("tname");

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

					html_edit += "<tr><td>Rotte:</td> <td><input type='text' size='8' value='" + kz + "' id='kz'/></td> </tr></table>";
					html_edit += "<table><tr><td>Foto:</td> <td><input type='button' class='edit_button' id='tname' value='" + fname + "' onclick='upload(" + fname + ")'/></td> </tr>";
					html_edit += "<form action='text1'><p>Anmerkung:<br><textarea id='text1' name='text1' cols='30' rows='3' >"+ text1 +"</textarea></p></form>";
					html_edit += "<tr><td>Koords:</td> <td><div id='Koords_marker' style='font-size:12px'></div></td></tr></table>";
					html_edit += "<table><tr><td><div<form action='#'><input type='button' id='" + point_id + "' anzahl='XXXanzahl' value='Änderung speichern' class='edit_button' onclick='editData(id)' /></form></div></td></tr></table>";
				if (mitglied == usr || mitglied == '') {
					html += '<td><form action="#"><input type="button" class="edit_button"  id="' + point_id + '" name="' + html_edit + '" value="Bearbeiten" onclick="javascript:change_content(id,name)"/></form>';
				}
					html += "</td></tr></table></div>";


			d = new Date();
			tname = d.getTime();


    function saveData() {

	  var tname = escape(document.getElementById("tname").value);

      var url = "./swsicht/scripte/phpsqlinfo_addrow_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 +  "&tname=" + tname + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();

  
    function saveAddressData() {

      var tname = escape(document.getElementById("tname").value);
 
      var url = "./swsicht/scripte/phpsqlinfo_addrow_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 + "&tname=" + tname + "&lat=" + latlng.lat() + "&lng=" + latlng.lng();


    function editData(id) {

		var tname = escape(document.getElementById("tname").value);
 
      var url = "./swsicht/scripte/php_edit_point_neu.php?bg=" + bg + "&sys=" + sys + "&anzahl=" + anzahl + "&address=" + address + "&ort=" + ort + "&datum=" + datum + "&uhrzeit=" + uhrzeit + "&type=" + type + "&kz=" + kz + "&mitglied=" + usr + "&text1=" + text1 + "&tname=" + tname + "&lat=" + latlng_marker.lat() + "&lng=" + latlng_marker.lng() + "&id=" + id;
 


<div id="footer">
    <p> Version 1.3 <span style="color: black;">•</span> Günter Friedrich <span style="color: black;">•</span> Tel.:09282-3653 <span style="color: black;">•</span>Mobil: 0174-3472600 <span style="color: black;">•</span>
