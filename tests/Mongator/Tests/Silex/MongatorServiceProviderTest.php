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
use Mongator\Silex\MongatorServiceProvider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class MongatorServiceProviderTest extends TestCase {
    public function setUp()
    {
        if (!class_exists('Mongator\Mongator')) {
            $this->markTestSkipped('Mongator was not installed.');
        }
    }

    public function testRegister()
    {
        $app = new Application();
        $app->register(new MongatorServiceProvider());

        $app['mongator.metadata.class'] = 'Model\Mapping\Metadata';
        $app['mongator.connection.database'] = 'mongator';

        $this->assertInstanceOf('Mongator\Mongator', $app['mongator']);
        $this->assertInstanceOf('Mongator\Cache\ArrayCache', $app['mongator.cache.data']);
        $this->assertInstanceOf('Mongator\Cache\ArrayCache', $app['mongator.cache.fields']);

        $this->assertInstanceOf('Model\Mapping\Metadata', $app['mongator']->getMetadataFactory());
        $this->assertSame('mongator', $app['mongator']->getConnection('default')->getDbName());
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterMissingMetadata()
    {
        $app = new Application();
        $app->register(new MongatorServiceProvider());
        $app['mongator.connection.database'] = 'mongator';
        $app['mongator'];
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterInvalidMetadata()
    {
        $app = new Application();
        $app->register(new MongatorServiceProvider());
        $app['mongator.connection.database'] = 'mongator';
        $app['mongator.metadata.class'] = 'Missing\Mapping\Metadata';
        $app['mongator'];
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterMissingDatabase()
    {
        $app = new Application();
        $app->register(new MongatorServiceProvider());
        $app['mongator.metadata.class'] = 'Model\Mapping\Metadata';
        $app['mongator'];
    }

    /**
     * @expectedException LogicException
     */
    public function testRegisterMissingDSN()
    {
        $app = new Application();
        $app->register(new MongatorServiceProvider());
        $app['mongator.connection.database'] = 'mongator';
        $app['mongator.metadata.class'] = 'Model\Mapping\Metadata';

        $app['mongator.connection.dsn'] = null;
        $app['mongator'];
    }
}