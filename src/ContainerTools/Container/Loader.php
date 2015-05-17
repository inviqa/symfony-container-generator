<?php

namespace ContainerTools\Container;

class Loader 
{
    /**
     * @param string $containerPath
     *
     * @return \ProjectServiceContainer
     */
    public function requireOnce($containerPath)
    {
        require_once $containerPath;

        return new \ProjectServiceContainer();
    }
}
