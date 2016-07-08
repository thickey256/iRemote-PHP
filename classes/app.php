<?php
	namespace Thickey;
	
	//so this should add a new event into the AWS SQS queue
	class app
	{
		function __construct()
		{
			include ('configs/config.php');
			
			//iRemote
			define ("iRemote_server",	$iRemote['server']);
			define ("iRemote_auth",		$iRemote['auth']);
			define ("iRemote_user",		$iRemote['user']);
			define ("iRemote_pass",		$iRemote['pass']);
			define ("iRemote_vin",		$iRemote['vin']);
			
			//slack
			define ("slack_hook_url",	$slack['hook_url']);
			define ("slack_user",		$slack['user']);
			define ("slack_channel",	$slack['channel']);
			define ("slack_icon",		$slack['icon']);
			
			//guzzle
			define ("guzzle_timeout",	$guzzle['request_timeout']);
			
		}
	}
?>