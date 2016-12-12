<?php
        namespace Thickey;

        use GuzzleHttp\Client;
        use GuzzleHttp\Message\Request;
        use GuzzleHttp\Message\Response;

	class dweet
	{
		function post_trip_data($trip_data_array)
		{
			$dweet_client = new Client();

			$response = $dweet_client->request('POST', dweet_endpoint,
			[
				'timeout' => guzzle_timeout,
				'body' => json_encode($trip_data_array),
				'headers' =>
				[
					'Content-Type' => 'application/json'
				]
			]);
			return $response;
		}

		function convert_consumption($kWh_100Km)
		{
			return 1 / (0.01609344 * $kWh_100Km);
		}

		function convert_km_to_miles($km)
		{
			return ceil($km / 1.609344);
		}

		function process_trip_data($trip)
		{
			$trip_data = $trip['lastTrip'];

			// Read from log file
			$trip_log_file = __DIR__.'/../configs/trip_log.json';
			if (@file_exists($trip_log_file))
			{
				$trip_log = json_decode(file_get_contents($trip_log_file),1);
				if ($trip_log['date'] == $trip_data['date'])
				{
					// Nothing has changed so return
					return 0;
				}
			}

			// Write to log file and contents
			$log_array['date'] = $trip_data['date'];
			$token = file_put_contents($trip_log_file, json_encode($log_array));

			// Ready to prepare the data array
			$data['date'] = $trip_data['date'];
			$data['electricDistance'] = dweet::convert_km_to_miles($trip_data['electricDistance']);
			$data['avgElectricConsumption'] = dweet::convert_consumption($trip_data['avgElectricConsumption']);

			$dweet_dash = dweet::post_trip_data($data);
		}
	}
?>
