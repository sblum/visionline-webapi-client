<?php
/**
 * Defines the class Expression.
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines a filter. This is the abstract base class of Expression and Junction.
 *
 * @see Expression
 * @see Junction
 */
abstract class Filter
{
    /**
     * Links this filter with a logical OR to the specified filter.
     *
     * @param Filter $f the filter to logically link to this filter
     *
     * @return Junction the resulting junction
     */
    public function or_(self $f)
    {
        $junction = new Junction(Junction::TypeOr);

        return $junction
      ->add($this)
      ->add($f);
    }

    /**
     * Links this filter with a logical AND to the specified filter.
     *
     * @param Filter $f the filter to logically link to this filter
     *
     * @return Junction the resulting junction
     */
    public function and_(self $f)
    {
        $junction = new Junction(Junction::TypeAnd);

        return $junction
      ->add($this)
      ->add($f);
    }

    /**
     * Shorthand for creating an expression with Operator::Eq.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Eq
     * @see Expression
     */
    public static function eq($field, $value)
    {
        return new Expression($field, Operator::Eq, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::NotEq.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::NotEq
     * @see Expression
     */
    public static function notEq($field, $value)
    {
        return new Expression($field, Operator::NotEq, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::Contains.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Contains
     * @see Expression
     */
    public static function contains($field, $value)
    {
        return new Expression($field, Operator::Contains, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::StartsWith.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::StartsWith
     * @see Expression
     */
    public static function startsWith($field, $value)
    {
        return new Expression($field, Operator::StartsWith, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::Gt.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Gt
     * @see Expression
     */
    public static function gt($field, $value)
    {
        return new Expression($field, Operator::Gt, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::Ge.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Ge
     * @see Expression
     */
    public static function ge($field, $value)
    {
        return new Expression($field, Operator::Ge, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::Lt.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Lt
     * @see Expression
     */
    public static function lt($field, $value)
    {
        return new Expression($field, Operator::Lt, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::Le.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::Le
     * @see Expression
     */
    public static function le($field, $value)
    {
        return new Expression($field, Operator::Le, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::OneOf.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::OneOf
     * @see Expression
     */
    public static function oneOf($field, $value)
    {
        return new Expression($field, Operator::OneOf, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::AllOf.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::AllOf
     * @see Expression
     */
    public static function allOf($field, $value)
    {
        return new Expression($field, Operator::AllOf, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::NoneOf.
     *
     * @param string $field
     * @param string $value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::NoneOf
     * @see Expression
     */
    public static function noneOf($field, $value)
    {
        return new Expression($field, Operator::NoneOf, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::IsTrue.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsTrue
     * @see Expression
     */
    public static function isTrue($field)
    {
        return new Expression($field, Operator::IsTrue);
    }

    /**
     * Shorthand for creating an expression with Operator::IsFalse.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsFalse
     * @see Expression
     */
    public static function isFalse($field)
    {
        return new Expression($field, Operator::IsFalse);
    }

    /**
     * Shorthand for creating an expression with Operator::IsEmpty.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsEmpty
     * @see Expression
     */
    public static function isEmpty($field)
    {
        return new Expression($field, Operator::IsEmpty);
    }

    /**
     * Shorthand for creating an expression with Operator::IsNotEmpty.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsNotEmpty
     * @see Expression
     */
    public static function isNotEmpty($field)
    {
        return new Expression($field, Operator::IsNotEmpty);
    }

    /**
     * Shorthand for creating an expression with Operator::IsNull.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsNull
     * @see Expression
     */
    public static function isNull($field)
    {
        return new Expression($field, Operator::IsNull);
    }

    /**
     * Shorthand for creating an expression with Operator::IsNotNull.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::IsNotNull
     * @see Expression
     */
    public static function isNotNull($field)
    {
        return new Expression($field, Operator::IsNotNull);
    }

    /**
     * Shorthand for creating an expression with Operator::InHierarchy.
     *
     * @param string $field The field
     * @param string $value The value
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::InHierarchy
     * @see Expression
     */
    public static function inHierarchy($field, $value)
    {
        return new Expression($field, Operator::InHierarchy, $value);
    }

    /**
     * Shorthand for creating an expression with Operator::InCurrentMonth.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::InCurrentMonth
     * @see Expression
     */
    public static function inCurrentMonth($field)
    {
        return new Expression($field, Operator::InCurrentMonth);
    }

    /**
     * Shorthand for creating an expression with Operator::InPreviousMonth.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::InPreviousMonth
     * @see Expression
     */
    public static function inPreviousMonth($field)
    {
        return new Expression($field, Operator::InPreviousMonth);
    }

    /**
     * Shorthand for creating an expression with Operator::InCurrentYear.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::InCurrentYear
     * @see Expression
     */
    public static function inCurrentYear($field)
    {
        return new Expression($field, Operator::InCurrentYear);
    }

    /**
     * Shorthand for creating an expression with Operator::InPreviousYear.
     *
     * @param string $field
     *
     * @return \Visionline\Crm\WebApi\Expression
     *
     * @see Operator::InPreviousYear
     * @see Expression
     */
    public static function inPreviousYear($field)
    {
        return new Expression($field, Operator::InPreviousYear);
    }
}
