<?php

	/*
		Please read the README.MD file inside the configs directory for info
	*/

	//iRemote
	$iRemote['server'] = 'https://b2vapi.bmwgroup.com';
	$iRemote['auth'] = 'Basic sorry-you-need-to-get-this-yourslef==';
	$iRemote['user'] = 'user@user.com';
	$iRemote['pass'] = 'password';
	$iRemote['vin'] = 'WBYxxxxxxxxxxxxxx';


	//slack
	$slack['hook_url'] = 'https://hooks.slack.com/services/your_hook_url_here';
	$slack['user'] = 'Name of your car';
	$slack['channel'] = '#random';
	$slack['icon'] = ':car:';
	
	//guzzle
	$guzzle['request_timeout'] = '5.0';
	
	//initialstate
	$initialstate['endpoint_url']	= 'https://groker.initialstate.com/api/events';
	$initialstate['access_key']		= 'your-key';
	$initialstate['bucket_key']		= 'your-bucket';
?>