<?php
namespace ContainerTools\Container\Build;

abstract class Handler
{
    protected $next = null;

    public function setNext(Handler $handler)
    {
        $this->next = $handler;
    }

    abstract public function process(Request $request);
}