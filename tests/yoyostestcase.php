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

  function testShouldNotDefaultNumericFieldsToZero() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');
    $this->setField('manufacturer', 'Duncan');
    $this->setField('model_name', 'FHZ');
    $this->setField('acq_date', '2010-01-03');
    $this->setField('acq_type', 'trade');
    // When
    $this->clickSubmit('Save');
    // Then
    $this->assertNoText('0 Duncan FHZ');

    // When
    $this->clickLink('Duncan FHZ');
    // Then
    $this->assertNoText('Value $0.00');
    $this->assertText('Value n/a');
    $this->assertNoText('Acquired by trade on 2010-01-03 for $0.00');
  }

  function testAddFirstYoyoToCollection() {
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');

    // scenario: create first yoyo
    $this->setField('manufacturer', 'Duncan');
    $this->setField('mod', 'the Modfather');
    $this->setField('country', 'China');
    $this->setField('model_year', '2005');
    $this->setField('model_name', 'FHZ');
    $this->assertField('notes', '');
    $this->setField('notes', 'I dyed this one myself');
    $this->assertField('value', '');
    $this->assertField('condition', '');
    $this->assertField('serialnum', '');
    $this->assertField('acq_date', '');
    $this->assertField('acq_type', false);
    $this->assertField('acq_party', '');
    $this->assertField('acq_price', '');
    $this->clickSubmit('Save');

    $this->assertText('Yoyo added to collection successfully');
    $this->assertText('2005 Duncan FHZ');

    $this->clickLink('collection');
    $this->assertLink('add another?');
    $this->assertText('Collection facts:');
    $this->assertText('1 yoyo(s) total');

    // scenario: view yoyo details
    $this->clickLink('2005 Duncan FHZ');

    $this->assertText('Manufacturer Duncan');
    $this->assertText('(modded by the Modfather)');
    $this->assertText('Model year 2005');
    $this->assertText('Model name FHZ');
    $this->assertText('Country China');
    $this->assertText('Notes I dyed this one myself');
    $this->assertText('Condition ');
    $this->assertText('Serial number ');

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
    $this->assertField('condition', '');
    $this->setField('condition', 'excellent');
    $this->assertField('mod', 'the Modfather');
    $this->setField('mod', '');
    $this->assertField('serialnum', '');
    $this->setField('serialnum', 'abc123');
    $this->clickSubmit('Save');

    $this->assertText('Yoyo saved successfully');
    $this->assertText('Manufacturer Nacnud');
    $this->assertText('Model year 5002');
    $this->assertText('Model name ZHF');
    $this->assertText('Notes green and pink');
    $this->assertText('Condition excellent');
    $this->assertText('Serial number abc123');
    $this->assertNoText('(modded by');
  }

  function testAddYoyoWithAdvancedInfo() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');
    // When
    $this->setField('model_name', 'FHZ');
    $this->setField('serialnum', 'abc123');
    $this->setField('condition', 'good');
    $this->setField('value', '45.00');
    $this->setField('acq_date', '2010-01-29');
    $this->setField('acq_type', 'purchase');
    $this->setField('acq_party', 'theyostore.com');
    $this->setField('acq_price', '45.00');
    $this->clickSubmit('Save');
    $this->clickLink('FHZ');
    // Then
    $this->assertText('Model name FHZ');
    $this->assertText('Serial number abc123');
    $this->assertText('Condition good');
    $this->assertText('Value $45.00');
    $this->assertText('Acquired by purchase on 2010-01-29 for $45.00 from theyostore.com');
  }

  function testAdvancedInfoValidations() {
    // Given
    $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('Add one?');
    // When
    $this->setField('mod', '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345');
    $this->setField('serialnum', '01234567890123456789012345678901234567890123456789012345678901234');
    $this->setField('value', 'asdf');
    $this->setField('acq_date', '01/29/2001');
    $this->setField('acq_party', '0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345');
    $this->setField('acq_price', 'asdf');
    $this->clickSubmit('Save', array('acq_type' => 'stealing')); // NB: in lieu of "$this->setField('acq_type', 'inherit');"
    // Then
    $this->assertText('The modder field can not exceed 255 characters in length');
    $this->assertText('The serial number field can not exceed 64 characters in length');
    $this->assertText('The value field must contain only numbers');
    $this->assertText('The acquisition date field must be in YYYY-MM-DD format');
    $this->assertText('Please choose from available acquisition methods');
    $this->assertText('The acquired from field can not exceed 255 characters in length');
    $this->assertText('The acquisition price field must contain only numbers');

    // When
    $this->setField('value', '1234567890123.34');
    $this->setField('acq_price', '1234567890123.34');
    $this->clickSubmit('Save');
    // Then
    $this->assertText('The value field can not exceed 15 characters in length');
    $this->assertText('The acquisition price field can not exceed 15 characters in length');
  }

  function testEditYoyoWithAdvancedInfo() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'FHZ');
    $this->insertRecord('acquisitions', array(
      'user_id' => $userid,
      'yoyo_id' => $yoyoid,
      'date' => "'2010-03-01'",
      'type' => "'purchase'",
      'party' => "'fred'",
      'price' => 14.56));
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    // When
    $this->clickLink('FHZ');
    $this->clickSubmit('Edit');
    // Then
    $this->assertField('acq_date', '2010-03-01');
    $this->assertField('acq_type', 'purchase');
    $this->assertField('acq_party', 'fred');
    $this->assertField('acq_price', '14.56');

    // When
    $this->setField('acq_date', '2009-12-13');
    $this->setField('acq_type', 'trade');
    $this->setField('acq_party', 'joe');
    $this->setField('acq_price', '');
    $this->clickSubmit('Save');
    // Then
    $this->assertText('Acquired by trade on 2009-12-13 for n/a from joe');
  }

  function testAdvancedInfoCanBeAddedToYoyo() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $this->createYoyo($userid, 'FHZ');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('FHZ');
    // When
    $this->clickSubmit('Edit');
    $this->setField('acq_date', '2009-12-13');
    $this->setField('acq_type', 'purchase');
    $this->setField('acq_party', 'yoyonation.com');
    $this->setField('acq_price', '15.99');
    $this->clickSubmit('Save');
    // Then
    $this->assertText('Acquired by purchase on 2009-12-13 for $15.99 from yoyonation.com');
  }

  function testDeleteYoyo() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'FHZ');
    $photoid = $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('FHZ');
    // When
    $this->clickSubmit('Delete');
    // Then
    $this->assertText('Your collection');
    $this->assertText('Yoyo deleted');
    $this->assertNoRecord('yoyos', 'id', $yoyoid);
    $this->assertNoRecord('photos', 'id', $photoid);
  }

  function testOnlyOwnerCanDeleteYoyo() {
    // Given
    $ownerid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($ownerid, 'FHZ');
    $photoid = $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $this->createUser('testUser2', 'testUser2@somewhere.com', 'Password2');
    $this->logInAs('testUser2', 'Password2');
    // When
    $this->post("/yoyo/$yoyoid/delete");
    // Then
    $this->assertText('Only owners are allowed to delete their own yoyos');
    $this->assertRecord('yoyos', 'id', $yoyoid);
    $this->assertRecord('photos', 'id', $photoid);
  }

  function testDeleteYoyoViaArchive() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'FHZ');
    $photoid = $this->createPhoto($yoyoid, 'http://somewhere.com/photo.jpg');
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('FHZ');
    // When
    $this->setFieldByName('archive', true);
    $this->clickSubmit('Delete');
    // Then
    $this->assertText('Your collection');
    $this->assertText('Yoyo archived and deleted');
    $this->assertRecord('archives', 'yoyo_id', $yoyoid);
    // TODO: assert contents of record (ARCHIVE.DATA) or something
    $this->assertNoRecord('yoyos', 'id', $yoyoid);
    $this->assertNoRecord('photos', 'id', $photoid);
  }

  function testOnlyOwnerCanViewYoyoDetails() {
    // Given
    $ownerid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($ownerid, 'FHZ');
    $this->createUser('testUser2', 'testUser2@somewhere.com', 'Password2');
    // When
    $this->logInAs('testUser2', 'Password2');
    $this->get("/yoyo/$yoyoid");
    // Then
    $this->assertText('Only owners are allowed to view yoyo details');
    $this->assertNoText('FHZ');
  }
}

