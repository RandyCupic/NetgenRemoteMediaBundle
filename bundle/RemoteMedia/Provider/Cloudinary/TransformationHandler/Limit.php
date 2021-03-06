<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\HandlerInterface;

/**
 * Class Limit.
 *
 * Same as the fit mode but only if the original image is larger
 * than the given limit (width and height), in which case the image
 * is scaled down so that it takes up as much space as possible within
 * a bounding box defined by the given width and height parameters.
 * The original aspect ratio is retained and all of the original image
 * is visible. This mode doesn't scale up the image if your requested
 * dimensions are larger than the original image's.
 */
class Limit implements HandlerInterface
{
    /**
     * Takes options from the configuration and returns
     * properly configured array of options.
     *
     * @param string $variationName name of the configured image variation configuration
     *
     * @return array
     */
    public function process(Value $value, $variationName, array $config = [])
    {
        $options = [
            'crop' => 'limit',
        ];

        if (isset($config[0]) && $config[0] !== 0) {
            $options['width'] = $config[0];
        }

        if (isset($config[1]) && $config[1] !== 0) {
            $options['height'] = $config[1];
        }

        return $options;
    }
}
