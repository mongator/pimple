<?php
/*
 * This file is part of the Skeetr package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mongator\Tests\Silex;
use Mongator\Silex\MondatorServiceProvider;

use Mongator\Extension\Core;
use Mongator\Extension\DocumentArrayAccess;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class MondatorServiceProviderTest extends TestCase {
    public function setUp()
    {
        if (!class_exists('Mongator\Mongator')) {
            $this->markTestSkipped('Mongator was not installed.');
        }
    }

    public function testRegister()
    {
        $app = new Application();
        $app->register(new MondatorServiceProvider(), array(
            'mongator.metadata.class' => 'Model\Mapping\Metadata',
            'mongator.models.output' => __DIR__ . '/../../../',
            'mongator.extensions' => array(new DocumentArrayAccess())
        ));

        $this->assertInstanceOf('Mandango\Mondator\Mondator', $app['mondator']);

        $extensions = $app['mondator']->getExtensions();
        $this->assertInstanceOf('Mongator\Extension\Core', $extensions[0]);
        $this->assertInstanceOf('Mongator\Extension\DocumentArrayAccess', $extensions[1]);

        $className = 'Model\Article' . uniqid();
        $app['mondator']->setConfigClasses(array(
            $className => array()
        ));

        $app['mondator']->process();
        $this->assertTrue(class_exists($className));
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterMissingOutput()
    {
        $app = new Application();
        $app->register(new MondatorServiceProvider());
        $app['mongator.models.output'] = __DIR__ . '/../../../';
        $app['mondator'];
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterMissingMetadata()
    {
        $app = new Application();
        $app->register(new MondatorServiceProvider());
        $app['mongator.metadata.class'] = 'Model\Mapping\Metadata';
        $app['mondator'];
    }

}