<?php
/**
 * Defines the class FileCache
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Implements a cache using files.
 * Files are locked to ensure, that concurrent calls to get and put do not corrupt the cache.
 */
class FileCache implements Cache
{
  /**
   *  The directory in which to store the cache's files.
   * @var string
   */
  private $directory;
  
  /**
   * Create a new file cache
   * @param string $directory The directory in which to store the cache's files.
   * @throws \InvalidArgumentException If the paramter directory is not set, does not denote a directory, or the specified directory is not writeable.
   */
  function __construct($directory)
  {
    if (!isset($directory))
    {
      throw new \InvalidArgumentException('Parameter "directory" is not set.');
    }
    
    if (!is_dir($directory))
    {
      throw new \InvalidArgumentException('Parameter "directory" is not a directory. Input was: ' . $directory);
    }
    
    if (!is_writable($directory))
    {
      throw new \InvalidArgumentException('Parameter "directory" must be writable. Input was: ' . $directory);
    }
    
    $this->directory = $directory;
  }

  /**
   * Computes the filename for a specified key.
   * @param string $key The key
   * @return string The filename
   */
  private function getFilename($key)
  {
    return $this->directory . DIRECTORY_SEPARATOR . sha1($key);
  }
  
  /**
   * Clears entries by deleting cache files.
   * @param int $ttl Time-to-live of a file in seconds (based on its modification time) that, if not reached, keeps it from being deleted by this method  
   */
  public function clear($ttl = 0)
  {
    foreach (scandir($this->directory) as $file)
    {
      $path = $this->directory . DIRECTORY_SEPARATOR . $file;
      if (is_file($path))
      {
        if (filemtime($path) + $ttl <= time())
        {
          unlink($path);
        }
      }
    }
  }

  /**
   * Returns the data stored under the specified key.
   * @param string $key The key under which the data is stored
   * @return mixed The data stored under the specified key.
   * @see \Visionline\Crm\WebApi\Cache::get()
   */
  public function get($key)
  {
    $file = $this->getFilename($key);

    if (!@file_exists($file))
    {
      return null;
    }

    if (!$fp = @fopen($file, 'rb'))
    {
      return null;
    }

    if (!@flock($fp, LOCK_SH))
    {
      throw new \Exception('Could not lock file: ' . $file);
    }
    
    $cache = @unserialize(stream_get_contents($fp));

    if ($cache == null)
    {
      throw new \Exception('Could not unserialize the contents of file: ' . $file);
    }

    if (!@flock($fp, LOCK_UN))
    {
      throw new \Exception('Could not unlock file: ' . $file);
    }
    @fclose($fp);

    return $cache;
  }

  /**
   * Stores the data under the specified key
   * @param string $key The key under which to store the data
   * @param mixed $data The data to store
   * @see \Visionline\Crm\WebApi\Cache::put()
   */
  public function put($key, $data)
  {
    $file = $this->getFilename($key);

    if (!$fp = @fopen($file, 'wb'))
    {
      throw new \Exception('Could not open file: ' . $file);
    }

    if (!@flock($fp, LOCK_EX))
    {
      throw new \Exception('Could not lock file: ' . $file);
    }
    
    fwrite($fp, serialize($data));
    
    if (!@flock($fp, LOCK_UN))
    {
      throw new \Exception('Could not unlock file: ' . $file);
    }
    
    @fclose($fp);
  }
}

?>