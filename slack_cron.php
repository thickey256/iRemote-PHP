<?php
	
	require __DIR__ . '/vendor/autoload.php';

	//setup iRemote credentials.
	$app = new Thickey\app();

	//get the status of the vehicle
	$status = Thickey\iRemote::get_vehicle_status();

	//process and post the status to slack
	$slack_message = new Thickey\slack_message($status);
