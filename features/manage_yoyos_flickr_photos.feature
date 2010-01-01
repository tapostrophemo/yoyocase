Feature: fetch and save yoyo photos from flickr
  As a collector of yoyos who has already uploaded photos to flickr
  I want to add photos of my collection from flickr
  So that I can see what my yoyos look like

  Background:
    Given a user exists with username: "testUser1", password: "Password1", flickr_userid: "foobarbaz"

  Scenario: I can see thumbnails from specially tagged photos from my flickr account
    Given I am logged in with username: "testUser1", password: "Password1"
    When I want to add a yoyo
    Then I should see "Link photos tagged yoyocase from your flickr account?"

# TODO: test that thumbnails displayed

  Scenario: I have not yet verified my flicrk account and cannot see thumbnails
    Given a user exists with username: "testUser2", password: "Password2", email: "testUser2@somewhere.com"
    And I am logged in with username: "testUser2", password: "Password2"
    When I want to add a yoyo
    Then I should not see "Link photos tagged yoyocase from your flickr account?"

