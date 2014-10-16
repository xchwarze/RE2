<html>
<head></head>
<body>
<script type="text/javascript">
<?php
require 'lib_functions.php';
require 'lib_recon.php';

$ip = GetIP();
$browser = GetBrowser();

echo '
browser = "' . $browser['browser'] . ' ver: ' . $browser['version'] . '";
ip = "' . $ip . '";
country = "' . GetCountry($ip) . '";
os = "' . GetOS() . '";
referer = "' . GetReferer() . '";

' . GetJavaVersion('deployJava', 'getJREs', $browser['browser']) . '
JavaVersion = deployJava.getJREs();

' . GetPDFVersion('PDFVersion', $browser['browser']) . '

' . GetFlashVersion('FlashVersion', $browser['browser']) . '


//se fini
alert(
	"IP: " + ip + " COUNTRY: " + country + "\n" + 
	"BROWSER: " + browser + " OS: " + os + "\n" + 
	"REFERER: " + referer + "\n" + 
	"JAVA VERSION: " + JavaVersion + "\n" +
	"PDF VERSION: " + PDFVersion + "\n" + 
	"FLASH VERSION: " + FlashVersion
);
';
?>
</script>
</body>
</html>