<?php
/*
 * This file is part of the Skeetr package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mongator\Tests\Silex\Command;
use Cilex\Provider\Console\Adapter\Silex\ConsoleServiceProvider;
use Silex\Application;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $app = new Application;
        $app->register(new ConsoleServiceProvider(array(
            'console.name' => 'MyApp',
            'console.version' => '1.0.5',
        )));

        $this->app = $app;
    }
}
