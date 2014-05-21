<?php
/*
 * This file is part of the Skeetr package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mongator\Tests\Pimple\Command;
use Cilex\Provider\Console\ConsoleServiceProvider;
use Pimple\Container;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $app = new Container;
        $app->register(new ConsoleServiceProvider);

        $app['console.name'] = 'MyApp';
        $app['console.version'] = '1.0.5';


        $this->app = $app;
    }
}
