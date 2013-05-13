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
use Mandango\Mondator\Mondator;
use Mongator\Silex\Command\GenerateCommand;

class GenerateCommandTest extends TestCase
{
    public function createMondatorMock()
    {
        $mondator = $this->getMock('Mandango\Mondator\Mondator');
        $mondator->expects($this->once())->method('process');
        $mondator->expects($this->once())
                 ->method('setConfigClasses')
                 ->with($this->equalTo($this->app['mongator.classes.config']));

        $this->app['mondator'] = $mondator;
    }

    public function testExecute()
    {
        $this->app['mongator.classes.config'] = array('foo' => 'bar');
        $this->createMondatorMock();

        $this->app['console']->add(new GenerateCommand());

        $command = $this->app['console']->find('mongator:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertSame("Generating models... Done\n", $commandTester->getDisplay());
    }

    public function testExecuteWithYaml()
    {
        $this->app['mongator.classes.config'] = array(
            'Model\Image' => array(
                'fields' => array('status' => 'string')
            )
        );
        
        $this->app['mongator.classes.yaml.path'] = __DIR__ . '/../../../../Resources/';
        $this->createMondatorMock();

        $this->app['console']->add(new GenerateCommand());

        $command = $this->app['console']->find('mongator:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertSame("Generating models... Done\n", $commandTester->getDisplay());
    }
}