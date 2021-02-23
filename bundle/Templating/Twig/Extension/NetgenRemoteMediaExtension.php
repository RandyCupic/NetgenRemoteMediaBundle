<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Templating\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Field;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig_Filter;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\Helper\TranslationHelper;
use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Helper;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\RemoteMediaProvider;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\VariationResolver;
use Twig\TwigFunction;

final class NetgenRemoteMediaExtension extends AbstractExtension
{
    /**
     * @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\RemoteMediaProvider
     */
    private $provider;

    /**
     * @var \eZ\Publish\Core\Helper\TranslationHelper
     */
    private $translationHelper;

    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Helper
     */
    private $helper;

    /**
     * @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\VariationResolver
     */
    private $variationResolver;

    /**
     * NetgenRemoteMediaExtension constructor.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\RemoteMediaProvider $provider
     * @param \eZ\Publish\Core\Helper\TranslationHelper $translationHelper
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Helper $helper
     * @param \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\VariationResolver $variationResolver
     */
    public function __construct(
        RemoteMediaProvider $provider,
        TranslationHelper $translationHelper,
        ContentTypeService $contentTypeService,
        Helper $helper,
        VariationResolver $variationResolver
    ) {
        $this->provider = $provider;
        $this->translationHelper = $translationHelper;
        $this->contentTypeService = $contentTypeService;
        $this->helper = $helper;
        $this->variationResolver = $variationResolver;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'scaling_format',
                [$this, 'scalingFormat']
            ),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'netgen_remote_variation',
                [$this, 'getRemoteImageVariation']
            ),
            new TwigFunction(
                'netgen_remote_video',
                [$this, 'getRemoteVideoTag']
            ),
            new TwigFunction(
                'netgen_remote_video_thumbnail',
                [$this, 'getVideoThumbnail']
            ),
            new TwigFunction(
                'netgen_remote_download',
                [$this, 'getResourceDownloadLink']
            ),
            new TwigFunction(
                'netgen_remote_media',
                [$this, 'getRemoteResource']
            ),
            new TwigFunction(
                'ngrm_is_croppable',
                [$this, 'contentTypeIsCroppable']
            ),
            new TwigFunction(
                'ngrm_available_variations',
                [$this, 'variationsForContent']
            ),
        ];
    }

    public function scalingFormat(array $variations): array
    {
        if (empty($variations)) {
            return $variations;
        }

        $availableVariations = [];

        foreach ($variations as $variationName => $variationConfig) {
            foreach ($variationConfig['transformations'] as $name => $config) {
                if ($name !== 'crop') {
                    continue;
                }

                $availableVariations[$variationName] = $config;
            }
        }

        return $availableVariations;
    }

    /**
     * Returns variation by specified format.
     *
     * @param string $fieldIdentifier
     * @param string|array $format
     * @param bool $secure
     *
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation
     */
    public function getRemoteImageVariation(Content $content, string $fieldIdentifier, $format, bool $secure = true): Variation
    {
        $field = $this->translationHelper->getTranslatedField($content, $fieldIdentifier);
        $contentTypeIdentifier = $this->contentTypeService->loadContentType($content->contentInfo->contentTypeId)->identifier;

        return $this->provider->buildVariation($field->value, $contentTypeIdentifier, $format, $secure);
    }

    /**
     * Generates html5 video tag for the video with provided id.
     *
     * @param string $fieldIdentifier
     * @param string|array $format
     *
     * @return string
     */
    public function getRemoteVideoTag(Content $content, string $fieldIdentifier, $format = ''): string
    {
        $field = $this->translationHelper->getTranslatedField($content, $fieldIdentifier);
        $contentTypeIdentifier = $this->contentTypeService->loadContentType($content->contentInfo->contentTypeId)->identifier;

        return $this->provider->generateVideoTag($field->value, $contentTypeIdentifier, $format);
    }

    /**
     * Returns thumbnail url.
     *
     * @param array $options
     *
     * @return string
     */
    public function getVideoThumbnail(Value $value, ?array $options = []): string
    {
        return $this->provider->getVideoThumbnail($value, $options);
    }

    /**
     * Returns the link to the remote resource.
     *
     * @return string
     */
    public function getResourceDownloadLink(Value $value): string
    {
        return $this->provider->generateDownloadLink($value);
    }

    /**
     * Creates variation directly form Value, without the need for Content.
     *
     * @param string|array $format
     *
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation
     */
    public function getRemoteResource(Value $value, $format): Variation
    {
        return $this->provider->buildVariation($value, 'custom', $format, true);
    }

    /**
     * Returns true if there is croppable variation configuration for the given content type.
     *
     * @return bool
     */
    public function contentTypeIsCroppable(Content $content): bool
    {
        $contentTypeIdentifier = $this->contentTypeService->loadContentType(
            $content->contentInfo->contentTypeId
        )->identifier;

        return !empty($this->variationResolver->getCroppbableVariations($contentTypeIdentifier));
    }

    /**
     * Returns the list of available variations for the given content.
     * If second parameter is true, it will return only variations with crop configuration.
     *
     * @param string $contentTypeIdentifier
     * @param bool $onlyCroppable
     *
     * @return array
     */
    public function variationsForContent(string $contentTypeIdentifier, bool $onlyCroppable = false): array
    {
        if ($contentTypeIdentifier instanceof Content) {
            $contentTypeIdentifier = $this->contentTypeService->loadContentType(
                $contentTypeIdentifier->contentInfo->contentTypeId
            )->identifier;
        }

        return $onlyCroppable ?
            $this->variationResolver->getCroppbableVariations($contentTypeIdentifier) :
            $this->variationResolver->getVariationsForContentType($contentTypeIdentifier);
    }
}
