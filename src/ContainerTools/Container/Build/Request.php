<?php

namespace ContainerTools\Container\Build;

use ContainerTools\Configuration;
use Symfony\Component\DependencyInjection\Container;

class Request
{
    private $container = null;
    /**
     * @var
     */
    private $configuration;

    /**
     * Request constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param null $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }


}