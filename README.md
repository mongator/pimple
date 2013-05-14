Mongator Silex Provider [![Build Status](https://travis-ci.org/mongator/silex.png?branch=master)](https://travis-ci.org/mongator/silex)
==============================

Provider for using Mongator with Silex framework


Requirements
------------

* PHP 5.3.x
* Unix system
* silex/silex
* mongator/mongator

Installation
------------

The recommended way to install Mongator/Silex is [through composer](http://getcomposer.org).
You can see [the package information on Packagist.](https://packagist.org/packages/mongator/silex)

```JSON
{
    "require": {
        "mongator/silex": "dev-master"
    }
}
```

Parameters
------------

* ```mongator.connection.dsn``` (default 'mongodb://localhost:27017'): database connection string
* ```mongator.connection.database```: the database name
* ```mongator.connection.name``` (default 'default'): the name of the connection 
* ```mongator.metadata.class```: The metadata factory class name 
* ```mongator.logger``` (default null): instance of a logger class
* ```mongator.cache.fields``` (default ArrayCache): instance of a mongator cache driver used in fields cache
* ```mongator.cache.data``` (default ArrayCache): instance of a mongator cache driver used in data cache
* ```mongator.extensions``` (default Array()): array of extension instances 
* ```mongator.models.output```: output path of the classes
* ```mongator.classes.config``` (default Array()): The config classes contain the information of the classes
* ```mongator.classes.yaml.path```: A valid dir with YAML definitions of the config classes


Registrating
------------

```PHP
$app->register(new Mongator\Silex\MondatorServiceProvider())
$app->register(new Mongator\Silex\MongatorServiceProvider(), array(
    'mongator.metadata.class' => 'Model\Mapping\Metadata',
    'mongator.models.output' => 'src/',
    'mongator.connection.database' => 'your_db'
));
```

Usage
------------

```PHP
use Symfony\Component\HttpFoundation\Response;

$app->post('/article', function ($id) use ($app) {
    $articleRepository = $app['mongator']->getRepository('Model\Article');
    $article = $articleRepository->findOneById($id);

    return new Response('', 201);
});
```

** Remember, before using the models you must generate them. (You can use the command provided with this package.) **

Commands
------------
With this package you can find three useful commands:

* ```mongator:generate```: Processes config classes and generates the files of the classes.
* ```mongator:indexes```: Ensures the indexes of all repositories
* ```mongator:fix```: Fixes all the missing references.

You need the suggested package ```cilex/console-service-provider```to use console commands on you Silex setup.

Tests
-----

Tests are in the `tests` folder.
To run them, you need PHPUnit.
Example:

    $ phpunit --configuration phpunit.xml.dist


License
-------

MIT, see [LICENSE](LICENSE)
