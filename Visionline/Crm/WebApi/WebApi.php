<?php
/**
 * Defines the class WebApi
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * This is the core class for the CRM-VISIONLINE WebApi PHP Client
 */
class WebApi
{
  const FieldKeyWebsiteDocumentsPrefix = '__webapi_websiteDocuments';
  const FieldKeyResponsibleContacts = '__webapi_responsibleContacts';
  const FieldKeyContactImages = '__webapi_contactImages';
  
  /**
   * Gets or sets the language in which language-specific strings will be
   * retrieved from the CRM-VISIONLINE system
   * @var string
   */
  public $language = Language::DE;

  /**
   * The endpoint used for webservice calls, e.g. https://app2.visionline.at/WebApi/WebApi.asmx?WSDL
   * @var string
   */
  private $endpoint;

  /**
   * The url used to get files, e.g. https://app2.visionline.at/WebApi/GetFile.ashx
   * This field is implicitly computed from the endpoint.
   * @var string
   */
  private $getFileUrl;
  
  /**
   * The connection to the CRM-VISIONLINE system 
   * @var Connection
   */
  public $connection;

  /**
   * Specifies whether debugging is enabled or not.
   * @var bool
   */
  private $debug = false;
  
  /**
   * Holds the time when the first debug message was provided. This is used for some light profiling.
   * @var int
   */
  private $debugStartTime;
  
  /**
   * Holds the collected debug messages
   * @var array
   */
  private $debugMessages = array();

  /**
   * The connection timeout used for webservice calls
   * @var int
   */
  private $connection_timeout = 10;
  
  /**
   * The buffer size for file operations (default is 32K)
   * @var int
   */
  private $bufferSize = 32768;
  
  /**
   * The cache
   * @var Cache
   */
  public $cache;

  /**
   * Specifies, whether to use cache for queryWebsiteDocuments 
   */
  private $cacheWebsiteDocuments = false;

  /**
   * @var bool|resource The stream context to use for soap operations
   */
  private $stream_context = false;

  /**
   * Creates a new CRM-VISIONLINE WebApi PHP Client
   * @param string $endpoint The endpoint (URL) of the CRM-VISIONLINE WebApi Webservice, e.g. https://app2.visionline.at/WebApi/WebApi.aspx?WSDL 
   * @param Connection $connection The connection information to the CRM-VISIONLINE system
   * @param array $options Set the specified options.
   * @throws \InvalidArgumentException If an unknown option was provided in $options
   */
  public function __construct($endpoint, Connection $connection, array $options)
  {
    $this->endpoint = $endpoint;
    $this->connection = $connection;

    foreach ($options as $key => $value)
    {
      $this->setOption($key, $value);
    }
    
    if (!isset($this->getFileUrl))
    {
      $pos = strrpos($this->endpoint, '/');
      if ($pos !== false)
      {
        $this->getFileUrl = substr($this->endpoint, 0, $pos) . '/GetFile.ashx';
      }
    }

    $this->client = new \SoapClient($this->endpoint, array(
        'trace' => $this->debug,
        'exceptions' => true,
        'connection_timeout'=> $this->connection_timeout,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        'soap_version' => SOAP_1_1,
        'encoding' => 'utf-8',
        'stream_context' => $this->stream_context,
        'classmap' => array(
            'Expression' => __NAMESPACE__ . '\Expression',
            'Junction' => __NAMESPACE__ . '\Junction',
            'Order' => __NAMESPACE__ . '\Order',
            'Connection' => __NAMESPACE__ . '\Connection',
            'QueryResult' => __NAMESPACE__ . '\QueryResult',
            'EnumFieldsResult' => __NAMESPACE__ . '\EnumFieldsResult',
            'RelatedQueryResult' => __NAMESPACE__ . '\RelatedQueryResult',
            'RelatedRoleQueryResult' => __NAMESPACE__ . '\RelatedRoleQueryResult',
            'Interest' => __NAMESPACE__ . '\Interest'
        ),
    ));
  }

  /**
   * Returns the debug messages that have been collected since
   * the last call to this method.
   * @return array The debug messages.
   */
  public function getDebugMessages()
  {
    $messages = $this->debugMessages;
    $this->debugMessages = array();
    return $messages;
  }
  
  /**
   * Sets the specified option to the specified value.
   * @param string $option The option's name
   * @param mixed $value The option's value
   * @throws \InvalidArgumentException If an invalid option name was supplied
   */
  private function setOption($option, $value)
  {
    if (property_exists($this, $option))
    {
      $this->$option = $value;
    }
    else
    {
      throw new \InvalidArgumentException("Unknown option: $option");
    }
  }
  
  /**
   * Creates a query
   * @param string $type The entity type
   * @return \Visionline\Crm\WebApi\Query The query
   * @see Query
   */
  public function createQuery($type)
  {
    return new Query($this, $type);
  }

  /**
   * If debugging is enabled, collects a debug message
   * @param mixed $message
   */
  public function debug($message)
  {
    if (isset($this->debug) && $this->debug)
    {
      if (!isset($this->debugStartTime))
      {
        $this->debugStartTime = microtime(true);
      }
      
      $message = '[WebApi] [+' . round(1000 * (microtime(true) - $this->debugStartTime)) . 'ms] ';
      foreach (func_get_args() as $arg)
      {
        if (!is_string($arg))
        {
          $arg = var_export($arg, true);
        }
        $message .= $arg;
        $message .= ' ';
      }
      
      $this->debugMessages[] = $message;
    }
  }
  
  /**
   * Calls the webservice method Query with the specified arguments
   * @param string $type the entity type
   * @param array $filters the filters to use for this query
   * @param array $orders the orders to use for this query
   * @param int $first index of the beginning of the result set
   * @param int $max maximum length of the result set
   * @return array an array of objects with the properties 'id' and 'lastModified'.
   * @throws \Exception if a remote error occurs
   */
  public function _Query($type, $filters, $orders, $first, $max)
  {
    try
    {
      $result = $this->client->Query($this->connection, $type, $filters, $orders, $first, $max);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
      
      $this->debug('Query - Result is', $result);
      $this->debug('Query - Request was', $this->client->__getLastRequest());
      $this->debug('Query - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('Query - Request was', $this->client->__getLastRequest());
      $this->debug('Query - Response was', $this->client->__getLastResponse());

      throw $e;
    }
    return $result;
  }
  
  /**
   * Calls the webservice method QueryResponsibleContacts with the specified arguments
   * @param string $type the entity type
   * @param int[] $ids the entity id
   * @return QueryResult an array of objects with the properties 'id' and 'lastModified'.
   * @throws \Exception if a remote error occurs
   */
  public function _QueryResponsibleContacts($type, $ids)
  {
    try
    {
      $result = $this->client->QueryMultipleResponsibleContacts($this->connection, $type, $ids);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
      
      $this->debug('QueryResponsibleContacts - Result is', $result);
      $this->debug('QueryResponsibleContacts - Request was', $this->client->__getLastRequest());
      $this->debug('QueryResponsibleContacts - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryResponsibleContacts - Request was', $this->client->__getLastRequest());
      $this->debug('QueryResponsibleContacts - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }

  /**
   * Calls the webservice method QueryContactImages with the specified arguments
   * @param array $ids the contact ids
   * @return array of RelatedQueryResult describing the images of the specified contacts
   * @throws \Exception if a remote error occurs
   */
  public function _QueryContactImages($ids)
  {
    try
    {
      $result = $this->client->QueryContactImages($this->connection, $ids);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
  
      $this->debug('QueryContactImages - Result is', $result);
      $this->debug('QueryContactImages - Request was', $this->client->__getLastRequest());
      $this->debug('QueryContactImages - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryContactImages - Request was', $this->client->__getLastRequest());
      $this->debug('QueryContactImages - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }

  /**
   * Calls the webservice method QueryWebsiteDocuments with the specified arguments
   * @param string $type the entity type
   * @param array $ids the entity ids
   * @param int $max specifies the maximum count of website documents returned per entity
   * @param array $filterByExtension filters the returned documents by the specified file extensions (array of strings)
   * @param array $filterByDokumentart filters the returned documents by the specified document types (array of strings)
   * @return array The query results
   * @throws \Exception if a remote error occurs
   */
  public function _QueryWebsiteDocuments($type, $ids, $max = null, $filterByExtension = null, $filterByDokumentart = null)
  {
    try
    {
      $result = $this->client->QueryMultipleWebsiteDocuments($this->connection, $type, $ids, $max, $filterByExtension, $filterByDokumentart);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
        
      $this->debug('QueryWebsiteDocuments - Result is', $result);
      $this->debug('QueryWebsiteDocuments - Request was', $this->client->__getLastRequest());
      $this->debug('QueryWebsiteDocuments - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryWebsiteDocuments - Request was', $this->client->__getLastRequest());
      $this->debug('QueryWebsiteDocuments - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }

  /**
   * Calls the webservice method QueryKontakte with the specified arguments
   * @param string $type the entity type (EntityType::Objekt or EntityType::Projekt)
   * @param array $ids the entity ids
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole filters the returned results by the specified roles (array of strings)
   * @return array The query results
   * @throws \Exception if a remote error occurs
   */
  public function _QueryKontakte($type, $ids, $max = null, $filterByRole = null)
  {
    try
    {
      $result = $this->client->QueryKontakte($this->connection, $type, $ids, $max, $filterByRole);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
  
      $this->debug('QueryKontakte - Result is', $result);
      $this->debug('QueryKontakte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryKontakte - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryKontakte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryKontakte - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }

  /**
   * Calls the webservice method QueryObjekte with the specified arguments
   * @param string $type the entity type (EntityType::Kontakt)
   * @param array $ids the entity ids
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole filters the returned results by the specified roles (array of strings)
   * @return array of \Visionline\Crm\WebApi\RelatedRoleQueryResult describing the real estates
   * @throws \Exception if a remote error occurs
   */
  public function _QueryObjekte($type, $ids, $max = null, $filterByRole = null)
  {
    try
    {
      $result = $this->client->QueryObjekte($this->connection, $type, $ids, $max, $filterByRole);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
  
      $this->debug('QueryObjekte - Result is', $result);
      $this->debug('QueryObjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryObjekte - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryObjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryObjekte - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }

  /**
   * Calls the webservice method QueryProjekte with the specified arguments
   * @param string $type the entity type (EntityType::Kontakt)
   * @param array $ids the entity ids
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole filters the returned results by the specified roles (array of strings)
   * @return array of \Visionline\Crm\WebApi\RelatedRoleQueryResult describing the projects
   * @throws \Exception if a remote error occurs
   */
  public function _QueryProjekte($type, $ids, $max = null, $filterByRole = null)
  {
    try
    {
      $result = $this->client->QueryProjekte($this->connection, $type, $ids, $max, $filterByRole);
      foreach ($result as $queryResult)
      {
        $queryResult->init();
      }
  
      $this->debug('QueryProjekte - Result is', $result);
      $this->debug('QueryProjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryProjekte - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryProjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryProjekte - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }
  
  /**
   * Calls the webservice method GetInterests with the specified arguments
   * @param array $kontaktIds The IDs of the contacts
   * @param array $filterByStatus filter the returned results by the specified stati
   * @return Interest[] describing the interests
   * @throws \Exception if a remote error occurs
   */
  public function _GetInterests($kontaktIds, $filterByStatus = null)
  {
    try
    {
      $result = $this->client->GetInterests($this->connection, $kontaktIds, $filterByStatus);
  
      $this->debug('QueryProjekte - Result is', $result);
      $this->debug('QueryProjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryProjekte - Response was', $this->client->__getLastResponse());
    }
    catch (\Exception $e)
    {
      $this->debug('QueryProjekte - Request was', $this->client->__getLastRequest());
      $this->debug('QueryProjekte - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
    return $result;
  }
  
  /**
   * Calls the webservice method Get with the specified arguments
   * @param string $type the entity type
   * @param array $ids the ids of the requested entities (array of int)
   * @param array $fields the requested fields (array of string)
   * @param boolean $return_ids Whether to return ids instead of values for relations
   * @throws \SoapFault if a remote error occurs
   * @return array A two-dimensional array that contains the results. The keys of the first level are
   * the ids of the entities. The keys of the second level are the field identifiers and values are
   * the corresponding field values. The field values are UTF-8 encoded strings.
   */
  public function _Get($type, array $ids, array $fields, $return_ids = false)
  {
    try
    {
      $getResults = $this->client->Get($this->connection, $type, $ids, $fields, $this->language, $return_ids);

      $this->debug('Get - Result is', $getResults);
      $this->debug('Get - Request was', $this->client->__getLastRequest());
      $this->debug('Get - Response was', $this->client->__getLastResponse());

      // transform result for easier access:
      $result = array();
      foreach ($getResults as $getResult)
      {
        $fields = array();
        foreach ($getResult->fields as $fieldResult)
        {
          $fields[$fieldResult->field] = $fieldResult->value;
        }
        $result[$getResult->id] = $fields;
      }
    }
    catch (\SoapFault $e)
    {
      $this->debug('Get - Request was', $this->client->__getLastRequest());
      $this->debug('Get - Response was', $this->client->__getLastResponse());
       
      throw $e;
    }
    return $result;
  }
  
  /**
   * Calls the webservice method Create with the specified arguments
   * @param string $type the entity type
   * @param array $fieldValues the field values to assign to the created entity (associative array of string)
   * @throws \SoapFault if a remote error occurs
   * @return int The id of the created entity
   */
  public function _Create($type, array $fieldValues)
  {
    try
    {
      $fieldValuesArray = array();
      foreach ($fieldValues as $field => $value) {
        $fieldValuesArray[] = array(
            'field' => $field,
            'value' => $value
        );
      }
      
      $id = $this->client->Create($this->connection, $type, $fieldValuesArray);
  
      $this->debug('Create - Result is', $id);
      $this->debug('Create - Request was', $this->client->__getLastRequest());
      $this->debug('Create - Response was', $this->client->__getLastResponse());
    }
    catch (\SoapFault $e)
    {
      $this->debug('Create - Request was', $this->client->__getLastRequest());
      $this->debug('Create - Response was', $this->client->__getLastResponse());
      
      throw $e;
    }
    return $id;
  }
  
  /**
   * Calls the webservice method Update with the specified arguments
   * @param string $type the entity type
   * @param int $id the id of the entity to be updated
   * @param array $fieldValues the field values to set (associative array of string)
   * @throws \SoapFault if a remote error occurs
   */
  public function _Update($type, $id, array $fieldValues)
  {
    try
    {
      $fieldValuesArray = array();
      foreach ($fieldValues as $field => $value) {
        $fieldValuesArray[] = array(
            'field' => $field,
            'value' => $value
        );
      }
  
      $this->client->Update($this->connection, $type, $id, $fieldValuesArray);
  
      $this->debug('Update - Request was', $this->client->__getLastRequest());
      $this->debug('Update - Response was', $this->client->__getLastResponse());
    }
    catch (\SoapFault $e)
    {
      $this->debug('Update - Request was', $this->client->__getLastRequest());
      $this->debug('Update - Response was', $this->client->__getLastResponse());
  
      throw $e;
    }
  }

  /**
   * Calls the webservice method EnumFields with the specified arguments
   * @param string $type the entity type
   * @param string $category the field category (optional)
   * @throws \SoapFault if a remote error occurs
   * @return array of EnumFieldsResult
   * @see EnumFieldsResult
   */
  public function _EnumFields($type, $category = NULL)
  {
    try
    {
      $this->debug('EnumFields - Arguments are ', func_get_args());
  
      $fields = $this->client->EnumFields($this->connection, $type, $category);
  
      $this->debug('EnumFields - Result is', $fields);
      $this->debug('EnumFields - Request was', $this->client->__getLastRequest());
      $this->debug('EnumFields - Response was', $this->client->__getLastResponse());
  
      return $fields;
    }
    catch (\SoapFault $e)
    {
      $this->debug('EnumFields - Request was', $this->client->__getLastRequest());
      $this->debug('EnumFields - Response was', $this->client->__getLastResponse());
       
      throw $e;
    }
    return $fields;
  }
  
  /**
   * Calls the webservice method CreateEnquiry with the specified arguments
   * @param Enquiry $enquiry the enquiry
   * @throws \SoapFault if a remote error occurs
   * @return CreateEnquiryResult
   * @see CreateEnquiryResult
   */
  public function _CreateEnquiry($enquiry)
  {
    try
    {
      $this->debug('CreateEnquiry - Arguments are ', func_get_args());
  
      $result = $this->client->CreateEnquiry($this->connection, $enquiry);
  
      $this->debug('CreateEnquiry - Result is', $result);
      $this->debug('CreateEnquiry - Request was', $this->client->__getLastRequest());
      $this->debug('CreateEnquiry - Response was', $this->client->__getLastResponse());
  
      return $result;
    }
    catch (\SoapFault $e)
    {
      $this->debug('CreateEnquiry - Request was', $this->client->__getLastRequest());
      $this->debug('CreateEnquiry - Response was', $this->client->__getLastResponse());
       
      throw $e;
    }
  }
  
  /**
   * Enumerates the fields in CRM-VISIONLINE system for the specified type
   * and optionally in the specified category.
   * @param string $type The entity type for which the fields should be enumerated
   * @param string $category The category of fields to which the result set should be limited. This string has to be UTF-8 encoded.
   * @return array of EnumFieldsResult
   * @see EnumFieldsResult
   */
  public function enumFields($type, $category = NULL)
  {
    return $this->_EnumFields($type, $category);
  }
  
  /**
  * Create an enquiry in the CRM-VISIONLINE system
  * @param Enquiry $enquiry the enquiry
  * @return CreateEnquiryResult
  * @see Enquiry
  */
  public function createEnquiry($enquiry)
  {
    return $this->_CreateEnquiry($enquiry);
  }
  
  /**
   * Queries the responsible contacts of the specified entities.
   * @param string $type The entity type
   * @param int|QueryResult|array $which Specifies the entities for which the responsible contacts should be queried
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedQueryResult describing the responsible contacts
   * @see EntityType
   */
  public function queryResponsibleContacts($type, $which)
  {
    $results = array();
    
    if (is_int($which))
    {
      return $this->queryResponsibleContacts($type, array($which));
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryResponsibleContacts($type, array($which));
    }
    else if (is_array($which))
    {
      // Initialize $results
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $results[$entry] = new QueryResult($entry);
        }
        else if ($entry instanceof QueryResult)
        {
          $results[$entry->id] = $entry;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
    
    // If cache is available, read cache entries where possible
    $getIds = $this->cacheRead($type, $results, array(WebApi::FieldKeyResponsibleContacts));
    
    // Transform cached results
    foreach ($results as $id => $value)
    {
      if (is_array($value))
      {
        $results[$id] = $value[WebApi::FieldKeyResponsibleContacts];
    
        // We can't guarantee the lastModified date when read from cache
        foreach ($results[$id] as $contact)
        {
          $contact->lastModified = null;
        }
      }
    }
    
    // Retrieve results not found in cache
    if (count($getIds) > 0)
    {
      $this->debug('queryResponsibleContacts: Going to call _QueryResponsibleContacts for ids = ', $getIds);
    
      // Call webservice
      $queryResults = $this->_QueryResponsibleContacts($type, $getIds);
    
      // Split results per entity
      $queryResultsPerId = array();
      foreach ($queryResults as $queryResult)
      {
        if (!isset($queryResultsPerId[$queryResult->relatedTo]))
        {
          $queryResultsPerId[$queryResult->relatedTo] = array();
        }
    
        array_push($queryResultsPerId[$queryResult->relatedTo], $queryResult);
      }
    
      // Write to cache and to $results
      foreach ($queryResultsPerId as $id => $queryResults)
      {
        // If cache is available, write cache entry
        $this->cacheWrite($type, $id, $results[$id]->lastModified, array(WebApi::FieldKeyResponsibleContacts => $queryResults));
    
        $results[$id] = $queryResults;
      }
    }
    
    // Transform results and return it
    $flatResults = array();
    foreach ($results as $result)
    {
      // $result is an array, if we got a result
      if (is_array($result))
      {
        $flatResults = array_merge($flatResults, $result);
      }
    }
    
    $this->debug("queryResponsibleContacts: results = ", $flatResults);
    
    return $flatResults;
  }
  
  /**
   * Queries the website documents of entities. Currently only the entity types Objekt and Projekt are supported.
   * @param string $type The entity type (EntityType::Objekt or EntityType::Projekt)
   * @param int|QueryResult|array $which Specifies the entities for which the website documents should be queried
   * @param int $max specifies the maximum count of website documents returned per entity
   * @param array $filterByExtension filters the returned documents by the specified file extensions (array of strings)
   * @param array $filterByDokumentart filters the returned documents by the specified document types (array of strings)
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedQueryResult describing the website documents
   * @see EntityType
   */
  public function queryWebsiteDocuments($type, $which, $max = null, $filterByExtension = null, $filterByDokumentart = null)
  {
    $results = array();
    
    if (is_int($which))
    {
      return $this->queryWebsiteDocuments($type, array($which), $max, $filterByExtension, $filterByDokumentart);
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryWebsiteDocuments($type, array($which), $max, $filterByExtension, $filterByDokumentart);
    }
    else if (is_array($which))
    {
      // Initialize $results
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $results[$entry] = new QueryResult($entry);
        }
        else if ($entry instanceof QueryResult)
        {
          $results[$entry->id] = $entry;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
    
    if (!$this->cacheWebsiteDocuments)
    {
      return $this->_QueryWebsiteDocuments($type, array_keys($results), $max, $filterByExtension, $filterByDokumentart);
    }
    else
    {
      // Build identifier for our synthetic field used for caching
      $functionArgs = func_get_args();
      $queryArgs = array_splice($functionArgs, 2);
      
      $this->debug('queryWebsiteDocuments: Building cache key from ', $queryArgs);
      
      $syntheticFieldIdentifier = WebApi::FieldKeyWebsiteDocumentsPrefix . sha1(serialize($queryArgs));
      
      // If cache is available, read cache entries where possible
      $getIds = $this->cacheRead($type, $results, array($syntheticFieldIdentifier));
      
      // Transform cached results
      foreach ($results as $id => $value)
      {
        if (is_array($value))
        {
          $results[$id] = $value[$syntheticFieldIdentifier];
          
          // We can't guarantee the lastModified date when read from cache
          foreach ($results[$id] as $document)
          {
            $document->lastModified = null;
          } 
        }
      }
      
      // Retrieve results not found in cache
      if (count($getIds) > 0)
      {
        $this->debug('queryWebsiteDocuments: Going to call _QueryWebsiteDocuments for ids = ', $getIds);
      
        // Call webservice
        $queryResults = $this->_QueryWebsiteDocuments($type, $getIds, $max, $filterByExtension, $filterByDokumentart);
      
        // Split results per entity
        $queryResultsPerId = array();
        foreach ($queryResults as $queryResult)
        {
          if (!isset($queryResultsPerId[$queryResult->relatedTo]))
          {
            $queryResultsPerId[$queryResult->relatedTo] = array();
          }
          
          array_push($queryResultsPerId[$queryResult->relatedTo], $queryResult);
        }
        
        // Write to cache and to $results
        foreach ($queryResultsPerId as $id => $queryResults)
        {
          // If cache is available, write cache entry
          $this->cacheWrite($type, $id, $results[$id]->lastModified, array($syntheticFieldIdentifier => $queryResults));
          
          $results[$id] = $queryResults;
        }
      }
  
      
      // Transform results and return it
      $flatResults = array();
      foreach ($results as $result)
      {
        // $result is an array, if we got a result
        if (is_array($result))
        {
          $flatResults = array_merge($flatResults, $result);
        }
      }
      
      $this->debug("queryWebsiteDocuments: results = ", $flatResults);
      
      return $flatResults;
    }
  }
  
  /**
   * Queries the contacts of real estates or projects.
   * @param string $type The entity type (EntityType::Objekt or EntityType::Projekt)
   * @param int|QueryResult|array $which Specifies the entities for which the contacts should be queried
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole Optionally filters the returned documents by the specified file extensions (array of strings)
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedRoleQueryResult describing the contacts
   * @see EntityType
   */
  public function queryKontakte($type, $which, $max = null, $filterByRole = null)
  {
    if (is_int($which))
    {
      return $this->queryKontakte($type, array($which), $max, $filterByRole);
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryKontakte($type, array($which), $max, $filterByRole);
    }
    else if (is_array($which))
    {
      $ids = array();
  
      // Initialize $ids
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $ids[] = $entry;
        }
        else if ($entry instanceof QueryResult)
        {
          $ids[] = $entry->id;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
      
      // Call webservice method
      return $this->_QueryKontakte($type, $ids, $max, $filterByRole);
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
  }

  /**
   * Queries the real estates of contacts.
   * @param string $type The entity type (EntityType::Kontakt)
   * @param int|QueryResult|array $which Specifies the contacts for which the real estates should be queried
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole Optionally filters the returned documents by the specified file extensions (array of strings)
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedRoleQueryResult describing the real estates
   * @see EntityType
   */
  public function queryObjekte($type, $which, $max = null, $filterByRole = null)
  {
    if (is_int($which))
    {
      return $this->queryObjekte($type, array($which), $max, $filterByRole);
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryObjekte($type, array($which), $max, $filterByRole);
    }
    else if (is_array($which))
    {
      $ids = array();
  
      // Initialize $ids
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $ids[] = $entry;
        }
        else if ($entry instanceof QueryResult)
        {
          $ids[] = $entry->id;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
  
      // Call webservice method
      return $this->_QueryObjekte($type, $ids, $max, $filterByRole);
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
  }
  
  /**
   * Queries the projects of contacts.
   * @param string $type The entity type (EntityType::Kontakt)
   * @param int|QueryResult|array $which Specifies the contacts for which the projects should be queried
   * @param int $max specifies the maximum number of results
   * @param array $filterByRole Optionally filters the returned documents by the specified file extensions (array of strings)
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedRoleQueryResult describing the projects
   * @see EntityType
   */
  public function queryProjekte($type, $which, $max = null, $filterByRole = null)
  {
    if (is_int($which))
    {
      return $this->queryProjekte($type, array($which), $max, $filterByRole);
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryProjekte($type, array($which), $max, $filterByRole);
    }
    else if (is_array($which))
    {
      $ids = array();
  
      // Initialize $ids
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $ids[] = $entry;
        }
        else if ($entry instanceof QueryResult)
        {
          $ids[] = $entry->id;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
  
      // Call webservice method
      return $this->_QueryProjekte($type, $ids, $max, $filterByRole);
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
  }
  
  /**
   * Queries the images of the specified contacts
   * @param int|QueryResult|array $which Specifies the contacts for which the images should be queried
   * @throws \InvalidArgumentException if $which is not of type int, QueryResult, array of int or array of QueryResult
   * @return array of \Visionline\Crm\WebApi\RelatedQueryResult describing the website documents
   */
  public function queryContactImages($which)
  {
    $results = array();
  
    if (is_int($which))
    {
      return $this->queryContactImages(array($which));
    }
    else if ($which instanceof QueryResult)
    {
      return $this->queryContactImages(array($which));
    }
    else if (is_array($which))
    {
      // Initialize $results
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $results[$entry] = new QueryResult($entry);
        }
        else if ($entry instanceof QueryResult)
        {
          $results[$entry->id] = $entry;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
  
    // If cache is available, read cache entries where possible
    $getIds = $this->cacheRead(EntityType::Kontakt, $results, array(WebApi::FieldKeyContactImages));
  
    // Transform cached results
    foreach ($results as $id => $value)
    {
      if (is_array($value))
      {
        $results[$id] = $value[WebApi::FieldKeyContactImages];
  
        // We can't guarantee the lastModified date when read from cache
        foreach ($results[$id] as $document)
        {
          $document->lastModified = null;
        }
      }
    }
  
    // Retrieve results not found in cache
    if (count($getIds) > 0)
    {
      $this->debug('queryContactImages: Going to call _QueryContactImages for ids = ', $getIds);
  
      // Call webservice
      $queryResults = $this->_QueryContactImages($getIds);
  
      // Split results per entity
      $queryResultsPerId = array();
      foreach ($queryResults as $queryResult)
      {
        if (!isset($queryResultsPerId[$queryResult->relatedTo]))
        {
          $queryResultsPerId[$queryResult->relatedTo] = array();
        }
  
        array_push($queryResultsPerId[$queryResult->relatedTo], $queryResult);
      }
  
      // Write to cache and to $results
      foreach ($queryResultsPerId as $id => $queryResults)
      {
        // If cache is available, write cache entry
        $this->cacheWrite(EntityType::Kontakt, $id, $results[$id]->lastModified, array(WebApi::FieldKeyContactImages => $queryResults));
  
        $results[$id] = $queryResults;
      }
    }
  
  
    // Transform results and return it
    $flatResults = array();
    foreach ($results as $result)
    {
      // $result is an array, if we got a result
      if (is_array($result))
      {
        $flatResults = array_merge($flatResults, $result);
      }
    }
  
    $this->debug("queryContactImages: results = ", $flatResults);
  
    return $flatResults;
  }
  
  /**
   * Creates an entity of the specified type and sets its fields to the specified values.
   * @param string $type The type of the entity to create
   * @param array $values The values to which the entities fields should be set (associative array where the key is the field identifier and the value the field´s value)
   * @return int The id of the created entity
   */
  public function create($type, array $values) {
    return $this->_Create($type, $values);
  }

  /**
   * Updates the entity of the specified type with the specified id and sets it´s fields to the specified values.
   * @param string $type The type of the entity to update
   * @param int $id The id of the entity to update
   * @param array $values The values to which the entity´s fields should be set (associative array where the key is the field identifier and the value the field´s value)
   */
  public function update($type, $id, array $values) {
    $this->_Update($type, $id, $values);
  }
  
  /**
   * Gets the values of the fields for the specified entities.
   * @param string $type The entity type
   * @param array|int $which Specifies for which entities the fields should be returned. This can either be the id of an entity (int),
   * multiple ids of entities (array of int), a query result describing an entity (QueryResult) or multiple query results describing entities
   * (array of QueryResult)
   * @param array $fields The requested fields (array of string). The strings in this array have to be UTF-8 encoded.
   * @param array $idFields The requested fields as ids (array of string). The strings in this array have to be UTF-8 encoded.
   * @return array A two-dimensional array that contains the results. The keys of the first level are
   * the ids of the entities. The keys of the second level are the field identifiers and values are
   * the corresponding field values.
   * @see EntityType
   * @see QueryResult
   */
  public function get($type, array $which, array $fields, array $idFields = array())
  {
    $results = array();
    
    if (is_int($which))
    {
      return $this->get($type, array($which), $fields, $idFields);
    }
    else if ($which instanceof QueryResult)
    {
      return $this->get($type, array($which), $fields, $idFields);
    }
    else if (is_array($which))
    {
      // Initialize $results
      foreach ($which as $entry)
      {
        if (is_int($entry))
        {
          $results[$entry] = new QueryResult($entry);
        }
        else if ($entry instanceof QueryResult)
        {
          $results[$entry->id] = $entry;          
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "which". Expected (array of) int or QueryResult, got ' . gettype($which));
    }
      
    // If cache is available, read cache entries where possible
    $getIds = $this->cacheRead($type, $results, $fields, $idFields);
    
    if (count($getIds) > 0)
    {
      $this->debug('get: Going to call _Get for ids = ', $getIds);
      
      // Call webservice
      $getResults = $this->_Get($type, $getIds, $fields, $idFields);

      foreach ($getResults as $id => $fields)
      {
        // If cache is available, write cache entries
        $this->cacheWrite($type, $id, $results[$id]->lastModified, $fields, $idFields);
        
        $results[$id] = $fields;
      }
    }
    
    return $results;
  }
  
  /**
   * Writes a cache entry.
   * @param string $type The entity type
   * @param int $id The entity id
   * @param int $lastModified The entitys last modification date
   * @param array $fields The entitys fields
   * @param array $idFields The entity`s fields with ids instead of names
   */
  private function cacheWrite($type, $id, $lastModified, $fields, $idFields = array())
  {
    // If cache is available, store retrieved data
    if (isset($this->cache))
    {
      // compute key
      $key = CacheEntry::computeKey($type, $id, $this->language);

      // Create cache entry
      $cacheEntry = new CacheEntry($type, $id, $lastModified, $fields, $idFields);
    
      // If an up-to-date cache entry already exists, merge it with the new one
      $oldCacheEntry = $this->cache->get($key);
      if ($oldCacheEntry != null && $oldCacheEntry->lastModified >= $cacheEntry->lastModified)
      {
        $cacheEntry = $cacheEntry->merge($oldCacheEntry);
      }
    
      // Put entry into cache
      $this->cache->put($key, $cacheEntry);
    
      $this->debug('get: Stored cache entry for key = ', $key);
    }
  }
  
  /**
   * Reads a cache entry.
   * @param string $type The entity type
   * @param array $results Results found in the cache are set to this array.
   * @param array $fields The entitys fields
   * @param boolean $return_ids Whether the cache entry contains IDs instead of values for relation fields
   * @return array The ids of the entities that could not be answered from the cache. 
   */
  private function cacheRead($type, &$results, $fields, $return_ids = false)
  {
    if (isset($this->cache))
    {
      foreach ($results as $result)
      {
        $key = CacheEntry::computeKey($type, $result->id, $this->language, $return_ids);
        $cacheEntry = $this->cache->get($key);
        if ($cacheEntry != null)
        {
          $this->debug('get: Found cache entry for key = ', $key);
    
          // If the existant cache entry is outdated, we can't use $cacheEntry
          if ($result->lastModified == null || $cacheEntry->lastModified < $result->lastModified)
          {
            $this->debug('get: - Cache entry not up to date. cacheEntry->lastModified = ', $cacheEntry->lastModified,
                'result->lastModified = ' . $result->lastModified);
            continue;
          }
    
          // If not all $fields are contained $existantCacheEntry->fields, we can't use $cacheEntry
          if (count(array_diff($fields, array_keys($cacheEntry->fields))) > 0)
          {
            $this->debug('get: - Cache entry does not contain requested fields. cacheEntry->fields = ', $cacheEntry->fields,
                'fields = ', $fields);
            continue;
          }
          
          // Remote fields from $cacheEntry->fields that have not been requested
          $cacheEntry->fields = array_intersect_key($cacheEntry->fields, array_fill_keys($fields, 0));
    
          // Use data from $cacheEntry
          $results[$result->id] = $cacheEntry->fields;
        }
        else
        {
          $this->debug('get: No cache entry found for key = ', $key);
        }
      }
    }
    else
    {
      $this->debug('get: No cache available.');
    }
    
    $getIds = array();
    foreach ($results as $result)
    {
      if ($result instanceof QueryResult)
      {
        array_push($getIds, $result->id);
      }
    }
    
    return $getIds;
  }
  
  /**
   * Returns the content(s) of the specified document(s) and optionally applies resizing (only works for images).
   * This method should not be called for large files from within an enduser-request, because it blocks until the whole file has been retrieved from the CRM-VISIONLINE system.
   * @param array|int $documents The document(s) for which the content should be returned. This can either be the id of the document (int),
   * multiple document ids (array of int), a query result describing a document (QueryResult) or multiple query results describing
   * multiple documents (array of QueryResult).
   * @param int $width The width to which the image should be resized (optional).
   * @param int $height The height to which the image should be resized (optional).
   * @param string $resizeMode Specifies how the image should be resized (optional).
   * @return string The file contents
   */
  public function getFile($documents, $width = null, $height = null, $resizeMode = null)
  {
    $op = new FileGetOperation($this, $this->getFileUrl, $this->connection, $this->bufferSize);
    return $op->exec($documents, $width, $height, $resizeMode);
  }
  
  /**
   * Saves the content of the specified document to a file in the specified directory and optionally applies resizing (only works for images).
   * This method should not be called for large files from within an enduser-request, because it blocks until the whole file has been retrieved from the CRM-VISIONLINE system.
   * @param int|QueryResult $document The document for which the content should be saved. This can either be the id of the document (int) or query result describing a document.
   * @param string $directory The directory to which the file should be written.
   * @param bool $forceDownload If true, the file is downloaded even if it already exists in the specified directory and is up-to-date regarding the lastModified date (optional).
   * @param int $width The width to which the image should be resized (optional).
   * @param int $height The height to which the image should be resized (optional).
   * @param string $resizeMode Specifies how the image should be resized (optional).
   * @return string The filename under which the contents have been saved.
   * @see saveFiles
   */
  public function saveFile($document, $directory, $forceDownload = false, $width = null, $height = null, $resizeMode = null)
  {
    $op = new FileSaveOperation($this, $this->getFileUrl, $this->connection, $this->bufferSize, $directory, $forceDownload);
    return $op->exec($document, $width, $height, $resizeMode);
  }
  
  /**
   * Saves the content of the specified documents to files in the specified directory and optionally applies resizing (only works for images).
   * This method should not be called for large files from within an enduser-request, because it blocks until the whole file has been retrieved from the CRM-VISIONLINE system.
   * @param array $documents The documents for which the content should be saved. This can either be ids or query results.
   * @param string $directory The directory to which the file should be written.
   * @param bool $forceDownload If true, the file is downloaded even if it already exists in the specified directory and is up-to-date regarding the lastModified date (optional).
   * @param int $width The width to which the images should be resized (optional).
   * @param int $height The height to which the images should be resized (optional).
   * @param string $resizeMode Specifies how the images should be resized (optional).
   * @return array The filenames under which the files have been saved, where the key is the documents id.
   */
  public function saveFiles($documents, $directory, $forceDownload = false, $width = null, $height = null, $resizeMode = null)
  {
    $op = new FileSaveOperation($this, $this->getFileUrl, $this->connection, $this->bufferSize, $directory, $forceDownload);
    $result = $op->execMultiple($documents, $width, $height, $resizeMode);
    $this->debug('saveFiles: result = ', $result);
    return $result;
  }
  
  /**
   * Outputs the content of the specified document(s) and optionally applies resizing (only works for images).
   * It is safe to call this method from within an enduser-request, because it immediately outputs chunks of data as they are received from the CRM-VISIONLINE system. 
   * @param array|int $document The document(s) for which the content should be output. This can either be the id of the document (int),
   * multiple document ids (array of int), a query result describing a document (QueryResult) or multiple query results describing
   * multiple documents (array of QueryResult).
   * @param boolean $sendHeaders If true, the HTTP headers 'Content-type' and 'Content-disposition' are sent via header()
   * @param int $width The width to which the image should be resized (optional).
   * @param int $height The height to which the image should be resized (optional).
   * @param string $resizeMode Specifies how the image should be resized (optional).
   * @param bool $attachment If true, the HTTP header 'Content-disposition' is set to 'attachment'
   */
  public function passthruFile($document, $sendHeaders = false, $width = null, $height = null, $resizeMode = null, $attachment = false)
  {
    $op = new FilePassthruOperation($this, $this->getFileUrl, $this->connection, $this->bufferSize, $sendHeaders, $attachment);
    $op->exec($document, $width, $height, $resizeMode);
  }
  
  /**
   * Returns the interests of the specified contact, optionally filtered by status.
   * @param int|QueryResult|array $contacts The contacts 
   * @param array $filterByStatus Filters the interests by their status
   * @return array of Interest: the interests of the contacts
   */
  public function getInterests($contacts, array $filterByStatus = null)
  {
    if (is_int($contacts))
    {
      return $this->getInterests(array($contacts), $filterByStatus);
    }
    else if ($contacts instanceof QueryResult)
    {
      return $this->getInterests(array($contacts), $filterByStatus);
    }
    else if (is_array($contacts))
    {
      $ids = array();
    
      // Initialize $ids
      foreach ($contacts as $entry)
      {
        if (is_int($entry))
        {
          $ids[] = $entry;
        }
        else if ($entry instanceof QueryResult)
        {
          $ids[] = $entry->id;
        }
        else
        {
          throw new \InvalidArgumentException('Invalid value for parameter "contacts". Expected (array of) int or QueryResult, got ' . gettype($entry));
        }
      }
    
      // Call webservice method
      return $this->_GetInterests($ids, $filterByStatus);
    }
    else
    {
      throw new \InvalidArgumentException('Invalid value for parameter "contacts". Expected (array of) int or QueryResult, got ' . gettype($contacts));
    }
  }
}
