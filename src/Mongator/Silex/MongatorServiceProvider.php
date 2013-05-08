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
use Mongator\Cache\ArrayCache;

class MongatorServiceProvider implements ServiceProviderInterface {
    public function register(Application $app) {
        $app['mongator.logger'] = $app->share(function($app) {
            if ( !$app['mongator.logger.enable'] ) return null;

            $querys = 0;
            return function($call) use ($app, &$querys) {
                if ( $app->config('mongator')->slow && 
                    $app->config('mongator')->slow > $call['time']
                ) {
                     return;
                }

                $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)[8];
                $msg = sprintf('[%d] %s->%s:%d %s@%s.%s in %d sec(s)', 
                    ++$querys, 
                    isset($caller['class']) ? $caller['class'] : 'NULL',
                    $caller['function'], isset($caller['line']) ? $caller['line'] : 0,
                    $call['type'], $call['database'], 
                    isset($call['collection']) ? $call['collection'] : '[global]', $call['time']
                );

                $context = [];
                if ( $app->config('mongator')->verbose > 0 ) $context = &$call;
                if ( $app->config('mongator')->verbose > 1 ) {
                    $msg .= PHP_EOL . var_export(
                        debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 
                        true
                    );
                }

                if ( $app->config('mongator')->slow ) {
                    $msg .= PHP_EOL . json_encode($call, JSON_PRETTY_PRINT);
                    $context = [];
                }

                $app['monolog.mongator']->addDebug($msg, $context);
            };
        });

        $app['mongator'] = $app->share(function($app) {
            $mongator = new Mongator(
                $app['mongator.metadata'], 
                $app['mongator.logger']            
            );

            $mongator->setFieldCache($app['mongator.field.cache']);
            $mongator->setDataCache($app['mongator.data.cache']);

            return $mongator;
        });

        $app['mongator.metadata'] = $app->share(function($app) {
            if ( !class_exists($app['mongator.metadata.class']) ) {
                throw new \LogicException(
                    'You must register "mongator.metadata.class" to you this provider'
                );
            }
            return new $app['mongator.metadata.class']();
        });

        $app['mongator.field.cache'] = $app->share(function($app) {
            return new ArrayCache();
        });

        $app['mongator.data.cache'] = $app->share(function($app) {
            return new ArrayCache();
        });

        $app['mongator.metadata.class'] = null;
        $app['mongator.logger.enable'] = false;
    }

    public function boot(Application $app) {

    }
}