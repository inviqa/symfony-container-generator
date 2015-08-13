<?php

namespace ContainerTools\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;

class Loader 
{
    /**
     * @param string $containerPath
     *
     * @return \ProjectServiceContainer
     */
    public function loadFrom($containerPath)
    {
        include_once $containerPath;

        return new \ProjectServiceContainer();
    }

}
