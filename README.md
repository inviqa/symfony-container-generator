# symfony-container-generator
Generates and caches a standalone Symfony DI container.

## Usage
Create a new Configuration object
```php
$generatorConfiguration = ContainerTools\Configuration::fromParameters(
    '/var/cache/container.php',              // name of file for compiled container
    ['/etc/services/', '/project/services'], // where to expect services.xml and services_test.xml
    true,                                    // debug mode - doesn't cache or generate container.php if true
    'xml'                                    // services extension 'xml' or 'yml'
);
Instantiate a new ContainerGenerator:
```

Instantiate a ContainerGenerator, and fetch the container from it:

```php
    $generator = new \ContainerTools\ContainerGenerator($generatorConfiguration);
    
    $container = $generator->getContainer();
    
    $mailer = $container->get('acme.mailer');
```

ContainerGenerator expects at least one services.xml file to exist, and will throw an exception if none are found. If any service_test.xml files exist they are loaded subsequently. Symfonys' configuration loader will merge the configurations and override test service definitions with production ones. (similarly for services.yml if 'yml' is configured)
