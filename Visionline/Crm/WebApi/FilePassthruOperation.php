<?php
/**
 * Defines the class FilePassthruOperation
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Internal class that defines the file-operation "passthru"
 * @internal
 */
class FilePassthruOperation extends FileOperation
{
  /**
   * Indicates whether headers should be sent
   * @var bool
   */
  private $sendHeaders;
  
  /**
   * Indicates whether the Content-disposition header should be set to 'attachment'. 
   * @var bool
   */
  private $attachment;
  
  /**
   * Creates a FilePassthruOperation
   * @param WebApi $webapi The WebApi
   * @param string $getFileUrl The URL of the GetFile-Handler
   * @param Connection $connection The connection settings to the CRM-VISIONLINE system
   * @param int $bufferSize The buffer size for file operations
   * @param bool $sendHeaders Indicates whether headers should be sent
   * @param bool $attachment Indicates whether the Content-disposition header should be set to 'attachment'
   */
  public function __construct(WebApi $webapi, $getFileUrl, Connection $connection, $bufferSize, $sendHeaders, $attachment)
  {
    parent::__construct($webapi, $getFileUrl, $connection, $bufferSize);
    
    $this->sendHeaders = $sendHeaders;
    $this->attachment = $attachment;
  }
  
  /**
   * Outputs the received data.
   * @param string $data The data
   * @see \Visionline\Crm\WebApi\FileOperation::process()
   */
  protected function process($data)
  {
    echo($data);
  } 

  /**
   * Sends the 'Content-type' and 'Content-disposition' headers if sendHeaders was set
   * @param int $id The id of the entity
   * @param int $width The width to which an image should be resized
   * @param int $height The height to which an image should be resized
   * @param string $resizeMode Specifies how an image should be resized
   * @param string $contentType The content type of the file
   * @param string $filename The filename of the file
   * @param string $extension The file extension of the file
   * @param int $lastModified The timestamp of the last modification of the file
   * @see \Visionline\Crm\WebApi\FileOperation::processMetaData()
   */
  protected function processMetaData($id, $width, $height, $resizeMode, $contentType, $filename, $extension, $lastModified)
  {
    if ($this->sendHeaders)
    {
      header('Content-type: ' . $contentType);
      header('Content-disposition: ' . ($this->attachment ? 'attachment' : 'inline') . '; filename="' . $filename . '"');
      header('Last-Modified: ' . $lastModified);
    }
  }
  
  /**
   * Returns null
   * @return null
   * @see \Visionline\Crm\WebApi\FileOperation::getResult()
   */
  protected function getResult()
  {
    return null; // No result
  }
  
  /**
   * Returns true
   * @param QueryResult $document The document in question
   * @param int $width The width to which an image should be resized
   * @param int $height The height to which an image should be resized
   * @param string $resizeMode Specifies how an image should be resized
   * @return true
   * @see \Visionline\Crm\WebApi\FileOperation::shouldDownload()
   */
  protected function shouldDownload(QueryResult $document, $width, $height, $resizeMode)
  {
    return true;
  }
  
}