<?php
	namespace Thickey;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Message\Request;
	use GuzzleHttp\Message\Response;
	
	class slack_message
	{
		function __construct($vehicle_status)
		{
			
			$status_log_file = __DIR__.'/../configs/status_log.json';
			
			if (@file_exists($status_log_file))
			{
				$status_log = json_decode(file_get_contents($status_log_file),1);
			}
			
			//sort the array out so it's a bit nicer
			$vehicle_status = $vehicle_status['vehicleStatus'];
				
			if ($status_log['timestamp'] == $vehicle_status['updateTime'])
			{
				//nothing has changed
			}
			else if (
						($status_log['event'] == 'CHARGING_STARTED') && ($vehicle_status['updateReason'] == 'CHARGING_STARTED')
					 || ($status_log['event'] == 'PREDICTION_UPDATE') && ($vehicle_status['updateReason'] == 'PREDICTION_UPDATE')
					 || ($status_log['event'] == 'VEHICLE_SECURED') && ($vehicle_status['updateReason'] == 'VEHICLE_SECURED')
				)
			{
				//still charging we don't want to do anything
				//take this out if you want an updated to your charging every time the script is run.
			}
			else
			{
				//sort out a file that logs the last 'new' result
				$log_array['timestamp'] = $vehicle_status['updateTime'];
				$log_array['event']		= $vehicle_status['updateReason'];
				$token = file_put_contents($status_log_file, json_encode($log_array));	
				/*
					so we need to sort out the updateReason..
					valud resons are as follows :-
						CHARGING_DONE
						CHARGING_INTERRUPED
						CHARGING_PAUSED
						CHARGING_STARTED
						CYCLIC_RECHARGING
						DISCONNECTED
						DOOR_STATE_CHANGED
						NO_CYCLIC_RECHARGING
						NO_LSC_TRIGGER
						ON_DEMAND
						PREDICTION_UPDATE
						TEMPORARY_POWER_SUPPLY_FAILURE
						UNKNOWN
						VEHICLE_MOVING
						VEHICLE_SECURED
						VEHICLE_SHUTDOWN
						VEHICLE_SHUTDOWN_SECURED
						VEHICLE_UNSECURED
				*/
				
				if ($vehicle_status['updateReason'] == 'VEHICLE_SHUTDOWN_SECURED')
				{
					$vehicle_status['updateReason'] = "Car Parked and Locked";
				}
				else if ($vehicle_status['updateReason'] == 'VEHICLE_SHUTDOWN')
				{
					$vehicle_status['updateReason'] = "Car Parked";
				}
				else if ($vehicle_status['updateReason'] == 'DOOR_STATE_CHANGED')
				{
					if ($vehicle_status['doorLockState'] == 'UNLOCKED')
					{
						$vehicle_status['updateReason'] = "Car Unlocked";
					}
					else
					{
						$vehicle_status['updateReason'] = "Car Locked";
					}
				}
				else if ($vehicle_status['updateReason'] == 'VEHICLE_MOVING')
				{
					$vehicle_status['updateReason'] = "Car Moving";
				}
				else if ($vehicle_status['updateReason'] == 'CHARGING_STARTED')
				{
					$vehicle_status['updateReason'] = "Charging Started";
				}
				else if ($vehicle_status['updateReason'] == 'CHARGING_DONE')
				{
					$vehicle_status['updateReason'] = "Charging Completed";
				}
				else if ($vehicle_status['updateReason'] == 'PREDICTION_UPDATE')
				{
					$vehicle_status['updateReason'] = "Charging Time Update";
				}
				else if ($vehicle_status['updateReason'] == 'VEHICLE_SECURED')
				{
					$vehicle_status['updateReason'] = "Car Locked";
				}
				

				$settings = [
					'username' => slack_user,
					'channel' => slack_channel,
					'icon'	=> slack_icon
				];

				$slack_client = new \Maknz\Slack\Client(slack_hook_url, $settings);
				
				$message['fallback'] = "Current Status";
				$message['text'] = "Current Car Status";
				$message['color'] = "good";
				
				$temp_field['title'] = "Update Event";
				$temp_field['value'] = $vehicle_status['updateReason'];
				$temp_field['short'] = "1";
				$message['fields'][] = $temp_field;
				
				$electric_percent = $vehicle_status['remainingRangeElectricMls'] / $vehicle_status['maxRangeElectricMls'];
				$electric_percent = $electric_percent * 100;
				$electric_percent = floor($electric_percent);
				
				$temp_field['title'] = "Electric Range";
				$temp_field['value'] = $vehicle_status['remainingRangeElectricMls']." miles : ".$electric_percent."%";
				$temp_field['short'] = "1";
				$message['fields'][] = $temp_field;
				
				$temp_field['title'] = "Rex Range";
				$temp_field['value'] = $vehicle_status['remainingRangeFuelMls']." miles : ".$vehicle_status['fuelPercent']."%";
				$temp_field['short'] = "1";
				$message['fields'][] = $temp_field;
				
				$temp_field['title'] = "Current Mileage";
				$temp_field['value'] = floor($vehicle_status['mileage'] * 0.621371)." miles";
				$temp_field['short'] = "1";
				$message['fields'][] = $temp_field;
				
				//if charging then it would be good to see how long is left!
				if (array_key_exists('chargingTimeRemaining', $vehicle_status))
				{
					//turn minutes to hours minutes
					$vehicle_status['chargingTimeRemaining'] = date('H:i', mktime(0,$vehicle_status['chargingTimeRemaining']));
					
					$temp_field['title'] = "Charge Time Remaining";
					$temp_field['value'] = $vehicle_status['chargingTimeRemaining'];
					$temp_field['short'] = "1";
					$message['fields'][] = $temp_field;
				}
				
				$slack_client->to(slack_channel)->attach($message)->send();
			}
		}
	}
?>