<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\Exception\TransformationHandlerFailedException;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\HandlerInterface;

/**
 * Class Opacity
 * Adjust the opacity of an image with the opacity parameter (o in URLs).
 * The parameter accepts a value between 0-100 representing the percentage
 * of transparency, where 100 means completely opaque and 0 is completely
 * transparent.
 *
 * Note: if the image format does not support transparency, the background
 * color is used instead as a base (white by default). The color can be
 * changed with the background parameter.
 */
class Opacity implements HandlerInterface
{
    /**
     * Takes options from the configuration and returns
     * properly configured array of options.
     *
     * @param string $variationName name of the configured image variation configuration
     *
     * @throws \Netgen\Bundle\RemoteMediaBundle\Exception\TransformationHandlerFailedException
     *
     * @return array
     */
    public function process(Value $value, $variationName, array $config = [])
    {
        if (empty($config[0])) {
            throw new TransformationHandlerFailedException(self::class);
        }

        return [
            'opacity' => $config[0],
        ];
    }
}
