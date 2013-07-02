<?php
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Mongator\Tests', __DIR__);
$loader->add('Model', __DIR__);

use Mandango\Mondator\Mondator;

$mondator = new Mondator();
$mondator->setConfigClasses(array());
$mondator->setExtensions(array(
    new Mongator\Extension\Core(array(
        'metadata_factory_class'  => 'Model\Mapping\Metadata',
        'metadata_factory_output' => __DIR__,
        'default_output'          => __DIR__,
    ))
));

$mondator->process();
