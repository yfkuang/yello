<?php
	//use Twilio\Rest\Client;
	
	//Your Account SID and Auth Token from twilio.com/console
	/*$account_sid = 'AC1a67400c4075f16f092296d0db4f8220';
	$auth_token = '3a4e85551888226c80235d42a6f96614';
	
	$twilio_number = "+18193030170";
	
	$twilio = new Client($account_sid, $auth_token);*/
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Say voice="alice" language="en-CA">Call service provided by LeadLabs Marketing. This call is directed to Untitled Service.</Say>
</Response>');

	$xml->asXML('xml/whisper.xml');

	use Twilio\Twiml;

	$response = new Twiml();
	$dial = $response->dial();
	$dial->number('+16137971900', ['url' => 'http://phplaravel-73309-509403.cloudwaysapps.com/xml/whisper.xml']);
	
	echo $response;
	//$response->dial('+16137971900');
	
	// In production, these should be environment variables. E.g.:
	//$auth_token = $_ENV["3a4e85551888226c80235d42a6f96614"];

?>