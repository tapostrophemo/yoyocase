@admin
Feature: Administrator Authentication
  As an admin of the yoyocase.net site
  I want to log into the system
  So that I can perform administrative activities related to the site

  Background:
    Given a user exists with username: "testAdmin1", password: "Password1", is_admin: true
    And I am on the home page

  Scenario: Low-level admin login
    When I follow "login"
    And I fill in "Username" with "testAdmin1"
    And I fill in "Password" with "Password1"
    And I press "Login"
    Then I should see "site admin"
    And I should see "logout"
    But I should not see "register"
    And I should not see "login"

