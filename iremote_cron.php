<?php
	
	require __DIR__ . '/vendor/autoload.php';

	//setup iRemote credentials.
	$app = new Thickey\app();

	//get the status of the vehicle
	$status = Thickey\iRemote::get_vehicle_status();
	$lastTrip = Thickey\iRemote::get_vehicle_last_trip();

	if (slack_enabled == 1)
	{
		//process and post the status to slack
		$slack_message = new Thickey\slack_message($status);
	}
	
	if (initialstate_enabled == 1)
	{
		//process and update initialstate dashboard
		$dashboard = Thickey\initialstate::new_status($status);
	}
	
	if (dweet_enabled == 1)
	{
		//process dweet efficiency update
		$dweet_dash = Thickey\dweet::process_trip_data($lastTrip);
	}
?>
