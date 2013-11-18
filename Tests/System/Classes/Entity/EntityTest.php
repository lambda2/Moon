<?php

include_once 'includeAll.php';

class Astre extends Entity {
    
}

class EntityTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Entity
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $core = Core::startEngine();
        $this->object = new Astre();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Entity::autoLoadLinkedClasses
     * @todo   Implement testAutoLoadLinkedClasses().
     */
    public function testAutoLoadLinkedClasses() {
        
    }

    /**
     * @covers Entity::__get
     * @todo   Implement test__get().
     */
    public function test__get() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::__isset
     * @todo   Implement test__isset().
     */
    public function test__isset() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::__call
     * @todo   Implement test__call().
     */
    public function test__call() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::loadBy
     * @todo   Implement testLoadBy().
     */
    public function testLoadBy() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getAllObjects
     * @todo   Implement testGetAllObjects().
     */
    public function testGetAllObjects() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getAll
     * @todo   Implement testGetAll().
     */
    public function testGetAll() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::get
     * @todo   Implement testGet().
     */
    public function testGet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::set
     * @todo   Implement testSet().
     */
    public function testSet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::__toString
     * @todo   Implement test__toString().
     */
    public function test__toString() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getTable
     * @todo   Implement testGetTable().
     */
    public function testGetTable() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::setTable
     * @todo   Implement testSetTable().
     */
    public function testSetTable() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getEditable
     * @todo   Implement testGetEditable().
     */
    public function testGetEditable() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::setEditable
     * @todo   Implement testSetEditable().
     */
    public function testSetEditable() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getBdd
     * @todo   Implement testGetBdd().
     */
    public function testGetBdd() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::setBdd
     * @todo   Implement testSetBdd().
     */
    public function testSetBdd() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getLinkedClasses
     * @todo   Implement testGetLinkedClasses().
     */
    public function testGetLinkedClasses() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::addRelation
     * @todo   Implement testAddRelation().
     */
    public function testAddRelation() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getExternalInstance
     * @todo   Implement testGetExternalInstance().
     */
    public function testGetExternalInstance() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::clearLinkedClasses
     * @todo   Implement testClearLinkedClasses().
     */
    public function testClearLinkedClasses() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getFields
     * @todo   Implement testGetFields().
     */
    public function testGetFields() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::setFields
     * @todo   Implement testSetFields().
     */
    public function testSetFields() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::hasAnId
     * @todo   Implement testHasAnId().
     */
    public function testHasAnId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getNameWithoutId
     * @todo   Implement testGetNameWithoutId().
     */
    public function testGetNameWithoutId() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getRelationClassName
     * @todo   Implement testGetRelationClassName().
     */
    public function testGetRelationClassName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getRelationClassInstance
     * @todo   Implement testGetRelationClassInstance().
     */
    public function testGetRelationClassInstance() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Entity::getProperName
     * @todo   Implement testGetProperName().
     */
    public function testGetProperName() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
