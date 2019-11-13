<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\Search;

final class Query
{
    /** @var string */
    private $query;

    /** @var string */
    private $resourceType;

    /** @var string|null */
    private $folder;

    /** @var string|null */
    private $tag;

    /** @var int */
    private $limit;

    /** @var string|null */
    private $nextCursor;

    /** @var array  */
    private $sortBy = ['created_at' => 'desc'];

    /**
     * Query constructor.
     *
     * @param string $query
     * @param string $resourceType
     * @param string|null $folder
     * @param string|null $tag
     * @param int $limit
     * @param string|null $nextCursor
     * @param array $sortBy
     */
    public function __construct(
        string $query,
        string $resourceType,
        int $limit,
        ?string $folder,
        ?string $tag,
        ?string $nextCursor,
        array $sortBy = ['created_at' => 'desc']
    ) {
        $this->query = $query;
        $this->resourceType = $resourceType;
        $this->folder = $folder;
        $this->tag = $tag;
        $this->limit = $limit;
        $this->nextCursor = $nextCursor;
        $this->sortBy = $sortBy;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @return string
     */
    public function getFolder(): ?string
    {
        return $this->folder;
    }

    /**
     * @return string
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getNextCursor(): ?string
    {
        return $this->nextCursor;
    }

    /**
     * @return array
     */
    public function getSortBy(): array
    {
        return $this->sortBy;
    }



}
