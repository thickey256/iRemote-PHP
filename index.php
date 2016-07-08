<?php
	
		require __DIR__ . '/vendor/autoload.php';

		//setup iRemote credentials.
		$app = new Thickey\app();
	
		/* Yeah I know mixing PHP and HTML.. I will burn in hell! */
	
	?>

	<h1>iRemote-PHP</h1>
	<p>Hi there, lets run some thigns to see if things are working.

	<h2>Get Vehicles</h2>
	<p>This will give us a nice array containing all the vehicles registed to ConnectedDrive.</p>
	<p><a href="<?=$_SERVER['PHP_SELF']?>?get_vehicles=1">Try it!</a></p>

	<hr />
	<?
		if ($_GET['get_vehicles'] == 1)
		{
			$vehicles = Thickey\iRemote::get_vehicles();
			var_dump($vehicles);
			?>
			<hr />
			<p>If you can see a var_dump and it has your vehiles then your config file is setup correctly.. Well done!</p>
			<?
		}

	?>