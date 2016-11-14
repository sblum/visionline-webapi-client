<?php
/**
 * Defines the class Operator
 * @package pagepackage
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines constants for operators of an expression.
 * @see Expression
 */
class Operator
{
    /**
     * Equal
     * @var string
     */
    const Eq = 'Eq';

    /**
     * Not equal
     * @var string
     */
    const NotEq = 'NotEq';

    /**
     * Less than
     * @var string
     */
    const Lt = 'Lt';

    /**
     * Less than or equal
     * @var string
     */
    const Le = 'Le';

    /**
     * Greater than
     * @var string
     */
    const Gt = 'Gt';

    /**
     * Greater than or equal
     * @var string
     */
    const Ge = 'Ge';

    /**
     * One of
     * @var string
     */
    const OneOf = 'OneOf';

    /**
     * All of
     * @var string
     */
    const AllOf = 'AllOf';

    /**
     * None of
     * @var string
     */
    const NoneOf = 'NoneOf';

    /**
     * Is true
     * @var string
     */
    const IsTrue = 'IsTrue';

    /**
     * Is false
     * @var string
     */
    const IsFalse = 'IsFalse';

    /**
     * StartsWith
     * @var string
     */
    const StartsWith = 'StartsWith';

    /**
     * Is empty
     * @var string
     */
    const IsEmpty = 'IsEmpty';

    /**
     * Is not empty
     * @var string
     */
    const IsNotEmpty = 'IsNotEmpty';
    
    /**
     * Is null
     * @var string
     */
    const IsNull = 'IsNull';
    
    /**
     * Is not null
     * @var string
     */
    const IsNotNull = 'IsNotNull';

    /**
     * Located in geographical region
     * @var string
     */
    const LocatedInGeo = 'LocatedInGeo';

    /**
     * Located in geographical location (position + radius)
     * @var string
     */
    const LocatedInGeoPos = 'LocatedInGeoPos';

    /**
     * Located in political region
     * @var string
     */
    const LocatedInPol = 'LocatedInPol';

    /**
     * Not located in political region
     * @var string
     */
    const NotLocatedInPol = 'NotLocatedInPol';

    /**
     * In hierarchy
     * @var string
     */
    const InHierarchy = 'InHierarchy';

    /**
     * Date is in current month
     * @var string
     */
    const InCurrentMonth = 'InCurrentMonth';

    /**
     * Date is in previous month
     * @var string
     */
    const InPreviousMonth = 'InPreviousMonth';
    
    /**
     * Date is in current year
     * @var string
     */
    const InCurrentYear = 'InCurrentYear';
    
    /**
     * Date is in previous year
     * @var string
     */
    const InPreviousYear = 'InPreviousYear';
    
    /**
     * String contains another string
     */
    const Contains = 'Contains';
}

?>