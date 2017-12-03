Feature: Managing users

  @javascript @db
  Scenario: I can log in as a user which has been added
    Given there are users:
      | user_login | user_pass | user_email       | role |
      | test       | test      | test@example.com | author |
    And I am logged in as test
    When I go to the dashboard
    Then the toolbar should show I am authenticated as test
  
  @javascript @db
  Scenario: I can specify a user which already exists and the test should not fail
    Given there are users:
       | user_login | user_pass | user_email       | role   |
       | test       | test      | test@example.com | author |
    And there are users:
       | user_login | user_pass | user_email       | role   |
       | test       | test      | test@example.com | author |
    And I am logged in as test
    When I go to the dashboard
    Then the toolbar should show I am authenticated as test

  @db
  Scenario: I can delete a user account
    Given there are users:
       | user_login | user_pass | user_email       | role   |
       | test       | test      | test@example.com | author |
    When I delete the "test" user account
    Then I should not be able to log in as "test"
