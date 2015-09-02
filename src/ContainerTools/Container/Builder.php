<?php

namespace ContainerTools\Container;

use ContainerTools\Configuration;
use ContainerTools\Container\Build\BuildChain;
use ContainerTools\Container\Build\Request;
use Symfony\Component\DependencyInjection\Container;

class Builder
{
    /**
     * @var BuildChain
     */
    private $buildHandler;

    /**
     * @param BuildChain $buildHandler
     */
    public function __construct(BuildChain $buildHandler)
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
