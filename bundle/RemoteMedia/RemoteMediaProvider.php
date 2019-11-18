<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia;

use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value;
use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\Search\Query;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\Search\Result;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\Registry;
use Psr\Log\LoggerInterface;

abstract class RemoteMediaProvider
{
    /** @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Transformation\Registry */
    protected $registry;

    /** @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\VariationResolver */
    protected $variationResolver;

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    public function __construct(Registry $registry, VariationResolver $variationsResolver, LoggerInterface $logger = null)
    {
        $this->registry = $registry;
        $this->variationResolver = $variationsResolver;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    abstract public function supportsContentBrowser(): bool;

    /**
     * @return bool
     */
    abstract public function supportsFolders();

    /**
     * Uploads the local resource to remote storage and builds the Value from the response.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\UploadFile $uploadFile
     * @param array $options
     *
     * @return Value
     */
    abstract public function upload(UploadFile $uploadFile, ?array $options = []): Value;

    /**
     * Gets the remote media Variation.
     * If the remote media does not support variations, this method should return the Variation
     * with the url set to original resource.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value $value
     * @param string $contentTypeIdentifier
     * @param string|array $format
     * @param bool $secure
     *
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation
     */
    abstract public function buildVariation(Value $value, string $contentTypeIdentifier, $format, ?bool $secure = true): Variation;

    /**
     * Lists all available folders.
     * If folders are not supported, should return empty array.
     *
     * @return array
     */
    abstract public function listFolders(): array;

    /**
     * @param $folder
     *
     * @return int
     */
    abstract function countResourcesInFolder(string $folder): int;

    /**
     * Counts available resources from the remote storage.
     *
     * @return int
     */
    abstract public function countResources(): int;

    /**
     * Counts available resources in specified folder from the remote storage.
     * If folders are not supported, should return zero.
     *
     * @param $folder
     *
     * @return int
     */
    abstract public function countResourcesInFolder($folder);

    /**
     * Searches for the remote resource containing term in the query.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\Search\Query $query
     *
     * @return \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\Search\Result
     */
    abstract public function searchResources(Query $query): Result;

    /**
     * Returns the remote resource with provided id and type.
     *
     * @param mixed $resourceId
     * @param string $resourceType
     *
     * @return Value
     */
    abstract public function getRemoteResource(string $resourceId, ?string $resourceType = 'image'): Value;

    /**
     * Adds tag to remote resource.
     *
     * @param string $resourceId
     * @param string $tag
     *
     * @return mixed
     */
    abstract public function addTagToResource(string $resourceId, string $tag);

    /**
     * Removes tag from remote resource.
     *
     * @param string $resourceId
     * @param string $tag
     *
     * @return mixed
     */
    abstract public function removeTagFromResource(string $resourceId, string $tag);

    /**
     * @param $resourceId
     * @param $tags
     *
     * @return mixed
     */
    abstract public function updateTags(string $resourceId, string $tags);

    /**
     * Updates the resource context.
     * eg. alt text and caption:
     * context = [
     *      'caption' => 'new caption'
     *      'alt' => 'alt text'
     * ];.
     *
     * @param string $resourceId
     * @param string $resourceType
     * @param array $context
     *
     * @return mixed
     */
    abstract public function updateResourceContext(string $resourceId, string $resourceType, array $context);

    /**
     * Returns thumbnail url for the video with provided id.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value $value
     * @param array $options
     *
     * @return string
     */
    abstract public function getVideoThumbnail(Value $value, ?array $options = []): string;

    /**
     * Generates html5 video tag for the video with provided id.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value $value
     * @param string $contentTypeIdentifier
     * @param string $format
     *
     * @return string
     */
    abstract public function generateVideoTag(Value $value, string $contentTypeIdentifier, ?string $format = ''): string;

    /**
     * Removes the resource from the remote.
     *
     * @param string $resourceId
     */
    abstract public function deleteResource(string $resourceId);

    /**
     * Generates the link to the remote resource.
     *
     * @param \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value $value
     *
     * @return string
     */
    abstract public function generateDownloadLink(Value $value): string;

    /**
     * Returns unique identifier of the provided.
     *
     * @return string
     */
    abstract public function getIdentifier(): string;

    /**
     * Logs the error if the logger is available.
     *
     * @param $message
     */
    protected function logError(string $message)
    {
        if ($this->logger instanceof LoggerInterface) {
            $this->logger->error($message);
        }
    }
}
