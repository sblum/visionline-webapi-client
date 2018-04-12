<?php
/**
 * Defines the class QueryResult.
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a result in the result set of a query.
 */
class QueryResult
{
    /**
     * The id of the entity.
     *
     * @var int
     */
    public $id;

    /**
     * The date of the last modification of the entity. This can be useful for caching.
     *
     * @var int
     */
    public $lastModified;

    /**
     * Create a query result.
     *
     * @param int $id           The id of the entity
     * @param int $lastModified The date of the last modification of the entity.
     */
    public function __construct($id, $lastModified = null)
    {
        $this->id = $id;
        $this->lastModified = $lastModified;
    }

    /**
     * Initializes a query result after being constructed by SoapClient.
     */
    public function init()
    {
        if (null != $this->lastModified) {
            $this->lastModified = \strtotime($this->lastModified);
        }
    }
}
