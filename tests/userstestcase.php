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

  function testMainUserPage() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    // When
    $this->logInAs('testUser1', 'Password1');
    // Then
    $this->assertLink('your gallery');
    $this->assertLink('all galleries');

    // When
    $this->clickLink('collection');
    // Then
    $this->assertLink('view gallery');
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
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $userid = $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'Password1'"));
    $this->deleteRecord('yoyos', array('model_name' => "'Freehand Zero'"));
    $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("1 yoyo in testUser1's collection");
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
    $this->insertRecord('photos', array('yoyo_id' => $yoyoid, 'url' => "'http://farm3.static.flickr.com/2692/4199629016_6565886579.jpg'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("1 yoyo in testUser1's collection");
    $this->assertText('Freehand Zero');
    $this->assertPattern('/http:\/\/farm5\.static\.flickr\.com\/4043\/4175358221_bc84a52a75\.jpg/');
    $this->assertPattern('/http:\/\/farm3\.static\.flickr\.com\/2692\/4199629016_6565886579\.jpg/');
  }

  function testGalleryStats() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $userid = $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'Password1'"));
    $this->deleteRecord('yoyos', array('model_name' => "'Freehand Zero'"));
    $yoyoid = $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'Freehand Zero'"));
    $this->deleteRecord('yoyos', array('model_name' => "'FHZ'"));
    $yoyoid = $this->insertRecord('yoyos', array('user_id' => $userid, 'model_name' => "'FHZ'"));
    // When
    $this->get(BASE_URL.'/yoyos/testUser1');
    // Then
    $this->assertText("2 yoyos in testUser1's collection");
  }

  function testAccountSettings() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertText('Username');
    $this->assertText('testUser1');
    $this->assertText('Email');
    $this->assertField('email', 'testUser1@somewhere.com');
    $this->assertField('password', '');
    $this->assertField('passconf', '');

    // When
    $this->setField('password', 'asdf');
    $this->setField('passconf', 'asdf');
    $this->setField('email', 'testUser1@elsewhere.com');
    $this->clickSubmit('Update');
    // Then
    $this->assertText('Preferences updated.');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertField('email', 'testUser1@elsewhere.com');

    // When
    $this->clickLink('logout');
    $this->logInAs('testUser1', 'asdf');
    // Then
    $this->assertText('Welcome back!');

    // When
    $this->clickLink('preferences');
    $this->setField('password', 'asdfx');
    $this->setField('passconf', 'asdf');
    $this->clickSubmit('Update');
    // Then
    $this->assertText('The confirm password field does not match the password field.');
  }

  function testPhotoIntegrationSettings() {
//updateRecord($tablename, $field, $value, $criteriaColumn, $criteriaValue)
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->updateRecord('users', 'flickr_userid', "'1234@4321'", 'username', "'testUser1'");
    $this->updateRecord('users', 'photobucket_username', "'ralph'", 'username', "'testUser1'");
    $this->logInAs('testUser1', 'Password1');
    // When
    $this->clickLink('preferences');
    // Then
    $this->assertText('flickr user id: 1234@4321');
    $this->assertText('Photobucket username: ralph');
  }
}

