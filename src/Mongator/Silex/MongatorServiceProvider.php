<?php
/*
 * This file is part of the Mongator package.
 *
 * (c) Máximo Cuadros <maximo@yunait.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mongator\Silex;
use Silex\Application;
use Silex\ServiceProviderInterface;

use Mongator\Mongator;
use Mongator\Connection;
use Mongator\Cache\ArrayCache;

class MongatorServiceProvider implements ServiceProviderInterface {
    public function register(Application $app) {

        $app['mongator'] = $app->share(function($app) {
            $mongator = new Mongator(
                $app['mongator.metadata'], 
                $app['mongator.logger']            
            );

            $mongator->setFieldsCache($app['mongator.cache.fields']);
            $mongator->setDataCache($app['mongator.cache.data']);
            $mongator->setConnection($app['mongator.connection.name'], $app['mandango.connection']);    
            return $mongator;
        });

        $app['mongator.metadata'] = $app->share(function($app) {
            if ( !class_exists($app['mongator.metadata.class']) ) {
                throw new \LogicException(
                    'You must register a valid "mongator.metadata.class" to this provider, maybe you forget to generate your models?'
                );
            }

            return new $app['mongator.metadata.class']();
        });


        $app['mandango.connection'] = $app->share(function($app) {
            if ( !$app['mongator.connection.dsn'] ) {
                throw new \LogicException(
                    'You must register "mongator.connection.dsn" to this provider'
                );
            }

            if ( !$app['mongator.connection.database'] ) {
                throw new \LogicException(
                    'You must register "mongator.connection.database" to this provider'
                );
            }

            return new Connection(
                $app['mongator.connection.dsn'], 
                $app['mongator.connection.database']
            );
        });

        $app['mongator.cache.fields'] = $app->share(function($app) {
            return new ArrayCache();
        });

        $app['mongator.cache.data'] = $app->share(function($app) {
            return new ArrayCache();
        });

        $app['mongator.metadata.class'] = null;
        $app['mongator.logger'] = null;

        $app['mongator.connection.name'] = 'default';
        $app['mongator.connection.dsn'] = 'mongodb://localhost:27017';
        $app['mongator.connection.database'] = null;
    }

    public function boot(Application $app) {

    }
}