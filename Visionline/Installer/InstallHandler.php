<?php

namespace Visionline\Installer;

use Composer\Script\PackageEvent;

class InstallHandler
{
    public static function extractPhar(PackageEvent $event)
    {
        $composer = $event->getComposer();
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (!array_key_exists('visionline-webclient-client', $extra)
            || !array_key_exists('phar', $extra['visionline-webclient-client'])
        ) {
            throw new \RuntimeException(
                sprintf(
                    "Please specify the path to the phar in composer.json\n\n%s",
                    json_encode(array('extra' => array('visionline-webclient-client' => array('phar' => '...'))))
                )
            );
        }

        $pharUri= $extra['visionline-webclient-client']['phar'];
        $event->getIO()->write(sprintf('Downloading Visionline phar from "%s"', $pharUri));

		$pharPath = __DIR__ . '/../Download/visionline.phar';
		$event->getIO()->write(sprintf('Saving phar at "%s"', $pharPath));
		
		$ch=curl_init();   
		curl_setopt($ch, CURLOPT_URL, $pharUri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER,0); 
		$response = curl_exec($ch);
		curl_close($ch);
		
		file_put_contents($pharPath, $response);
		
		
    }
}
