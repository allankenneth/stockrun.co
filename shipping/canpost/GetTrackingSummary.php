<?php
 /**
 * The GetTrackingSummary service returns the most recent/significant event for a 
 * parcel. If it has been delivered, the delivery details are returned.
 * 
 **/

function getTrackingStatus($tracking) {
	
	$userProperties = parse_ini_file(realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/canpost/user.ini');

	$username = $userProperties['username'];
	$password = $userProperties['password'];

	$service_url = 'https://soa-gw.canadapost.ca/vis/track/pin/' . $tracking . '/summary';

	$curl = curl_init($service_url); // Create REST Request
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
	curl_setopt($curl, CURLOPT_CAINFO, realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/canpost/third-party/cert/cacert.pem'); // Mozilla cacerts
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept:application/vnd.cpc.track+xml', 'Accept-Language:en-CA'));
	$curl_response = curl_exec($curl); // Execute REST Request
	if(curl_errno($curl)){
		echo 'Curl error: ' . curl_error($curl) . "\n";
	}
	curl_close($curl);

	libxml_use_internal_errors(true);
	$xml = simplexml_load_string($curl_response);
	if (!$xml) {
	
		echo 'Failed loading XML' . "\n";
		echo $curl_response . "\n";
		foreach(libxml_get_errors() as $error) {
			echo "\t" . $error->message;
		}
	} else {
		
		$trackingSummary = $xml->children('http://www.canadapost.ca/ws/track');
		if ( $trackingSummary->{'pin-summary'} ) {
			
			foreach ( $trackingSummary as $pinSummary ) {
				return $pinSummary->{'event-description'};
			}
		} else {
			
			return "Failed to communicate with Canada Post properly.";
			// $messages = $xml->children('http://www.canadapost.ca/ws/messages');		
			// foreach ( $messages as $message ) {
			// 	echo 'Error Code: ' . $message->code . "\n";
			// 	echo 'Error Msg: ' . $message->description . "\n\n";
			// }
		}
	}
}
?>