<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use ContainerTools\Configuration;
use ContainerTools\ContainerGenerator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
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
     * @var Exception
     */
    private $generatorException;

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

        if (file_exists('features/etc/services_test.xml')) {
            unlink('features/etc/services_test.xml');
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

        try {
            $this->generatedContainer = $container->getContainer();
        } catch (Exception $e) {
            $this->generatorException = $e;
        }
    }

    /**
     * @Then I should receive an instance of that container
     */
    public function iShouldReceiveAnInstanceOfThatContainer()
    {
        expect($this->generatedContainer)->toBeAnInstanceOf(ContainerBuilder::class);
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
        copy('features/dummy/container.cache.php', 'container.cache.php');

        if (!file_exists($this->cachedContainerFile)) {
            throw new RuntimeException(sprintf('Expected cached container file %s to exist.', $this->cachedContainerFile));
        }

        $this->containerStat = stat($this->cachedContainerFile);
    }

    /**
     * @Then I should receive an instance of the existing container
     */
    public function iShouldReceiveAnInstanceOfTheExistingContainer()
    {
        expect($this->generatedContainer)->toBeAnInstanceOf(Container::class);
        expect($this->containerStat)->toBe(stat($this->cachedContainerFile));
    }

    /**
     * @Given I have test services
     */
    public function iHaveTestServices()
    {
        copy('features/dummy/services_test.xml', 'features/etc/services_test.xml');
    }

    /**
     * @Given the test environment is set
     */
    public function theTestEnvironemtnIsSet()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml');

        $this->configuration->setTestEnvironment(true);
    }

    /**
     * @Then the test services should be available
     */
    public function theTestServicesShouldBeAvailable()
    {
        expect($this->generatedContainer->has('test_service'))->toBe(true);
    }

    /**
     * @Given I have do not have test services
     */
    public function iHaveDoNotHaveTestServices()
    {
        expect(file_exists('features/etc/services_test.xml'))->toBe(false);
    }

    /**
     * @Then an exception should be thrown
     */
    public function anExceptionShouldBeThrown()
    {
        expect($this->generatorException)->toBeAnInstanceOf(Exception::class);
    }

    /**
     * @Given the test environment is not set
     */
    public function theTestEnvironmentIsNotSet()
    {
        $this->configuration = Configuration::fromParameters($this->cachedContainerFile, ['features/etc/'], true, 'xml');

        $this->configuration->setTestEnvironment(false);
    }

    /**
     * @Then the test services should not be available
     */
    public function theTestServicesShouldNotBeAvailable()
    {
        expect($this->generatedContainer->has('test_service'))->toBe(false);

    }
}
