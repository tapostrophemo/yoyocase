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
    $this->assertLink('View gallery');
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

  function testAccountSettings() {
    // Given
    $this->deleteRecord('users', array('username' => "'testUser1'"));
    $pass = sha1('Password1');
    $this->insertRecord('users', array('username' => "'testUser1'", 'email' => "'testUser1@somewhere.com'", 'crypted_password' => "'$pass'"));
    $this->get(BASE_URL.'/login');
    $this->setField('username', 'testUser1');
    $this->setField('password', 'Password1');
    $this->clickSubmit('Login');
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
    $this->clickLink('login');
    $this->setField('username', 'testUser1');
    $this->setField('password', 'asdf');
    $this->clickSubmit('Login');
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
}

