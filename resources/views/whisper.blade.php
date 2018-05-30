<?php
	/*use Twilio\Twiml;
	
	$response = new Twiml();
	$response->say('Call service provided by LeadLabs Marketing. This call is directed to Untitled Service.', ['voice' => 'woman', 'language' => 'en-CA']);

	echo $response;*/
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<Response>
	<Say voice="alice" language="en-CA">Call service dddddprovided by LeadLabs Marketing. This call is directed to Untitled Service.</Say>
</Response>');
	
	/*$response = $xml->addChild('Response');
	$say = $response->addChild('Say', 'Call service provided by LeadLabs Marketing. This call is directed to Untitled Service.');
	$say->addAttribute('voice','alice');
	$say->addAttribute('language','en-CA');*/

	$xml->asXML('xml/whisper.xml');
?>