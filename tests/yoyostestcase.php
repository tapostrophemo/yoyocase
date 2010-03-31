<?php

class YoyosTestCase extends MY_WebTestCase
{
  function testCollectionStartsOutEmpty() {
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');

    $this->clickLink('collection');

    $this->assertText("You don't have any yoyos in your collection.");
    $this->assertLink("Add one?");
  }

  function testYoyoNameRequired() {
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');

    $this->clickSubmit('Save');

    $this->assertText('The model name field is required.');
  }

  function testShouldNotDefaultYearToZero() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');
    $this->setField('manufacturer', 'Duncan');
    $this->setField('model_name', 'FHZ');
    // When
    $this->clickSubmit('Save');
    // Then
    $this->assertNoText('0 Duncan FHZ');
  }

  function testAddFirstYoyoToCollection() {
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');

    // scenario: create first yoyo
    $this->setField('manufacturer', 'Duncan');
    $this->setField('country', 'China');
    $this->setField('model_year', '2005');
    $this->setField('model_name', 'FHZ');
    $this->assertField('notes', '');
    $this->setField('notes', 'I dyed this one myself');
    $this->clickSubmit('Save');

    $this->assertText('Yoyo added to collection successfully');
    $this->assertText('2005 Duncan FHZ');

    $this->clickLink('collection');
    $this->assertLink('Add another?');
    $this->assertText('Collection facts:');
    $this->assertText('1 yoyo(s) total');

    // scenario: view yoyo details
    $this->clickLink('2005 Duncan FHZ');

    $this->assertText('Manufacturer Duncan');
    $this->assertText('Model year 2005');
    $this->assertText('Model name FHZ');
    $this->assertText('Country China');
    $this->assertText('Notes I dyed this one myself');

    // scenario: edit yoyo details
    $this->clickSubmit('Edit');
    $this->assertField('manufacturer', 'Duncan');
    $this->setField('manufacturer', 'Nacnud');
    $this->assertField('model_year', '2005');
    $this->setField('model_year', '5002');
    $this->assertField('model_name', 'FHZ');
    $this->setField('model_name', 'ZHF');
    $this->assertField('notes', 'I dyed this one myself');
    $this->setField('notes', 'green and pink');
    $this->clickSubmit('Save');

    $this->assertText('Yoyo saved successfully');
    $this->assertText('Manufacturer Nacnud');
    $this->assertText('Model year 5002');
    $this->assertText('Model name ZHF');
    $this->assertText('Notes green and pink');
  }
}

