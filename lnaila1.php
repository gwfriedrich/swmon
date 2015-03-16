<?php

/*** KONFIGURATION ***/

// Definiert Konstanten für das Script
define('MD5_ENCRYPT', true); // Aktiviert Verschlüsselung für Passwort. Wenn "true" gesetzt, müssen Passwörter von $usrdata md5-verschlüsselt vorliegen. Standard: false
define('SUCCESS_URL', 'naila1.php'); // URL, zu welcher nach erfolgreichen Login umgeleitet wird.
define('LOGIN_FORM_URL', 'lnaila1.html'); // URL mit Anmeldeformular
// Array mit Benutzerdaten: Besteht aus Array-Elementen mit paarweisen Benutzernamen und Passwörtern
$usrdata = array(

	array("usr" => "GFriedrich","pwd" => "1ca9a56907c0adc2a11ba8f08d1d4286"),
	array("usr" => "RFriedrich","pwd" => "1ca9a56907c0adc2a11ba8f08d1d4286"),
	array("usr" => "WHuebner","pwd" => "392632ac0465c1a651c4c9b1a682bfd4"),
	array("usr" => "ABauer","pwd" => "9146354ce2c89c21c6856450d51f0a55"),
	array("usr" => "MHampl","pwd" => "888e7b2ad97bce432f75aeb7c37fd419"),
	array("usr" => "ESitte","pwd" => "9ce0486494f86630a75a82bc38595d26"),
	array("usr" => "SRank","pwd" => "a85c1674e61b77f003c311848c6f2ea3"),
	array("usr" => "MGossler","pwd" => "3edd1ea51c4a226f9db0f291273059b8"),
	array("usr" => "RHagen","pwd" => "aea8f7c9a51479dfb632eddda4ad3a2c"),
	array("usr" => "VKnoernschild","pwd" => "5aab4b15ad68c4c5dbba8a2e979f6a5b"),
	array("usr" => "EGerber","pwd" => "fce027fee9033dce1191d7e3c24e30cc"),
	array("usr" => "MDegel","pwd" => "fce027fee9033dce1191d7e3c24e30cc"),
	array("usr" => "EGerber","pwd" => "fce027fee9033dce1191d7e3c24e30cc"),
	array("usr" => "MDegel","pwd" => "8fe7b2697db28ab603babeba29385421"),
	array("usr" => "AHuettner","pwd" => "43497d2e824fad94bf2ff5d6df8264b1"),
	array("usr" => "SEul","pwd" => "bc6407d084322e6669409e434e48c3ab")
	/* Ende */
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
					$_SESSION['bg'] = "1";
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