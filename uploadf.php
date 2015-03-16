<?php
// geht nicht bei Firefox und Opera, da diese bei window.open eine neue Session eröffnen
// session_start();
// if(!$_SESSION['logged_in'])
//	header('Location: ../index.html');
?>

<!doctype html>
 
<meta name="content-type" content="text/html; charset=utf-8" />

<html>
<head>
<style>
body {
    background-image: url(""); background-repeat:no-repeat;  background-color: green;
}
</style>
</head>
<body>
</html>
</pre>
<form action=<?php echo  "'./upload.php?tname="; echo $_GET["tname"] ; echo  "'"; ?> method='post' enctype='multipart/form-data'>
	<B><br>
	<input type='file' name='fileToUpload' id='fileToUpload'>
	<br><br><br>
	<input type='submit' value='Foto hochladen' name='submit'>
</form>
<pre>