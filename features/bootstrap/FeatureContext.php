<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use ContainerTools\Configuration;
use ContainerTools\ContainerGenerator;
use Symfony\Component\DependencyInjection\Container;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Container
     */
    private $generatedContainer;

    /**
     * @var string
     */
    private $cachedContainerFile = 'container.cache.php';

    /**
     * @var int
     */
    private $containerStat = 0;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @beforeScenario
     */
    public function clearCachedContainer()
    {
        if (file_exists($this->cachedContainerFile)) {
            unlink($this->cachedContainerFile);
        }
    }

    /**
     * @Given I am in debug mode
     */
    public function iAmInDebugMode()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml');
    }

    /**
     * @When I generate the container
     */
    public function iGenerateTheContainer()
    {
        $container = new ContainerGenerator($this->configuration);
        $this->generatedContainer = $container->getContainer();
    }

    /**
     * @Then I should receive an instance of that container
     */
    public function iShouldReceiveAnInstanceOfThatContainer()
    {
        expect($this->generatedContainer)->toBeAnInstanceOf(Container::class);
    }

    /**
     * @Then it should not be cached in a file
     */
    public function itShouldNotBeCachedInAFile()
    {
        if (file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Cache file %s should not have been created.', $this->cachedContainerFile));
        }
    }

    /**
     * @Given the cached container file does not exist
     */
    public function theCachedContainerFileDoesNotExist()
    {
        if (file_exists($this->cachedContainerFile)) {
            throw new RuntimeException('Cached container file should not exist prior to scenario');
        }
    }

    /**
     * @Given I am not in debug mode
     */
    public function iAmNotInDebugMode()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], false, 'xml');
    }

    /**
     * @Then it should be cached in a file
     */
    public function itShouldBeCachedInAFile()
    {
        if (!file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Cache file %s should have been created.', $this->cachedContainerFile));
        }

        require_once($this->cachedContainerFile);
        $container = new \ProjectServiceContainer();

        expect($container)->toBeAnInstanceOf(Container::class);
    }

    /**
     * @Given the cached container file already exists
     */
    public function theCachedContainerFileAlreadyExists()
    {
        $this->iGenerateTheContainer();

        if (!file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Expected cached container file %s to exist.', $this->cachedContainerFile));
        }

        $this->containerStat = stat($this->cachedContainerFile);
    }

    /**
     * @Then I should reiceve an instance of the existing container
     */
    public function iShouldReiceveAnInstanceOfTheExistingContainer()
    {
        expect($this->generatedContainer)->toBeAnInstanceOf(Container::class);
        expect($this->containerStat)->toBe(stat($this->cachedContainerFile));
    }
}
