<?php
/**
 * Defines the class RelatedQueryResult
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a result in the result set of a query that is related to another entity and specifies a role.
 */
class RelatedRoleQueryResult extends RelatedQueryResult
{
  /**
   * The role of the relation
   * @var string
   */
  public $role;
  
  
  /**
   * Create a query result
   * @param int $id The id of the entity
   * @param int $lastModified The date of the last modification of the entity.
   * @param int $relatedTo The id of the entity to which this result is related
   * @param string $role The role of the relation to the entity
   */
  public function _construct($id, $lastModified = null, $relatedTo = null, $role = null)
  {
    parent::__construct($id, $lastModified, $relatedTo);
    
    $this->role = $role;
  }
}