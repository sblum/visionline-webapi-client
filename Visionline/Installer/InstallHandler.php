<?php

namespace Visionline\Installer;

use Composer\Script\Event;

class InstallHandler
{
	public static function extractPhar(Event $event)
	{
		$composer = $event->getComposer();
		$extra = $event->getComposer()->getPackage()->getExtra();
		
		$output = '';		
		$output .= print_r($composer, true) . "\n\n\n\n###############################\n\n\n\n";
		$output .= print_r($extra) . "\n\n\n\n###############################\n\n\n\n";
		$output .= print_r($event) . "\n\n\n\n###############################\n\n\n\n";
		
		file_put_contents('output.log', $output);
	}
}