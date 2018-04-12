<?php
/**
 * Defines the class FileOperation.
 */

namespace Visionline\Crm\WebApi;

/**
 * Internal abstract base class defining a file operation.
 *
 * @internal
 */
abstract class FileOperation
{
    /**
     * The WebApi.
     *
     * @var WebApi
     */
    protected $webapi;

    /**
     * The URL from which to get files.
     *
     * @var string
     */
    private $getFileUrl;

    /**
     * The connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * The buffer size.
     *
     * @var int
     */
    private $bufferSize;

    /**
     * Create a new FileOperation.
     *
     * @param WebApi     $webapi     The WebApi
     * @param string     $getFileUrl The URL of the GetFile-Handler
     * @param Connection $connection The connection settings to the CRM-VISIONLINE system
     * @param int        $bufferSize The buffer size for file operations
     */
    public function __construct(WebApi $webapi, $getFileUrl, Connection $connection, $bufferSize)
    {
        $this->webapi = $webapi;
        $this->getFileUrl = $getFileUrl;
        $this->connection = $connection;
        $this->bufferSize = $bufferSize;
    }

    /**
     * Executes the file operation.
     *
     * @param int|QueryResult $document               The document to retrieve
     * @param array           $stream_context_options The stream context options
     * @param int             $width                  The width to which an image should be resized
     * @param int             $height                 The height to which an image should be resized
     * @param string          $resizeMode             Specifies how an image should be resized
     *
     * @throws \InvalidArgumentException If an invalid argument was supplied
     * @throws \Exception                if an exception occured
     *
     * @return mixed The result of this file operation
     */
    public function exec($document, $stream_context_options, $width = null, $height = null, $resizeMode = null)
    {
        if (\is_int($document)) {
            // if only id is specified, we create a QueryResult with no lastModifiedDate set
            return $this->exec(new QueryResult($document), $stream_context_options, $width, $height, $resizeMode);
        } elseif ($document instanceof QueryResult) {
            $shouldDownload = $this->shouldDownload($document, $width, $height, $resizeMode);

            $this->webapi->debug('FileOperation::exec - shouldDownload returned ', $shouldDownload);

            if (false !== $shouldDownload) {
                $ifModifiedSince = null;
                if (\is_int($shouldDownload)) {
                    $ifModifiedSince = $shouldDownload;
                }

                $this->download($document->id, $width, $height, $resizeMode, $stream_context_options, $ifModifiedSince);
            }

            return $this->getResult();
        } elseif (\is_array($document)) {
            /*
             * Left for compatibility:
             * Multiple documents should be processed using execMultiple
             */
            $results = [];
            foreach ($document as $entry) {
                \array_push($results, $this->exec($entry, $stream_context_options, $width, $height, $resizeMode));
            }

            return $results;
        } else {
            throw new \InvalidArgumentException('Invalid value for parameter "document". Expected (array of) int or QueryResult, got '.\gettype($document));
        }
    }

    /**
     * Executes the file operation.
     *
     * @param array  $documents              The documents to retrieve
     * @param array  $stream_context_options The stream context options
     * @param int    $width                  The width to which an image should be resized
     * @param int    $height                 The height to which an image should be resized
     * @param string $resizeMode             Specifies how an image should be resized
     *
     * @throws \InvalidArgumentException If an invalid argument was supplied
     * @throws \Exception                if an exception occured
     *
     * @return mixed The results of this file operation
     */
    public function execMultiple($documents, $stream_context_options, $width = null, $height = null, $resizeMode = null)
    {
        $results = [];
        foreach ($documents as $document) {
            if (\is_int($document)) {
                $id = $document;
            } elseif ($document instanceof QueryResult) {
                $id = $document->id;
            } else {
                throw new \InvalidArgumentException('Invalid value int parameter "documents". Expected int or QueryResult, got '.\gettype($document));
            }

            $results[$id] = $this->exec($document, $stream_context_options, $width, $height, $resizeMode);
        }

        return $results;
    }

    /**
     * Downloads the contents of the specified document.
     *
     * @param int    $id                     The id of the document
     * @param int    $width                  The width to which an image should be resized
     * @param int    $height                 The height to which an image should be resized
     * @param string $resizeMode             Specifies how an image should be resized
     * @param array  $stream_context_options The stream context options
     * @param int    $ifModifiedSince        If specified, the file is only downloaded if it was modified since <code>$ifModifiedSince</code> (timestamp)
     *
     * @throws \Exception If an error occurs during download
     */
    private function download($id, $width, $height, $resizeMode, $stream_context_options, $ifModifiedSince = null)
    {
        // Build uri
        $uri = new UriBuilder($this->getFileUrl);
        $uri->addParameter('host', $this->connection->host);
        $uri->addParameter('port', $this->connection->port);
        $uri->addParameter('username', $this->connection->username);
        $uri->addParameter('password', $this->connection->password);
        $uri->addParameter('id', $id);

        if (isset($width)) {
            $uri->addParameter('width', $width);
        }

        if (isset($height)) {
            $uri->addParameter('height', $height);
        }

        if (isset($resizeMode)) {
            $uri->addParameter('resizeMode', $resizeMode);
        }

        // Begin request
        if (isset($ifModifiedSince) && \is_int($ifModifiedSince)) {
            // If $ifModifiedSince is specified, set the If-Modified-Since header

            $header = 'If-Modified-Since: '.\date('r', $ifModifiedSince);

            $this->webapi->debug('FileOperation::download - Adding header ', $header);

            $source = \fopen($uri, 'rb', false, \stream_context_create(\array_merge(
          $stream_context_options,
          [
              'http' => [
                  'header' => $header,
              ],
          ]
      )));
        } else {
            $source = \fopen($uri, 'rb', false, \stream_context_create($stream_context_options));
        }

        $this->webapi->debug('FileOperation::download - Requesting ', (string) $uri);

        if (!$source) {
            throw new \Exception('Could not open '.$uri);
        }

        // Get content-type and file extension from stream meta-data (http-headers)
        $meta = \stream_get_meta_data($source);
        if (isset($meta['wrapper_data']) && \is_array($meta['wrapper_data'])) {
            $this->webapi->debug('FileOperation::download - Headers: ', $meta['wrapper_data']);
            foreach ($meta['wrapper_data'] as $header) {
                if (0 === \stripos($header, 'HTTP/1.1 304')) {
                    // Not modified: Return immediately
                    $this->webapi->debug('FileOperation::download - Retrieved status: 304 Not Modified');

                    \fclose($source);

                    return;
                }
                if (0 === \stripos($header, 'content-type:')) {
                    $contentType = \substr($header, \strpos($header, ':') + 2);
                }
                if (0 === \stripos($header, 'last-modified:')) {
                    $lastModified = \strtotime(\substr($header, \strpos($header, ':') + 2));
                } else {
                    $lastModified = \time();
                }
                if (0 === \stripos($header, 'content-disposition:')) {
                    $contentDisposition = \substr($header, \strpos($header, ':') + 1);
                    $pos = \stripos($contentDisposition, 'filename=');
                    if (false !== $pos) {
                        $filename = \substr($contentDisposition, $pos + \strlen('filename='));
                        if (0 === \strpos($filename, '"')) {
                            $filename = \substr($filename, 1, \strlen($filename) - 2);
                        }
                        $extension = \strtolower(\substr($filename, \strrpos($filename, '.')));
                    }
                }
            }
        }

        if (!isset($contentType)) {
            throw new \Exception('Could not get content-type from '.$uri);
        }

        if (!isset($extension)) {
            throw new \Exception('Could not get file extension from '.$uri);
        }

        $this->webapi->debug('FileOperation::download - Got content-type: ', $contentType);
        $this->webapi->debug('FileOperation::download - Got file extension: ', $extension);
        $this->webapi->debug('FileOperation::download - Got last-modified: ', $lastModified);

        $this->processMetaData($id, $width, $height, $resizeMode, $contentType, $filename, $extension, $lastModified);

        // Process file data
        while (!\feof($source)) {
            $data = \fread($source, $this->bufferSize);
            $this->process($data);
        }

        // Close file handle
        \fclose($source);
    }

    /**
     * Abstract method to process data of the downloaded file.
     *
     * @param string $data
     */
    abstract protected function process($data);

    /**
     * Abstract method to process meta data of the downloaded file.
     *
     * @param int    $id           The id of the entity
     * @param int    $width        The width to which an image should be resized
     * @param int    $height       The height to which an image should be resized
     * @param string $resizeMode   Specifies how an image should be resized
     * @param string $contentType  The content type of the file
     * @param string $filename     The filename of the file
     * @param string $extension    The file extension of the file
     * @param int    $lastModified The timestamp of the last modification of the file
     */
    abstract protected function processMetaData($id, $width, $height, $resizeMode, $contentType, $filename, $extension, $lastModified);

    /**
     * Abstract method to return the result of the file operation.
     */
    abstract protected function getResult();

    /**
     * Abstract method to indicate whether the file should be downloaded.
     *
     * @param QueryResult $document   The document in question
     * @param int         $width      The width to which an image should be resized
     * @param int         $height     The height to which an image should be resized
     * @param string      $resizeMode Specifies how an image should be resized
     *
     * @return true, if the file should be downloaded; false, if the file should not be downloaded; or a timestamp if the file should be downloaded if it is newer than this timestamp
     */
    abstract protected function shouldDownload(QueryResult $document, $width, $height, $resizeMode);
}
