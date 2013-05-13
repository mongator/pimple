Mongator Silex Provider [![Build Status](https://travis-ci.org/mongator/silex.png?branch=master)](https://travis-ci.org/mongator/silex)
==============================

Provider to use Mongator with Silex framework


Requirements
------------

* PHP 5.3.x
* Unix system
* silex/silex
* mongator/mongator

Installation
------------

The recommended way to install Mongator/Silex is [through composer](http://getcomposer.org).
You can see [package information on Packagist.](https://packagist.org/packages/mongator/silex)

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


Usage
------------
...

Tests
-----

Tests are in the `tests` folder.
To run them, you need PHPUnit.
Example:

    $ phpunit --configuration phpunit.xml.dist


License
-------

MIT, see [LICENSE](LICENSE)