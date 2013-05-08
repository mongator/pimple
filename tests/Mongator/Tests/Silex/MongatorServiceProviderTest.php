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

        $this->app = new Application();
        $this->app->register(new MongatorServiceProvider());
    }

    public function testRegister()
    {
        $this->assertInstanceOf('Mongator\Mongator', $this->app['mongator']);
        $this->assertInstanceOf('Mongator\Cache\ArrayCache', $this->app['mongator.data.cache']);
        $this->assertInstanceOf('Mongator\Cache\ArrayCache', $this->app['mongator.field.cache']);
    }
}