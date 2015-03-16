<?php
// UPLOAD Fotos

// Gets data from URL parameters
$tname = $_GET["tname"];
$target_dir = "./uploads/";
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . $tname . ".JPG";
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if (empty($_FILES["fileToUpload"]["name"])) {
    $MSG = "keine Datei ausgewaehlt.";
    $uploadOk = 0;
}
else
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $MSG = "Datei ist kein Bild.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $MSG = "Die Datei existiert bereits.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 6144000) {
    $MSG = "Die Datei ist zu groß.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "JPG") 
{
    $MSG = "nur JPG Dateien zulaessig.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $MSG = "Datei ". basename( $_FILES["fileToUpload"]["name"]). " wurde hochgeladen.";
    } else {
        $MSG = "Fehler beim Hochladen.";
    }
}
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<body style="margin:10px; padding:10px;"> 
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript">
alert("<?php echo $MSG; ?>");
window.close();
</script>
</body>
</head>
</html>