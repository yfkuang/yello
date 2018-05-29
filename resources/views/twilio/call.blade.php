<?php
	//Creates XML file to act as whisper
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Say voice="alice" language="en-CA">Call service provided by LeadLabs Marketing. This call is directed to Untitled Service.</Say>
</Response>');

	$xml->asXML('xml/whisper.xml');

	use Twilio\Twiml;
	
	//Forwards response to call
	$response = new Twiml();
	$dial = $response->dial();
	$dial->number('+16137971900', ['url' => 'http://phplaravel-73309-509403.cloudwaysapps.com/xml/whisper.xml']);//Forwards to whisper
	
	echo $response;
?>