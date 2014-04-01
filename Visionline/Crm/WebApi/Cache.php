<?php
/**
 * Defines the interface Cache
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Specifies the interface for a cache.
 * Implementors have to make sure, that the cache doesn't get corrupted by concurrent calls to the get and put methods.
 * It is recommended to periodically invalidate the data in the cache, to ensure that data that is referenced by an entity gets refeshed.
 */
interface Cache
{
  /**
   * Returns the data stored under the specified key.
   * @param string $key The key under which the data is stored
   * @return mixed The data stored under the specified key.
   */
  public function get($key);
  
  /**
   * Stores the data under the specified key
   * @param string $key The key under which to store the data
   * @param mixed $data The data to store
   */
  public function put($key, $data);
}

?>