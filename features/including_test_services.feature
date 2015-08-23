Feature: Including test services

  Scenario: Including test services when test environment is set
    Given I have test services
    And the test environment is set
    When I generate the container
    Then the test services should be available

  Scenario: Not including test services when test environment is not set
    Given I have test services
    And the test environment is not set
    When I generate the container
    Then the test services should not be available

