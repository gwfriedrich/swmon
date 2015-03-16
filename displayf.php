<?php
// geht nicht bei Firefox und Opera, da diese bei window.open eine neue Session eröffnen
// session_start();
// if(!$_SESSION['logged_in'])
//	header('Location: ../index.html');
// else
if (isset($_GET['tname']))
	{
		$filename = "./uploads/";
		$filename .= ($_GET['tname']);
		if( ! file_exists ($filename)) 
		die("Bild nicht gefunden!");
		$info = getimagesize($filename);
		switch($info[2]) 
		{
		case 1: //gif
			header("Content-type: image/gif");
			break;
		case 2: // jpeg
			header("Content-type: image/jpeg");
			break;
		case 3: // png
			header("Content-type: image/png");
			break;
		case 4: // jpg
			header("Content-type: image/jpg");
			break;
		default: die ("Ungültiges Dateiformat");
			break;
		}
	readfile($filename);
	}
	else
		die ("Kein Bild angegeben");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript">
</script>
</head>
</body>
</html>
