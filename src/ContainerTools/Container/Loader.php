<?php

namespace ContainerTools\Container;

use ProjectServiceContainer;

class Loader 
{
    /**
     * @param string $containerPath
     *
     * @return ProjectServiceContainer
     */
    public function loadFrom($containerPath)
    {
        include_once $containerPath;

        return new ProjectServiceContainer();
    }

}
