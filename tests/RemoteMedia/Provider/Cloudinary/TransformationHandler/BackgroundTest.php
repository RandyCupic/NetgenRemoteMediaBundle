<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Tests\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Exception\TransformationHandlerFailedException;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler\Background;

class BackgroundTest extends BaseTest
{
    /**
     * @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler\Background
     */
    protected $background;

    public function setUp()
    {
        parent::setUp();
        $this->background = new Background();
    }

    public function testBackgroundSimple()
    {
        $this->assertEquals(
            ['background' => 'black'],
            $this->quality->process($this->value, 'test', ['black'])
        );
    }

    public function testBackgroundWithRgbTypeHexTriplet()
    {
        $this->assertEquals(
            [
                'background' => 'rgb:3e2222',
            ],
            $this->quality->process($this->value, 'test', ['rgb', '3e2222'])
        );
    }

    public function testBackgroundWithRgbType3digitHex()
    {
        $this->assertEquals(
            [
                'background' => 'rgb:777',
            ],
            $this->quality->process($this->value, 'test', ['rgb', '777'])
        );
    }

    public function testBackgroundWithRgbTypeHexTripletHash()
    {
        $this->assertEquals(
            [
                'background' => 'rgb:#3e2222',
            ],
            $this->quality->process($this->value, 'test', ['rgb', '#3e2222'])
        );
    }

    public function testBackgroundWithRgbTypeRgba()
    {
        $this->assertEquals(
            [
                'background' => 'rgb:3e222240',
            ],
            $this->quality->process($this->value, 'test', ['rgb', '3e222240'])
        );
    }

    public function testBackgroundWithNonRgbType()
    {
        $this->expectException(TransformationHandlerFailedException::class);

        $this->quality->process($this->value, 'test', ['test', '#3e2222']);
    }

    public function testMissingNamedTransformationConfiguration()
    {
        $this->expectException(TransformationHandlerFailedException::class);

        $this->quality->process($this->value, 'test');
    }
}
