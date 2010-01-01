Feature: Manage Yo-yo Collection
  In order to manage my yoyo collection
  Registered users will need to be able to add, edit, and sometimes delete yoyos

  Background:
    Given a user exists with username: "testUser1", password: "Password1"

  Scenario: Yoyo collection starts out empty
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have no yoyos
    When I follow "collection"
    Then I should see "You don't have any yoyos in your collection."
    And I should see "Add one?"

  Scenario: Add first yoyo to collection
    Given I am logged in with username: "testUser1", password: "Password1"
    And I follow "collection"
    And I follow "Add one?"
    When I fill in "Manufacturer" with "Duncan"
    And I fill in "Country" with "China"
    And I fill in "Model year" with "2005"
    And I fill in "Model name" with "FHZ"
    And I press "Save"
    Then I should see "Yoyo added to collection successfully"
    And I should have 1 yoyo in my collection
    And I should see "2005 Duncan FHZ"
    And I should see "Add another?"
    And I should see "Collection facts:"
    And I should see "1 yoyo(s) total"

  Scenario: View yoyo details
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have a yoyo in my collection year: 2004, manufacturer: "Duncan", name: "FHZ"
    And I follow "collection"
    When I follow "2004 Duncan FHZ"
    Then I should see "Manufacturer"
    And I should see "Duncan"
	And I should see "Model year"
    And I should see "2004"
	And I should see "Model name"
    And I should see "FHZ"

  Scenario: Edit yoyo details
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have a yoyo in my collection year: 2003, manufacturer: "Nacnud", name: "one foot throw"
    And I follow "collection"
    And I follow "2003 Nacnud one foot throw"
    When I press "Edit"
    And I fill in "Manufacturer" with "Duncan"
    And I fill in "Model year" with "2004"
    And I fill in "Model name" with "Freehand Zero"
    And I press "Save"
    Then I should see "Yoyo saved successfully"
    And I should see "2004 Duncan Freehand Zero"
    And I should have 1 yoyo in my collection

