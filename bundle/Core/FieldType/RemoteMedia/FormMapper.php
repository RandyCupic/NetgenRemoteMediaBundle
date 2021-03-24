<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia;

use EzSystems\EzPlatformContentForms\Data\Content\FieldData;
use EzSystems\EzPlatformAdminUi\Form\Data\FieldDefinitionData;
use EzSystems\RepositoryForms\FieldType\FieldDefinitionFormMapperInterface;
use EzSystems\RepositoryForms\FieldType\FieldValueFormMapperInterface;
use Netgen\Bundle\RemoteMediaBundle\Form\FieldType\RemoteMediaFieldType;
use Symfony\Component\Form\FormInterface;

class FormMapper implements FieldDefinitionFormMapperInterface, FieldValueFormMapperInterface
{
    public function mapFieldValueForm(FormInterface $fieldForm, FieldData $data)
    {
        $fieldForm
            ->add(
                $fieldForm->getConfig()->getFormFactory()->createBuilder()
                    ->create(
                        'value',
                        RemoteMediaFieldType::class,
                        [
                            'required' => $data->fieldDefinition->isRequired,
                            'label' => $data->fieldDefinition->getName(),
                            'field' => $data->field,
                        ]
                    )
                    ->setAutoInitialize(false)
                    ->getForm()
            );
    }

    public function mapFieldDefinitionForm(FormInterface $fieldDefinitionForm, FieldDefinitionData $data): void
    {
    }
}
