<?php
/**
 * Defines the class ResizeMode.
 */

namespace Visionline\Crm\WebApi;

/**
 * Declares constants that describe how an image should be resized.
 *
 * @see ResizeMode::Scale
 * @see ResizeMode::Crop
 */
abstract class ResizeMode
{
    /**
     * Specifies that an image should be scaled to the specified size.
     *
     * @var string
     */
    const Scale = 'Scale';

    /**
     * Specifies that an image should be cropped to the specified size.
     *
     * @var string
     */
    const Crop = 'Crop';

    /**
     * Private empty constructor.
     */
    private function __construct()
    {
    }
}
