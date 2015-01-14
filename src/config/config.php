<?php
/**
 * Configuration File Auth service.
 *
 * @author     Gponster <anhvudg@gmail.com>
 * @copyright  Copyright (c) 2014
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| Auth Config
	|--------------------------------------------------------------------------
	*/
	'users' => [
		[            
			'username' 			=> 'gponster',
            'email' 			=> 'gponster@outlook.com',
            'password' 			=> Hash::make('gponst3R1233456789'),	// test purposes only, use hashed value here
        ],
	]
);