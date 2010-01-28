Feature: User Authentication
  As a visitor of the yoyocase.net site
  I want to log into the system
  So that I can participate in activities related to yoyo collecting

  Background:
    Given a user exists with username: "testUser1", password: "Password1"
    And I am on the home page

  Scenario: Low-level login
    When I follow "login"
    And I fill in "Username" with "testUser1"
    And I fill in "Password" with "Password1"
    And I press "Login"
    Then I should be on my account page
    And I should see "Welcome back, testUser1!"
    And I should see "logout"
    But I should not see "register"
    And I should not see "login"
    And I should not see "site admin"

  Scenario: Logging out
    Given I am logged in with username: "testUser1", password: "Password1"
    When I follow "logout"
    Then I should not see "logout"
    But I should see "login"

  Scenario: Unsuccessful login
    When I follow "login"
    And I login as "testUser1", "badPassword1"
    Then I should not see "logout"
    But I should see "login"
    And I should see "Password is not valid"

