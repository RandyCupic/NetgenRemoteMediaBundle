<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Exception;

use Exception;

class TransformationHandlerFailedException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $handlerIdentifier
     * @param mixed $handlerClass
     */
    public function __construct($handlerClass)
    {
        parent::__construct(sprintf('Transformation handler \'%s\' identifier failed.', $handlerClass));
    }
}
