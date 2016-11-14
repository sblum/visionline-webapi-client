<?php
/**
 * Defines the class FileSaveOperation
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Internal class that defines the file-operation "save" 
 * @internal
 */
class FileSaveOperation extends FileOperation
{
  /**
   * The directory to which to save the file
   * @var string
   */
  private $directory;
  
  /**
   * Specifies whether the file should be downloaded, even if it exists and is not outdated.
   * @var bool
   */
  private $forceDownload;

  /**
   * Holds the filename under which the file has been saved.
   * @var string
   */
  private $filename;
  
  /**
   * Holds the file handle to the file
   * @var resource
   */
  private $fd;
  
  /**
   * Holds the timestamp of the last modification
   * @var int
   */
  private $lastModified;
  
  /**
   * Creates a save file operation
   * @param WebApi $webapi The WebApi
   * @param string $getFileUrl The URL of the GetFile-Handler
   * @param Connection $connection The connection settings to the CRM-VISIONLINE system
   * @param int $bufferSize The buffer size for file operations
   * @param string $directory The directory to which to save the file
   * @param bool $forceDownload Specifies whether the file should be downloaded, even if it exists and is not outdated.
   */
  public function __construct(WebApi $webapi, $getFileUrl, Connection $connection, $bufferSize, $directory, $forceDownload)
  {
    parent::__construct($webapi, $getFileUrl, $connection, $bufferSize);

    $this->directory = $directory;
    $this->forceDownload = $forceDownload;
  }
  
  /**
   * Saves the downloaded data to the file.
   * @param string $data The data to write
   */
  protected function process($data)
  {
    fwrite($this->fd, $data);
  } 

  /**
   * Processes the meta data of the file to download
   * @param int $id The id of the entity
   * @param int $width The width to which an image should be resized
   * @param int $height The height to which an image should be resized
   * @param string $resizeMode Specifies how an image should be resized
   * @param string $contentType The content type of the file
   * @param string $filename The filename of the file
   * @param string $extension The file extension of the file
   * @param int $lastModified The timestamp of the last modification of the file
   */
  protected function processMetaData($id, $width, $height, $resizeMode, $contentType, $filename, $extension, $lastModified)
  {
    $this->filename = $this->directory . '/' . $this->getFilename($id, $width, $height, $resizeMode, $contentType, $extension);
    
    if (!file_exists(dirname($this->filename)))
    {
      mkdir(dirname($this->filename));
    }
    
    $this->fd = fopen($this->filename, 'w');
    $this->lastModified = $lastModified;
  }
  
  /**
   * Returns the filename to which the file has been saved.
   * @see \Visionline\Crm\WebApi\FileOperation::getResult()
   * @return string The filename
   */
  protected function getResult()
  {
    if (is_resource($this->fd))
    {
      fclose($this->fd);
    }
    if (is_numeric($this->lastModified))
    {
      touch($this->filename, $this->lastModified);
    }
    return $this->filename;
  }
  
  /**
   * Returns true, if forceDownload has been specified, the file does not yet exist, or the file is outdated.
   * @param QueryResult $document The document in question
   * @param int $width The width to which an image should be resized
   * @param int $height The height to which an image should be resized
   * @param string $resizeMode Specifies how an image should be resized
   * @return bool Whether the file should be downloaded
   * @see \Visionline\Crm\WebApi\FileOperation::shouldDownload()
   */
  protected function shouldDownload(QueryResult $document, $width, $height, $resizeMode)
  {
    $this->webapi->debug("FileSaveOperation::shouldDownload - document = ", $document);
    
    if (!$this->forceDownload)
    {
      $filename = $this->getFilename($document->id, $width, $height, $resizeMode);
      $filepath = $this->directory . '/' . $filename;
      $dirpath = dirname($filepath);
      
      $this->webapi->debug("FileSaveOperation::shouldDownload - looking up file = ", $filepath);
      
      if (!file_exists($dirpath))
      {
        $this->webapi->debug("FileSaveOperation::shouldDownload - directory for document does not exist.");
        return true;
      }
      
      if ($dir = opendir($dirpath))
      {
        while (($entry = readdir($dir)) !== false)
        {
          if (strpos($entry, basename($filename)) === 0)
          {
            $this->webapi->debug("FileSaveOperation::shouldDownload - found local copy ", $entry);
            
            $this->filename = $dirpath . '/' . $entry;
            
            // Get timestamp of our copy
            $timestamp = filemtime($this->filename);
    
            $this->webapi->debug("FileSaveOperation::shouldDownload - local copy's filemtime = ", $timestamp);
            
            // If the file is newer than the document's last modification, we don't need to download it;
            // else we return the timestamp to allow sending the If-Modified-Since header
            if ($document->lastModified != null && $timestamp >= $document->lastModified)
            {
              return false;
            }
            else 
            {
              return $timestamp;
            }
          }
        }
        closedir($dir);
      }
    }
    
    return true;
  }
  
  /**
   * Calculates the filename from the specified parameters
   * @param int $id The id of the entity
   * @param int $width The width to which an image should be resized
   * @param int $height The height to which an image should be resized
   * @param string $resizeMode Specifies how an image should be resized
   * @param string $contentType The content type of the file
   * @param string $extension The file extension of the file
   * @return string The filename
   */
  private function getFilename($id, $width = null, $height = null, $resizeMode = null, $contentType = null, $extension = null)
  {
    $filename = $id . '/' . $id;
    
    if (isset($width) || isset($height) || isset($resizeMode))
    {
      $filename .= "[$width,$height,$resizeMode]";
    }
    
    $filename .= '-';
    
    if (isset($contentType))
    {
      $filename .= str_replace('/', '_', $contentType);
    }
  
    if (isset($extension))
    {
      $filename .= $extension;
    }
    
    return $filename;
  }
  
}