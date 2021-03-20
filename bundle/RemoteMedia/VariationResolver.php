<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia;

use eZ\Publish\Core\MVC\ConfigResolverInterface;

class VariationResolver
{
    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    private $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * Return merged variations defined for a provided content type and default ones.
     *
     * @param string $contentTypeIdentifier
     *
     * @return array
     */
    public function getVariationsForContentType($contentTypeIdentifier)
    {
        $variations = $this->configResolver->getParameter('image_variations', 'netgen_remote_media');

        $defaultVariations = $variations['default'] ?? [];
        $contentTypeVariations = $variations[$contentTypeIdentifier] ?? [];

        return \array_merge($defaultVariations, $contentTypeVariations);
    }

    /**
     * Returns variations for a provided content type which have 'crop' transformation configured.
     *
     * @param $contentTypeIdentifier
     *
     * @return array
     */
    public function getCroppbableVariations($contentTypeIdentifier)
    {
        $variations = $this->getVariationsForContentType($contentTypeIdentifier);

        $croppableVariations = [];
        foreach ($variations as $variationName => $variationOptions) {
            if (isset($variationOptions['transformations']['crop'])) {
                $croppableVariations[$variationName] = $variationOptions;
            }
        }

        return $croppableVariations;
    }

    /**
     * Returns variations to be used when embedding image into ezxml text.
     *
     * @return array
     */
    public function getEmbedVariations()
    {
        $variations = $this->configResolver->getParameter('image_variations', 'netgen_remote_media');
        $variations = $variations['embedded'] ?? [];

        $croppableVariations = [];
        foreach ($variations as $variationName => $variationOptions) {
            if (isset($variationOptions['transformations']['crop'])) {
                $croppableVariations[$variationName] = $variationOptions;
            }
        }

        return $croppableVariations;
    }
}
