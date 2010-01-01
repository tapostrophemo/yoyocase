Feature: fetch and save yoyo photos from flickr
  As a collector of yoyos who has already uploaded photos to flickr
  I want to add photos of my collection from flickr
  So that I can see what my yoyos look like

  Background:
    Given a user exists with username: "testUser1", password: "Password1", flickr_userid: "foobarbaz"

  Scenario: I can see one thumbnail from specially tagged photo in my flickr photostream
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 1 photo in my photostream
    When I want to add a yoyo
    Then I should see "Link photos tagged yoyocase from your flickr photostream?"
    And I should see 1 thumbnail

  Scenario: I can see multiple thumbnails from specially tagged photos from my flickr photostream
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 3 photos in my photostream
    When I want to add a yoyo
    Then I should see 3 thumbnails

# TODO: Scenario: I have multiple pages of tagged photos (flickr API has max limit per request)

  Scenario: I have verified my flickr account but have not tagged any photos with "yoyocase"
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 0 photos in my photostream
    When I want to add a yoyo
    Then I should see "Link photos tagged yoyocase from your flickr photostream?"
    But I should see 0 thumbnails
    And I should see "You need to tag some photos in your flickr photostream with yoyocase"

  Scenario: I have not yet verified my flickr account so flickr integration is not available
    Given a user exists with username: "testUser2", password: "Password2", email: "testUser2@somewhere.com"
    And I am logged in with username: "testUser2", password: "Password2"
    When I want to add a yoyo
    Then I should not see "Link photos tagged yoyocase from your flickr photostream?"
    And I should see "Verify flickr account"

