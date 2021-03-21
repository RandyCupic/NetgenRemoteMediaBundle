<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\Exception\TransformationHandlerFailedException;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\HandlerInterface;

/**
 * Class Background
 * Use the background parameter to set the background color of the image.
 * The image background is visible when padding is added with one of the
 * padding crop modes, when rounding corners, when adding overlays, and
 * with semi-transparent PNGs and GIFs.
 *
 * An opaque color can be set as an RGB hex triplet (e.g., b_rgb:3e2222),
 * a 3-digit RGB hex (e.g., b_rgb:777) or a named color (e.g., b_green).
 * Cloudinary's client libraries also support a # shortcut for RGB
 * (e.g., setting background to #3e2222 which is then translated to rgb:3e2222).
 */
class Background implements HandlerInterface
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

        if (empty($config[1])) {
            return [
                'background' => $config[0],
            ];
        }

        if ($config[0] === 'rgb') {
            return [
                'background' => $config[0] . ':' . $config[1],
            ];
        }

        throw new TransformationHandlerFailedException(self::class);
    }
}
