<?php

include_once '/var/www/moon/System/Classes/Forms/Field.php';
include_once '/var/www/moon/System/Classes/Forms/TextArea.php';

class TextAreaTest extends PHPUnit_Framework_TestCase {

    /**
     * @var TextArea
     */
    protected $object;


    protected function setUp() {
        $this->object = new TextArea("taName", "Hello world");
    }



    /**
     * @covers TextArea::getInnerText
     */
    public function testGetInnerText() {
        $this->assertEquals("Hello world",$this->object->getInnerText());
    }

    /**
     * @covers TextArea::setInnerText
     */
    public function testSetInnerText() {
        $this->assertEquals("Hello world",$this->object->getInnerText());
        $this->object->setInnerText("Bye world");
        $this->assertEquals("Bye world",$this->object->getInnerText());
    }

    /**
     * @covers TextArea::getRows
     */
    public function testGetRows() {
        $this->assertEquals(2,$this->object->getRows());
    }

    /**
     * @covers TextArea::setRows
     */
    public function testSetRows() {
        $this->assertEquals(2,$this->object->getRows());
        $this->object->setRows(10);
        $this->assertEquals(10,$this->object->getRows());
    }

    /**
     * @covers TextArea::getHtml
     */
    public function testGetHtml() {
        $this->assertEquals(
                '<textarea name="taName" rows="2" >Hello world</textarea>',
                $this->object->getHtml());
    }

}
