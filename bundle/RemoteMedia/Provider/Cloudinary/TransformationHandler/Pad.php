<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\HandlerInterface;

/**
 * Class Pad.
 *
 * Resize the image to fill the given width and height while retaining
 * the original aspect ratio and with all of the original image visible.
 * If the proportions of the original image do not match the given width
 * and height, padding is added to the image to reach the required size.
 * You can also specify the color of the background in the case that padding is added.
 */
class Pad implements HandlerInterface
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
            'crop' => 'pad',
        ];

        if (isset($config[0]) && $config[0] !== 0) {
            $options['width'] = $config[0];
        }

        if (isset($config[1]) && $config[1] !== 0) {
            $options['height'] = $config[1];
        }

        if (!empty($config[2])) {
            $options['background'] = $config[2];
        }

        return $options;
    }
}
