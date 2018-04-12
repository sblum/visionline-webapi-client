<?php
/**
 * Defines the class Order.
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines the order of a result set.
 */
class Order
{
    /**
     * The fields by which the result set should be sorted.  If multiple fields are specified, the first filled-in field per entity is used for comparison.
     *
     * @var string
     */
    public $fields;

    /**
     * Specifies whether the order should be ascending or descending.
     *
     * @var bool
     */
    public $asc;

    /**
     * Specifies whether the order is random. If <code>true</code>, other
     * settings are ignored and a random order is performed.
     *
     * @var bool
     */
    public $random;

    /**
     * Creates a new Order.
     *
     * @param string|array $field     The fields by which the result set should be sorted.  If multiple fields are specified, the first filled-in field per entity is used for comparison.
     * @param bool         $ascending Specifies whether the order should be ascending or descending. If true, the order will be ascending, otherwise descending.
     * @param bool         $random    Specifies whether the order is random. If <code>true</code>, the other parameters are ignored and a random order is performed.
     */
    public function __construct($field, $ascending, $random = false)
    {
        if (\is_array($field)) {
            $this->fields = $field;
        } else {
            $this->fields = [$field];
        }

        $this->asc = $ascending;
        $this->random = $random;
    }

    /**
     * Shorthand for creating an ascending order by the specified field.
     *
     * @param string|array $field The fields by which the result set should be sorted. If multiple fields are specified, the first filled-in field per entity is used for comparison.
     *
     * @return \Visionline\Crm\WebApi\Order
     */
    public static function asc($field)
    {
        return new self($field, true);
    }

    /**
     * Shorthand for creating an descending order by the specified field.
     *
     * @param string|array $field The fields by which the result set should be sorted.  If multiple fields are specified, the first filled-in field per entity is used for comparison.
     *
     * @return \Visionline\Crm\WebApi\Order
     */
    public static function desc($field)
    {
        return new self($field, false);
    }

    /**
     * Shorthand for creating a random order.
     *
     * @return \Visionline\Crm\WebApi\Order
     */
    public static function random()
    {
        return new self(null, false, true);
    }

    /**
     * Returns the string representation of this order.
     *
     * @return string The string representation of this order
     */
    public function __toString()
    {
        if ($this->random) {
            return 'random';
        } else {
            return (!$this->asc ? 'desc:' : '').\implode(',', $this->fields);
        }
    }

    /**
     * Create an order from a string representation previously generated by __toString().
     *
     * @param string $orderstr A string representation of an order
     *
     * @return Order The order created from the string representation
     */
    public static function fromString($orderstr)
    {
        if (!empty($orderstr)) {
            $asc = true;
            $fields = null;

            foreach (\explode(':', $orderstr) as $part) {
                if ('random' == $part) {
                    return self::random();
                } elseif ('desc' == $part) {
                    $asc = false;
                } else {
                    $fields = \explode(',', $part);
                }
            }

            return new self($fields, $asc);
        } else {
            return null;
        }
    }
}
