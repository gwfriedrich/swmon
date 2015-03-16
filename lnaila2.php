<?php

/*** KONFIGURATION ***/

// Definiert Konstanten für das Script
define('MD5_ENCRYPT', true); // Aktiviert Verschlüsselung für Passwort. Wenn "true" gesetzt, müssen Passwörter von $usrdata md5-verschlüsselt vorliegen. Standard: false
define('SUCCESS_URL', 'naila2.php'); // URL, zu welcher nach erfolgreichen Login umgeleitet wird.
define('LOGIN_FORM_URL', 'lnaila2.html'); // URL mit Anmeldeformular
// Array mit Benutzerdaten: Besteht aus Array-Elementen mit paarweisen Benutzernamen und Passwörtern
$usrdata = array(

	array("usr" => "GFriedrich","pwd" => "1ca9a56907c0adc2a11ba8f08d1d4286"),
	array("usr" => "HWunderatsch","pwd" => "262f7c57b80902f605b54686672efaaa"),
	array("usr" => "SEul","pwd" => "bc6407d084322e6669409e434e48c3ab"),
	array("usr" => "TLippert","pwd" => "5506a9e44642065b441478018d55c327"),
	array("usr" => "WDondl","pwd" => "f38d7a50a2d8b4f2564de7801c824ce6")
	/* Ende   TLippert tl20150303 HWunderatsch hw20150202 WDondl wd20150101 */
);

header("Content-Type: text/html; charset=utf-8"); // Melde Browser die verwendete Zeichenkodierung

// PHP-Session starten und aktuellen Stand abfragen
session_start();
$_SESSION['logged_in'] = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) ? true : false;
$_SESSION['usr'] = (isset($_SESSION['usr'])) ? $_SESSION['usr'] : '';

$error = array();
if(!isset($_POST['login'])){
	header('Location: '.LOGIN_FORM_URL);
}else{

	if($_SESSION['usr'] != ''){
		if(count($error) == 0)
			$error[] = "Es liegt bereits eine Anmeldung vor, bitte erst abmelden !";
	}else{

		$usr = (!empty($_POST['user']) && trim($_POST['user']) != '') ? $_POST['user'] : false;
		$pwd = (!empty($_POST['password']) && trim($_POST['password']) != '') ? $_POST['password'] : false;
		
		if(!$usr || !$pwd){
			if(count($error) == 0)
				$error[] = "Bitte geben Sie Benutzername und Passwort ein.";
		}else{
			$pwd = (MD5_ENCRYPT === true) ? md5($pwd) : $pwd; // Passwort eingabe MD5-encrypten, falls Option gesetzt ist
			foreach($usrdata as $ud){ // Benutzer-Liste durchlaufen und je mit Formular-Eingaben vergleichen
				if($usr != $ud['usr'] || $pwd != $ud['pwd']){
					if(count($error) == 0)
					$error[] = "Benutzername und/oder Passwort nicht korrekt.";
				}else{
					$_SESSION['logged_in'] = true;
					$_SESSION['usr'] = $usr;
					$_SESSION['bg'] = "2";
					header('Location: '.SUCCESS_URL);
				}
			}
		}
	}
}

?><!doctype html>
<html>
	<head>
		<meta name="content-type" content="text/html; charset=utf-8" />
		<title>Login-Fehler</title>
	</head>
	<body>
		<ul>
		<?php
		foreach($error as $out){
			?>
			<li><?php echo $out; ?></li>
			<?php
		}
		?>
		</ul>
		<p><a href="<?php echo LOGIN_FORM_URL; ?>">Zur Anmeldeseite</a></p>
	</body>
</html>