<?php

namespace Visionline\Installer;

use Composer\Script\Event;

class InstallHandler
{
	public static function extractPhar(Event $event)
	{
		$composer = $event->getComposer();
		$extra = $event->getComposer()->getPackage()->getExtra();

		if (!array_key_exists('visionline-webclient-client', $extra)
            || !array_key_exists('phar', $extra['visionline-webclient-client'])
        ) {
            throw new \RuntimeException(
                sprintf(
                    "Please specify the path to the phar in composer.json\n\n\s",
                    json_encode(array('visionline-webclient-client' => array('phar' => '...')))
                )
            );
        }

        $pharPath = $extra['visionline-webclient-client']['phar'];
        $event->getIO()->write(sprintf('Downloading Visionline phar from "%s"', $pharPath));

        print_r($composer->getConfig());
		print_r($event->getOperation()->getPackage(););
	}
}