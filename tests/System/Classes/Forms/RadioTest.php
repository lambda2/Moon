<?php

include_once '/var/www/lwf/System/Classes/Forms/Field.php';
include_once '/var/www/lwf/System/Classes/Forms/Radio.php';

class RadioTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Radio
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Radio("rName", "rValue");
    }

    /**
     * @covers Radio::__construct
     */
    public function testType(){
        $this->object = new Radio("rName", "rValue");
        $this->assertEquals("rName",$this->object->getName());
        $this->assertEquals("rValue",$this->object->getValue());
    }

}
