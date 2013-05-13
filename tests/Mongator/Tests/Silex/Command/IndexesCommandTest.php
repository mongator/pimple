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
use Symfony\Component\Console\Tester\CommandTester;
use Mongator\Mongator;
use Mongator\Silex\Command\IndexesCommand;

class IndexesCommandTest extends TestCase
{
    public function createMongatorMock()
    {
        $mongator = $this->getMockBuilder('Mongator\Mongator')
            ->disableOriginalConstructor()
            ->getMock();

        $mongator->expects($this->once())->method('ensureAllIndexes');

        $this->app['mongator'] = $mongator;
    }

    public function testExecute()
    {
        $this->createMongatorMock();

        $this->app['console']->add(new IndexesCommand());

        $command = $this->app['console']->find('mongator:indexes');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertSame("Ensuring Indexes... Done\n", $commandTester->getDisplay());
    }
}