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

		
	}
?>