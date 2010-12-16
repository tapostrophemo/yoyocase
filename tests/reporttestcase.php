<?php

class ReportTestCase extends MY_WebTestCase
{
  function testSummaryReportWithoutYoyos() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    // When
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('collection report');
    // Then
    $this->assertText('Number of yoyos: 0');
    $this->assertText('Total paid for yoyos: $0');
    $this->assertText('Total value of yoyos: $0');
  }

  function testSummaryReportForSingleYoWithNoEnteredValues() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'Freehand Zero');
    $this->updateRecordValues('yoyos', 'id', $yoyoid, array(
      'notes' => "'pretty colors'",
    ));
    $this->insertRecord('acquisitions', array(
      'yoyo_id' => $yoyoid,
      'user_id' => $userid,
      'type' => "'purchase'",
      'date' => "'2010-12-16'",
      'party' => "'toy store'",
    ));
    // When
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('collection report');
    // Then
    $this->assertText('Number of yoyos: 1');
    $this->assertText('Total paid for yoyos: $0');
    $this->assertText('Total value of yoyos: $0');
    $this->assertText('Year Manufacturer (Country) Model Value Condition Serial # Acquired From Price Notes');
    $this->assertText('Freehand Zero purchase 2010-12-16 toy store pretty colors');
  }

  function testSummaryReportForSingleYoWithEnteredValues() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'FHZ');
    $this->updateRecord('yoyos', 'value', 17.0, 'id', $yoyoid);
    $this->insertRecord('acquisitions', array('yoyo_id' => $yoyoid, 'user_id' => $userid, 'price' => 15.5));
    // When
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('collection report');
    // Then
    $this->assertText('Number of yoyos: 1');
    $this->assertText('Total paid for yoyos: $15.50');
    $this->assertText('Total value of yoyos: $17.00');
  }

  function testSummaryReportForMultipleRowsWithValues() {
    // Given
    $userid = $this->createUser('testUser1', 'testUser1@somewhere.com', 'Password1');
    $yoyoid = $this->createYoyo($userid, 'FHZ');
//    $this->updateRecord('yoyos', "\`condition\`", "'good'", 'id', $yoyoid);
    $this->updateRecordValues('yoyos', 'id', $yoyoid, array(
      'manufacturer' => "'Duncan'",
      'country' => "'China'",
      'model_year' => 2010,
      'notes' => "'<em>awesome</em> colors!'",
      '\`condition\`' => "'good'",
      '\`mod\`' => "'TheModfather'",
      'serialnum' => "'abc123'",
      'value' => 17.0,
    ));
    $this->insertRecord('acquisitions', array(
      'yoyo_id' => $yoyoid,
      'user_id' => $userid,
      'type' => "'purchase'",
      'date' => "'2010-12-16'",
      'party' => "'toy store'",
      'price' => 15.5,
    ));

    $yoyoid = $this->createYoyo($userid, 'Brain');
    $this->updateRecordValues('yoyos', 'id', $yoyoid, array(
      'manufacturer' => "'Yomega'",
      'value' => 15.0,
    ));
    $this->insertRecord('acquisitions', array(
      'yoyo_id' => $yoyoid,
      'user_id' => $userid,
      'party' => "'school fair'",
      'price' => 11.99,
    ));

    // When
    $this->logInAs('testUser1', 'Password1');
    $this->clickLink('collection');
    $this->clickLink('collection report');

    // Then
    $this->assertText('Number of yoyos: 2');
    $this->assertText('Total paid for yoyos: $27.49');
    $this->assertText('Total value of yoyos: $32.00');
    $this->assertText('Year Manufacturer (Country) Model Value Condition Serial # Acquired From Price Notes');
    $this->assertText('2010 Duncan (China) FHZ $17.00 good abc123 purchase 2010-12-16 toy store $15.50 awesome colors!');
    $this->assertText('Yomega Brain $15.00 school fair $11.99');
  }
}

