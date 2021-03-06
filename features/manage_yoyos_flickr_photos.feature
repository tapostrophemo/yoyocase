Feature: fetch and save yoyo photos from flickr
  As a collector of yoyos who has already uploaded photos to flickr
  I want to add photos of my collection from flickr
  So that I can see what my yoyos look like

  Background:
    Given a user exists with username: "testUser1", password: "Password1", flickr_userid: "foobarbaz"

  Scenario: I can see one thumbnail of photo in my flickr photostream
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 1 photo in my photostream
    When I want to add a yoyo
    Then I should see "Link photos from your flickr photostream?"
    And I should see 1 thumbnail

  Scenario: Save urls of photos while adding new yoyo
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 2 photos in my photostream
    When I want to add a yoyo
    And I fill in "Model name" with "FHZ"
    And I check "photo_0"
    And I check "photo_1"
    And I press "Save"
    Then I should see "Yoyo added to collection successfully"
    And I should see image "http://somewhere.com/photo_1_medium.jpg"
    And I should see image "http://somewhere.com/photo_2_medium.jpg"

  Scenario: Save url of photo while editing existing yoyo
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have a yoyo in my collection year: 2004, manufacturer: "Duncan", name: "FHZ"
    And yoyo 0 has no photos
    And I have 1 photo in my photostream
    And I want to update yoyo year: 2004, manufacturer: "Duncan", name: "FHZ"
    When I check "photo_0"
    And I press "Save"
    Then I should see image "http://somewhere.com/photo_1_medium.jpg"

  Scenario: I can see multiple thumbnails of photos from my flickr photostream
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 3 photos in my photostream
    When I want to add a yoyo
    Then I should see 3 thumbnails

  Scenario: Should not display already saved flickr photos
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have a yoyo in my collection year: 2004, manufacturer: "Duncan", name: "FHZ" with photo "http://somewhere.com/photo_3_medium.jpg"
    And I have 3 photos in my photostream
    When I want to update yoyo year: 2004, manufacturer: "Duncan", name: "FHZ"
    Then I should see 2 thumbnails
    And I should not see "photo 3"

  Scenario: Should allow me to remove photos
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have a yoyo in my collection year: 2004, manufacturer: "Duncan", name: "FHZ" with photo "http://somewhere.com/photo_1_medium.jpg"
    When I want to update yoyo year: 2004, manufacturer: "Duncan", name: "FHZ"
    Then I should see image "http://somewhere.com/photo_1_medium.jpg"
    When I follow "remove"
    Then I should not see image "http://somewhere.com/photo_1_medium.jpg"

# TODO: Scenario: I have multiple pages of photos (flickr API has max limit per request)

  Scenario: I have verified my flickr account but have no photos
    Given I am logged in with username: "testUser1", password: "Password1"
    And I have 0 photos in my photostream
    When I want to add a yoyo
    Then I should see "Link photos from your flickr photostream?"
    But I should see 0 thumbnails
    And I should see "You need to add some photos to your flickr photostream"

  Scenario: I have not yet verified my flickr account so flickr integration is not available
    Given a user exists with username: "testUser2", password: "Password2", email: "testUser2@somewhere.com"
    And I am logged in with username: "testUser2", password: "Password2"
    When I want to add a yoyo
    Then I should not see "Link photos from your flickr photostream?"
    And I should see "Verify flickr account"

