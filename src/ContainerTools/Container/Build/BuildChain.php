<?php

namespace ContainerTools\Container\Build;

use ContainerTools\Container\Build\Handler\ContainerAlreadyBuiltHandler;
use ContainerTools\Container\Build\Handler\DebugModeHandler;
use ContainerTools\Container\Build\Handler\RebuildContainerHandler;

class BuildChain extends Handler
{
    /**
     * @param DebugModeHandler $debugModeHandler
     * @param ContainerAlreadyBuiltHandler $alreadyBuiltHandler
     * @param RebuildContainerHandler $rebuildContainerHandler
     */
    public function __construct(
        DebugModeHandler $debugModeHandler,
        ContainerAlreadyBuiltHandler $alreadyBuiltHandler,
        RebuildContainerHandler $rebuildContainerHandler
    ) {
        $this->next = $debugModeHandler;
        $debugModeHandler->next = $alreadyBuiltHandler;
        $alreadyBuiltHandler->next = $rebuildContainerHandler;
    }

    public function process(Request $request)
    {
        $this->next->process($request);
    }
}
