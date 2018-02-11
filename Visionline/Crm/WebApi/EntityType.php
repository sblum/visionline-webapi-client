<?php
/**
 * Defines the class EntityType.
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines constants for entity types.
 *
 * @see EntityType::Objekt
 * @see EntityType::Projekt
 * @see EntityType::Dokument
 */
abstract class EntityType
{
    /**
     * Constant for entity type 'Objekt'.
     *
     * @var string
     */
    const Objekt = 'Objekt';

    /**
     * Constant for entity-type 'Projekt'.
     *
     * @var string
     */
    const Projekt = 'Projekt';

    /**
     * Constant for entity-type 'Dokument'.
     *
     * @var string
     */
    const Dokument = 'Dokument';

    /**
     * Constant for entity-type 'Kontakt'.
     *
     * @var string
     */
    const Kontakt = 'Kontakt';

    /**
     * Constant for entity-type 'Aktivität'.
     */
    const Aktivität = 'Aktivität';

    /**
     * Constant for entity-type 'Anfrage'.
     */
    const Anfrage = 'Anfrage';
}
