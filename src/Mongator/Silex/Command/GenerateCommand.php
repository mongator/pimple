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

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('mongator:generate')
            ->setDescription('Process config classes and generate the files of the classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getContainer();

        $output->write('<info>Generating models... </info>', false);

        if ( isset($app['mongator.classes.yaml.path']) && $app['mongator.classes.yaml.path'] ) {
            if ( !is_dir($path = $app['mongator.classes.yaml.path']) ) {
                throw new \LogicException(
                    'Registered "mongator.classes.yaml.path" not is a valid path.'
                );
            }

            $app['mongator.classes.config'] = $this->readYAMLs($path);
            //var_dump( $app['mongator.classes.config']);
        }

        $app['mondator']->setConfigClasses($app['mongator.classes.config']);
        $app['mondator']->process();

        $output->writeln('<comment>Done</comment>');
    }

    private function readYAMLs($paths)
    {
        if ( !is_array($paths) ) $paths = (array) $paths;

        $defs = array();
        foreach ($paths as $path) {
            foreach ($this->findYAMLs($path . '/*.yaml') as $file) {
                $defs = array_merge($defs, yaml_parse(file_get_contents($file)));
            }
        }

        return $defs;
    }

    private function findYAMLs($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir) {
            $files = array_merge($files, $this->findYAMLs($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }
}
