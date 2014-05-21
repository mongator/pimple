<?php
/*
 * This file is part of the Mongator package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mongator\Pimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mandango\Mondator\Mondator;
use Mongator\Extension\Core;

class MondatorServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['mondator'] = function($app) {
            if (!$app['mongator.models.output']) {
                throw new \LogicException(
                    'You must register "mongator.models.output" to this provider'
                );
            }

            if (!$app['mongator.metadata.class']) {
                throw new \LogicException(
                    'You must register "mongator.metadata.class" to this provider'
                );
            }

            $extensions = array(
                new Core(array(
                    'metadata_factory_class' => $app['mongator.metadata.class'],
                    'metadata_factory_output' => $app['mongator.models.output'],
                    'default_output' => $app['mongator.models.output'],
                ))
            );

            $mondator = new Mondator();
            $mondator->setExtensions(array_merge(
                $extensions,
                $app['mongator.extensions']
            ));

            return $mondator;
        };

        $app['mongator.extensions'] = array();
        $app['mongator.models.output'] = null;
    }
}
