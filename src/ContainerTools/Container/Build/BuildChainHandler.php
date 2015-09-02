<?php

namespace ContainerTools\Container\Build;

class BuildChainHandler extends Handler
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