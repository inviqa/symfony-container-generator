# symfony-container-generator
Generates and caches a standalone Symfony DI container

## Usage
```php
        $generator = new ContainerGenerator(
            '/var/cache/container.php',
            ['/etc/services/', '/project/services'],
            $debug = true
        );

        $container = $generator->getContainer();
        
        $mailer = $container->get('acme.mailer');
```
