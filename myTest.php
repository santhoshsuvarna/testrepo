preg_replace('/(?<=#Start McAfeeSecure Code#).*?(?=#End McAfeeSecure Code#)/s', '', $org_script);
<?php
exec("find / -name pathDefServerLog.txt | xargs grep -l 'documentRoot'| cut -d= -f2",$arrayServerLogFiles);
$matchPattern	=	'|#Start McAfeeSecure Code#(.*)#End McAfeeSecure Code#|s';
//Execute with files
foreach($arrayServerLogFiles	as	$arrayServerLogFile) {
	$strLogFileContent		=	file_get_contents($arrayServerLogFile, FILE_USE_INCLUDE_PATH);
	$arrayLogFileContents	=	json_decode($strLogFileContent);	
	$i = 0; $registeredDomains = "";$domains = "";; 	
	while ($i < count($arrayLogFileContents)) {
		$domains .= '&h='.$arrayLogFileContents[$i]->domain;
		$i++;
		//At a time we can pass only 100 domains. So limitting the number 
		if ((fmod($i,100)== 0) || ($i == count($arrayLogFileContents))){
			$domains = preg_replace('/&h=/', '?h=', $domains, 1);			
			$urlQuery = 'https://api.mcafeesecure.com/rpc/certified-host-lookup'.$domains;
			//Starts curl
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlQuery); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);			
			$registeredDomains	.=	$output;	
			$domains = "";			
		}
	}
	//Check the last character is a new line.
	if(substr($registeredDomains,-1)	==	"\n") {
		$registeredDomains	=	rtrim($registeredDomains, "\n");
	}	
	$arrRegisteredDomains = explode("\n", $registeredDomains);		
	foreach($arrayLogFileContents	as	$arrayLogFileContent) {
		if (in_array($arrayLogFileContent->domain, $arrRegisteredDomains)) {
			//Check for the htaccess file.
			if (file_exists($arrayLogFileContent->documentRoot.'.htaccess')) {
				$fileContent	=	file_get_contents($arrayLogFileContent->documentRoot.'.htaccess', FILE_USE_INCLUDE_PATH);
				//Check for the script			
				$fileContent = preg_replace($matchPattern, '', $fileContent);
				file_put_contents($arrayLogFileContent->documentRoot.'.htaccess',$fileContent);
			}	
		}
	}	
}