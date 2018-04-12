<?php
/**
 * Defines the class EntityType.
 */

namespace Visionline\Crm\WebApi;

/**
 * Defines constants for change types.
 *
 * @see ChangeType::Create
 * @see ChangeType::Update
 * @see ChangeType::Delete
 */
class ChangeType
{
    /** Constant for change type 'Create' */
    const Create = 'Create';

    /** Constant for change type 'Update' */
    const Update = 'Update';

    /** Constant for change type 'Delete' */
    const Delete = 'Delete';

    /**
     * Empty private constructor.
     */
    private function __construct()
    {
    }
}
