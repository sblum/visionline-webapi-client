<?php

namespace Visionline\Installer;

use Composer\Script\Event;

class InstallHandler
{
	public static function extractPhar(Event $event)
	{
		$composer = $event->getComposer();
		$installedPackage = $event->getOperation()->getPackage();
		
		print_r($composer);
		print_r($installedPackage);
		print_r($event);
	}
}