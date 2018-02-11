visionline-webapi-client
========================

[![Build Status](https://travis-ci.org/sblum/visionline-webapi-client.svg?branch=master)](https://travis-ci.org/sblum/visionline-webapi-client)
[![Coverage Status](https://coveralls.io/repos/github/sblum/visionline-webapi-client/badge.svg?branch=master)](https://coveralls.io/github/sblum/visionline-webapi-client?branch=master)

Extraction of the visionline webapi client phar for the crm from visionline.at

Visionline is CRM, which offers a PHP phar for the webapi client.
See https://app2.visionline.at/Help/

1. Add to your composer.json and specify the uri of the phar
	
		// composer.json

 		{
    		// ...
    		"require": {
        		// ...
        		"sebastianblum/visionline-webapi-client": "^1.0"
    		}
 		}


2. Use composer to download and install the library

		$ php composer.phar update sebastianblum/visionline-webapi-client

	
3. Use the library 

		// example.php

		<?php
			require_once('./vendor/autoload.php');
		
			$connection = new \Visionline\Crm\WebApi\Connection(...);
			$webapi = new \Visionline\Crm\WebApi\WebApi(...);
			
This library is used at the Terrafinanz Website https://www.terrafinanz.de/ to communicate with the Visionline CRM.		
		
		
