<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\RemoteMediaStorage\Gateway;

use Countable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\RemoteMediaStorage\Gateway;
use PDO;
use RuntimeException;

class LegacyStorage extends Gateway
{
    /**
     * Connection.
     *
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Stores the data in the database based on the given field data.
     *
     * @param $fieldId
     * @param $resourceId
     * @param $contentId
     * @param $providerIdentifier
     * @param $version
     */
    public function storeFieldData($fieldId, $resourceId, $contentId, $providerIdentifier, $version)
    {
        $connection = $this->getConnection();

        $query = $connection->createQueryBuilder();
        $query
            ->select($connection->quoteIdentifier('resource_id'))
            ->from($connection->quoteIdentifier('ngremotemedia_field_link'))
            ->where(
                $query->expr()->eq(
                    $connection->quoteIdentifier('field_id'),
                    ':fieldId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('version'),
                    ':version'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('provider'),
                    ':provider'
                )
            )
            ->setParameter('fieldId', $fieldId, ParameterType::INTEGER)
            ->setParameter('version', $version, ParameterType::INTEGER)
            ->setParameter('provider', $providerIdentifier, ParameterType::STRING)
            ->distinct();

        $statement = $query->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ((is_array($rows) || $rows instanceof Countable ? \count($rows) : 0) > 0) {
            $query = $connection->createQueryBuilder();
            $query
                ->update('ngremotemedia_field_link')
                ->set(
                    $connection->quoteIdentifier('resource_id'),
                    ':resourceId'
                )
                ->where(
                    $query->expr()->eq(
                        $connection->quoteIdentifier('field_id'),
                        ':fieldId'
                    ),
                    $query->expr()->eq(
                        $connection->quoteIdentifier('version'),
                        ':version'
                    )
                )
                ->setParameter('resourceId', $resourceId, ParameterType::STRING)
                ->setParameter('fieldId', $fieldId, ParameterType::INTEGER)
                ->setParameter('version', $version, ParameterType::INTEGER);

            $query->execute();
        } else {
            $query = $connection->createQueryBuilder();
            $query
                ->insert($connection->quoteIdentifier('ngremotemedia_field_link'))
                ->set(
                    $connection->quoteIdentifier('contentobject_id'),
                    ':contentId'
                )->set(
                    $connection->quoteIdentifier('field_id'),
                    ':fieldId'
                )->set(
                    $connection->quoteIdentifier('version'),
                    ':version'
                )->set(
                    $connection->quoteIdentifier('resource_id'),
                    ':resourceId'
                )->set(
                    $connection->quoteIdentifier('provider'),
                    ':provider'
                )
                ->setParameter('contentId', $contentId, ParameterType::INTEGER)
                ->setParameter('fieldId', $fieldId, ParameterType::INTEGER)
                ->setParameter('version', $version, ParameterType::INTEGER)
                ->setParameter('resourceId', $resourceId, ParameterType::STRING)
                ->setParameter('provider', $providerIdentifier, ParameterType::STRING);

            $query->execute();
        }
    }

    /**
     * Gets the resource ID stored in the field.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param mixed $contentId
     * @param mixed $fieldId
     * @param mixed $versionNo
     * @param mixed $providerIdentifier
     *
     * @return int product ID
     */
    /*public function getFieldData(VersionInfo $versionInfo)
    {
        //return $this->loadFieldData($versionInfo->contentInfo->id);
    }*/

    /**
     * Deletes the entry in the link table for the provided field id and version.
     *
     * @param $contentId
     * @param $fieldId
     * @param $versionNo
     * @param $providerIdentifier
     *
     * @return mixed
     */
    public function deleteFieldData($contentId, $fieldId, $versionNo, $providerIdentifier)
    {
        $connection = $this->getConnection();
        $query = $connection->createQueryBuilder();
        $query
            ->delete($connection->quoteIdentifier('ngremotemedia_field_link'))
            ->where(
                $query->expr()->eq(
                    $connection->quoteIdentifier('field_id'),
                    ':fieldId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('contentobject_id'),
                    ':contentId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('version'),
                    ':version'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('provider'),
                    ':provider'
                )
            )
            ->setParameter('fieldId', $fieldId, ParameterType::INTEGER)
            ->setParameter('contentId', $contentId, ParameterType::INTEGER)
            ->setParameter('version', $versionNo, ParameterType::INTEGER)
            ->setParameter('provider', $providerIdentifier, ParameterType::STRING);

        $query->execute();
    }

    /**
     * Loads resource id for the provided field id and version.
     *
     * @param $contentId
     * @param $fieldId
     * @param $versionNo
     * @param $providerIdentifier
     *
     * @return array
     */
    public function loadFromTable($contentId, $fieldId, $versionNo, $providerIdentifier)
    {
        $connection = $this->getConnection();

        $query = $connection->createQueryBuilder();
        $query
            ->select($connection->quoteIdentifier('resource_id'))
            ->from($connection->quoteIdentifier('ngremotemedia_field_link'))
            ->where(
                $query->expr()->eq(
                    $connection->quoteIdentifier('field_id'),
                    ':fieldId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('contentobject_id'),
                    ':contentId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('version'),
                    ':version'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('provider'),
                    ':provider'
                )
            )
            ->setParameter('fieldId', $fieldId, ParameterType::INTEGER)
            ->setParameter('contentId', $contentId, ParameterType::INTEGER)
            ->setParameter('version', $versionNo, ParameterType::INTEGER)
            ->setParameter('provider', $providerIdentifier, ParameterType::STRING)
            ->distinct();

        $statement = $query->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return \array_map(
            static function ($item) {
                return $item['resource_id'];
            },
            $rows
        );
    }

    /**
     * Checks if the remote resource is connected to any content.
     *
     * @param $resourceId
     * @param $providerIdentifier
     *
     * @return bool
     */
    public function remoteResourceConnected($resourceId, $providerIdentifier)
    {
        $connection = $this->getConnection();

        $query = $connection->createQueryBuilder();
        $query
            ->select($connection->quoteIdentifier('resource_id'))
            ->from($connection->quoteIdentifier('ngremotemedia_field_link'))
            ->where(
                $query->expr()->eq(
                    $connection->quoteIdentifier('resource_id'),
                    ':resourceId'
                ),
                $query->expr()->eq(
                    $connection->quoteIdentifier('provider'),
                    ':provider'
                )
            )
            ->setParameter('resourceId', $resourceId, ParameterType::STRING)
            ->setParameter('provider', $providerIdentifier, ParameterType::STRING)
            ->distinct();

        $statement = $query->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return (is_array($rows) || $rows instanceof Countable ? \count($rows) : 0) > 0;
    }

    /**
     * Returns the active connection.
     *
     * @throws \RuntimeException if no connection has been set, yet
     *
     * @return \Doctrine\DBAL\Connection
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            throw new RuntimeException('Missing database connection.');
        }

        return $this->connection;
    }
}
