<?php
/**
 * Defines the class StoredEnquiry.
 */

namespace Visionline\Crm\WebApi;

/**
 * Describes an enquiry stored in CRM-VISIONLINE.
 */
class StoredEnquiry extends Enquiry
{
    /**
     * Die ID der Anfrage.
     *
     * @var int
     */
    public $id;

    /**
     * Der Status der Anfrage.
     *
     * @var string
     */
    public $status;

    /**
     * ID des zugeordneten Kontakts.
     *
     * @var int
     */
    public $kontaktId;

    /**
     * IDs der zugeordneten Objekte.
     *
     * @var array of int
     */
    public $objektIds;

    /**
     * IDs der zugeordneten Projekte.
     *
     * @var array of int
     */
    public $projektIds;

    /**
     * IDs der zugeordneten Dokumente.
     *
     * @var array of int
     */
    public $dokumentIds;

    /**
     * IDs der zugeordneten Betreuer.
     *
     * @var array of int
     */
    public $betreuerIds;

    /**
     * IDs der Kontakte der zugeordneten Betreuer.
     *
     * @var array of int
     */
    public $betreuerKontaktIds;
}
