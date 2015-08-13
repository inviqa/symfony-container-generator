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
        include_once $containerPath;

        return new \ProjectServiceContainer();
    }
}
