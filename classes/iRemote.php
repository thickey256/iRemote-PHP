<?php
	namespace Thickey;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Message\Request;
	use GuzzleHttp\Message\Response;
	
	class iRemote
	{
		/* This will look for token config file if it exists */
		/* If not then it will get and save a new one */
		function get_token()
		{
			$token_file = __DIR__.'/configs/iremote_token.json';
			$token_file = str_replace('/classes','',$token_file);
			
			//load the config variables
			if (@file_exists($token_file))
			{
				$current_date  = new \DateTime('', new \DateTimeZone("Europe/London"));
				
				//load the config variables
				if (@file_exists($token_file))
				{
					$token_array = json_decode(file_get_contents($token_file),1);
				
					//has the token expired?
					$current_date  = new \DateTime('', new \DateTimeZone("Europe/London"));
					$expiry_date   = new \DateTime($token_array['token_expiry']);
					
					//token has expired :(
					if ($current_date > $expiry_date)
					{
						//but we can get a new one! yay!
						$token_array = iRemote::get_new_token();
					}
				}
			}
			//otherwise get a new token to play with!
			else
			{
				$token_array = iRemote::get_new_token();
			}
			return $token_array['access_token'];
		}
		
		/* This will get a new token from BMW and save it to file for future use */
		function get_new_token()
		{
			//so lets try and get a token..
			$iremote_client = new Client();
			
			
			$response = $iremote_client->request('POST', iRemote_server.'/webapi/oauth/token/',
			[
				'timeout' => guzzle_timeout,
				'form_params' =>
				[
					'grant_type'	=> 'password',
					'username'		=> iRemote_user,
					'password'		=> iRemote_pass,
					'scope'			=> 'remote_services vehicle_data'
				],
				'headers'  =>
				[
					'Authorization' => iRemote_auth,
					'Content-Type' => 'application/x-www-form-urlencoded'
				]
			]);
			
			$token_array = json_decode($response->getBody(),1);
	
			//so lets write the token to a file.
			$token_file = __DIR__.'/configs/iremote_token.json';
			$token_file = str_replace('/classes','',$token_file);
			
			//we need to sort out the expiry date.
			//no need to worry about timezones etc as 8 hours from now is the same in any timezone!
			$expiry_date = new \DateTime();
			date_add($expiry_date, date_interval_create_from_date_string($token_array['expires_in'].' seconds'));
			
			$token_json['access_token'] = $token_array['access_token'];
			$token_json['token_expiry'] = $expiry_date->format('Y-m-d H:i:s');
			
			$token = file_put_contents($token_file, json_encode($token_json));
			
			return $token_json;
		}
		
		//gets list of vehicles and returns a nice array
		function get_vehicles()
		{
			$iremote_client = new Client();
		
			$server_url = iRemote_server.'/webapi/v1/user/vehicles';
			$response = $iremote_client->request('GET', $server_url,
			[
				'timeout' => guzzle_timeout,
				'headers'  =>
				[
					'Authorization' => 'Bearer '.iRemote::get_token(),
					'Content-Type' => 'application/x-www-form-urlencoded'
				]
			]);

			$response_array = json_decode($response->getBody(),1);
			
			return $response_array;
		}
		
		//gets the status of a specific vehicle
		function get_vehicle_status($vin = iRemote_vin)
		{
			$iremote_client = new Client();
		
			$server_url = iRemote_server.'/webapi/v1/user/vehicles/'.$vin.'/status';
			$response = $iremote_client->request('GET', $server_url,
			[
				'timeout' => guzzle_timeout,
				'headers'  =>
				[
					'Authorization' => 'Bearer '.iRemote::get_token(),
					'Content-Type' => 'application/x-www-form-urlencoded'
				]
			]);

			$response_array = json_decode($response->getBody(),1);
			
			return $response_array;
		}
		
	}
?>