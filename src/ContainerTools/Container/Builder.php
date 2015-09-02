<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Container\Build\BuildChainHandler;
use ContainerTools\Container\Build\BuildHandler;
use ContainerTools\Container\Build\ContainerAlreadyBuiltHandler;
use ContainerTools\Container\Build\DebugModeHandler;
use ContainerTools\Container\Build\RebuildContainerHandler;
use ContainerTools\Container\Build\Request;
use Symfony\Component\DependencyInjection\Container;

class Builder
{
    /**
     * @var BuildHandler
     */
    private $buildHandler;

    /**
     * @param BuildChainHandler $buildHandler
     */
    public function __construct(BuildChainHandler $buildHandler)
    {
        $this->buildHandler = $buildHandler;
    }

    /**
     * @param Configuration $configuration
     *
     * @return Container
     */
    public function build(Configuration $configuration)
    {
        $containerRequest = new Request($configuration);

        $this->buildHandler->process($containerRequest);

        return $containerRequest->getContainer();
    }

}
