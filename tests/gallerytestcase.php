<?php

class GalleryTestCase extends MY_WebTestCase
{
  function testGalleryStats() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->createYoyo($userid, 'Freehand Zero');
    $this->createYoyo($userid, 'FHZ');
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("2 yoyos in testUser1's collection");
  }

  function testViewUserListForGalleries() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    // When
    $this->get(BASE_URL.'/galleries');
    // Then
    $this->assertLink('testUser1');
    $this->assertText("testUser1 (1 yo's, 1 pics)");
  }

  function testGalleryForUnknownUser() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText('User "testUser1" not found');
  }

  function testGalleryForRegisteredUserWithNoPhotos() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText('User "testUser1" has no yoyos in their collection');
  }

  function testGalleryWithYoyosNoPhotos() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->createYoyo($userid, 'Freehand Zero');
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("1 yoyo in testUser1's collection");
    $this->assertText('Freehand Zero');
    $this->assertPattern('/icon_unknown\.png/');
  }

  function testGalleryWithPhotos() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $this->createPhoto($yoyoid, 'http://farm5.static.flickr.com/4043/4175358221_bc84a52a75.jpg');
    $this->createPhoto($yoyoid, 'http://farm3.static.flickr.com/2692/4199629016_6565886579.jpg');
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("1 yoyo in testUser1's collection");
    $this->assertText('Freehand Zero');
    $this->assertPattern('/http:\/\/farm5\.static\.flickr\.com\/4043\/4175358221_bc84a52a75\.jpg/');
    $this->assertPattern('/http:\/\/farm3\.static\.flickr\.com\/2692\/4199629016_6565886579\.jpg/');
  }

  function testViewGalleryLinksShouldHaveUrlEncodedLinks() {
    // Given
    $this->deleteRecord('users', array('username' => "'drumma/yoyo'"));
    $this->get(BASE_URL.'/register');
    // When
    $this->setField('username', 'drumma/yoyo');
    $this->setField('password', 'Password1');
    $this->setField('email', 'testUserDY@somewhere.com');
    $this->clickSubmit('Register');
    // Then
    $this->assertText('Welcome, drumma/yoyo!');

    // When
    $this->clickLink('collection');
    // Then
    $this->assertPattern('/\/yoyos\/drumma%2Fyoyo/');

    // When
    $this->clickLink('yoyocase.net');
    $this->clickLink('all galleries');
    // Then
    $this->assertPattern('/\/yoyos\/drumma%2Fyoyo/');
  }
}

