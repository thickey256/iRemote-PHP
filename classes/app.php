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
			
			//initialstate
			define ("initialstate_endpoint",	$initialstate['endpoint_url']);
			define ("initialstate_access_key",	$initialstate['access_key']);
			define ("initialstate_bucket_key",	$initialstate['bucket_key']);
			
			
			//guzzle
			define ("guzzle_timeout", $guzzle['request_timeout']);
			
			//which cron items to run
			define ("slack_enabled", $cron['post_to_slack']);
			define ("initialstate_enabled", $cron['post_to_initialstate']);
		}
	}
?>