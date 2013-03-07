<?php

include_once '/var/www/lwf/System/Classes/Forms/Field.php';
include_once '/var/www/lwf/System/Classes/Forms/Button.php';


class ButtonTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Button
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Button("unBouton","button","bouton","Click me");
    }

    /**
     * @covers Button::getInnerText
     */
    public function testGetInnerText() {
        $this->assertEquals("Click me",$this->object->getInnerText());
    }

    /**
     * @covers Button::setInnerText
     */
    public function testSetInnerText() {
        $this->object->setInnerText("Clock me!");
        $this->assertEquals("Clock me!",$this->object->getInnerText());
    }

    /**
     * @covers Button::getHtml
     */
    public function testGetHtml() {
        
        $this->assertEquals(
                '<button type="button" value="bouton" name="unBouton" >Click me</button>'
                ,$this->object->getHtml());
    }

}
