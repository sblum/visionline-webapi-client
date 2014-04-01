<?php
/**
 * Defines the class Expression
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Represents an expression filter that can be applied to a query.
 * @example new WebApi\Expression('KaufpreisBruttoObjekt', WebApi\Operator::Lt, '100000')
 */
class Expression extends Filter
{
  /**
   * Specifies the field. This string has to be UTF-8 encoded.
   * @var string
   */
  public $field;
  
  /**
   * Specifies the operator
   * @var string
   * @see Operator
   */
  public $op;
  
  /**
   * Specifies the value. This string has to be UTF-8 encoded.
   * @var string
   */
  public $value;
  
  /**
   * Creates a new expression filter using the specified values. 
   * @param string $field Specifies the field. This string has to be UTF-8 encoded.
   * @param string $op Specifies the operator.
   * @param string $value Specifies the value. Can be omitted for unary operators. This string has to be UTF-8 encoded.
   */
  public function __construct($field, $op, $value = NULL)
  {
    $this->field = $field;
    $this->op = $op;
    $this->value = $value;
  }
}

?>