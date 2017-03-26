<?php

namespace spec\ContainerTools;

use ContainerTools\Configuration;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigurationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("container.cache.php", ['features/etc/'], true, 'xml');
    }

    function it_should_set_environment_to_test_when_setTestEnvironment_true_is_called()
    {
        $this->beConstructedThrough('fromParameters', ['container.cache.php', ['features/etc/'], true, 'xml']);
        $this->setTestEnvironment(true);
        $this->getEnvironment()->shouldReturn('test');
    }

    function it_should_not_change_environment_when_setTestEnvironment_false_is_called()
    {
        $this->beConstructedThrough('fromParameters', ['container.cache.php', ['features/etc/'], true, 'xml']);
        $this->setTestEnvironment(false);
        $this->getEnvironment()->shouldReturn('prod');
    }
}
