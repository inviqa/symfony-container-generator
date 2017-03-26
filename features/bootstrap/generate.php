<?php
require_once 'vendor/autoload.php';

use ContainerTools\ContainerGenerator;
use ContainerTools\Configuration;

$configFolders = preg_split('/,/', $argv[1]);
$isDebug = $argv[2] == 'true';
$environment = $argv[3];

$config = Configuration::fromParameters('./container.cache.php', $configFolders, $isDebug, 'xml', $environment);

$container = new ContainerGenerator($config);
$container->getContainer();

include_once 'container.cache.php';
$container = new ProjectServiceContainer();

file_put_contents('serialized.container', serialize($container->getServiceIds()));
