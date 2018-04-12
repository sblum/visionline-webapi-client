<?php
/**
 * Defines the class FileGetOperation.
 */

namespace Visionline\Crm\WebApi;

/**
 * Internal class that defines the file-operation "get".
 *
 * @internal
 */
class FileGetOperation extends FileOperation
{
    /**
     * The downloaded data.
     *
     * @var string
     */
    private $data;

    /**
     * Create a get file operation.
     *
     * @param WebApi     $webapi     The WebApi
     * @param string     $getFileUrl The URL of the GetFile-Handler
     * @param Connection $connection The connection settings to the CRM-VISIONLINE system
     * @param int        $bufferSize The buffer size for file operations
     */
    public function __construct(WebApi $webapi, $getFileUrl, Connection $connection, $bufferSize)
    {
        parent::__construct($webapi, $getFileUrl, $connection, $bufferSize);
    }

    /**
     * Appends the retrieved data to the internal buffer.
     *
     * @param string $data The data
     *
     * @see \Visionline\Crm\WebApi\FileOperation::process()
     */
    protected function process($data)
    {
        $this->data .= $data;
    }

    /**
     * Does nothing.
     *
     * @param int    $id           The id of the entity
     * @param int    $width        The width to which an image should be resized
     * @param int    $height       The height to which an image should be resized
     * @param string $resizeMode   Specifies how an image should be resized
     * @param string $contentType  The content type of the file
     * @param string $filename     The filename of the file
     * @param string $extension    The file extension of the file
     * @param int    $lastModified The timestamp of the last modification of the file
     *
     * @see \Visionline\Crm\WebApi\FileOperation::processMetaData()
     */
    protected function processMetaData($id, $width, $height, $resizeMode, $contentType, $filename, $extension, $lastModified)
    {
        // Nothing to do
    }

    /**
     * Returns the internal buffer.
     *
     * @return string The internal buffer
     *
     * @see \Visionline\Crm\WebApi\FileOperation::getResult()
     */
    protected function getResult()
    {
        return $this->data;
    }

    /**
     * Returns true.
     *
     * @param QueryResult $document   The document in question
     * @param int         $width      The width to which an image should be resized
     * @param int         $height     The height to which an image should be resized
     * @param string      $resizeMode Specifies how an image should be resized
     *
     * @see \Visionline\Crm\WebApi\FileOperation::shouldDownload()
     */
    protected function shouldDownload(QueryResult $document, $width, $height, $resizeMode)
    {
        return true;
    }
}
