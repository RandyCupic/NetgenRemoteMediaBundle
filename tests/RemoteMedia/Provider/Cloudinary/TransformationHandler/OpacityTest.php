<?php

declare(strict_types=1);

namespace Netgen\Bundle\RemoteMediaBundle\Tests\RemoteMedia\Provider\Cloudinary\TransformationHandler;

use Netgen\Bundle\RemoteMediaBundle\Exception\TransformationHandlerFailedException;
use Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler\Opacity;

class OpacityTest extends BaseTest
{
    /**
     * @var \Netgen\Bundle\RemoteMediaBundle\RemoteMedia\Provider\Cloudinary\TransformationHandler\Opacity
     */
    protected $opacity;

    public function setUp()
    {
        parent::setUp();
        $this->opacity = new Opacity();
    }

    public function testOpacitySimple()
    {
        $this->assertEquals(
            ['opacity' => 80],
            $this->opacity->process($this->value, 'test', [80])
        );
    }

    public function testMissingNamedTransformationConfiguration()
    {
        $this->expectException(TransformationHandlerFailedException::class);

        $this->opacity->process($this->value, 'test');
    }
}
