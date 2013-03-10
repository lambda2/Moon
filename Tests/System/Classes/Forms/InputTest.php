<?php


include_once '/var/www/moon/System/Classes/Exceptions.php';
include_once '/var/www/moon/System/Classes/Configuration.php';
include_once '/var/www/moon/System/Classes/Forms/Field.php';
include_once '/var/www/moon/System/Classes/Forms/Input.php';

class InputTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Input
     */
    protected $object;


    protected function setUp() {
        $this->object = new Input("iName", "text");
    }

    /**
     * @covers Input::getHtml
     */
    public function testGetHtml() {
        $this->assertEquals(
                '<input type="text" name="iName" >',
                $this->object->getHtml());
    }
    
    /**
     * @covers Input::__construct
     * @expectedException AlertException
     */
    public function testTypeIncorrect() {
        
        $this->object = new Input("iName", "banana");
        
    }

}
