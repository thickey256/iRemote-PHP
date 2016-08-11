<?php
	namespace Thickey;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Message\Request;
	use GuzzleHttp\Message\Response;
	
	class initialstate
	{
		/* This will look for token config file if it exists */
		/* If not then it will get and save a new one */
		function change_status($data_array)
		{
			$initialstate_client = new Client();
						
			$response = $initialstate_client->request('POST', initialstate_endpoint,
			[
				'timeout' => guzzle_timeout,
				'body' => json_encode($data_array),
				'headers'  =>
				[
					'Content-Type' => 'application/json',
					'Accept-Version' => '0.0.1',
					'X-IS-AccessKey' => initialstate_access_key,
					'X-IS-BucketKey' => initialstate_bucket_key
				]
			]);
			return $response;
		}
		
		//converts KM to Miles
		function convert_km_to_miles($km)
		{
			return ceil($km / 1.609344);
		}
		
		//takes a charging time (mins) and makes it hours and mins
		function process_charging_time($charging_time)
		{
			if ($charging_time)
			{
				//turn minutes to hours minutes
				$charging_time = date('H:i', mktime(0,$charging_time));
			}
			else
			{
				$charging_time = '00:00';
			}
			return $charging_time;
		}
		
		//Takes the charging status and returns a human readable result
		function process_charging_status($status)
		{
			/*
				The results I'm expecting are as follows :-
					CHARGING
					ERROR
					FINISHED_FULLY_CHARGED
					FINISHED_NOT_FULL
					INVALID
					NOT_CHARGING
					WAITING_FOR_CHARGING
			*/
			
			if ($status == 'INVALID')
			{
				$status = "Not Charging";
			}
			return ucwords(strtolower($status));
		}
		
		//sorts out a nice array of data to post to initialstate
		function new_status($status)
		{
			//plot the current location on a map tile
			$data_count = 0;
			$data_array[0]['key'] = 'Map';
			$data_array[$data_count]['value'] = $status['vehicleStatus']['position']['lat'].','.$status['vehicleStatus']['position']['lon'];
			
			//total mileage
			$data_count = 0;
			$data_array[0]['key'] = 'Total Mileage';
			
			//convert KM to Miles
			$status['vehicleStatus']['mileage'] =  initialstate::convert_km_to_miles($status['vehicleStatus']['mileage']);
			$data_array[$data_count]['value'] = $status['vehicleStatus']['mileage'];
			
			//the remaining battery power in miles
			$data_count ++;
			$data_array[$data_count]['key'] = 'Battery';
			$data_array[$data_count]['value'] = $status['vehicleStatus']['remainingRangeElectricMls'];
			
			//the remaining battery power in %
			$data_count ++;
			$data_array[$data_count]['key'] = 'Battery Percent';
			$data_array[$data_count]['value'] = $status['vehicleStatus']['chargingLevelHv'];
			
			//the remaining petrol power in %
			$data_count ++;
			$data_array[$data_count]['key'] = 'Petrol Percent';
			$data_array[$data_count]['value'] = $status['vehicleStatus']['fuelPercent'];
			
			//the charging cable status
			$data_count ++;
			$data_array[$data_count]['key'] = 'Connection Status';
			$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['connectionStatus']));
			
			//the status of charging
			$data_count ++;
			$data_array[$data_count]['key'] = 'Charging Status';
			$status['vehicleStatus']['chargingStatus'] = initialstate::process_charging_status($status['vehicleStatus']['chargingStatus']);
			$data_array[$data_count]['value'] = $status['vehicleStatus']['chargingStatus'];
			
			//current extimate of charging time left.
			$data_count ++;
			$data_array[$data_count]['key'] = 'Charging Time Left';
			$status['vehicleStatus']['chargingTimeRemaining'] = initialstate::process_charging_time($status['vehicleStatus']['chargingTimeRemaining']);
			$data_array[$data_count]['value'] = $status['vehicleStatus']['chargingTimeRemaining'];
			
			//now the status of the doors/windows/trunk
				$data_count ++;
				$data_array[$data_count]['key'] = 'Driver Front Door';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['doorDriverFront']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Driver Rear Door';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['doorDriverRear']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Passenger Front Door';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['doorPassengerFront']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Passenger Rear Door';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['doorPassengerRear']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Driver Front Window';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['windowDriverFront']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Driver Rear Window';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['windowDriverRear']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Passenger Front Window';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['windowPassengerFront']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Passenger Rear Window';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['windowPassengerRear']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Trunk';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['trunk']));
				
				$data_count ++;
				$data_array[$data_count]['key'] = 'Hood';
				$data_array[$data_count]['value'] = ucwords(strtolower($status['vehicleStatus']['hood']));
				
			//post the data to initialstate
			$dashboard = initialstate::change_status($data_array);
		}
	}
?>