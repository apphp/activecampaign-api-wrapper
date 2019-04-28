<?php

	/**
	 * To run these examples get and insert username, password and api key.
	 * To get these parameters, you have to register on activecampaign.com
	 */
	
	use \Apphp\ActiveCampaign\ActiveCampaign;

	# Load library
	require('ActiveCampaign.php');
	
	# Create new object
	$config = array(
		'username' 	=> '<USERNAME>',
		'password' 	=> '<PASSWORD>',
		'api_key'	=> '<API-KEY>',
	);
	
