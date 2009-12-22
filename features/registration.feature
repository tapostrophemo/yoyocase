Feature: Account Registration
  As a visitor of the yoyocase.net site
  I want to register for an account
  So that I can participate in activities related to yoyo collecting

  Background:
    Given I am on the home page

  Scenario: Register a new user
    Given I have no users
    When I follow "register"
    And I fill in "Username" with "testUser1"
    And I fill in "Password" with "Password1"
    And I fill in "Email" with "testUser1@somewhere.com"
    And I press "Register"
    Then a user should exist with username: "testUser1", email: "testUser1@somewhere.com"
    And I should be logged in
    And I should see "Welcome, testUser1!"

  Scenario: Attempt to register with existing username fails
    Given a user exists with username: "testUser1", password: "Password1"
    When I register with username: "testUser1", password: "anotherPassword", email: "another@email.com"
    Then I should not be logged in
    And I should see "Username has already been taken"

  Scenario: Registration page shows fun site facts
    Given I have no users
    When I follow "register"
    Then I should see "yoyocase.net facts:"
    And I should see "0 accounts total"

  Scenario: Fun site facts, phase 2
    Given a user exists with username: "testUser1", password: "Password1"
    When I follow "register"
    Then I should see "1 accounts total"

