Feature: Including environment-specific services

  Scenario: Including environment services
    Given I have "test" environment services
    And the environment is set to "test"
    When I generate the container
    Then the "test" services should be available
