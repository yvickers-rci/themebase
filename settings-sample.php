<?php
switch($_SERVER['HTTP_HOST']){
	/* live settings should be the default */
	default:
		$themebase_settings = array(
			'client_directory'=>'sample-client',

		    'typekit_id'=>'',
			'addthis_id'=>'',
			'ga_id'=>'',

		    'image_sizes'=>array(
				array('thumb-800x600', 800, 600, true),
				array('thumb-480x320', 480, 320, true),
			),
		);
	break;

	/* dev environments */
}