<?php
/**
 * Defines the class Query
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a query which can be run against a CRM-VISIONLINE WebApi Webservice.
 */
class Query
{
  /**
   * The WebApi to use for this query
   * @var WebApi
   */
  private $webapi = NULL;

  /**
   * The entity type of this query
   * @var string
   */
  private $type = NULL;

  /**
   * The index of first result to be retrieved by this query
   * @var int
   */
  private $first = NULL;
  
  /**
   * The amount of elements returned by this query
   * @var int
   */
  private $max = NULL;
  
  /**
   * The set of filters used in this query
   * @var array of Filter
   */
  private $filters = array();
  
  /**
   * The set of orders used in this query
   * @var array of Order
   */
  private $orders = array();
  
  /**
   * Creates a new query
   * @param WebApi $webapi The instance of the WebApi PHP Client to use
   * @param string $type The entity type of this query
   */
  public function __construct(WebApi $webapi, $type)
  {
    $this->webapi = $webapi;
    $this->type = $type;
  }
  
  /**
   * Adds a filter to the query
   * @param Filter $filter The filter to add.
   * @return \Visionline\Crm\WebApi\Query A reference to this query.
   */
  public function add($filter)
  {
    array_push($this->filters, $filter);
    return $this;
  }
  
  /**
   * Adds an order to the query
   * @param Order $order The order to add to this query
   * @return \Visionline\Crm\WebApi\Query A reference to this query.
   */
  public function order($order)
  {
    return $this->addOrder($order);
  }
  
  /**
   * Adds an order to the query
   * @param Order $order The order to add to this query
   * @return \Visionline\Crm\WebApi\Query A reference to this query.
   */
  public function addOrder($order)
  {
    array_push($this->orders, $order);
    return $this;
  }
  
  /**
   * Specifies the index of first result to be retrieved by this query
   * @param int $first The index of first result to be retrieved by this query
   * @return \Visionline\Crm\WebApi\Query A reference to this query.
   */
  public function first($first)
  {
    $this->first = $first;
    return $this;
  }
  
  /**
   * Specifies the amount of elements returned by this query
   * @param int $max The amount of elements returned by this query
   * @return \Visionline\Crm\WebApi\Query A reference to this query.
   */
  public function max($max)
  {
    $this->max = $max;
    return $this;
  }

  /**
   * Runs this query and returns its results.
   * @return array of QueryResult
   * @throws \SoapFault if a remote error occurs.
   * @see QueryResult
   */
  public function result()
  {
    return $this->webapi->_Query($this->type, $this->filters, $this->orders, $this->first, $this->max);
  }
  
  /**
   * Runs this query and returns its result. If this query produces multiple results, only the first result is returned.
   * @return QueryResult
   * @throws \SoapFault if a remote error occurs.
   * @see QueryResult
   */
  public function uniqueResult()
  {
    $result = $this->result();
    if (is_array($result) && count($result)>0)
    {
      return $result[0];
    }
    else
    {
      return null;
    }
  }
  
  /**
   * Runs this query by calling result() and returns the specified fields of the matching entities by calling WebApi::get for the query results.
   * @param array $fields An array of strings specifying which fields should be returned. The strings in the array have to be UTF-8 encoded.
   * @param bool $return_ids Whether to return ids instead of values for relations
   * @return array A two-dimensional array that contains the results. The keys of the first level are
   * the ids of the entities. The keys of the second level are the field identifiers and values are
   * the corresponding field values. The field values are UTF-8 encoded strings.
   * @throws \SoapFault if a remote error occurs.
   * @see WebApi::get
   */
  public function fields(array $fields, $return_ids = false)
  {
  	$queryResults = $this->result();
    $fieldResults = $this->webapi->get($this->type, $queryResults, $fields, $return_ids);
  	
  	uksort($fieldResults, function($id1, $id2) use ($queryResults) {
  		foreach ($queryResults as $queryResult)
  		{
  			if ($queryResult->id == $id1)
  			{
  				return -1;
  			}
  			if ($queryResult->id == $id2)
  			{
  				return 1;
  			}
  		}
  	});
  	
  	return $fieldResults;
  }
}

?>