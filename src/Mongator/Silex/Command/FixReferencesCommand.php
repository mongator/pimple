<?php
/*
 * This file is part of the Mongator package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Mongator\Silex\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixReferencesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('mongator:fix')
            ->setDescription('Fixes all the missing references.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getContainer();

        $output->write('<info>Fixing missing References... </info>', false);        
        $app['mongator']->fixAllMissingReferences();

        $output->writeln('<comment>Done</comment>');
    }
}