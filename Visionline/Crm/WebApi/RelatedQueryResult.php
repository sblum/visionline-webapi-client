<?php
/**
 * Defines the class RelatedQueryResult
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a result in the result set of a query that is related to another entity.
 */
class RelatedQueryResult extends QueryResult
{
  /**
   * The id of the entity to which this result is related
   * @var int
   */
  public $relatedTo;
  
  
  /**
   * Create a query result
   * @param int $id The id of the entity
   * @param int $lastModified The date of the last modification of the entity.
   * @param int The id of the entity to which this result is related
   */
  public function _construct($id, $lastModified = null, $relatedTo = null)
  {
    parent::__construct($id, $lastModified);
    
    $this->relatedto = $relatedTo;
  }
  
  /**
   * Initializes a query result after being constructed by SoapClient.
   */
  public function init()
  {
    if ($this->lastModified != null)
    {
      $this->lastModified = strtotime($this->lastModified);
    }
  }
}