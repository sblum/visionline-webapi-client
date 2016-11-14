<?php
/**
 * Defines the class Junction
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a junction of filters that can be applied to a query.
 */
class Junction extends Filter
{
  /**
   * Junction type representing a logical AND
   * @var string
   */
  const TypeAnd = "And";

  /**
   * Junction type representing a logical OR
   * @var string
   */
  const TypeOr = "Or";
  
  /**
   * The type of the junction
   * @var string
   */
  public $type;

  /**
   * The set of filters linked with this junction
   * @var array of Filter
   * @see Filter
   */
  public $filters = array();
  
  /**
   * Creates a junction of the specified type
   * @param string $type
   * @see Junction::TypeAnd
   * @see Junction::TypeOr
   */
  public function __construct($type)
  {
    $this->type = $type;
  }
  
  /**
   * Adds a filter to this junction
   * @param Filter $filter The filter to add
   * @return Junction A reference to this junction
   */
  public function add($filter)
  {
    array_push($this->filters, $filter);
    return $this;
  }
}

?>