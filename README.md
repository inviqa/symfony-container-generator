[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/inviqa/symfony-container-generator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/inviqa/symfony-container-generator/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/inviqa/symfony-container-generator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/inviqa/symfony-container-generator/build-status/master)
# symfony-container-generator
Generates and caches a standalone Symfony DI container. You can use this to easily use Symfonys DI Container in any legacy project, all you need to provide is a list of folders where the DI Container configuration is stored, whether you are in debug or production mode, and the format your configuration files are in.

## Usage
Create a new Configuration object
```php
$generatorConfiguration = ContainerTools\Configuration::fromParameters(
    '/var/cache/container.php',              // name of file for compiled container
    ['/etc/services/', '/project/services'], // where to expect services.xml and services_test.xml
    true |                                   // debug mode - caches container.php with meta file and only regenerates when resources are modified
    false,                                   // production mode - caches container.php and doesn't regenerate unless deleted
    'xml'|'yml',                             // services extension 'xml' or 'yml'
    'prod'                                   // optional environment, the default value is 'prod'
);
```

Instantiate a ContainerGenerator, and fetch the container from it:

```php
    $generator = new \ContainerTools\ContainerGenerator($generatorConfiguration);
    
    $container = $generator->getContainer();
    
    $mailer = $container->get('acme.mailer');
```

ContainerGenerator expects at least one services.xml file to exist, and will throw an exception if none are found.

## Environment Services
Sometimes it's necessary, in specific environments, to provide mock services that replace the real services. These can be provided in services_\<environment\>.xml if the environment configuration option was used.

If any service_\<environment\>.xml files exist they will be loaded subsequently. Symfony's configuration loader will merge the configurations and override environment service definitions with production ones. (similarly for services.yml if 'yml' is configured)

As of version 0.3.0 ContainerGenerator uses the ConfigCache component, which keeps track of resources and regenerated the cached container only when a resource is modified. This means that if debug mode is enabled, a file called `/var/cache/container.php.meta` will be generated in the same folder as the cached container.
