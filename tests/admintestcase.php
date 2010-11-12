<?php

class AdminTestCase extends MY_WebTestCase
{
  function testAdminCanViewOtherAccounts() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testAdmin1', 'AdminPassword1');
    // When
    $this->clickLink('site admin');
    $this->clickLink('user accounts');
    // Then
    $this->assertText('Username Registered on Last login');
    $this->assertNoText('Email');
    $this->assertLink('testAdmin1');
    $this->assertLink('testUser1');
    // TODO: verify registered_on/last_login timestamps
    $this->assertNoText('testAdmin1@somewhere.com');
    $this->assertNoText('testUser1@somewhere.com');

    // When
    $this->get("/admin/userDetail/$userid");
    // Then
    $this->assertText('testUser1@somewhere.com');

    // When
    $this->back();
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1y / 0p)");

    // When
    $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $this->clickLink('(refresh)');
    // Then
    $this->assertText("testUser1 (1y / 1p)");
  }

  function testAdminAccountListIsSortable() {
    // Given
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');

    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('logout');
    sleep(1);

    $this->createUser('anotherUser', 'anotherUser@somewhere.com', 'Password1');
    sleep(1);

    $this->createUser('zUser', 'zUser@somewhere.com', 'Password1');
    $this->logInAs('zUser', 'Password1');
    $this->clickLink('logout');
    sleep(1);
    $this->logInAs('zUser', 'Password1');
    $this->clickLink('logout');

    // When
    $this->logInAs('testAdmin1', 'AdminPassword1');
    $this->clickLink('site admin');
    $this->clickLink('user accounts');

    // When..Then
    $this->clickLink('Username');
    $this->assertPattern('/anotherUser.*testUser1.*zUser/s');

    $this->clickLink('Registered on');
    $this->assertPattern('/zUser.*anotherUser.*testUser1/s');

    $this->clickLink('Last login');
    $this->clickLink('/zUser.*testUser1.*anotherUser/s');
  }

  function testAdminSeesImagesNeedingThumbnails() {
    $this->createAdminUser('testAdmin1', 'testAdmin1@somewhere.com', 'AdminPassword1');
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $photoid = $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $yoyoid2 = $this->createYoyo($userid, 'Throw Monkey');
    $photoid2 = $this->createPhoto($yoyoid2, 'http://elsewhere.com/photo.jpg');
    $this->setMaxThumbnailId($photoid - 1);
    $this->logInAs('testAdmin1', 'AdminPassword1');

    $this->clickLink('site admin');
    $this->clickLink('check thumbnails');

    $this->assertText('ID Username Yoyo URL');
    $this->assertText("$photoid testUser1 Freehand Zero http://somewhere.com/photo.jpg");
    $this->assertText("$photoid2 testUser1 Throw Monkey http://elsewhere.com/photo.jpg");
  }
}

