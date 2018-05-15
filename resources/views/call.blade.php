<?php
	use Twilio\Rest\Client;

	// Your Account SID and Auth Token from twilio.com/console
	$account_sid = 'AC1a67400c4075f16f092296d0db4f8220';
	$auth_token = '3a4e85551888226c80235d42a6f96614';
	// In production, these should be environment variables. E.g.:
	//$auth_token = $_ENV["3a4e85551888226c80235d42a6f96614"];

	// A Twilio number you own with SMS capabilities
	$twilio_number = "+18193030170";

	$client = new Client($account_sid, $auth_token);
	$client->messages->create(
		// Where to send a text message (your cell phone?)
		'+16137971900',
		array(
			'from' => $twilio_number,
			'body' => 'more shit!'
		)
	);
?>