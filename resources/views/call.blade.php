<?php
	//use Twilio\Rest\Client;
	
	//Your Account SID and Auth Token from twilio.com/console
	/*$account_sid = 'AC1a67400c4075f16f092296d0db4f8220';
	$auth_token = '3a4e85551888226c80235d42a6f96614';
	
	$twilio_number = "+18193030170";
	
	$twilio = new Client($account_sid, $auth_token);
	$call = $twilio->calls->create("+16137971900", $twilio_number,
                           array(
								'method' => "GET",
								'statusCallback' => "https://www.myapp.com/events",
								'statusCallbackEvent' => "initiated",
								'statusCallbackMethod' => "POST",
								'url' => "http://phplaravel-73309-509403.cloudwaysapps.com/preface.xml"
							)
                  );*/
	use Twilio\Twiml;

	$response = new Twiml();
	$response->dial('+16137971900');
	
	echo $response;
	//$response->dial('+16137971900');
	
	// In production, these should be environment variables. E.g.:
	//$auth_token = $_ENV["3a4e85551888226c80235d42a6f96614"];

?>