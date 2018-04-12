<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 19.12.2017
 * Time: 10:54.
 */

namespace Visionline\Crm\WebApi;

class EventQueryResult
{
    /** @var int ID des events */
    public $id;

    /** @var string Typ der Änderung */
    public $changeType;

    /** @var string Typ der geänderten Entität */
    public $entityType;

    /** @var int ID der geänderten Entität */
    public $entityId;

    /** @var int ID des Betreuers, der die Änderung durchgeführt hat */
    public $betreuerId;

    /** @var int ID zum Betreuer gehörenden Kontakts, der die Änderung durchgeführt hat */
    public $betreuerContactId;

    /** @var string Name des Users, der die Änderung durchgeführt hat */
    public $username;

    /** @var string Die neuen Werte der Properties */
    public $state;
}
