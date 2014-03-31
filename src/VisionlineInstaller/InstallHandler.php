<?php

namespace VisionlineInstaller;

use Composer\Script\Event;

class InstallHandler
{
	public static function extractPhar(Event $event)
	{
		$composer = $event->getComposer();
		$installedPackage = $event->getOperation()->getPackage();
		
		$output = '';		
		$output .= print_r($composer, true) . "\n\n\n\n###############################\n\n\n\n";
		$output .= print_r($installedPackage) . "\n\n\n\n###############################\n\n\n\n";
		$output .= print_r($event) . "\n\n\n\n###############################\n\n\n\n";
		
		file_put_contents('output.log', $output);
	}
}