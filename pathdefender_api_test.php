<?php

function curl_call_get($query) 
{	
    $curl = curl_init();
    # Create Curl Object
    //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);     
    # Allow self-signed certs
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);      
    curl_setopt($curl, CURLOPT_HEADER,array('xapikey: 5052362fd70e4db3b9755b65d0e83974','Accept: application/json'));	
    # Do not include header in output
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);     
    # Return contents of transfer on curl_exec
	//curl_setopt( $curl, CURLOPT_HTTPHEADER, array('xapikey: 5052362fd70e4db3b9755b65d0e83974','Accept: application/json'));	
    curl_setopt($curl, CURLOPT_URL, $query);               
    # execute the query
    $result = curl_exec($curl);
    
    //print_r($result);
    
    if ($result == false) {
		echo '<pre/>';
        print_r("curl_exec threw error \"" . curl_error($curl) . "\" for $query");     
    }
    curl_close($curl);

    return $result;		
}

function curl_call_post($query,$data) 
{	
	//Starts curl
	$ch = curl_init();
	if (!is_resource($ch)) return false;
	curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , 0 );
	curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , 0 );
	curl_setopt( $ch , CURLOPT_URL , $query );
	curl_setopt( $ch , CURLOPT_POST , 1 );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('xapikey: 5052362fd70e4db3b9755b65d0e83974','Accept: application/json'));	
	curl_setopt( $ch , CURLOPT_POSTFIELDS , $data );
	curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
	curl_setopt( $ch , CURLOPT_VERBOSE , 0 );
	
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;		
}

function create_account(){
	$url = "https://api.mcafeesecure.com/api/v1/sonoracreateaccount.json";
	$url .= "?company.name=GTTest1&user.1.email=santhosh.suvarna@glowtouch.com&user.1.firstName=Santhosh&user.1.lastName=Suvarna&user.1.phone=9343821600&user.1.title=API+tester2&site.1.host=http://teststage1.com";
	$res = curl_call_get($url);
	echo '<pre/>';
	print_r($res);
}


?>