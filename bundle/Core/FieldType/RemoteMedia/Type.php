<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia;

use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\FieldType;
use eZ\Publish\Core\FieldType\Value as BaseValue;
use eZ\Publish\SPI\FieldType\Value as SPIValue;
use eZ\Publish\SPI\Persistence\Content\FieldValue;

final class Type extends FieldType
{
    public function getFieldTypeIdentifier(): string
    {
        return 'ngremotemedia';
    }

    public function getName(SPIValue $value, FieldDefinition $fieldDefinition, string $languageCode): string
    {
        if (!empty($value->resourceId)) {
            return $value->resourceId;
        }

        return '';
    }

    /**
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value
     */
    public function getEmptyValue(): Value
    {
        return new Value();
    }

    /**
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value
     */
    public function fromHash($hash): Value
    {
        if (!\is_array($hash)) {
            return $this->getEmptyValue();
        }

        if (isset($hash['input_uri'])) {
            return new InputValue($hash);
        }

        // @todo: finish this!!!!
        $valueHash = [
            'resourceId' => $hash['resourceId'],
            'secureUrl' => $hash['secure_url'],
        ];

        return new Value($hash);
    }

    public function toHash(SPIValue $value): array
    {
        return (array) $value;
    }

    public function toPersistenceValue(SPIValue $value): FieldValue
    {
        if ($value instanceof InputValue) {
            return new FieldValue(
                [
                    'data' => null,
                    'externalData' => [
                        'input_uri' => $value->input_uri,
                        'alt_text' => $value->alt_text,
                        'caption' => $value->caption,
                        'variations' => $value->variations,
                    ],
                    'sortKey' => $this->getSortInfo($value),
                ]
            );
        } elseif ($value instanceof Value) {
            return new FieldValue(
                [
                    'data' => $value,
                    'externalData' => $value,
                    'sortKey' => $this->getSortInfo($value),
                ]
            );
        }
    }

    /**
     * @return \Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Value
     */
    public function fromPersistenceValue(FieldValue $fieldValue): Value
    {
        if ($fieldValue->data === null) {
            return $this->getEmptyValue();
        } elseif ($fieldValue->data === $this->getEmptyValue()) {
            return $this->getEmptyValue();
        }

        if ($fieldValue->data instanceof Value) {
            return $fieldValue->data;
        }

        return new Value($fieldValue->data);
    }

    public function isEmptyValue(SPIValue $value): bool
    {
        if ($value instanceof InputValue && !empty($value->input_uri)) {
            return false;
        }

        return $value === null || $value === $this->getEmptyValue() || empty($value->resourceId);
    }

    public function isSearchable(): bool
    {
        return true;
    }

    protected function getSortInfo(BaseValue $value)
    {
        return null;
    }

    protected function createValueFromInput($inputValue)
    {
        if ($inputValue instanceof InputValue) {
            return $inputValue;
        } elseif (\is_string($inputValue)) {
            $newValue = new InputValue();
            $newValue->input_uri = $inputValue;

            return $newValue;
        } elseif (\is_array($inputValue)) {
            return new InputValue($inputValue);
        }

        return $inputValue;
    }

    protected function checkValueStructure(BaseValue $value): void
    {
        if ($value instanceof Value && !\is_string($value->resourceId)) {
            throw new InvalidArgumentType('$value', 'string', $value->resourceId);
        } elseif ($value instanceof InputValue && !\is_string($value->input_uri)) {
            throw new InvalidArgumentType('$value', 'string', $value->input_uri);
        }
    }

    protected static function checkValueType($value): void
    {
        if (!$value instanceof Value && !$value instanceof InputValue) {
            throw new InvalidArgumentType('$value', 'Netgen\\Bundle\\RemoteMediaBundle\\Core\\FieldType\\RemoteMedia\\Value or "Netgen\\Bundle\\RemoteMediaBundle\\Core\\FieldType\\RemoteMedia\\InputValue",', $value);
        }
    }
}
