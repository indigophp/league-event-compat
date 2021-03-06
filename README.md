# League Event Compatibility

[![Latest Version](https://img.shields.io/github/release/indigophp/league-event-compat.svg?style=flat-square)](https://github.com/indigophp/league-event-compat/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/league-event-compat/develop.svg?style=flat-square)](https://travis-ci.org/indigophp/league-event-compat)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/league-event-compat.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/league-event-compat)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/league-event-compat.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/league-event-compat)
[![HHVM Status](https://img.shields.io/hhvm/indigophp/league-event-compat.svg?style=flat-square)](http://hhvm.h4cc.de/package/indigophp/league-event-compat)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/league-event-compat.svg?style=flat-square)](https://packagist.org/packages/indigophp/league-event-compat)

**League Event compatibility layer for Symfony EventDispatcher.**


## Install

Via Composer

``` bash
$ composer require indigophp/league-event-compat
```

## Usage

This package provides a wrapper for League Event by implementing `EventDispatcherInterface` to replace Symfony EventDispatcher in your application. This way you can use your custom Domain Events and Listeners in packages which lock Symfony EventDispatcher in.

``` php
use League\Event\Compat\Symfony\EventDispatcher;

// You can optionally pass an EmitterInterface in the constructor
$eventDispatcher = new EventDispatcher;

// use $eventDispatcher as you would use the symfony one

// You can add your custom listeners which will receive an object implementing EventInterface
$eventDispatcher->getEmitter()->addListener(...);

// DON'T EMIT EVENTS MANUALLY WHICH HAVE SYMFONY LISTENERS
$eventDispatcher->getEmitter()->emit('symfony_event');
```


## Testing

``` bash
$ phpspec run
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/league-event-compat/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
