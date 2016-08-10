<?php
	
	require __DIR__ . '/vendor/autoload.php';

	//setup iRemote credentials.
	$app = new Thickey\app();

	//get the status of the vehicle
	$status = Thickey\iRemote::get_vehicle_status();
	
	$data_count = 0;
	$data_array[0]['key'] = 'Map';
	$data_array[$data_count]['value'] = $status['vehicleStatus']['position']['lat'].','.$status['vehicleStatus']['position']['lon'];
	
	$data_count ++;
	$data_array[$data_count]['key'] = 'Battery';
	$data_array[$data_count]['value'] = $status['vehicleStatus']['remainingRangeElectricMls'];
	
	$data_count ++;
	$data_array[$data_count]['key'] = 'Petrol';
	$data_array[$data_count]['type'] = 'gauge';
	$data_array[$data_count]['min'] = 0;
	$data_array[$data_count]['max'] = 100;
	$data_array[$data_count]['value'] = $status['vehicleStatus']['fuelPercent'];
	
	$dashboard = Thickey\initialstate::change_status($data_array);
	
	
	echo "<pre style='clear:both; background-color: #bd7ecc; color: #000000'>";
		print_r($dashboard);
	echo "</pre>";

	//process and post the status to slack
	//$slack_message = new Thickey\slack_message($status);
