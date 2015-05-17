# symfony-container-generator
Generates and caches a standalone Symfony DI container

## Usage

Instantiate a new ContainerGenerator with these arguments:
- Name of php file for storing compildes container (e.g. container.cache.php)
- Array of folders where services.xml files are stored
- Debug/Development: When set to true, container.php is not generated or cached

```php
        $generator = new \ContainerTools\ContainerGenerator(
            '/var/cache/container.php',
            ['/etc/services/', '/project/services'],
            $debug = true
        );

        $container = $generator->getContainer();
        
        $mailer = $container->get('acme.mailer');
```
