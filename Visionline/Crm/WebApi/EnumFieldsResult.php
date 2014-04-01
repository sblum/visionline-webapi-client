<?php
/**
 * Defines the class EnumFieldsResult
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Describes a field 
 * 
 */
class EnumFieldsResult
{
  /**
   * The unique field identifier
   * @var string
   */
  public $field;
  
  /**
   * The field's name
   * @var string
   */
  public $name;
}