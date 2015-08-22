Feature: Loading Multiple Service Files


  Scenario: Loading more that one service file with the same name
    Given I have configured different services in both "etc/" and "etc2/"
    When I generate the container
    Then it should contain services from both files