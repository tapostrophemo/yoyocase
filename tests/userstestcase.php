<?php

class UsersTestCase extends MY_WebTestCase
{
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
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'Password1'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText('User "testUser1" has no yoyos in their collection');
  }

  function testGalleryWithYoyosNoPhotos() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $userid = $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'Password1'"));
    $this->deleteRecord('yoyos', array('model_name' => "'Freehand Zero'"));
    $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText('1 yoyo(s) collected by "testUser1"');
    $this->assertText('Freehand Zero');
    $this->assertPattern('/icon_unknown\.png/');
  }

  function testGalleryWithPhotos() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $userid = $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'Password1'"));
    $this->deleteRecord('yoyos', array('model_name' => "'Freehand Zero'"));
    $yoyoid = $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    $this->insertRecord('photos', array('yoyo_id' => $yoyoid, 'url' => "'http://farm5.static.flickr.com/4043/4175358221_bc84a52a75.jpg'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText('1 yoyo(s) collected by "testUser1"');
    $this->assertText('Freehand Zero');
    $this->assertPattern('/http:\/\/farm5\.static\.flickr\.com\/4043\/4175358221_bc84a52a75\.jpg/');
  }
}
	
