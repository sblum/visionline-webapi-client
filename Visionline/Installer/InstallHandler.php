<?php

namespace Visionline\Installer;

use Composer\Script\PackageEvent;

class InstallHandler
{
    public static function extractPhar(PackageEvent $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        if (!array_key_exists('visionline-webclient-client', $extra)
            || !array_key_exists('phar', $extra['visionline-webclient-client'])
        ) {
            throw new \RuntimeException(
                sprintf(
                    "Please specify the path to the phar in the composer.json\n\n%s",
                    json_encode(array('extra' => array('visionline-webclient-client' => array('phar' => '...'))))
                )
            );
        }

        $pharUri= $extra['visionline-webclient-client']['phar'];
        $event->getIO()->write(sprintf('Downloading Visionline phar from "%s"', $pharUri));

		$folder = __DIR__ . '/../Download';
		mkdir($folder);
		
		$pharPath = sprintf('%s/visionline.phar', $folder);
		$event->getIO()->write(sprintf('Saving phar at "%s"', $pharPath));
		
		// Download phar
		$fp = fopen ($pharPath, 'w+');
		$ch=curl_init();   
		curl_setopt($ch, CURLOPT_URL, $pharUri);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER,0); 
		$response = curl_exec($ch);
		curl_close($ch);		
		fclose($fp);
		
		$extractFolder = __DIR__ . '/../Crm';
		mkdir($extractFolder);
		$extractFolder .= '/WebApi';
		mkdir($extractFolder);
		
		$phar = new \Phar($pharPath);
		$phar->uncompressAllFiles();
		$phar->extractTo($extractFolder, null, true); // extract all files, and overwrite
		$event->getIO()->write(sprintf('Phar is extracted to "%s"', $extractFolder));
    }
}
