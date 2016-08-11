<?php
	
	require __DIR__ . '/vendor/autoload.php';

	//setup iRemote credentials.
	$app = new Thickey\app();

	//get the status of the vehicle
//	$status = Thickey\iRemote::get_vehicle_status();


	$status = json_decode('{"vehicleStatus":{"vin":"WBY1Z42070V585725","mileage":1586,"updateReason":"VEHICLE_SHUTDOWN_SECURED","updateTime":"2016-08-11T09:49:37+0200","doorDriverFront":"CLOSED","doorDriverRear":"CLOSED","doorPassengerFront":"CLOSED","doorPassengerRear":"CLOSED","windowDriverFront":"CLOSED","windowDriverRear":"CLOSED","windowPassengerFront":"CLOSED","windowPassengerRear":"CLOSED","trunk":"CLOSED","rearWindow":"INVALID","hood":"CLOSED","doorLockState":"SECURED","parkingLight":"OFF","positionLight":"OFF","remainingFuel":4.95,"remainingRangeElectric":90,"remainingRangeElectricMls":55,"remainingRangeFuel":59,"remainingRangeFuelMls":36,"maxRangeElectric":107,"maxRangeElectricMls":66,"fuelPercent":62,"maxFuel":8,"connectionStatus":"DISCONNECTED","chargingStatus":"INVALID","chargingLevelHv":87,"lastChargingEndReason":"UNKNOWN","lastChargingEndResult":"UNKNOWN","position":{"lat":51.302143,"lon":-0.72679585,"heading":37,"status":"OK"},"internalDataTimeUTC":"2016-08-11T07:49:37","singleImmediateCharging":false}}',1);
	
	//process and post the status to slack
	$slack_message = new Thickey\slack_message($status);
	
	//process and update initialstate dashboard
	$dashboard = Thickey\initialstate::new_status($status);