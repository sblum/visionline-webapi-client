<?php
/**
 * Defines the class CreateEnquiryResult.
 */

namespace Visionline\Crm\WebApi;

/**
 * Describes a result of the CreateEnquiry method.
 */
class CreateEnquiryResult
{
    /**
     * Set of warnings that occurred during creating the enquiry.
     *
     * @var array
     */
    public $warnings;

    /**
     * The enquiry that was created.
     *
     * @var StoredEnquiry
     */
    public $enquiry;
}
