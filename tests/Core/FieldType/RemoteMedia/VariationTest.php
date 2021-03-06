<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Tests\Core\FieldType\RemoteMedia;

use eZ\Publish\Core\FieldType\Value as BaseValue;
use Netgen\Bundle\RemoteMediaBundle\Core\FieldType\RemoteMedia\Variation;
use PHPUnit\Framework\TestCase;

class VariationTest extends TestCase
{
    public function testInstanceOfValue()
    {
        $this->assertInstanceOf(BaseValue::class, new Variation());
    }

    public function testConstructionWithParameters()
    {
        $parameters = [
            'url' => 'test/path/image.jpg',
            'width' => '150',
            'height' => '200',
            'coords' => [
                'x' => 20,
                'y' => 30,
            ],
        ];

        $variation = new Variation($parameters);

        $this->assertEquals(\json_encode($parameters), (string) $variation);
    }

    public function testConstructionWithoutParameters()
    {
        $expectedResponseArray = [
            'url' => null,
            'width' => null,
            'height' => null,
            'coords' => [
                'x' => 0,
                'y' => 0,
            ],
        ];

        $variation = new Variation();

        $this->assertEquals(\json_encode($expectedResponseArray), (string) $variation);
    }
}
