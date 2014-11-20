<?php
/**
 * Defines the class CacheEntry
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Internal class that defines an entry in the cache
 * @internal
 */
class CacheEntry
{
  /**
   * The entity type
   * @var string
   */
  public $type;
  
  /**
   * The entity id 
   * @var int
   */
  public $id;
  
  /**
   * The date of the last modification of the entity
   * @var int
   */
  public $lastModified;
  
  /**
   * The field values of the the entity
   * @var array
   */
  public $fields;
  
  /**
   * Create a cache entry
   * @param string $type The entity type
   * @param int $id The entity id
   * @param int $lastModified The date of the last modification of the entity
   * @param array $fields The field values of the the entity
   * @throws \InvalidArgumentException If invalid arguments were supplied
   */
  public function __construct($type, $id, $lastModified = null, $fields = array())
  {
    if (!isset($type))
    {
      throw new \InvalidArgumentException('Parameter "type" must be set');  
    }
    
    if (!isset($id) || !is_int($id))
    {
      throw new \InvalidArgumentException('Parameter "id" must be set and of type "int".');
    }
    
    $this->type = $type;
    $this->id = $id;
    $this->lastModified = $lastModified;
    $this->fields = $fields;
  }
  
  /**
   * Merges this cache entry with another cache entry and returns the result. This cache entry and the specified other cache entry are not modified.
   * @param CacheEntry $other The cache entry to be merged.
   * @throws \InvalidArgumentException If no other cache entry was supplied or its properties are not set correctly.
   * @return \Visionline\Crm\WebApi\CacheEntry The merged cache entry
   */
  public function merge(CacheEntry $other)
  {
    if (!isset($other))
    {
      throw new \InvalidArgumentException('Parameter "other" must be set.');
    }
    
    if (!isset($other->type))
    {
      throw new \InvalidArgumentException('Property "type" of parameter "other" must be set');  
    }
    
    if (!isset($other->id) || !is_int($other->id))
    {
      throw new \InvalidArgumentException('Property "id" of parameter "other" must be set and of type "int".');
    }
    
    if ($this->type != $other->type)
    {
      throw new \InvalidArgumentException('Property "type" of parameter "other" does not match this entry\'s type.');
    }
    
    if ($this->id != $other->id)
    {
      throw new \InvalidArgumentException('Property "id" of parameter "other" does not match this entry\'s id.');
    }

    $result = new CacheEntry($this->type, $this->id);
    $result->lastModified = max($this->lastModified, $other->lastModified);
    $result->fields = array_merge($this->fields, $other->fields);
    
    return $result;
  }
  
  /**
   * Returns the key under which this cache entry can be stored or retrieved.
   * @return string The key under which this cache entry can be stored or retrieved.
   */
  public function getKey()
  {
    return CacheEntry::computeKey($this->type, $this->id);
  }
  
  /**
   * Computes the key under which a cache entry can be stored or retrieved.
   * @param string $type The entity type
   * @param string $id the entity id
   * @return string The key under which a cache entry can be stored or retrieved.
   */
  public static function computeKey($type, $id)
  {
    return $type . '#' . $id;
  }
}

?>