@db
Feature: Managing themes

  Background:
    Given I am logged in as an admin

  Scenario: Activate a plugin
    When I switch the theme to "twentysixteen"
    And I am on the dashboard
    Then I should see "twentysixteen" in the "#wp-version a" element
